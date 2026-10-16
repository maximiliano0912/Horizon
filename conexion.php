<?php
$host = "localhost";
$user = "root";       
$pass = "";           
$db   = "horizon";    

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>