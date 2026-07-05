<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['clients'])) {
    $_SESSION['clients'] = [
        ['id' => 1, 'nom' => 'Jean Dupont', 'email' => 'jean.dupont@example.com'],
        ['id' => 2, 'nom' => 'Sophie Martin', 'email' => 'sophie.martin@example.com']
    ];
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
