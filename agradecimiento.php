<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agradecimiento por tu Compra</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
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
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Agradecemos tu Compra</h2>
        <p>Gracias por elegir nuestros productos. Tu compra ha sido realizada con éxito.</p>
        <p>Detalles de tu pedido:</p>

        <?php
        // Verificar si existen detalles en el carrito
        if (!empty($_SESSION['carrito'])) {
            // Obtener el último pedido realizado por el usuario
            $usuario_id = $_SESSION['user_id'];
            $stmt_pedido = $pdo->prepare("SELECT * FROM Pedidos WHERE UsuarioID = ? ORDER BY PedidoID DESC LIMIT 1");
            $stmt_pedido->execute([$usuario_id]);
            $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

            if ($pedido) {
                // Obtener detalles del pedido desde la tabla DetallesPedidos
                $pedido_id = $pedido['PedidoID'];
                $stmt_detalles_pedido = $pdo->prepare("SELECT * FROM DetallesPedidos WHERE PedidoID = ?");
                $stmt_detalles_pedido->execute([$pedido_id]);

                while ($detalle_pedido = $stmt_detalles_pedido->fetch(PDO::FETCH_ASSOC)) {
                    // Mostrar detalles del artículo y cantidad comprada
                    echo "<p>Artículo: " . $detalle_pedido['ArticuloID'] . "</p>";
                    echo "<p>Cantidad: " . $detalle_pedido['Cantidad'] . "</p>";
                    echo "<p>Precio Unitario: " . $detalle_pedido['PrecioUnitario'] . " €</p>";
                    echo "<p>Total del Artículo: " . $detalle_pedido['TotalPedido'] . " €</p>";
                    echo "<hr>";
                }
            } else {
                echo "<p>No se encontraron detalles del pedido.</p>";
            }
        } else {
            echo "<p>No se encontraron detalles del pedido.</p>";
        }
        ?>

        <a href="index.php">Volver a la Página Principal</a>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
