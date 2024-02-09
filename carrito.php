<?php
session_start();

include 'header.php';


require 'db_connection.php';

$usuarioID = $_SESSION['user_id'];


if (isset($_POST['agregar_carrito'])) {
    $codigo_articulo = $_POST['codigo_articulo'];
    $cantidad = $_POST['cantidad'];

    $_SESSION['carrito'][$codigo_articulo] = $cantidad;

     header("Location: carrito.php?added_to_cart=true");
    exit();
}



define('STANDARD_SHIPPING_COST', 7.5); 

// Verificar si el usuario está autenticado
$user_authenticated = isset($_SESSION['user_id']);
$is_customer = $user_authenticated && $_SESSION['rol'] === 'cliente';


// Obtener detalles de artículos en el carrito
$carrito_detalles = [];
if (!empty($_SESSION['carrito'])) {
    $codigo_articulos = array_keys($_SESSION['carrito']);
    $placeholders = str_repeat('?,', count($codigo_articulos) - 1) . '?';

    $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE Codigo IN ($placeholders)");
    $stmt->execute($codigo_articulos);
    $carrito_detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Procesar la actualización del carrito si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_carrito'])) {
    foreach ($_POST['cantidad'] as $codigo_articulo => $cantidad) {
     
        $cantidad = max(1, min(10, intval($cantidad)));
        $_SESSION['carrito'][$codigo_articulo] = $cantidad;
    }
}

$total_price = 0.0;
foreach ($carrito_detalles as $articulo) {
    if ($articulo['enOferta']) {
        $item_price = $articulo['Precio'] * 0.90 * $_SESSION['carrito'][$articulo['Codigo']];
    } else {
        $item_price = $articulo['Precio'] * $_SESSION['carrito'][$articulo['Codigo']];
    }
    $total_price += $item_price;
}

// Añade el coste del envío
$total_price += (count($carrito_detalles) * STANDARD_SHIPPING_COST);

// Formatea el total del carrito con dos decimales
$total_price_formatted = number_format($total_price, 2);


