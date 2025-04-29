const Plugin = window.PluginBaseClass;

export default class AgeCheckerPlugin extends Plugin {
    async init() {
        this.apiKey = this.getApiKey();
        this.customer = this.getCurrentCustomer();
        this.updateStatusEndpoint = this.getUpdateStatusEndpoint();
        this.temporaryDeniedPageUri = this.getTemporaryDeniedUrl();
        this.numberOfDenialsAllowed = 3;

        const data = this.getPopupData();

        const customerGroupArr = this.getCustomerGroup();
        const ageIgnoredForGroupAttendees = customerGroupArr?.includes(customer.groupId);

        if (this.isAgeConfirmed() || ageIgnoredForGroupAttendees) {
            return false
        }

        this.startAgeVerification(this.customer, data);
    }

    isAgeConfirmed() {
        return ["true", "1"].includes(this.el.getAttribute('userAgeIsConfirmed'));
    }

    getCustomerGroup() {
        return this.el.getAttribute('customerGroup');
    }

    getCurrentCustomer() {
        const customerJson = this.el.getAttribute('currentCustomer');
        return JSON.parse(customerJson);
    }

    getApiKey() {
        return this.el.getAttribute('apiKey');
    }

    getUpdateStatusEndpoint() {
        return this.el.getAttribute('updateStatusEndpoint');
    }

    getTemporaryDeniedUrl() {
        return this.el.getAttribute('temporaryDeniedUri');
    }

    getPopupData() {
        const popupDataJson = this.el.getAttribute('popupData');
        return JSON.parse(popupDataJson);
    }

    async setVerified(url, data) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        if (!res.ok) throw new Error('Network response was not ok');

        return await res.json();
    }

    startAgeVerification(customer, data) {
        if (this.getItemWithExpiry('temporaryDenied')) {
            this.redirectToTemporaryDeniedPage();
            return false;
        }

        this.denied = false;

        const verificationData = this.getVerificationData(data, customer);
        const config = this.getAgeCheckerConfig(this.apiKey, verificationData);

        this.loadAgeCheckerScript(config);
    }

    getVerificationData(data, customer) {
        const birthday = new Date(customer.birthday);
        return {
            first_name: data?.first_name || customer.firstName,
            last_name: data?.last_name || customer.lastName,
            address: data?.street_address || customer.defaultBillingAddress.street,
            city: data?.city || customer.defaultBillingAddress.city,
            zip: data?.zip || customer.defaultBillingAddress.zipcode,
            country: data?.country || customer.defaultBillingAddress.country.iso,
            state: data?.state || customer.defaultBillingAddress?.countryState?.shortCode.split('-')[1],
            dob_day: birthday.getDate(),
            dob_month: birthday.getMonth() + 1,
            dob_year: birthday.getFullYear(),
        };
    }

    getAgeCheckerConfig(apiKey, verificationData) {
        return {
            bind_form_submit: true,
            element: '#confirmFormSubmit',
            key: this.apiKey,
            show_close: true,
            onstatuschanged: this.onStatusChanged.bind(this),
            data: verificationData,
        };
    }

    async onStatusChanged(status) {
        const updateStatusData = {
            ageVerified: true,
            uuid: status.uuid
        };

        if (status?.status === 'accepted') {
            this.setVerified(this.updateStatusEndpoint, updateStatusData);
        } else if (status?.status === 'denied') {
            this.denyCount = this.denyCount ? this.denyCount + 1 : 1;

            const oneDay = 24 * 60 * 60 * 1000;
            this.setItemWithExpiry('denyCount', this.denyCount ?? 1, oneDay);

            if (this.denyCount >= this.numberOfDenialsAllowed) {
                this.setItemWithExpiry('temporaryDenied', true, oneDay);
                this.redirectToTemporaryDeniedPage();
            }
        }
    }

    redirectToTemporaryDeniedPage() {
        window.location.href = this.temporaryDeniedPageUri;
    }

    getItemWithExpiry(key) {
        const storedData = localStorage.getItem(key);

        if (!storedData) return null;

        const parsedData = JSON.parse(storedData);
        const currentTime = new Date().getTime();

        if (currentTime > parsedData.expiry) {
            localStorage.removeItem(key);
            return null;
        }

        return parsedData;
    }

    setItemWithExpiry(key, value, ttl) {
        const currentTime = new Date().getTime();

        const data = {
            value: value,
            expiry: currentTime + ttl,
        };

        localStorage.setItem(key, JSON.stringify(data));
    }

    loadAgeCheckerScript(config) {
        (function(w,d) {
            w.AgeCheckerConfig=config;

            if(config.path&&(w.location.pathname+w.location.search).indexOf(config.path)) return;

            var h=d.getElementsByTagName("head")[0];
            var a=d.createElement("script");
            a.src="https://cdn.agechecker.net/static/popup/v1/popup.js";
            a.crossOrigin="anonymous";

            a.onerror=function(){
                w.location.href="https://agechecker.net/loaderror";
            };

            h.insertBefore(a,h.firstChild);
        })(window, document);
    }
}
