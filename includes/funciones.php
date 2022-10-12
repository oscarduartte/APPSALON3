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
    if ($actual !== $proximo){
        return true;
    } 
    
    return false;
}

//funcion para revisar que le usuario este autenticado

function isAuth() : void {
    if (!isset($_SESSION['login'])) { //si login existe (desde logincontroller)
        header('Location:/'); //sino existe se manda a location:/ pa iniciar sesion
    }
}

function isAdmin() : void {
    if(!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}
