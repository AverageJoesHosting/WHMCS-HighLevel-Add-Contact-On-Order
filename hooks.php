<?php

use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Hook triggered after an order is paid.
 */
add_hook('OrderPaid', 1, function($vars) {
    $apiKey = Capsule::table('tbladdonmodules')
        ->where('module', 'highlevelsync')
        ->where('setting', 'apiKey')
        ->value('value');

    if (!$apiKey) {
        logActivity("HighLevel Sync Error: API Key not configured. Contact sync skipped.");
        return;
    }

    $orderId = $vars['orderid'];
    $orderData = Capsule::table('tblorders')->where('id', $orderId)->first();
    $clientData = Capsule::table('tblclients')->where('id', $orderData->userid)->first();

    if ($clientData) {
        $contactData = [
            "firstName" => $clientData->firstname,
            "lastName"  => $clientData->lastname,
            "email"     => $clientData->email,
            "phone"     => $clientData->phonenumber,
            "address"   => $clientData->address1,
            "city"      => $clientData->city,
            "state"     => $clientData->state,
            "postalCode" => $clientData->postcode,
            "tags"      => ["whmcs order"],
        ];

        if (!sendToHighLevel($contactData, $apiKey, $orderId)) {
            logActivity("HighLevel Sync Error: Failed to sync order ID $orderId after multiple attempts.");
        }
    }
});

/**
 * AJAX endpoint for batch processing.
 */
add_hook('AdminAreaPage', 1, function($vars) {
    if ($_REQUEST['action'] === 'sync_previous_orders_batch') {
        $offset = intval($_REQUEST['offset']);
        $batchSize = 100;

        $apiKey = Capsule::table('tbladdonmodules')
            ->where('module', 'highlevelsync')
            ->where('setting', 'apiKey')
            ->value('value');

        if (!$apiKey) {
            echo json_encode(['status' => 'error', 'message' => 'API Key not configured.']);
            exit;
        }

        $orders = Capsule::table('tblorders')
            ->select('id', 'userid')
            ->offset($offset)
            ->limit($batchSize)
            ->get();

        foreach ($orders as $order) {
            $client = Capsule::table('tblclients')->where('id', $order->userid)->first();

            if ($client) {
                $contactData = [
                    "firstName" => $client->firstname,
                    "lastName"  => $client->lastname,
                    "email"     => $client->email,
                    "phone"     => $client->phonenumber,
                    "address"   => $client->address1,
                    "city"      => $client->city,
                    "state"     => $client->state,
                    "postalCode" => $client->postcode,
                    "tags"      => ["whmcs order"],
                ];

                if (!sendToHighLevel($contactData, $apiKey, $order->id)) {
                    logActivity("HighLevel Sync Error: Failed to sync order ID {$order->id}");
                }
            }
        }

        echo json_encode(['status' => 'success', 'processed' => count($orders)]);
        exit;
    }
});

/**
 * Sends data to HighLevel with retry logic.
 */
function sendToHighLevel($contactData, $apiKey, $orderId, $retryCount = 3) {
    $attempt = 0;
    $success = false;

    while ($attempt < $retryCount && !$success) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://rest.gohighlevel.com/v1/contacts/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json",
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            $success = true;
        } else {
            $attempt++;
            $errorMessage = $curlError ?: "HTTP Code: $httpCode, Response: $response";
            logActivity("HighLevel Sync Error: Order ID $orderId - $errorMessage");
        }
    }

    return $success;
}
