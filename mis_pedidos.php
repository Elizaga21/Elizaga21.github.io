<?php
include 'db_connection.php';
include 'header.php';

if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'cliente') {
    $user_id = $_SESSION['user_id'];

    $stmt_user = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user = $stmt_user->fetch();

    $stmt_orders = $pdo->prepare("SELECT * FROM Pedidos WHERE UsuarioID = ?");
    $stmt_orders->execute([$user_id]);
    $orders = $stmt_orders->fetchAll();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar la actualización del EstadoPedido
        if (isset($_POST['updateOrder'])) {
            $pedidoID = $_POST['pedidoID'];
            $nuevoEstado = $_POST['nuevoEstado'];

            $stmt_update_order = $pdo->prepare("UPDATE Pedidos SET EstadoPedido = ? WHERE PedidoID = ?");
            $stmt_update_order->execute([$nuevoEstado, $pedidoID]);
        }

        // Procesar la cancelación del pedido dentro de las primeras 24 horas
        if (isset($_POST['cancelOrder'])) {
            $pedidoID = $_POST['pedidoID'];

            $stmt_get_order_date = $pdo->prepare("SELECT Fecha FROM Pedidos WHERE PedidoID = ?");
            $stmt_get_order_date->execute([$pedidoID]);
            $fechaPedido = $stmt_get_order_date->fetchColumn();

            $fechaActual = date('Y-m-d');
            $diferenciaFechas = strtotime($fechaActual) - strtotime($fechaPedido);
            $diferenciaHoras = $diferenciaFechas / (60 * 60);

            if ($diferenciaHoras <= 24) {
                // Realizar la cancelación del pedido
                $stmt_cancel_order = $pdo->prepare("UPDATE Pedidos SET EstadoPedido = 'Cancelado' WHERE PedidoID = ?");
                $stmt_cancel_order->execute([$pedidoID]);
            } else {
                $error_message = "No se puede cancelar el pedido después de 24 horas.";
        }
    }
    }
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mis Pedidos</title>
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

#content {
            margin-top: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #content h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .order {
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            width: 70%;
            margin: 0 auto;
        }

        .order p {
            margin: 0 0 10px;
        }

        .order ul {
            list-style: none;
            padding: 0;
        }

        .order li {
            margin-bottom: 5px;
        }

        .order li strong {
            margin-right: 5px;
        }
         /* Estilos para el formulario de actualizar EstadoPedido */
         .update-form {
             margin-top: 10px;
         }
         
         .update-form label {
             margin-right: 10px;
         }
         
         .update-form select {
             width: 80px; 
             padding: 5px;
             margin-right: 10px;
         }
         
         .update-form button {
             padding: 10px 15px; 
             margin-top: 10px;
             margin-bottom: 10px;
             background-color: #007bff; 
             color: #fff; 
             border: none;
             cursor: pointer;
         }
         
         .update-form button:hover {
             background-color: #0056b3; 
         }
         
         .cancelOrder-form {
           margin-left: auto; 
           }
         .cancelOrder-form button {
             padding: 10px 15px; 
             margin-top: 10px;
             margin-bottom: 10px; 
             background-color: #dc3545; 
             color: #fff; 
             border: none;
             cursor: pointer;
         }
         
         .cancelOrder-form button:hover {
             background-color: #c82333; 
         }

         .error-message {
    color: #dc3545; 
    margin-top: 10px;
    font-weight: bold;
}


    </style>
</head>
    <body>
    <div id="container">

        <div id="content">
            <h2>Historial de Pedidos de <?= htmlspecialchars($user['nombre']) ?></h2>

            <?php foreach ($orders as $order): ?>
                <?php
                $stmt_order_details = $pdo->prepare("SELECT * FROM DetallesPedidos WHERE PedidoID = ?");
                $stmt_order_details->execute([$order['PedidoID']]);
                $order_details = $stmt_order_details->fetchAll();

                // Obtener la fecha del pedido
                $stmt_get_order_date = $pdo->prepare("SELECT Fecha FROM Pedidos WHERE PedidoID = ?");
                $stmt_get_order_date->execute([$order['PedidoID']]);
                $fechaPedido = $stmt_get_order_date->fetchColumn();

                // Calcular la diferencia en horas
                $fechaActual = date('Y-m-d');
                $diferenciaFechas = strtotime($fechaActual) - strtotime($fechaPedido);
                $diferenciaHoras = $diferenciaFechas / (60 * 60);

                // Verificar si han pasado más de 24 horas y si es el pedido en el que se hizo clic
                $mostrarError = $diferenciaHoras > 24 && isset($_POST['cancelOrder']) && $_POST['pedidoID'] == $order['PedidoID'];
                ?>


                <div class="order">
                    <p><strong>Número de Pedido:</strong> <?= htmlspecialchars($order['PedidoID']) ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($order['Fecha']) ?></p>
                    <p><strong>Estado:</strong> <?= htmlspecialchars($order['EstadoPedido']) ?></p>

                    <h3>Detalles del Pedido:</h3>
                    <ul>
                        <?php foreach ($order_details as $detail): ?>
                            <li>
                                Artículo: <?= htmlspecialchars($detail['ArticuloID']) ?>, 
                                Cantidad: <?= htmlspecialchars($detail['Cantidad']) ?>, 
                                Total Pedido: <?= htmlspecialchars($detail['TotalPedido']) ?>
                            </li>

                            
                        <?php endforeach; ?>
                    </ul>

                    <form action="" method="post" class="update-form">
    <label for="nuevoEstado">Actualizar EstadoPedido:</label>
    <select name="nuevoEstado" id="nuevoEstado">
        <option value="Pendiente">Pendiente</option>
        <option value="En Proceso">En Proceso</option>
        <option value="Enviado">Enviado</option>
        <option value="Completado">Completado</option>
    </select>
    <input type="hidden" name="pedidoID" value="<?= $order['PedidoID'] ?>">
    <button type="submit" name="updateOrder">Actualizar Pedido</button>
</form>

 <!-- Formulario para cancelar el pedido con identificador único -->
 <form action="" method="post" class="cancelOrder-form" id="cancelOrderForm<?= $order['PedidoID'] ?>">
                        <input type="hidden" name="pedidoID" value="<?= $order['PedidoID'] ?>">
                        <button type="submit" name="cancelOrder">Cancelar Pedido</button>
                    </form>

                    <!-- Mensaje de error específico para este pedido -->
                    <?php if ($mostrarError): ?>
                        <p class="error-message">No se puede cancelar el pedido después de 24 horas.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    </body>
    </html>

    <?php
} else {
    header("Location: login.php");
    exit();
}
?>
