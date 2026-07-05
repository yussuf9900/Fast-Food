<?php

require_once __DIR__ . '/../view/gerant.php';
require_once __DIR__ . '/../controller/plat.php';
require_once __DIR__ . '/../utils/cli.php';

function executerEspaceGerant() {
    while (true) {
        afficherMenuGerant();
        $choix = demanderSaisie("Votre choix : ");

        if ($choix === '1') {
            executerGestionCatalogue();
        } elseif ($choix === '2') {
            executerGestionCommandesGerant();
        } elseif ($choix === '3') {
            afficherInfo("Déconnexion de l'espace Gérant.");
            break;
        } else {
            afficherErreur("Choix invalide.");
        }
    }
}

function executerGestionCommandesGerant() {
    afficherInfo("La gestion des commandes sera disponible dans l'Incrément 4.");
}
