<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['clients'])) {
    $_SESSION['clients'] = [];
}

function getClients() {
    return $_SESSION['clients'];
}

function getClientById($id) {
    foreach ($_SESSION['clients'] as $client) {
        if ($client['id'] == $id) {
            return $client;
        }
    }
    return null;
}

function saveClient($client) {
    if (!isset($client['id'])) {
        $client['id'] = count($_SESSION['clients']) + 1;
    }
    $_SESSION['clients'][] = $client;
    return $client;
}
