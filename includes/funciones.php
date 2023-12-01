<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo) : bool {
    if($actual !== $proximo) {
        return true;
    }
    return false;
}

function isSession() : void {
    if(!isset($_SESSION)) {
        session_start();
    }
}

function isAdmin() {
    if(!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}

// Funcion que revisa que el usuario este autenticado
function isAuth(): void {
    if(!isset($_SESSION['login'])) { // si no esta definida y si su valor es null
        header('Location: /'); // Si es false o null entonces lo mandoamos a la ruta raiz
    }
}