// Procesar la compra si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['realizar_compra'])) {
     // Verificar si el usuario está autenticado y tiene el rol adecuado
     if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'cliente' && $_SESSION['rol'] !== 'navegante')) {
        header("Location: registro.php");
        exit();
    }
    try {
        $pdo->beginTransaction();

        // Crear un nuevo pedido
        $usuario_id = $_SESSION['user_id'];
        $fecha_pedido = date('Y-m-d');
        $estado_pedido = 'Pendiente';

        $stmt_datos_envio = $pdo->prepare("SELECT direccion, localidad, provincia, pais, codpos FROM usuarios WHERE id = ?");
        $stmt_datos_envio->execute([$usuario_id]);
        $datos_envio_usuario = $stmt_datos_envio->fetch(PDO::FETCH_ASSOC);
        
        // Insertar en la tabla Pedidos
        $stmt_pedido = $pdo->prepare("INSERT INTO Pedidos (UsuarioID, Fecha, EstadoPedido, DireccionEnvio, LocalidadEnvio, ProvinciaEnvio, PaisEnvio, CodPosEnvio, FormaPago) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_pedido->execute([
            $usuario_id,
            $fecha_pedido,
            $estado_pedido,
            $datos_envio_usuario['direccion'],
            $datos_envio_usuario['localidad'],
            $datos_envio_usuario['provincia'],
            $datos_envio_usuario['pais'],
            $datos_envio_usuario['codpos'],
            'Tarjeta de Crédito' 
        ]);
        
        $pedido_id = $pdo->lastInsertId();
        // Agregar detalles del pedido
        foreach ($carrito_detalles as $articulo) {
            $cantidad = $_SESSION['carrito'][$articulo['Codigo']];
            $precio_unitario = $articulo['Precio'];
            $item_price = $precio_unitario * $cantidad;

            // Actualizar la sesión con el total del pedido
            $total_pedido += $item_price;

        
            // Verificar si la entrada ya existe
            $stmt_verificar = $pdo->prepare("SELECT * FROM DetallesPedidos WHERE PedidoID = ? AND ArticuloID = ?");
            $stmt_verificar->execute([$pedido_id, $articulo['Codigo']]);
            $existente = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
        
            if (!$existente) {
               // Agregar a la tabla DetallesPedidos
           $stmt_detalle = $pdo->prepare("INSERT INTO DetallesPedidos (PedidoID, ArticuloID, Cantidad, PrecioUnitario, TotalPedido) VALUES (?, ?, ?, ?, ?)");
           $stmt_detalle->execute([$pedido_id, $articulo['Codigo'], $cantidad, $precio_unitario, $item_price]);
    
            }
        
        }

        // Limpiar el carrito
        unset($_SESSION['carrito']);

        // Confirmar la transacción
        $pdo->commit();

        header("Location: pasarela.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        echo "Error al procesar la compra: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
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
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        .cart-item {
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }

        .cart-item img {
            max-width: 150px; 
            height: auto;
            margin-bottom: 10px;
        }

        .cart-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .cart-item p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .cart-item a {
            color: #007bff;
            cursor: pointer;
        }

        .cart-buttons {
            margin-top: 20px;
        }

        input[name^="cantidad"] {
            width: 50px; 
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .empty-cart {
            color: #6c757d;
            font-size: 18px;
            margin-top: 20px;
        }

        .continue-shopping {
            margin-top: 20px;
            text-align: right;
        }

        .continue-shopping a {
            text-decoration: none;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
                   border-radius: 5px;
                   transition: background-color 0.3s ease, color 0.3s ease;
               }
       
         .continue-shopping a:hover {
            background-color: #218838;
         }
       
               .empty-cart-container {
           text-align: center;
           padding: 20px;
           background-color: #fff;
           border: 1px solid #ddd;
           border-radius: 8px;
           box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
           margin: 20px auto;
       }
       
       .empty-cart-icon {
           font-size: 48px;
           color: #6c757d;
           margin-bottom: 10px;
       }
       
       .empty-cart-message {
           font-size: 18px;
           color: #6c757d;
           margin-bottom: 20px;
       }
       

    </style>
</head>
<body>

    <div class="container">
            
        <?php if (!empty($carrito_detalles)): ?>
            <form action="carrito.php" method="post">
            <?php foreach ($carrito_detalles as $articulo): ?>
                <div class="cart-item">
                    <img src="<?php echo $articulo['Imagen']; ?>" alt="<?php echo $articulo['Nombre']; ?>">
                    <h3><?php echo $articulo['Nombre']; ?></h3>
                    <p>Cantidad: 
                        <input type="number" name="cantidad[<?php echo $articulo['Codigo']; ?>]" 
                        value="<?php echo $_SESSION['carrito'][$articulo['Codigo']]; ?>" 
                        min="1" max="10">
                    </p>
                    <?php if ($articulo['enOferta']): ?>
                        <p>Precio Antiguo: <del><?php echo $articulo['Precio']; ?> €</del></p>
                        <p>Precio Nuevo: <?php echo number_format($articulo['Precio'] * 0.90, 2); ?> €</p>
                    <?php else: ?>
                        <p>Precio: <?php echo $articulo['Precio']; ?> €</p>
                    <?php endif; ?>
                    <p>Total: <?php echo number_format(($articulo['enOferta'] ? ($articulo['Precio'] * 0.90 * $_SESSION['carrito'][$articulo['Codigo']]) : ($articulo['Precio'] * $_SESSION['carrito'][$articulo['Codigo']])), 2); ?> €</p>
                    <a href="eliminar_del_carrito.php?codigo_articulo=<?php echo $articulo['Codigo']; ?>">Eliminar del Carrito</a>
                </div>
            <?php endforeach; ?>

                <p>Envío: <?php echo STANDARD_SHIPPING_COST; ?> € por cada artículo</p>

                <div class="cart-buttons">
                <p>Total del Carrito: <?php echo $total_price_formatted; ?> €</p>
                    <button type="submit" name="actualizar_carrito">Actualizar Carrito</button>
                </div>
            </form>
            <form action="carrito.php" method="post">
                <div class="cart-buttons">
                    <button type="submit" name="realizar_compra">Realizar Pedido</button>
                </div>
            </form>
               <?php else: ?>
                   <div class="empty-cart-container">
           <i class="fas fa-shopping-cart empty-cart-icon"></i>
           <p class="empty-cart-message">El carrito está vacío.</p>
            </div>
       
               <?php endif; ?>
               <div class="continue-shopping">
                   <a href="index.php">Seguir Comprando</a>
               </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
