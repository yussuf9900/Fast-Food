<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['commandes'])) {
    $_SESSION['commandes'] = [
        [
            'id_commande' => 'CMD-1042',
            'client_id' => 1,
            'statut' => 'En attente',
            'lignes' => [
                ['id_plat' => 1, 'quantite' => 1],
                ['id_plat' => 3, 'quantite' => 2]
            ],
            'id_livreur' => null,
            'paiement' => 'Accepte'
        ]
    ];
}

function getCommandes() {
    return $_SESSION['commandes'];
}

function getCommandeById($id_commande) {
    foreach ($_SESSION['commandes'] as $commande) {
        if ($commande['id_commande'] === $id_commande) {
            return $commande;
        }
    }
    return null;
}

function saveCommande($commande) {
    $_SESSION['commandes'][] = $commande;
    return $commande;
}

function updateCommandeStatus($id_commande, $statut) {
    foreach ($_SESSION['commandes'] as &$commande) {
        if ($commande['id_commande'] === $id_commande) {
            $commande['statut'] = $statut;
            return true;
        }
    }
    return false;
}

function updateCommandeLivreur($id_commande, $id_livreur) {
    foreach ($_SESSION['commandes'] as &$commande) {
        if ($commande['id_commande'] === $id_commande) {
            $commande['id_livreur'] = $id_livreur;
            return true;
        }
    }
    return false;
}

function enregistrerDateLivraison($id_commande) {
    foreach ($_SESSION['commandes'] as &$commande) {
        if ($commande['id_commande'] === $id_commande) {
            $commande['livraison_commencee_a'] = time();
            return true;
        }
    }
    return false;
}
