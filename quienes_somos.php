<?php include 'db_connection.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniaturas y Colecciones - Quiénes Somos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: "Helvetica Now Text", Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
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

        #container_quienes_somos {
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center; 
        min-height: 100vh; 
       }
       
       .quienes-somos-container {
           text-align: justify; 
           max-width: 800px;
           margin: 0 auto;
           margin-top: 20px;
             }
             
             .banner-image {
          width: 100%;
          max-height: 450px; 
          object-fit: cover;
          margin-bottom: 0; 
          border-radius: 8px;
      }

       
       h2 {
           color: #495057;
           font-size: 24px;
           margin-bottom: 20px;
       }
       
       p {
           margin-bottom: 20px;
       }

.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 20px;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
}

.gallery img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s ease-in-out;
    margin-bottom: 20px;
}

.gallery img:hover {
    transform: scale(1.1);
}

    
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="container">
        <div id="content_quienes_somos">
            <img class="banner-image" src="logo/White Simple Light Wedding Bio Link Website.png" alt="Banner de la tienda">
            <div class="quienes-somos-container">
                <h2>Quiénes Somos</h2>
                <p>Bienvenido a Miniaturas y Colecciones, tu tienda especializada en la venta de miniaturas y colecciones de coches, camiones y motos clásicos. Nos apasiona el mundo de los vehículos clásicos y queremos compartir esa pasión contigo.</p>
                <p>Nuestro catálogo incluye una amplia selección de miniaturas y colecciones de alta calidad, desde modelos a escala detallados hasta ediciones exclusivas de tus vehículos clásicos favoritos.</p>
                <p>Explora nuestra tienda y descubre piezas únicas para tu colección o encuentra el regalo perfecto para los entusiastas de los vehículos clásicos en tu vida.</p>
            </div>

            <h2>Galería</h2>
            <div class="gallery">
                <img src="logo/FS0_Low - Google Chrome 27_01_2024 10_43_03.png" alt="Coleccion Camiones Americanos">
                <img src="logo/American Cars_ colección coches .png" alt="American Cars">
                <img src="logo/Ferrari Fórmula 1_ modelos .png" alt="Ferrari">
                <img src="logo/Motos GP_ colección.png" alt="Moto GP">
               
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
