# HighLevel Contact Sync for WHMCS

Automatically syncs WHMCS client data to HighLevel CRM when an order is paid. Includes tagging, activity logging, and an easy-to-configure admin interface.

## Features
- **Automated Contact Creation**: Syncs client data to HighLevel CRM on order payment.
- **Tagging**: Adds the "whmcs order" tag to synced contacts.
- **Configurable API Key**: Manage the HighLevel API key in the WHMCS Addon Modules configuration.
- **Activity Logging**: Logs successful and failed sync attempts in WHMCS for transparency.

## Installation

1. **Download and Upload**:
   - Place the `highlevelsync` folder into the `modules/addons/` directory of your WHMCS installation.

2. **Activate the Addon**:
   - Log in to your WHMCS Admin area.
   - Navigate to **Setup > Addon Modules**.
   - Locate "HighLevel Contact Sync" and click **Activate**.

3. **Configure the Addon**:
   - Enter your HighLevel API key in the configuration settings and save.

4. **Test the Integration**:
   - Place a test order in WHMCS and verify that the contact is created in HighLevel CRM with the correct data and tags.

## File Structure
```
modules/addons/highlevelsync/
├── highlevelsync.php   # Main addon file (defines the module)
├── hooks.php           # Hook file (implements the logic)
```

## How It Works
- The addon listens for the `OrderPaid` hook in WHMCS.
- When triggered, it retrieves the client and order information and sends it to HighLevel CRM.
- The contact is created in HighLevel with a "whmcs order" tag for identification.

## Requirements
- WHMCS (compatible with version 7.0+).
- HighLevel API access and a valid API key.

## License
This project is licensed under the MIT License.

---

**Developed by Average Joe's Hosting LLC**