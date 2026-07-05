<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/model/client.php';
require_once __DIR__ . '/model/gerant.php';
require_once __DIR__ . '/model/livreur.php';
require_once __DIR__ . '/model/plat.php';
require_once __DIR__ . '/model/commande.php';

require_once __DIR__ . '/controller/auth.php';

if (php_sapi_name() === 'cli') {
    executerAuthentification();
}
