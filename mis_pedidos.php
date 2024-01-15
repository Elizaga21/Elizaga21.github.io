<?php
include 'db_connection.php';
include 'header.php';

// Check if the user is logged in and has the 'cliente' role
if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'cliente') {
    $user_id = $_SESSION['user_id'];

    // Fetch user details
    $stmt_user = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user = $stmt_user->fetch();

    // Fetch orders for the user
    $stmt_orders = $pdo->prepare("SELECT * FROM Pedidos WHERE UsuarioID = ?");
    $stmt_orders->execute([$user_id]);
    $orders = $stmt_orders->fetchAll();

    // Display user details and orders
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mis Pedidos</title>
        <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">    </head>
    <body>
    <div id="container">
        <?php include 'menu_izquierda.php'; ?>
        <div id="content">
            <h2>Historial de Pedidos de <?= htmlspecialchars($user['nombre']) ?></h2>

            <?php
            foreach ($orders as $order) {
                // Fetch order details
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
    // Redirect to login page if the user is not logged in or doesn't have the 'cliente' role
    header("Location: login.php");
    exit();
}
?>
