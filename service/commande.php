<?php

require_once __DIR__ . '/../model/plat.php';

function calculerTotalCommande($lignes) {
    $total = 0;
    foreach ($lignes as $ligne) {
        $plat = getPlatById($ligne['id_plat']);
        if ($plat) {
            $total += $plat['prix'] * $ligne['quantite'];
        }
    }
    return $total;
}

function validerPaiementSimule($reponse) {
    return strtolower(trim($reponse)) === 'oui';
}
