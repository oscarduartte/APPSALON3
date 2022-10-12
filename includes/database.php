<?php

$db = mysqli_connect( //en esta parte reemplazo las variables del server de prueba, por las variables de entorno que estan en el archivo .env
    $_ENV['DB_HOST'], 
    $_ENV['DB_USER'],  
    $_ENV['DB_PASS'], 
    $_ENV['DB_BD']
);


$db->set_charset("utf8");

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
