<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniaturas y Colecciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: "Helvetica Now Text", Helvetica, Arial, sans-serif;
            background-color: #fff; /* Cambiando el fondo a blanco */
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #dedddd;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            max-height: 40px;
        }

        .nombre-tienda {
            font-size: 18px;
        }

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
        }

        .banner-image {
            width: 100%; /* Ajustar el ancho de la imagen al 100% */
            max-height: 300px; /* Altura máxima de la imagen del banner */
            object-fit: cover; /* Cubrir completamente el contenedor manteniendo las proporciones */
            margin-bottom: 20px; /* Espaciado inferior para separar del contenido siguiente */
            border-radius: 8px; /* Borde redondeado */
        }

        .quienes-somos-container {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            color: #495057;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
        }

        footer {
            background-color: black;
            color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div id="container">
        <div id="content">
            <img class="banner-image" src="/logo/White Simple Light Wedding Bio Link Website.png" alt="Banner de la tienda"> <!-- Agregando la imagen del banner -->
            <div class="quienes-somos-container">
                <p>Bienvenido a nuestra tienda, especializada en la venta de miniaturas y colecciones de coches, camiones y motos clásicos. Nos apasiona el mundo de los vehículos clásicos y queremos compartir esa pasión contigo.</p>
                <p>Nuestro catálogo incluye una amplia selección de miniaturas y colecciones de alta calidad, desde modelos a escala detallados hasta ediciones exclusivas de tus vehículos clásicos favoritos.</p>
                <p>Explora nuestra tienda y descubre piezas únicas para tu colección o encuentra el regalo perfecto para los entusiastas de los vehículos clásicos en tu vida.</p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
