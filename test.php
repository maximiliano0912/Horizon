<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/db.php';

echo 'Conexión OK. Clientes en la base: ' .
    $pdo->query('SELECT COUNT(*) FROM clientes')->fetchColumn();