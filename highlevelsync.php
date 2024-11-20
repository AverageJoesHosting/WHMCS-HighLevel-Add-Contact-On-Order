<?php

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

/**
 * Configuration settings for the HighLevel Contact Sync addon.
 */
function highlevelsync_config() {
    return [
        'name' => 'HighLevel Contact Sync',
        'description' => 'Automatically syncs WHMCS client data to HighLevel CRM after an order is paid.',
        'version' => '1.0',
        'author' => "Average Joe's Hosting LLC",
        'fields' => [
            'apiKey' => [
                'FriendlyName' => 'HighLevel API Key',
                'Type' => 'text',
                'Size' => '50',
                'Default' => '',
                'Description' => 'Enter your HighLevel API key here.',
            ],
        ],
    ];
}

/**
 * Called when the addon is activated.
 */
function highlevelsync_activate() {
    return [
        'status' => 'success',
        'description' => 'HighLevel Contact Sync has been activated.',
    ];
}

/**
 * Called when the addon is deactivated.
 */
function highlevelsync_deactivate() {
    return [
        'status' => 'success',
        'description' => 'HighLevel Contact Sync has been deactivated.',
    ];
}

/**
 * Displayed in the admin area when viewing the addon configuration.
 */
function highlevelsync_output($vars) {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    // Get the WHMCS base URL
    $baseUrl = rtrim(\App::getSystemURL(), '/') . '/';

    if ($action === 'sync_previous_orders') {
        echo '<p>Syncing previous orders. This may take a while...</p>';
        echo '<div id="progress-bar" style="width: 100%; background: #f3f3f3; border: 1px solid #ccc; height: 20px; position: relative;">
                <div id="progress" style="width: 0%; background: #4caf50; height: 100%; text-align: center; line-height: 20px; color: white;">0%</div>
              </div>';
        echo '<script src="' . $baseUrl . 'modules/addons/highlevelsync/assets/progress.js"></script>';
        echo '<script>processBatch(0);</script>';
    } else {
        echo '<p>HighLevel Contact Sync is active. Configure the settings above.</p>';
        echo '<a href="?module=highlevelsync&action=sync_previous_orders" class="btn btn-primary">Sync Previous Orders</a>';
    }
}
