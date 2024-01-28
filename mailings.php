<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'empleado' && $_SESSION['rol'] !== 'administrador')) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha enviado el formulario de envío de mailing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recuperar los datos del formulario
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    // Consulta para obtener la lista de correos de clientes
    $stmt = $pdo->query("SELECT email FROM usuarios WHERE rol = 'cliente' AND activo = true");
    $correos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Configuración de cabeceras para el envío de correos
    $headers = "From: infominiaturasycolecciones@gmail.com\r\n";
    $headers .= "Reply-To: infominiaturasycolecciones@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Enviar correo a cada cliente
    foreach ($correos as $correo) {
        mail($correo, $asunto, $mensaje, $headers);
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envío de Mailings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    
</head>
<style>
    .container_mailing {
        margin-top: 20px;
    }

  
    form {
        max-width: 400px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #495057;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    button {
        background-color: #007bff;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

</style>
<body>

    <?php include 'header.php'; ?>

    <div class="container container_mailing text-center">
        <h2>Envío masivo de Mailings a Clientes (publicidad, felicitación navideña, ofertas)</h2>
        <form method="post">
            <div class="form-group">
                <label for="asunto">Asunto:</label>
                <input type="text" name="asunto" id="asunto" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mensaje">Mensaje:</label>
                <textarea name="mensaje" id="mensaje" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Mailings</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
