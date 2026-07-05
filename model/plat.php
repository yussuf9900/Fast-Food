<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['plats'])) {
    $_SESSION['plats'] = [];
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
