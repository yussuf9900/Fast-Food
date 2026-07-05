<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['plats'])) {
    $_SESSION['plats'] = [
        [
            'id' => 1,
            'nom' => 'Burger XL',
            'prix' => 3500,
            'description' => 'Un délicieux double cheese burger servi avec frites.'
        ],
        [
            'id' => 2,
            'nom' => 'Pizza Reine',
            'prix' => 5000,
            'description' => 'Pizza tomate, jambon, champignons frais et mozzarella fondante.'
        ],
        [
            'id' => 3,
            'nom' => 'Tacos Poulet',
            'prix' => 3000,
            'description' => 'Tacos poulet croustillant avec frites et sauce fromagère maison.'
        ]
    ];
}

function getPlats() {
    return $_SESSION['plats'];
}

function getPlatById($id) {
    foreach ($_SESSION['plats'] as $plat) {
        if ($plat['id'] == $id) {
            return $plat;
        }
    }
    return null;
}

function savePlat($plat) {
    if (!isset($plat['id'])) {
        $plat['id'] = count($_SESSION['plats']) + 1;
    }
    $_SESSION['plats'][] = $plat;
    return $plat;
}
