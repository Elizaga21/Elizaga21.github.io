<?php
include 'db_connection.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniaturas y Colecciones</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'header.php'; ?>


    <div id="container">
    <?php include 'menu_izquierda.php'; ?>
        <div id="content">
            
                     <?php   include 'home.php'; ?>
               
        </div>

        <?php include 'menu_derecha.php'; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
