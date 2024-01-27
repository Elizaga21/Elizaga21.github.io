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


    </style>
</head>
    <body>
    <div id="container">

        <div id="content">
            <h2>Historial de Pedidos de <?= htmlspecialchars($user['nombre']) ?></h2>

            <?php
            foreach ($orders as $order) {
           
                $stmt_order_details = $pdo->prepare("SELECT * FROM DetallesPedidos WHERE PedidoID = ?");
                $stmt_order_details->execute([$order['PedidoID']]);
                $order_details = $stmt_order_details->fetchAll();
                ?>

                <div class="order">
                    <p><strong>Número de Pedido:</strong> <?= htmlspecialchars($order['PedidoID']) ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($order['Fecha']) ?></p>
                    <p><strong>Estado:</strong> <?= htmlspecialchars($order['EstadoPedido']) ?></p>

                    <h3>Detalles del Pedido:</h3>
                    <ul>
                        <?php
                        foreach ($order_details as $detail) {
                            echo "<li>";
                            echo "Artículo: {$detail['ArticuloID']}, Cantidad: {$detail['Cantidad']}, Precio Unitario: {$detail['PrecioUnitario']}";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php } ?>

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
