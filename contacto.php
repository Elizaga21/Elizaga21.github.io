<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Miniaturas y Colecciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos personalizados */
        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        #content {
            flex-grow: 1;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 100px;
        }

        .buscador {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            align-items: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .preguntas-frecuentes {
            text-align: center;
            color: #007bff;
        }

        h2, h3 {

            margin-top: 10px;
            margin-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .contacto-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
        }

        img {
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="container">
        <div id="content">

            <!-- Sección de preguntas frecuentes -->
            <div class="preguntas-frecuentes container">
                <h2>ENVÍO Y ENTREGA</h2>
                <ul>
                    <li>El envío se realiza entre 3-4 días laborables</li>
                    <li>La entrega será entre 6-7 días laborables una vez que se realiza el envío</li>
                </ul>

                <br>

                <h3>CONTACTA CON NOSOTROS</h3>
                <p>Cerraremos el 25 de diciembre y el 1 de enero.</p>

                <div class="contacto-info">
                <img src="icons/phone_in_talk_FILL0_wght400_GRAD0_opsz24.svg" alt="Icono Telefono">
                    <div>
                        <h4>PRODUCTOS Y PEDIDOS</h4>
                        <p>+34 95555555</p>
                        <p>10:00-18:00 (Lunes - Viernes)</p>
                        <p>9:00-18:00 (Sábado)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
