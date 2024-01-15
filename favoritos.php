<?php
session_start();
require 'db_connection.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario actual
$usuarioID = $_SESSION['user_id'];

// Obtener los artículos marcados como favoritos por el usuario
$stmt = $pdo->prepare("SELECT Articulos.* FROM Articulos
                      JOIN Favoritos ON Articulos.Codigo = Favoritos.ArticuloCodigo
                      WHERE Favoritos.UsuarioID = ?");
$stmt->execute([$usuarioID]);
$favoritos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Favoritos</title>
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
    max-width: 800px;
    width: 100%;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
}

h2 {
    font-size: 24px;
    margin-top: 20px;
    margin-bottom: 30px;
    color: #333;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    margin-bottom: 30px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
}

strong {
    font-size: 18px;
    color: #333;
}

p {
    margin-top: 10px;
    color: #555;
}

img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin-top: 15px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tus Artículos Favoritos</h2>

        <?php if (!empty($favoritos)) : ?>
            <ul>
                <?php foreach ($favoritos as $articulo) : ?>
                    <li>
                        <strong><?php echo $articulo['Nombre']; ?></strong>
                        <p><?php echo $articulo['Descripcion']; ?></p>
                        <p>Precio: $<?php echo number_format($articulo['Precio'], 2); ?></p>
                        <img src="<?php echo $articulo['Imagen']; ?>" alt="Imagen del artículo">
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No tienes ningún artículo marcado como favorito.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
