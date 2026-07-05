<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/model/client.php';
require_once __DIR__ . '/model/gerant.php';
require_once __DIR__ . '/model/livreur.php';
require_once __DIR__ . '/model/plat.php';
require_once __DIR__ . '/model/commande.php';

require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/plat.php';
require_once __DIR__ . '/service/commande.php';
require_once __DIR__ . '/service/livreur.php';

require_once __DIR__ . '/utils/reference.php';

require_once __DIR__ . '/view/auth.php';
require_once __DIR__ . '/view/client.php';
require_once __DIR__ . '/view/gerant.php';

require_once __DIR__ . '/controller/auth.php';

if (php_sapi_name() === 'cli') {
    executerAuthentification();
} else {
    $erreurLogin = null;
    $messageSucces = null;
    $messageErreur = null;
    $erreursFormPlat = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = isset($_POST['action']) ? $_POST['action'] : '';

        if ($action === 'login') {
            $role = isset($_POST['role']) ? $_POST['role'] : '';
            if ($role === 'client') {
                $_SESSION['role'] = 'client';
                $_SESSION['panier'] = [];
                header('Location: index.php');
                exit();
            } elseif ($role === 'gerant') {
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                if (validerMotDePasseGerant($password)) {
                    $_SESSION['role'] = 'gerant';
                    header('Location: index.php');
                    exit();
                } else {
                    $erreurLogin = "Mot de passe gérant incorrect.";
                }
            }
        } elseif ($action === 'logout') {
            session_destroy();
            header('Location: index.php');
            exit();
        }

        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'client') {
                if ($action === 'ajouter_panier') {
                    $idPlat = intval($_POST['id_plat']);
                    $qte = intval($_POST['quantite']);
                    if ($qte > 0 && getPlatById($idPlat)) {
                        $trouve = false;
                        foreach ($_SESSION['panier'] as &$ligne) {
                            if ($ligne['id_plat'] == $idPlat) {
                                $ligne['quantite'] += $qte;
                                $trouve = true;
                                break;
                            }
                        }
                        if (!$trouve) {
                            $_SESSION['panier'][] = [
                                'id_plat' => $idPlat,
                                'quantite' => $qte
                            ];
                        }
                        $_SESSION['message_succes'] = "Plat ajouté au panier !";
                    }
                    header('Location: index.php');
                    exit();
                } elseif ($action === 'supprimer_panier') {
                    $idPlat = intval($_POST['id_plat']);
                    foreach ($_SESSION['panier'] as $index => $ligne) {
                        if ($ligne['id_plat'] == $idPlat) {
                            unset($_SESSION['panier'][$index]);
                            $_SESSION['panier'] = array_values($_SESSION['panier']);
                            $_SESSION['message_succes'] = "Article retiré du panier.";
                            break;
                        }
                    }
                    header('Location: index.php');
                    exit();
                } elseif ($action === 'valider_commande') {
                    if (!empty($_SESSION['panier'])) {
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
                        $_SESSION['message_succes'] = "Votre commande a été enregistrée avec succès ! Référence : " . $ref;
                    } else {
                        $_SESSION['message_erreur'] = "Votre panier est vide.";
                    }
                    header('Location: index.php');
                    exit();
                }
            } elseif ($_SESSION['role'] === 'gerant') {
                if ($action === 'ajouter_plat') {
                    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
                    $prix = isset($_POST['prix']) ? $_POST['prix'] : '';
                    $description = isset($_POST['description']) ? $_POST['description'] : '';

                    $erreurs = validerPlat($nom, $prix, $description);
                    if (empty($erreurs)) {
                        $plat = [
                            'nom' => $nom,
                            'prix' => floatval($prix),
                            'description' => $description
                        ];
                        savePlat($plat);
                        $_SESSION['message_succes'] = "Le plat a été ajouté au catalogue avec succès !";
                    } else {
                        $_SESSION['erreurs_form_plat'] = $erreurs;
                    }
                    header('Location: index.php');
                    exit();
                } elseif ($action === 'valider_commande_gerant') {
                    $idCommande = isset($_POST['id_commande']) ? $_POST['id_commande'] : '';
                    if (updateCommandeStatus($idCommande, 'En préparation')) {
                        $_SESSION['message_succes'] = "La commande " . $idCommande . " est passée en préparation.";
                    } else {
                        $_SESSION['message_erreur'] = "Impossible de valider la commande.";
                    }
                    header('Location: index.php');
                    exit();
                } elseif ($action === 'assigner_livreur_gerant') {
                    $idCommande = isset($_POST['id_commande']) ? $_POST['id_commande'] : '';
                    $idLivreur = intval($_POST['id_livreur']);
                    if (validerDisponibiliteLivreur($idLivreur)) {
                        updateCommandeLivreur($idCommande, $idLivreur);
                        updateCommandeStatus($idCommande, 'En livraison');
                        updateLivreurStatus($idLivreur, false);
                        $_SESSION['message_succes'] = "Livreur assigné et commande en livraison.";
                    } else {
                        $_SESSION['message_erreur'] = "Le livreur sélectionné n'est pas disponible.";
                    }
                    header('Location: index.php');
                    exit();
                }
            }
        }
    }

    if (isset($_SESSION['message_succes'])) {
        $messageSucces = $_SESSION['message_succes'];
        unset($_SESSION['message_succes']);
    }
    if (isset($_SESSION['message_erreur'])) {
        $messageErreur = $_SESSION['message_erreur'];
        unset($_SESSION['message_erreur']);
    }
    if (isset($_SESSION['erreurs_form_plat'])) {
        $erreursFormPlat = $_SESSION['erreurs_form_plat'];
        unset($_SESSION['erreurs_form_plat']);
    }

    if (!isset($_SESSION['role'])) {
        afficherMenuConnexionWeb($erreurLogin);
    } else {
        if ($_SESSION['role'] === 'client') {
            $commandes = getCommandes();
            $commandesClient = [];
            foreach ($commandes as $cmd) {
                if ($cmd['client_id'] === 1) {
                    $commandesClient[] = $cmd;
                }
            }
            afficherEspaceClientWeb(getPlats(), $_SESSION['panier'], $commandesClient, $messageSucces, $messageErreur);
        } elseif ($_SESSION['role'] === 'gerant') {
            afficherEspaceGerantWeb(getPlats(), getCommandes(), getLivreurs(), $messageSucces, $messageErreur, $erreursFormPlat);
        }
    }
}
