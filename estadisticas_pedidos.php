<?php
session_start();
require 'db_connection.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Consulta para obtener estadísticas de pedidos
$sql = "SELECT EstadoPedido, COUNT(*) as Cantidad FROM Pedidos GROUP BY EstadoPedido";
$stmt = $pdo->query($sql);
$estadisticasPedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Pedidos</title>
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

.table-container {
    margin-top: 20px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th,
table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

table th {
    background-color: #007bff;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tr:hover {
    background-color: #e9ecef;
}
    </style>
<body>

    <div class="container">
        <h2>Estadísticas de Pedidos</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Estado del Pedido</th>
                    <th>Cantidad</th>
                </tr>
                <?php foreach ($estadisticasPedidos as $estadistica): ?>
                    <tr>
                        <td><?php echo $estadistica['EstadoPedido']; ?></td>
                        <td><?php echo $estadistica['Cantidad']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
