<?php

require_once __DIR__ . '/../view/client.php';
require_once __DIR__ . '/../view/plat.php';
require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../model/commande.php';
require_once __DIR__ . '/../service/commande.php';
require_once __DIR__ . '/../utils/cli.php';
require_once __DIR__ . '/../utils/reference.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

function executerEspaceClient() {
    while (true) {
        afficherMenuClient();
        $choix = demanderSaisie("Votre choix : ");

        if ($choix === '1') {
            $plats = getPlats();
            afficherCatalogueConsole($plats);
        } elseif ($choix === '2') {
            $plats = getPlats();
            if (empty($plats)) {
                afficherErreur("Aucun plat n'est disponible dans le catalogue.");
                continue;
            }
            afficherCatalogueConsole($plats);
            $idPlat = demanderSaisie("Entrez l'ID du plat à ajouter au panier : ");
            $plat = getPlatById(intval($idPlat));

            if ($plat) {
                $quantite = demanderSaisie("Entrez la quantité : ");
                $qteInt = intval($quantite);
                if ($qteInt > 0) {
                    $trouve = false;
                    foreach ($_SESSION['panier'] as &$ligne) {
                        if ($ligne['id_plat'] == $plat['id']) {
                            $ligne['quantite'] += $qteInt;
                            $trouve = true;
                            break;
                        }
                    }
                    if (!$trouve) {
                        $_SESSION['panier'][] = [
                            'id_plat' => $plat['id'],
                            'quantite' => $qteInt
                        ];
                    }
                    afficherSucces("Plat ajouté au panier !");
                } else {
                    afficherErreur("La quantité doit être supérieure à 0.");
                }
            } else {
                afficherErreur("Plat introuvable.");
            }
        } elseif ($choix === '3') {
            afficherPanier($_SESSION['panier']);
        } elseif ($choix === '4') {
            if (empty($_SESSION['panier'])) {
                afficherErreur("Votre panier est vide.");
                continue;
            }

            afficherPanier($_SESSION['panier']);
            $total = calculerTotalCommande($_SESSION['panier']);
            afficherInfo("Total à payer : " . $total . " FCFA");

            $reponse = demanderSaisie("Simuler le paiement ? Saisissez 'oui' pour accepter : ");

            if (validerPaiementSimule($reponse)) {
                $ref = genererReferenceCommande();
                $commande = [
                    'id_commande' => $ref,
                    'client_id' => 1,
                    'statut' => 'En attente',
                    'lignes' => $_SESSION['panier'],
                    'id_livreur' => null,
                    'paiement' => 'Accepte'
                ];
                saveCommande($commande);
                $_SESSION['panier'] = [];
                afficherSucces("Commande validée et enregistrée avec succès ! Référence : " . $ref);
            } else {
                afficherErreur("Paiement refusé ou annulé.");
            }
        } elseif ($choix === '5') {
            afficherInfo("Déconnexion de l'espace Client.");
            break;
        } else {
            afficherErreur("Choix invalide.");
        }
    }
}
