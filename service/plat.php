<?php

function validerPlat($nom, $prix, $description) {
    $erreurs = [];

    if (empty(trim($nom))) {
        $erreurs[] = "Le nom du plat est obligatoire.";
    }

    if (!is_numeric($prix) || $prix <= 0) {
        $erreurs[] = "Le prix doit être un nombre positif.";
    }

    return $erreurs;
}
