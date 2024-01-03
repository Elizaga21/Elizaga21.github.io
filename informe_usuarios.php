<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

// Lógica para generar el informe de usuarios (puedes adaptarla según tus necesidades)
$stmt = $pdo->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<!-- ... -->
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Informe de Usuarios</h2>
        <!-- Código para mostrar el informe -->
        <!-- ... -->
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
