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
 
1. **Clone the Plugin Repository**
 
- Open your terminal and run the following command in your Shopware 6 custom plugins directory (usually located at custom/plugins/):
```bash
git clone https://github.com/solution25com/agechecker-shopware-6-solution25.git
```
 
## Install & Activate the Plugin
 
1. Log in to your Shopware 6 Administration.
2. Navigate to **Extensions > My Extensions**.
3. Locate the **AgeChecker** plugin and click **Install**, then **Activate**.
 
## Verify Installation
 
- The plugin will now appear in your active extensions list.
- Check that the plugin name and version are visible.
 
![img1](https://github.com/user-attachments/assets/719ab524-7636-45b9-966a-66670f356c47)

 
---
 
## Plugin Configuration
 
### API Key Setup
 
- Go to **Extensions > My Extensions > AgeChecker**.
- Select the appropriate **Sales Channel**.
- Enter the **API Key** provided by your external age verification service.

 > [!WARNING]
 > Each sales channel requires a unique API key.
 
![img2](https://github.com/user-attachments/assets/01e9a268-1dae-4eea-b6dd-db453b959765)

## How It Works
 
### User Initiates Checkout
 
- AgeChecker triggers a popup based on the configured method.
 
### Verification Attempt
 
- The user provides their birthdate.
 
![img3](https://github.com/user-attachments/assets/cfc8b2aa-edb9-4d43-996b-e395f9223d11)
 
  
- If successful, checkout proceeds.
 
![img4](https://github.com/user-attachments/assets/3f110c91-8364-4093-a441-deab0a3385d5)

  
- If unsuccessful:
  - The user can retry up to 3 times.
  - After 3 failed attempts, the user is temporarily denied access.
 
![img5](https://github.com/user-attachments/assets/a43fda8d-86b8-40f8-9314-707e8c6b7c9c)

 
### Temporary Denial Logic
 
- Denied users are blocked for 24 hours before they can try again.
 
---
 
## Testing and Troubleshooting
 
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
