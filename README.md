# HighLevel Contact Sync for WHMCS

Automatically syncs WHMCS client data to HighLevel CRM when an order is paid. Includes tagging, activity logging, and an easy-to-configure admin interface.

---

## Features
- **Automated Contact Creation**: Syncs client data to HighLevel CRM on order payment.
- **Tagging**: Adds the "whmcs order" tag to synced contacts for easy identification.
- **Configurable API Key**: Manage the HighLevel API key directly in the WHMCS admin interface.
- **Activity Logging**: Logs successful and failed sync attempts in WHMCS for transparency.

---

## Installation

### **1. Download and Upload**
- Place the `highlevelsync` folder into the `modules/addons/` directory of your WHMCS installation.

### **2. Activate the Addon**
- Log in to your WHMCS Admin area.
- Navigate to **Setup > Addon Modules**.
- Locate "HighLevel Contact Sync" and click **Activate**.

### **3. Configure the Addon**
- Enter your **Primary Global API Key** in the addon settings:
  - **Where to find the API Key**:
    - Log in to your HighLevel account.
    - Go to **Settings > Business Profile > API Key**.
    - Copy the **Primary Global API Key**.
  - Paste the key into the **HighLevel API Key** field in the addon configuration.
  - Save your settings.

---

## Important Notes

- **Use the Primary Global API Key**:  
  The global API key provides full access to the HighLevel Contacts API and ensures compatibility with this addon. Avoid using private integration keys, as they may not have the necessary permissions for syncing contacts.

- **Ensure API Key Permissions**:  
  The API key must include the following scope:
  - `contacts.write`

- **API Endpoint**:  
  The addon uses the HighLevel Contacts API endpoint:  
  `https://rest.gohighlevel.com/v1/contacts/`

---

## Syncing Previous Orders

1. Go to **Setup > Addon Modules > HighLevel Contact Sync**.
2. Click the **Sync Previous Orders** button to process existing orders in batches.
3. Monitor the progress using the built-in progress bar.

---

## File Structure

```
modules/addons/highlevelsync/
├── highlevelsync.php       # Main addon file (defines the module)
├── hooks.php               # Hook file (implements the sync logic)
├── assets/                 # Static assets directory
│   └── progress.js         # JavaScript file for progress updates
```

---

## Troubleshooting

- **Invalid API Key Error**:  
  If you receive `{"msg":"Api key is invalid."}`, ensure the following:
  1. You're using the **Primary Global API Key** (Settings > Business Profile > API Key).
  2. The API key has the required `contacts.write` permission.
  3. The key is entered correctly without leading/trailing spaces.

- **API Connectivity Issues**:
  - Ensure your WHMCS server can reach the HighLevel API (`rest.gohighlevel.com`).
  - Test connectivity using tools like Postman or CURL.

---

## License
This project is licensed under the MIT License.

---

**Developed by Average Joe's Hosting LLC**
