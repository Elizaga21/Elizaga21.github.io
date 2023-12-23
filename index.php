<?php include 'db_connection.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniaturas y Colecciones</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="container">
        <?php include 'menu_izquierda.php'; ?>

        <div id="content">
            <?php
            // Lógica para mostrar contenido dinámico según la sección actual
            if (isset($_GET['seccion'])) {
                $seccion = $_GET['seccion'];

                switch ($seccion) {
                    case 'quienes_somos':
                        include 'quienes_somos.php';
                        break;

                    case 'contacto':
                        include 'contacto.php';
                        break;

                    // Agregar más casos según las secciones que necesites

                    default:
                        include 'home.php'; // Página principal por defecto
                        break;
                }
            } else {
                include 'home.php'; // Página principal por defecto
            }
            ?>
        </div>

        <?php include 'menu_derecha.php'; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
