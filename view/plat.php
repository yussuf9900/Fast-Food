<?php

function afficherCatalogueConsole($plats) {
    if (empty($plats)) {
        echo "Le catalogue est vide.\n";
        return;
    }
    echo "\n=== CATALOGUE DES PLATS ===\n";
    foreach ($plats as $plat) {
        echo "ID          : " . $plat['id'] . "\n";
        echo "Nom         : " . $plat['nom'] . "\n";
        echo "Prix        : " . $plat['prix'] . " FCFA\n";
        echo "Description : " . $plat['description'] . "\n";
        echo "---------------------------\n";
    }
}

function afficherMenuCatalogue() {
    echo "\n=== ESPACE GÉRANT : CATALOGUE ===\n";
    echo "1. Lister les plats\n";
    echo "2. Ajouter un plat\n";
    echo "3. Retour au menu Gérant\n";
    echo "---------------------------\n";
}
