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
$stmt = $pdo->prepare("SELECT a.Nombre, a.Precio, a.Imagen, dp.Cantidad, dp.TotalPedido, p.Fecha, p.EstadoPedido
                      FROM DetallesPedidos dp
                      JOIN Articulos a ON dp.ArticuloID = a.Codigo
                      JOIN Pedidos p ON dp.PedidoID = p.PedidoID
                      WHERE p.UsuarioID = ? AND p.EstadoPedido IN ('En Proceso', 'Enviado', 'Completado')
                      ORDER BY p.Fecha DESC");

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

        #container {
            max-width: 800px;
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; 
        }


        .articulos-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .articulo {
            width: 300px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .articulo strong {
            display: block;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
        }

        .articulo p {
            margin: 5px 0;
        }

        .articulo img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
        }

        .container h2 {
            text-align: center; 
            margin-top: 10px;
            padding: 10px;
        }
      
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Artículos Comprados</h2>
        <?php if (!empty($articulosComprados)): ?>
            <div class="articulos-container">
                <?php foreach ($articulosComprados as $articulo): ?>
                    <div class="articulo">
                        <strong><?php echo $articulo['Nombre']; ?></strong>
                        <p>Cantidad: <?php echo $articulo['Cantidad']; ?></p>
                        <p>Precio: <?php echo $articulo['Precio']; ?> €</p>
                        <p>Total del Pedido: <?php echo $articulo['TotalPedido']; ?> €</p>
                        <p>Fecha de Compra: <?php echo $articulo['Fecha']; ?></p>
                        <p>Estado del Pedido: <?php echo $articulo['EstadoPedido']; ?></p>
                        <img src="<?php echo $articulo['Imagen']; ?>" alt="<?php echo $articulo['Nombre']; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No has comprado ningún artículo aún.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>