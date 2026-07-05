<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['gerants'])) {
    $_SESSION['gerants'] = [];
}

function getGerants() {
    return $_SESSION['gerants'];
}
