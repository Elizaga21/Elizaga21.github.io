<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}
require 'db_connection.php';

// Consulta para obtener los artículos más vendidos
$sql = "SELECT Imagen, COUNT(*) as Cantidad FROM Articulos WHERE masvendido = '1' GROUP BY Imagen ORDER BY Cantidad DESC LIMIT 5";
$stmt = $pdo->query($sql);
$articulosMasVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos Más Vendidos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .container {
            max-width: 800px;
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #495057;
        }

        .articulos-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .articulo {
            text-align: center;
            margin-bottom: 20px;
        }

        .articulo img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Artículos Más Vendidos</h2>
        <div class="articulos-container">
            <?php foreach ($articulosMasVendidos as $articulo): ?>
                <div class="articulo">
                    <img src="<?php echo $articulo['Imagen']; ?>" alt="Artículo">
                    <p>Cantidad: <?php echo $articulo['Cantidad']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
