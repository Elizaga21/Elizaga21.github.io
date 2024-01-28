<?php
session_start();
require 'db_connection.php';

// Verificar si el usuario está autenticado como cliente
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Consulta para obtener los artículos comprados por el cliente
$stmt = $pdo->prepare("SELECT a.Codigo, a.Nombre, a.Descripcion, a.Precio, a.Imagen, c.Nombre AS Categoria, cp.FechaCompra
                      FROM Compras cp
                      JOIN Articulos a ON cp.CodigoArticulo = a.Codigo
                      JOIN Categorias c ON a.CategoriaID = c.CategoriaID
                      WHERE cp.IDUsuario = ?
                      ORDER BY cp.FechaCompra DESC");

$stmt->execute([$user_id]);
$articulosComprados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos Comprados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
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

        ul {
            list-style: none;
            padding: 0;
        }

        .container p {
            color: #495057;
            margin-top: 20px;
        }

        .container p::before {
            content: "\2022"; 
            color: #007bff;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .container p img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .container p a {
            color: #007bff;
        }

        .container p a:hover {
            text-decoration: underline;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Artículos Comprados</h2>
        <?php if (!empty($articulosComprados)): ?>
            <ul>
                <?php foreach ($articulosComprados as $articulo): ?>
                    <li>
                        <strong><?php echo $articulo['Nombre']; ?></strong>
                        <p><?php echo $articulo['Descripcion']; ?></p>
                        <p>Categoría: <?php echo $articulo['Categoria']; ?></p>
                        <p>Precio: <?php echo $articulo['Precio']; ?> €</p>
                        <p>Fecha de Compra: <?php echo $articulo['FechaCompra']; ?></p>
                        <img src="<?php echo $articulo['Imagen']; ?>" alt="<?php echo $articulo['Nombre']; ?>">
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No has comprado ningún artículo aún.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>