<?php
session_start();
include 'header.php';

require 'db_connection.php';


// Verificar si el usuario está autenticado y ha realizado el pago
$user_authenticated = isset($_SESSION['user_id']);
$is_customer = $user_authenticated && $_SESSION['rol'] === 'cliente';

if (!$is_customer) {
    header("Location: index.php");
    exit();
}

$paymentMethodId = $_POST['paymentMethodId'];

// Obtén la información del carrito (ajusta según la estructura real de tu carrito)
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];


// URL a la que se redirigirá al cliente después del pago
$return_url = 'http://miniaturasycolecciones.infinityfreeapp.com/agradecimiento.php'; // Reemplaza con la URL correcta
// Utiliza el token para procesar el pago con Stripe
require 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51OdDwPEKHkfLvKZ8CnBUWumB8OBhoYl9oePNIPqhQWpdbQgWqyAYKkSSMvMfjEIf3kQwdHd0C0CUyXYcIzc8yuui000W6mUJdu');


try {
    // Crea un PaymentIntent utilizando el token directamente
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 1000,  // Monto en centavos (ajusta según tus necesidades)
        'currency' => 'eur',
        'description' => 'Compra de prueba',
        'payment_method' => $paymentMethodId,
        'confirm' => true,
        'receipt_email' => 'elisabetaudiovisual@gmail.com',
        'return_url' => $return_url, 
    ]);

    // Pago exitoso, realiza las operaciones adicionales necesarias
    $payment_intent = $paymentIntent->id;

    // Registra la transacción en la tabla de Compras
    registrar_transaccion($payment_intent, $_SESSION['user_id'], $carrito);

    $pedido_id = obtener_id_del_ultimo_pedido($_SESSION['user_id']);
    actualizar_estado_pedido($pedido_id, 'Enviado');

    // Enviar correo electrónico de confirmación al cliente
    enviar_correo_confirmacion($_SESSION['user_id']);

    // Limpiar la sesión después de confirmar el pago
    unset($_SESSION['pago_confirmado']);
    unset($_SESSION['carrito']);

    // Redireccionar al usuario a la página de agradecimiento
    header("Location: " . $return_url);
    exit();
} catch (\Stripe\Exception\CardException $e) {
    // Error de tarjeta
    echo "Error de tarjeta: " . $e->getMessage();
} catch (\Exception $e) {
    // Otros errores
    echo "Error al procesar el pago: " . $e->getMessage();
}


// Función para actualizar el estado del pedido
function actualizar_estado_pedido($pedido_id, $nuevo_estado) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE Pedidos SET EstadoPedido = ? WHERE PedidoID = ?");
    $stmt->execute([$nuevo_estado, $pedido_id]);
}

// Función para obtener el ID del último pedido del usuario
function obtener_id_del_ultimo_pedido($user_id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT PedidoID FROM Pedidos WHERE UsuarioID = ? ORDER BY PedidoID DESC LIMIT 1");
    $stmt->execute([$user_id]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['PedidoID'];
    }

    return null;
}

// Función para enviar correo electrónico de confirmación
function enviar_correo_confirmacion($user_id) {
    // Aquí deberías implementar la lógica para enviar el correo electrónico de confirmación.
    // Puedes utilizar librerías como PHPMailer o servicios de correo electrónico externos.
    // Aquí proporciono un ejemplo básico:
    
    $to = obtener_direccion_correo($user_id);
    $subject = "Confirmación de compra";
    $message = "Gracias por tu compra. Tu pedido ha sido confirmado y está en proceso de envío.";

    // Puedes personalizar y expandir este mensaje según tus necesidades.

    // mail($to, $subject, $message);
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

// Función para registrar la transacción en la tabla de Compras
function registrar_transaccion($payment_intent, $user_id, $carrito) {
    global $conn;

    $fecha_compra = date('Y-m-d H:i:s');

    foreach ($carrito as $item) {
        $codigo_articulo = $item['codigo'];  // Obtener el código del artículo del carrito
        $precio = $item['precio'];
        $cantidad = $item['cantidad'];

        $sql = "INSERT INTO Compras (IDUsuario, CodigoArticulo, FechaCompra, Precio, Cantidad) 
                VALUES ($user_id, '$codigo_articulo', '$fecha_compra', $precio, $cantidad)";

        if ($conn->query($sql) !== TRUE) {
            echo "Error al registrar la transacción en la base de datos: " . $conn->error;
            return;  // Salir en caso de error
        }
    }

    // Registro exitoso
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
    <script src="https://js.stripe.com/v3/"></script>


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

form {
  width: 500px;
  margin: 0 auto;
}

.form-group {
  margin-bottom: 15px;
}

.form-control {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.btn {
  width: 100%;
  background-color: #000;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
}

        </style>
</head>
<body>

<form action="process.php" method="POST">

<div class="form-group">
  <label for="email">Email</label>
  <input type="email" class="form-control" id="email" name="email" required>
</div>

<div class="form-group">
  <label for="card-number">Card Number</label>
  <input type="text" class="form-control" id="card-number" name="card-number" required>
</div>

<div class="form-group">
  <label for="expiration-date">Expiration Date</label>
  <input type="text" class="form-control" id="expiration-date" name="expiration-date" required>
</div>

<div class="form-group">
  <label for="cvc">CVC</label>
  <input type="text" class="form-control" id="cvc" name="cvc" required>
</div>

<div class="form-group">
  <label for="name-on-card">Name on Card</label>
  <input type="text" class="form-control" id="name-on-card" name="name-on-card" required>
</div>

<div class="form-group">
  <label for="country">Country</label>
  <select class="form-control" id="country" name="country" required>
    <option value="United States">United States</option>
    <option value="Canada">Canada</option>
    <option value="Mexico">Mexico</option>
    <option value="Brazil">Brazil</option>
    <option value="España">España</option>
  </select>
</div>

<button type="submit" class="btn btn-primary">Pay</button>

</form>

  <?php include 'footer.php'; ?>

  <script>
  var stripe = Stripe('pk_test_51OdDwPEKHkfLvKZ82d07UlaG3aUGH6Ooct7aSpsveedoJtl2btOMOSwgjNSHbKzEPNEfTdlJfe3dPgH6eiyd801g00lV1WKP9j');

  // Crea un PaymentMethod cuando se envía el formulario
  var form = document.getElementById('payment-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createPaymentMethod({
      type: 'card',
      card: new cardNumberElement(),
      billing_details: {
        email: document.getElementById('email').value,
        name: document.getElementById('name-on-card').value,
        address: {
          country: document.getElementById('country').value
        }
      }
    }).then(function(result) {
      if (result.error) {
        // Manejar errores de validación
      } else {
        // Envía el ID del PaymentMethod a tu backend
        fetch('/tu/endpoint/backend', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({paymentMethodId: result.paymentMethod.id}),
        }).then(function(response) {
          return response.json();
        }).then(function(responseJson) {
          // Manejar la respuesta desde tu backend
        });
      }
    });
  });
</script>
</body>
</html>
