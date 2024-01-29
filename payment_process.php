<?php
session_start();
include 'header.php';
require 'vendor/autoload.php';
require 'db_connection.php';

\Stripe\Stripe::setApiKey('sk_test_51OdDwPEKHkfLvKZ8CnBUWumB8OBhoYl9oePNIPqhQWpdbQgWqyAYKkSSMvMfjEIf3kQwdHd0C0CUyXYcIzc8yuui000W6mUJdu');

$carrito = $_SESSION['carrito'];
$usuarioID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$paymentMethodId = $_POST['paymentMethodId'];

$return_url = 'http://miniaturasycolecciones.infinityfreeapp.com/agradecimiento.php';

try {
    // Crea un PaymentIntent utilizando el token directamente
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 1000,
        'currency' => 'eur',
        'description' => 'Compra de prueba',
        'payment_method' => $paymentMethodId,
        'confirm' => true,
        'receipt_email' => 'elisabetaudiovisual@gmail.com',
        'return_url' => $return_url, 
        'payment_method_types' => ['card', 'ideal'],
    ]);

    // Pago exitoso, realiza las operaciones adicionales necesarias
    $payment_intent = $paymentIntent->id;

    $clientSecret = $paymentIntent->client_secret;
    registrar_transaccion($payment_intent, $_SESSION['user_id'], $carrito);

    $pedido_id = obtener_id_del_ultimo_pedido($_SESSION['user_id']);
    actualizar_estado_pedido($pedido_id, 'En Proceso');

    enviar_correo_confirmacion($_SESSION['user_id']);

    unset($_SESSION['pago_confirmado']);
    unset($_SESSION['carrito']);

    header("Location: " . $return_url);
    exit();
} catch (\Stripe\Exception\CardException $e) {
    // Error de tarjeta
    echo "Error de tarjeta: " . $e->getMessage();
} catch (\Exception $e) {
    // Otros errores
    echo "Error al procesar el pago: " . $e->getMessage();
}

function actualizar_estado_pedido($pedido_id, $nuevo_estado) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE Pedidos SET EstadoPedido = ? WHERE PedidoID = ?");
    $stmt->execute([$nuevo_estado, $pedido_id]);
}

function obtener_id_del_ultimo_pedido($user_id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT PedidoID FROM Pedidos WHERE UsuarioID = ? ORDER BY PedidoID DESC LIMIT 1");
    $stmt->execute([$user_id]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['PedidoID'];
    }

    return null;
}

function enviar_correo_confirmacion($user_id) {
    global $pdo;

    // Obtén la dirección de correo electrónico del usuario
    $to = obtener_direccion_correo($user_id);

    if (!$to) {
        echo "No se pudo obtener la dirección de correo electrónico del usuario.";
        return;
    }

    $subject = "Confirmación de compra";
    $message = "Gracias por tu compra. Tu pedido ha sido confirmado y está en proceso de envío.";

    $user_email = obtener_direccion_correo($user_id);
    if ($user_email) {
    // Establecer encabezados adicionales
    $headers = "From: info@miniaturasycolecciones.com\r\n";
    $headers .= "Reply-To: $user_email\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Enviar el correo electrónico
    if (mail($to, $subject, $message, $headers)) {
        echo "Correo electrónico de confirmación enviado exitosamente.";
    } else {
        echo "Error al enviar el correo electrónico de confirmación.";
    }
}
}
// Función para obtener la dirección de correo del usuario
function obtener_direccion_correo($user_id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['email'];
    }

    return null;
}

function registrar_transaccion($payment_intent, $user_id, $carrito) {
    global $conn;

    $fecha_compra = date('Y-m-d H:i:s');

    foreach ($carrito as $item) {
        $codigo_articulo = $item['codigo'];  
        $precio = $item['precio'];
        $cantidad = $item['cantidad'];

        $sql = "INSERT INTO Compras (IDUsuario, CodigoArticulo, FechaCompra, Precio, Cantidad) 
                VALUES ($user_id, '$codigo_articulo', '$fecha_compra', $precio, $cantidad)";

        if ($conn->query($sql) !== TRUE) {
            echo "Error al registrar la transacción en la base de datos: " . $conn->error;
            return;
        }
    }
}
?>
