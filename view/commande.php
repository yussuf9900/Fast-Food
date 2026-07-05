<?php

require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../model/livreur.php';
require_once __DIR__ . '/../service/commande.php';

function afficherCommandesConsole($commandes) {
    if (empty($commandes)) {
        echo "Aucune commande trouvée.\n";
        return;
    }
    echo "\n=== LISTE DES COMMANDES ===\n";
    foreach ($commandes as $commande) {
        $total = calculerTotalCommande($commande['lignes']);
        $livreurText = 'Aucun';
        if ($commande['id_livreur'] !== null) {
            $livreur = getLivreurById($commande['id_livreur']);
            if ($livreur) {
                $livreurText = $livreur['nom'];
            }
        }
        echo "Réf       : " . $commande['id_commande'] . "\n";
        echo "Statut    : " . $commande['statut'] . "\n";
        echo "Total     : " . $total . " FCFA\n";
        echo "Livreur   : " . $livreurText . "\n";
        echo "Paiement  : " . $commande['paiement'] . "\n";
        echo "Plats commandés :\n";
        foreach ($commande['lignes'] as $ligne) {
            $plat = getPlatById($ligne['id_plat']);
            if ($plat) {
                echo "  - " . $plat['nom'] . " x" . $ligne['quantite'] . "\n";
            }
        }
        echo "---------------------------\n";
    }
}

function afficherMenuGestionCommandes() {
    echo "\n=== ESPACE GÉRANT : COMMANDES ===\n";
    echo "1. Lister toutes les commandes\n";
    echo "2. Lister les commandes en attente\n";
    echo "3. Valider une commande (Passer en préparation)\n";
    echo "4. Assigner un livreur (Passer en livraison)\n";
    echo "5. Retour au menu Gérant\n";
    echo "---------------------------\n";
}
