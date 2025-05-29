<?php


function getDBConnection()
{
    $host = "localhost";
    $db_name = 'optitask';
    $username = "admin";
    $password = "admin123";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        return null;
    }
}
