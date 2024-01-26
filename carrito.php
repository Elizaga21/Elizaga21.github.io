<?php
include 'header.php';
session_start();

require 'db_connection.php';

if (isset($_POST['agregar_carrito'])) {
    $codigo_articulo = $_POST['codigo_articulo'];
    $cantidad = $_POST['cantidad'];

    $_SESSION['carrito'][$codigo_articulo] = $cantidad;
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
        // Asegúrate de que la cantidad esté en el rango permitido (1 a 10)
        $cantidad = max(1, min(10, intval($cantidad)));
        $_SESSION['carrito'][$codigo_articulo] = $cantidad;
    }
}

// Calculate the total price for the entire order
$total_price = 0.0;
foreach ($carrito_detalles as $articulo) {
    $item_price = $articulo['Precio'] * $_SESSION['carrito'][$articulo['Codigo']];
    $total_price += $item_price;
}

// Add the shipping cost to the total
$total_price += (count($carrito_detalles) * STANDARD_SHIPPING_COST);




// Procesar el formulario de datos de envío y pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_pedido']) && !$is_customer) {
    // Procesar y guardar los datos en la base de datos
    try {
        $pdo->beginTransaction();

        // Obtener datos del formulario
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $direccion_envio = $_POST['direccion_envio'];
        $localidad_envio = $_POST['localidad_envio'];
        $provincia_envio = $_POST['provincia_envio'];
        $pais_envio = $_POST['pais_envio'];
        $codpos_envio = $_POST['codpos_envio'];
        $telefono_envio = $_POST['telefono_envio']; 
        $email = $_POST['email']; 
        $Contrasena = password_hash($_POST['Contrasena'], PASSWORD_DEFAULT);
    
        // Realizar la inserción en la base de datos
        $stmt_pedido = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, direccion, localidad, provincia, pais, codpos, telefono, email, rol, activo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'cliente', true, ?)");
        $stmt_pedido->execute([$nombre, $apellidos, $direccion_envio, $localidad_envio, $provincia_envio, $pais_envio, $codpos_envio, $telefono_envio, $email, $Contrasena]);
        $usuario_id = $pdo->lastInsertId();


        // Confirmar la transacción
        $pdo->commit();

        // Después de confirmar el pedido
$_SESSION['datos_envio_confirmados'] = true;
$_SESSION['rol'] = 'cliente';


        // Redireccionar al usuario con un mensaje de alerta en JavaScript
        echo '<script>alert("Datos de envío guardados correctamente."); window.location.href="carrito.php";</script>';
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        echo "Error al procesar los datos de envío: " . $e->getMessage();
    }
}

$pedido_id = $pdo->lastInsertId();

// Procesar la compra si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['realizar_compra'])) {
    try {
        $pdo->beginTransaction();

        // Crear un nuevo pedido
        $usuario_id = $_SESSION['user_id'];
        $fecha_pedido = date('Y-m-d');
        $estado_pedido = 'Pendiente';

        $stmt_pedido = $pdo->prepare("INSERT INTO Pedidos (UsuarioID, Fecha, EstadoPedido) VALUES (?, ?, ?)");
        $stmt_pedido->execute([$usuario_id, $fecha_pedido, $estado_pedido]);
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

        
    

        // Redireccionar a la página de agradecimiento
        header("Location: realizar_pago.php");
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
            max-width: 150px; /* Ajusta el tamaño de la imagen */
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
            width: 50px; /* Ajusta el ancho del input de cantidad */
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
    </style>
</head>
<body>

    <div class="container">
        <h2>Datos de Envío</h2>
 <!-- Mostrar el formulario de datos de envío y pago solo si el usuario no tiene el rol de cliente -->
 <?php if (!$is_customer && empty($_SESSION['datos_envio_confirmados'])): ?>          
          <form action="carrito.php" method="post">
    <!-- ... Otros campos del formulario ... -->
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required>

    <label for="apellidos">Apellidos:</label>
    <input type="text" name="apellidos" required>

    <label for="direccion_envio">Dirección de Envío:</label>
    <input type="text" name="direccion_envio" required>

    <label for="localidad_envio">Localidad de Envío:</label>
    <input type="text" name="localidad_envio" required>

    <label for="provincia_envio">Provincia de Envío:</label>
    <input type="text" name="provincia_envio" required>

    <label for="pais_envio">País de Envío:</label>
    <input type="text" name="pais_envio" required>

    <label for="codpos_envio">Código Postal de Envío:</label>
    <input type="text" name="codpos_envio" required>

    <label for="telefono_envio">Teléfono:</label>
    <input type="text" name="telefono_envio" required>

    <label for="email">Email:</label>
    <input type="text" name="email" required>
    
    <label for="Contrasena">Contraseña:</label>
    <input type="text" name="Contrasena" required>

    
                    <input type="submit" name="confirmar_pedido" value="Confirmar Datos Envío">
                </form>
            <?php endif; ?>
            
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
                        <p>Precio: <?php echo $articulo['Precio']; ?> €</p>
                        <p>Total: <?php echo ($articulo['Precio'] * $_SESSION['carrito'][$articulo['Codigo']]); ?> €</p>
                        <a href="eliminar_del_carrito.php?codigo_articulo=<?php echo $articulo['Codigo']; ?>">Eliminar</a>
                    </div>
                <?php endforeach; ?>

                <p>Envío: <?php echo STANDARD_SHIPPING_COST; ?> € por cada artículo</p>

                <div class="cart-buttons">
                <p>Total del Carrito: <?php echo ($total_price); ?> €</p>
                    <button type="submit" name="actualizar_carrito">Actualizar Carrito</button>
                </div>
            </form>
            <form action="carrito.php" method="post">
                <div class="cart-buttons">
                    <button type="submit" name="realizar_compra">Realizar Compra</button>
                </div>
            </form>
        <?php else: ?>
            <p class="empty-cart">El carrito está vacío.</p>
        <?php endif; ?>
        <div class="continue-shopping">
            <a href="index.php">Seguir Comprando</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
