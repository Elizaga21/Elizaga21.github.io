<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
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

.admin-links {
    margin-top: 20px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column; 
    align-items: center; 
}

.admin-link {
    text-decoration: none;
    color: #495057;
    padding: 10px;
    margin: 5px;
    border: 1px solid #000;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
}

.admin-link:hover {
    background-color: #000;
    color: #fff;
}

.admin-links::before {
    content: "Mantenimiento";
    font-weight: bold;
    margin-bottom: 10px;
    color: #000;
}


    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
        <h2>Panel de Administrador</h2>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <?php
            $stmt_user = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
            $stmt_user->execute([$_SESSION['user_id']]);
            $admin = $stmt_user->fetch();
            ?>
            <p class="textoBienvenida">Bienvenido, <?php echo $admin['nombre']; ?></p>
        <?php endif; ?>

        
        <div class="admin-links">
            <a href="informe_usuarios.php" class="admin-link">Informe de Usuarios</a>
            <a href="informe_articulos.php" class="admin-link">Informe de Artículos</a>
            <a href="mantenimiento_categorias.php" class="admin-link">Mantenimiento de Categorías</a>
            <a href="estadisticas_pedidos.php" class="admin-link">Estadísticas de Pedidos</a>
            <a href="productos_mas_vendidos.php" class="admin-link">Productos más Vendidos</a>
            <a href="ventas.php" class="admin-link">Ventas del Mes</a>
        </div>

    </div>


    <?php include 'footer.php'; ?>
</body>
</html>



    
  