<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['livreurs'])) {
    $_SESSION['livreurs'] = [];
}

function getLivreurs() {
    return $_SESSION['livreurs'];
}

function getLivreurById($id) {
    foreach ($_SESSION['livreurs'] as $livreur) {
        if ($livreur['id'] == $id) {
            return $livreur;
        }
    }
    return null;
}

function saveLivreur($livreur) {
    if (!isset($livreur['id'])) {
        $livreur['id'] = count($_SESSION['livreurs']) + 1;
    }
    $_SESSION['livreurs'][] = $livreur;
    return $livreur;
}

function updateLivreurStatus($id, $disponible) {
    foreach ($_SESSION['livreurs'] as &$livreur) {
        if ($livreur['id'] == $id) {
            $livreur['disponible'] = $disponible;
            return true;
        }
    }
    return false;
}
