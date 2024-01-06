<?php
$host = 'sql210.infinityfree.com';
$database = 'if0_35665002_tienda_virtual';
$username = 'if0_35665002';
$password = 'gVS6umS01vgjWi';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>