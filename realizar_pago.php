<?php
include 'header.php';
session_start();

require 'db_connection.php';

// Verificar si el usuario está autenticado
$user_authenticated = isset($_SESSION['user_id']);
$is_customer = $user_authenticated && $_SESSION['rol'] === 'cliente';

// Verificar si se ha enviado el formulario de pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['realizar_pago']) && $is_customer) {
    try {
        // Aquí procesarías la información de pago y realizarías las operaciones necesarias
        // Puedes obtener información adicional del carrito y del usuario desde la sesión

        if ($_POST['forma_pago'] === 'tarjeta') {
            // Información simulada de la tarjeta de crédito (deberías usar una pasarela de pago real)
            $numero_tarjeta = $_POST['numero_tarjeta'];
            $fecha_expiracion = $_POST['fecha_expiracion'];
            $cvv = $_POST['cvv'];

            // Validar la tarjeta (esto es solo un ejemplo, utiliza una pasarela de pago real)
            if (validar_tarjeta($numero_tarjeta, $fecha_expiracion, $cvv)) {
                // Proceso de pago exitoso

                // Después de procesar el pago
                $_SESSION['pago_confirmado'] = true;

                // Redireccionar al usuario con un mensaje de alerta en JavaScript
                echo '<script>alert("Pago realizado correctamente."); window.location.href="agradecimiento.php";</script>';
                exit();
            } else {
                throw new Exception("Error en la validación de la tarjeta de crédito.");
            }
        } elseif ($_POST['forma_pago'] === 'paypal') {
            // Aquí procesarías el pago con PayPal (deberías usar la API de PayPal o SDK)
            // Simplemente devolveré true para este ejemplo simulado
            $pago_con_paypal_exitoso = true;

            if ($pago_con_paypal_exitoso) {
                // Proceso de pago con PayPal exitoso

                // Después de procesar el pago
                $_SESSION['pago_confirmado'] = true;

                // Redireccionar al usuario con un mensaje de alerta en JavaScript
                echo '<script>alert("Pago con PayPal realizado correctamente."); window.location.href="agradecimiento.php";</script>';
                exit();
            } else {
                throw new Exception("Error en el pago con PayPal.");
            }
        }
    } catch (Exception $e) {
        echo "Error al procesar el pago: " . $e->getMessage();
    }
}

// Si el usuario no está autenticado o ya ha confirmado el pago, redirigir a otra página
if (!$is_customer || isset($_SESSION['pago_confirmado'])) {
    header("Location: index.php");
    exit();
}

// Función de ejemplo para validar la tarjeta de crédito (simulada)
function validar_tarjeta($numero_tarjeta, $fecha_expiracion, $cvv) {
    // Aquí deberías utilizar una pasarela de pago real para validar la tarjeta
    // Simplemente devolveré true para este ejemplo simulado
    return true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pago</title>
    <script src="https://js.stripe.com/v3/"></script>

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
    max-width: 400px;
    width: 100%;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
}

h2 {
    color: #007bff;
}

label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    color: #333;
}

select,
input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

#campos_tarjeta,
#campos_paypal {
    display: none;
}

button {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>

    <div class="container">
        <h2>Realizar Pago</h2>
        <!-- Formulario de pago -->
        <form action="realizar_pago.php" method="post">
            <!-- Agrega aquí los campos necesarios para la forma de pago -->
           
<label for="forma_pago">Selecciona el método de pago:</label>
<select name="forma_pago" id="forma_pago" required>
    <option value="tarjeta">Tarjeta de Crédito</option>
    <option value="paypal">PayPal</option>
</select>

<!-- Campos para tarjeta de crédito -->
<div id="campos_tarjeta" style="display: none;">
    <label for="numero_tarjeta">Número de Tarjeta:</label>
    <div id="numero_tarjeta" style="width: 100%;"></div>

    <label for="fecha_expiracion">Fecha de Expiración:</label>
    <div id="fecha_expiracion" style="width: 100%;"></div>

    <label for="cvv">CVV:</label>
    <div id="cvv" style="width: 100%;"></div>
</div>

            <!-- Campos para PayPal (puedes personalizar según las necesidades de PayPal) -->
            <div id="campos_paypal" style="display: none;">
                <!-- Agrega los campos necesarios para el pago con PayPal -->
            </div>

            <!-- Otros campos del formulario -->

            <button type="submit" name="realizar_pago">Realizar Pago</button>
        </form>

        <script>
    var stripe = Stripe('TU_CLAVE_PUBLICA_DE_PRUEBA');
    var elements = stripe.elements();

    // Configurar elementos de Stripe para campos de tarjeta
    var card = elements.create('card');
    card.mount('#numero_tarjeta');

    // Evento para mostrar u ocultar campos según la forma de pago seleccionada
    document.querySelector('select[name="forma_pago"]').addEventListener('change', function() {
        var camposTarjeta = document.getElementById('campos_tarjeta');
        var camposPaypal = document.getElementById('campos_paypal');

        if (this.value === 'tarjeta') {
            camposTarjeta.style.display = 'block';
            camposPaypal.style.display = 'none';
        } else if (this.value === 'paypal') {
            camposTarjeta.style.display = 'none';
            camposPaypal.style.display = 'block';
        } else {
            camposTarjeta.style.display = 'none';
            camposPaypal.style.display = 'none';
        }
    });

    // Agregar la lógica para manejar el formulario de pago con Stripe
    document.getElementById('realizar_pago').addEventListener('click', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Mostrar errores al usuario (puedes personalizar según tus necesidades)
                alert(result.error.message);
            } else {
                // Token creado con éxito, envía el token al servidor para procesar el pago
                // Aquí deberías realizar una llamada AJAX o enviar el token al servidor utilizando un formulario oculto
                alert('Token creado: ' + result.token.id);
            }
        });
    });
</script>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
