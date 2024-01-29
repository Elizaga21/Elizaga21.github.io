<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Devolución</title>
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
            margin: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }


        p {
            line-height: 1.5;
            color: #495057;
        }

        .cliente-link {
            display: block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }

        .cliente-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <h2>Política de Devolución</h2>

        <p>
            En Miniaturas y Colecciones, queremos asegurarnos de que estés completamente satisfecho con tu compra. 
            Si necesitas devolver un artículo, ¡estamos aquí para ayudarte!
        </p>

        <p>
            <strong>Plazo de Devolución:</strong> Dispones de 7 días hábiles a partir de la recepción del artículo para solicitar una devolución.
        </p>

        <p>
            <strong>Reembolso Completo:</strong> Te ofrecemos un reembolso completo si el artículo no presenta ningún desperfecto y se encuentra en su estado original.
        </p>

        <p>
            <strong>Estado del Artículo:</strong> Todos los artículos se envían en perfecto estado. En caso de que algún artículo tenga alguna imperfección, te lo comunicaremos antes del envío.
        </p>

        <p>
            <strong>Roturas del Producto:</strong> Si observas alguna rotura en el producto al recibirlo, por favor, realiza fotografías para documentar el caso. Esto nos ayudará a procesar tu devolución de manera eficiente.
        </p>

        <p>
            Para iniciar el proceso de devolución, por favor, contacte directamente con nosotros y sigue las instrucciones proporcionadas. <a href="contacto.php" class="cliente-link">Contacto</a> 
        </p>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
