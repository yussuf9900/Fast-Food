<?php

require_once __DIR__ . '/../view/commande.php';
require_once __DIR__ . '/../model/commande.php';
require_once __DIR__ . '/../model/livreur.php';
require_once __DIR__ . '/../service/livreur.php';
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
    while (true) {
        afficherMenuGestionCommandes();
        $choix = demanderSaisie("Votre choix : ");

        if ($choix === '1') {
            $commandes = getCommandes();
            afficherCommandesConsole($commandes);
        } elseif ($choix === '2') {
            $commandes = getCommandes();
            $attente = [];
            foreach ($commandes as $commande) {
                if ($commande['statut'] === 'En attente') {
                    $attente[] = $commande;
                }
            }
            afficherCommandesConsole($attente);
        } elseif ($choix === '3') {
            $ref = demanderSaisie("Référence de la commande à valider : ");
            $commande = getCommandeById($ref);
            if ($commande && $commande['statut'] === 'En attente') {
                updateCommandeStatus($ref, 'En préparation');
                afficherSucces("La commande est maintenant en préparation !");
            } else {
                afficherErreur("Commande introuvable ou statut incorrect (doit être 'En attente').");
            }
        } elseif ($choix === '4') {
            $ref = demanderSaisie("Référence de la commande à livrer : ");
            $commande = getCommandeById($ref);
            if ($commande && $commande['statut'] === 'En préparation') {
                $livreurs = getLivreurs();
                echo "\nLivreurs disponibles :\n";
                foreach ($livreurs as $livreur) {
                    $dispoText = $livreur['disponible'] ? "Disponible" : "Occupé";
                    echo "ID: " . $livreur['id'] . " | Nom: " . $livreur['nom'] . " | " . $dispoText . "\n";
                }
                $idLivreur = demanderSaisie("Entrez l'ID du livreur : ");
                $idLivreurInt = intval($idLivreur);

                if (validerDisponibiliteLivreur($idLivreurInt)) {
                    updateCommandeLivreur($ref, $idLivreurInt);
                    updateCommandeStatus($ref, 'En livraison');
                    updateLivreurStatus($idLivreurInt, false);
                    afficherSucces("Livreur assigné ! La commande est maintenant en livraison.");
                } else {
                    afficherErreur("Livreur non disponible ou inexistant.");
                }
            } else {
                afficherErreur("Commande introuvable ou statut incorrect (doit être 'En préparation').");
            }
        } elseif ($choix === '5') {
            break;
        } else {
            afficherErreur("Choix invalide.");
        }
    }
}
