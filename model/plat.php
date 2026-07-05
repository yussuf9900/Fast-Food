<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['plats'])) {
    $_SESSION['plats'] = [
        [
            'id' => 1,
            'nom' => 'Double Cheese Burger',
            'prix' => 3500,
            'description' => 'Pain brioché, steak, fromage fondu.',
            'image' => 'images/burger_xl.jpg'
        ],
        [
            'id' => 2,
            'nom' => 'Pizza Margherita',
            'prix' => 4000,
            'description' => 'Tomate, mozzarella, basilic frais.',
            'image' => 'images/pizza_reine.jpg'
        ],
        [
            'id' => 3,
            'nom' => 'Salade César',
            'prix' => 2500,
            'description' => 'Salade, poulet grillé, sauce maison.',
            'image' => 'images/salade_cesar.jpg'
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
