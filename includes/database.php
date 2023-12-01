<?php

    $servername = $_ENV['DB_HOST'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $dbname = $_ENV['DB_NAME'];

    try {
        $db = new mysqli($servername, $username, $password, $dbname);
        $db->set_charset('utf8');
    } catch (mysqli_sql_exception $e) {
        echo "Error de Conexion: " . mysqli_connect_error();
        exit;
    }

// * Segunda Forma de Hacer la conexion

// $db = mysqli_connect('localhost', 'root', '', 'NomDb');


// if (!$db) {
//     echo "Error: No se pudo conectar a MySQL.";
//     echo "errno de depuración: " . mysqli_connect_errno();
//     echo "error de depuración: " . mysqli_connect_error();
//     exit;
// }
