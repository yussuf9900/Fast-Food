<?php

function demanderSaisie($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

function afficherSucces($message) {
    echo "\033[32m" . $message . "\033[0m\n";
}

function afficherErreur($message) {
    echo "\033[31m" . $message . "\033[0m\n";
}

function afficherInfo($message) {
    echo "\033[34m" . $message . "\033[0m\n";
}
