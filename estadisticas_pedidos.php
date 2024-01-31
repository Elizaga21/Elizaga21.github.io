<?php
session_start();
require 'db_connection.php';
include 'header.php';

// Función para modificar el EstadoPedido
function modificarEstadoPedido($pdo, $pedidoID, $nuevoEstado) {
    $stmt = $pdo->prepare("UPDATE Pedidos SET EstadoPedido = ? WHERE PedidoID = ?");
    $stmt->execute([$nuevoEstado, $pedidoID]);
}

// Función para eliminar un pedido (baja lógica)
function eliminarPedido($pdo, $pedidoID) {
    $stmtEliminarDetalles = $pdo->prepare("DELETE FROM DetallesPedidos WHERE PedidoID = ?");
    $stmtEliminarDetalles->execute([$pedidoID]);

    $stmtEliminarPedido = $pdo->prepare("UPDATE Pedidos SET activo = 0 WHERE PedidoID = ?");
    $stmtEliminarPedido->execute([$pedidoID]);
}


// Verificar si se envió el formulario para modificar el EstadoPedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modificar_estado'])) {
        $pedidoID = $_POST['pedido_id'];
        $nuevoEstado = $_POST['nuevo_estado'];
        modificarEstadoPedido($pdo, $pedidoID, $nuevoEstado);
    } elseif (isset($_POST['eliminar_pedido'])) {
        $pedidoID = $_POST['pedido_id'];
        eliminarPedido($pdo, $pedidoID);
    }
}

// Paginación
$elementosPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicio = ($paginaActual - 1) * $elementosPorPagina;

// Consulta para obtener detalles de pedidos con límite y desplazamiento (solo registros activos)
$sqlPedidos = "SELECT P.PedidoID, P.UsuarioID, P.EstadoPedido, P.Fecha, D.TotalPedido 
               FROM Pedidos P 
               JOIN DetallesPedidos D ON P.PedidoID = D.PedidoID
               WHERE P.activo = 1
               LIMIT :indiceInicio, :elementosPorPagina";
$stmtPedidos = $pdo->prepare($sqlPedidos);
$stmtPedidos->bindParam(':indiceInicio', $indiceInicio, PDO::PARAM_INT);
$stmtPedidos->bindParam(':elementosPorPagina', $elementosPorPagina, PDO::PARAM_INT);
$stmtPedidos->execute();
$pedidosPaginados = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para contar el total de pedidos (solo registros activos)
$sqlTotalPedidos = "SELECT COUNT(*) as total FROM Pedidos WHERE activo = 1";
$stmtTotalPedidos = $pdo->query($sqlTotalPedidos);
$totalPedidos = $stmtTotalPedidos->fetch(PDO::FETCH_ASSOC)['total'];
$paginasTotales = ceil($totalPedidos / $elementosPorPagina);

// Consulta para obtener estadísticas de pedidos (solo registros activos)
$sqlEstadisticas = "SELECT EstadoPedido, COUNT(*) as Cantidad FROM Pedidos WHERE activo = 1 GROUP BY EstadoPedido";
$stmtEstadisticas = $pdo->query($sqlEstadisticas);
$estadisticasPedidos = $stmtEstadisticas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas y Detalles de Pedidos</title>
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
            width: 120%;
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

        .btn-group {
        margin-right: 10px;
        margin-bottom: 10px; 
           }
       
           .pagination {
           display: inline-block;
           margin-bottom: 0;
       }
       
       .pagination a {
           color: #007bff;
           padding: 8px 16px;
           text-decoration: none;
           transition: background-color 0.3s;
           border: 1px solid #ddd;
       }
       
       .pagination a:hover {
           background-color: #007bff;
           color: #fff;
           border: 1px solid #007bff;
       }
       
       .pagination .active a {
           background-color: #007bff;
           color: #fff;
           border: 1px solid #007bff;
       }
       
       .pagination li {
           display: inline-block;
           margin-right: 5px;
       }
       
       .pagination li:last-child {
           margin-right: 0;
       }
    </style>
</head>
<body>

<div class="container">
        <h2>Estadísticas y Detalles de Pedidos</h2>
        
        <!-- Estadísticas de Pedidos -->
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

        <!-- Lista de Pedidos -->
        <header>
            <h1>Lista de Pedidos</h1>
        </header>
        <div class="table-container">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>PedidoID</th>
                        <th>UsuarioID</th>
                        <th>Estado del Pedido</th>
                        <th>Fecha</th>
                        <th>Total del Pedido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidosPaginados as $pedido): ?>
                        <tr>
                            <td><?php echo $pedido['PedidoID']; ?></td>
                            <td><?php echo $pedido['UsuarioID']; ?></td>
                            <td><?php echo $pedido['EstadoPedido']; ?></td>
                            <td><?php echo $pedido['Fecha']; ?></td>
                            <td><?php echo $pedido['TotalPedido']; ?></td>
                            <td>
                                <div class="btn-group">
                                    <!-- Formulario para modificar estado del pedido -->
                                    <form method="post" action="">
                                        <input type="hidden" name="pedido_id" value="<?php echo $pedido['PedidoID']; ?>">
                                        <select name="nuevo_estado" class="form-control">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="En Proceso">En Proceso</option>
                                            <option value="Enviado">Enviado</option>
                                            <option value="Completado">Completado</option>
                                        </select>
                                        <button type="submit" name="modificar_estado" class="btn btn-primary">Modificar Estado</button>
                                    </form>
                                </div>
                                <div class="btn-group">
                                    <!-- Formulario para eliminar pedido -->
                                    <form method="post" action="">
                                        <input type="hidden" name="pedido_id" value="<?php echo $pedido['PedidoID']; ?>">
                                        <button type="submit" name="eliminar_pedido" class="btn btn-danger">Eliminar Pedido</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

         <!-- Paginación -->
         <div style="text-align: center; margin-top: 20px;">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $paginasTotales; $i++): ?>
                    <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>