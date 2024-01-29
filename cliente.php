<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
}

.container {
    text-align: center;
    max-width: 800px;
    width: 100%;
    margin: auto;
    padding: 20px;
}

h2, h3 {
    color: #495057;
}

.welcome-text {
    color: #28a745;
}

.order-form {
    margin-top: 20px;
}

.order-button {
    background-color: #000; 
    color: #fff; 
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.order-button:hover {
    background-color: #333; 
}

.cliente-links {
    margin-top: 20px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    align-items: center; 
}

.cliente-link {
    text-decoration: none;
    color: #495057;
    padding: 10px;
    margin: 5px;
    border: 1px solid #000;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
}

.cliente-link:hover {
    background-color: #000;
    color: #fff;
}

.cliente-links::before {
    content: "Consultar";
    font-weight: bold;
    margin-bottom: 10px;
    color: #000;
}
    </style>
</head>
<body>
    
    <?php include 'header.php'; ?>

<div class="container">
        <h2>Panel de Cliente</h2>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <?php
            $stmt_user = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
            $stmt_user->execute([$_SESSION['user_id']]);
            $cliente = $stmt_user->fetch();
            ?>
            <p class="textoBienvenida">Hola, <?php echo $cliente['nombre']; ?></p>
        <?php endif; ?>

        
        <div class="cliente-links">
            <a href="perfil.php" class="cliente-link">Perfil</a>
            <a href="mis_pedidos.php" class="cliente-link"> Estado Pedidos</a>
            <a href="articulos_comprados.php" class="cliente-link">Tus Artículos ya comprados</a>
            <a href="manual.php" class="cliente-link">Manual Usuario</a>
            <a href="devolucion.php" class="cliente-link">Política de Devolución</a>
            <a href="eliminar_cuenta.php" class="cliente-link">Eliminar Cuenta</a>


        </div>

    </div>

    <?php include 'footer.php'; ?>


</body>
</html>
