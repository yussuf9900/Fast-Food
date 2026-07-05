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

function mettreAJourStatutsLivraisons() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['commandes'])) {
        return;
    }
    
    foreach ($_SESSION['commandes'] as &$commande) {
        if ($commande['statut'] === 'En livraison' && isset($commande['livraison_commencee_a'])) {
            if (time() - $commande['livraison_commencee_a'] >= 30) {
                $commande['statut'] = 'Livrée';
                if ($commande['id_livreur'] !== null) {
                    updateLivreurStatus($commande['id_livreur'], true);
                }
            }
        }
    }
    unset($commande);
}
