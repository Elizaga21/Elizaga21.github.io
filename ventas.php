<?php
require 'db_connection.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Consulta para obtener información de ventas
$sql = "SELECT Fecha, EstadoPedido, COUNT(*) as Cantidad, SUM(dp.Cantidad) as TotalVentas 
        FROM Pedidos p
        JOIN DetallesPedidos dp ON p.PedidoID = dp.PedidoID
        GROUP BY Fecha, EstadoPedido";
$stmt = $pdo->query($sql);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Ventas</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #000;
            color: #fff;
        }

        /* Agrega estilos adicionales según sea necesario */
    </style>
</head>
<body>

    <div class="container">
        <h2>Información de Ventas</h2>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Estado del Pedido</th>
                <th>Cantidad de Pedidos</th>
                <th>Total Ventas</th>
            </tr>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?php echo $venta['Fecha']; ?></td>
                    <td><?php echo $venta['EstadoPedido']; ?></td>
                    <td><?php echo $venta['Cantidad']; ?></td>
                    <td><?php echo $venta['TotalVentas']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

