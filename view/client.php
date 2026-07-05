<?php

require_once __DIR__ . '/../model/plat.php';

function afficherMenuClient() {
    echo "\n=== ESPACE CLIENT ===\n";
    echo "1. Consulter le catalogue des plats\n";
    echo "2. Ajouter un plat au panier\n";
    echo "3. Voir mon panier\n";
    echo "4. Finaliser et payer ma commande\n";
    echo "5. Se déconnecter\n";
    echo "---------------------\n";
}

function afficherPanier($lignes) {
    if (empty($lignes)) {
        echo "Votre panier est vide.\n";
        return;
    }
    echo "\n=== VOTRE PANIER ===\n";
    $total = 0;
    foreach ($lignes as $index => $ligne) {
        $plat = getPlatById($ligne['id_plat']);
        if ($plat) {
            $sousTotal = $plat['prix'] * $ligne['quantite'];
            $total += $sousTotal;
            echo ($index + 1) . ". Nom: " . $plat['nom'] . " | Qte: " . $ligne['quantite'] . " | Prix unit: " . $plat['prix'] . " FCFA | Sous-total: " . $sousTotal . " FCFA\n";
        }
    }
    echo "---------------------\n";
    echo "Total panier : " . $total . " FCFA\n";
}
