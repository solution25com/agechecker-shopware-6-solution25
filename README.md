[![Packagist Version](https://img.shields.io/packagist/v/solution25/age-checker.svg)](https://packagist.org/packages/solution25/age-checker)
[![Packagist Downloads](https://img.shields.io/packagist/dt/solution25/age-checker.svg)](https://packagist.org/packages/solution25/age-checker)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](https://github.com/solution25com/agechecker-shopware-6-solution25/blob/main/LICENSE.md)

# AgeChecker
 
## Introduction
 
The AgeChecker plugin helps merchants enforce age verification on their Shopware 6 storefronts. By integrating with an external age verification service, it ensures that customers meet age requirements before proceeding to checkout.
 
This tool is especially useful for businesses selling age-restricted products, providing a configurable and user-friendly verification layer that integrates seamlessly with Shopware's sales channels.
 
### Key Features
 
1. **Sales Channel Integration**
   - Add API keys per sales channel for flexible configuration.
2. **Age Verification Options**
   - Customers can verify their age by entering their date of birth.
3. **Retry Management**
   - Users have 3 attempts by default to verify their age.
4. **Temporary Denial Logic**
   - Block users temporarily after 3 failed attempts.
5. **Admin Panel Configuration**
   - Easily manage settings from the Shopware admin panel.
 
## Get Started
### Installation & Activation
1. **Download**
## Git
- Clone the Plugin Repository:
- Open your terminal and run the following command in your Shopware 6 custom plugins directory (usually located at custom/plugins/):
```bash
git clone https://github.com/solution25com/agechecker-shopware-6-solution25.git
```
 
2. **Install the Plugin in Shopware 6**
- Log in to your Shopware 6 Administration panel.
- Navigate to Extensions > My Extensions.
- Locate the newly cloned plugin and click Install.
3. **Activate the Plugin**
- After installation, click Activate or toggle the plugin to activate it
4. **Verify Installation**
- After activation, you will see AgeChecker in the list of installed plugins.
- The plugin name, version, and installation date should appear as shown in the screenshot below.
 
![img1](https://github.com/user-attachments/assets/719ab524-7636-45b9-966a-66670f356c47)

 
---
 
 
## Plugin Configuration
 
1. **Access Plugin Settings**
- Go to Settings > System > Plugins.
- Locate AgeChecker and click the three dots (...) icon or the plugin name to open its settings.
 
2. **API Key Setup**
 
- Go to **Extensions > My Extensions > AgeChecker**.
- Select the appropriate **Sales Channel**.
- Enter the **API Key** provided by AgeChecker.net. You must create an account there to obtain your API Key.
 
 > [!WARNING]
 > Each sales channel requires a unique API key.
 
![img2](https://github.com/user-attachments/assets/01e9a268-1dae-4eea-b6dd-db453b959765)

3. **Save Configuration**
- Click Save in the top-right corner to store your settings.
 
## How It Works
 
1. **User Initiates Checkout**
 
- AgeChecker triggers a popup based on the configured method.
 
2. **Verification Attempt**
 
- The user provides their birthdate.
 
![img3](https://github.com/user-attachments/assets/cfc8b2aa-edb9-4d43-996b-e395f9223d11)
 
  
- If successful, checkout proceeds.
 
![img4](https://github.com/user-attachments/assets/3f110c91-8364-4093-a441-deab0a3385d5)

  
- If unsuccessful:
  - The user can retry up to 3 times.
  - After 3 failed attempts, the user is temporarily denied access.
 
![img5](https://github.com/user-attachments/assets/a43fda8d-86b8-40f8-9314-707e8c6b7c9c)

 

3. **Temporary Denial Logic**
- Denied users are blocked for 24 hours before they can try again.
 
---

# Age Checker Plugin - API Documentation
 
This document describes the API endpoint for the Age Checker Plugin for Shopware 6. The plugin integrates with AgeChecker.net to verify a customer’s age and stores the result in the customer’s custom fields.
 
---
 
## Update Customer Age Verification Status
 
**Endpoint**  
`POST /age-checker-user-status`
 
### Description
 
Updates the `custom_age_confirmed_` custom field for a logged-in customer based on the age verification result received from the AgeChecker.net service.
 
### System Checks
 
- Customer must be logged in.
- The `uuid` from AgeChecker.net must be provided.
- Calls the AgeChecker.net API to verify the result.
- Sets the customer’s custom field `custom_age_confirmed_` to `true` if the verification is successful (`status: accepted`).
 
### Request Headers
 
```
sw-context-token: <your-sales-channel-context-token>
Content-Type: application/json
```
 
> **Note**: The `sw-context-token` must be obtained via the Shopware Store API authentication process or created automatically in a Storefront session.
 
### Example Request Body
 
```json
{
  "uuid": "8e7c35e2-3f48-4fc9-9283-fb0e198b0fd5"
}
```
 
### Successful Response
 
```json
{
  "message": "Customer age verification status updated"
}
```
 
### Example Error Response
 
```json
{
  "message": "uuid is required"
}
```

 
 ---
 
## Troubleshooting
 
### Verify Functionality
 
- Confirm popup appears on checkout.
- Test both verification methods.
- Simulate 3 failed attempts to confirm that the user is blocked afterward.
 
 
## FAQ
 
- **Is an API key required?**  
  Yes. Without a valid API key, age verification will not function.
 
- **Can I limit it to certain sales channels?**  
  Yes. You can enable or disable the plugin per sales channel.
 
- **Does this plugin block checkout?**  
  Yes, until the user passes age verification.
 
- **What happens after 3 failed attempts?**  
  The user is redirected to a temporary denial page and cannot retry until the timeout expires.

  ---

  ## [Wiki](https://github.com/solution25com/agechecker-shopware-6-solution25/wiki)
