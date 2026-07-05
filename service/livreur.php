<?php

require_once __DIR__ . '/../model/livreur.php';

function validerDisponibiliteLivreur($id_livreur) {
    $livreur = getLivreurById($id_livreur);
    if ($livreur && $livreur['disponible'] === true) {
        return true;
    }
    return false;
}
