<?php

require_once __DIR__ . '/../view/auth.php';
require_once __DIR__ . '/../service/auth.php';
require_once __DIR__ . '/../utils/cli.php';

function executerAuthentification() {
    while (true) {
        afficherMenuConnexion();
        $choix = demanderSaisie("Votre choix : ");

        if ($choix === '1') {
            executerEspaceClient();
        } elseif ($choix === '2') {
            afficherSaisieMotDePasse();
            $motDePasse = demanderSaisie("");
            if (validerMotDePasseGerant($motDePasse)) {
                afficherSucces("Authentification réussie !");
                executerEspaceGerant();
            } else {
                afficherErreur("Mot de passe incorrect !");
            }
        } elseif ($choix === '3') {
            afficherInfo("Au revoir !");
            exit(0);
        } else {
            afficherErreur("Choix invalide, veuillez réessayer.");
        }
    }
}

function executerEspaceClient() {
    afficherInfo("Espace Client en cours de développement...");
}

function executerEspaceGerant() {
    afficherInfo("Espace Gérant en cours de développement...");
}
