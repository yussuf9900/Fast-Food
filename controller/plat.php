<?php

require_once __DIR__ . '/../view/plat.php';
require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../service/plat.php';
require_once __DIR__ . '/../utils/cli.php';

function executerGestionCatalogue() {
    while (true) {
        afficherMenuCatalogue();
        $choix = demanderSaisie("Votre choix : ");

        if ($choix === '1') {
            $plats = getPlats();
            afficherCatalogueConsole($plats);
        } elseif ($choix === '2') {
            $nom = demanderSaisie("Nom du plat : ");
            $prix = demanderSaisie("Prix du plat : ");
            $description = demanderSaisie("Description du plat : ");

            $erreurs = validerPlat($nom, $prix, $description);

            if (empty($erreurs)) {
                $plat = [
                    'nom' => $nom,
                    'prix' => floatval($prix),
                    'description' => $description
                ];
                savePlat($plat);
                afficherSucces("Le plat a été ajouté avec succès !");
            } else {
                foreach ($erreurs as $erreur) {
                    afficherErreur($erreur);
                }
            }
        } elseif ($choix === '3') {
            break;
        } else {
            afficherErreur("Choix invalide.");
        }
    }
}
