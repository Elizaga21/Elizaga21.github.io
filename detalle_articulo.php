<?php
session_start();

require 'db_connection.php';

if (isset($_GET['codigo_articulo'])) {
    $codigo_articulo = $_GET['codigo_articulo'];

    $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE Codigo = ?");
    $stmt->execute([$codigo_articulo]);
    $articulo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$articulo) {
        
        header("Location: index.php");
        exit();
    }
} else {
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Artículo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>   
 
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        body {
    font-family: 'Helvetica Now Text', Helvetica, Arial, sans-serif;
    background-color: #f8f9fa;
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
    text-align: center;
}

.container h2 {
    color: #495057;
    font-size: 28px;
    margin-bottom: 20px;
}

.container img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 20px;
}

.container h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.container p {
    font-size: 16px;
    margin-bottom: 20px;
}

.container form {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
}

.container form input[type="number"] {
    width: 60px;
    margin-right: 10px;
    padding: 8px;
}

.container form button {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.container form button:hover {
    background-color: #0056b3;
}

.related-products {
        text-align: center;
    }

    .carousel-inner {
            max-width: 400px; 
            margin: 0 auto;
        }

        .carousel-item {
            text-align: center;
        }

        .carousel-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 10%;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 25px;
            height: 25px;
        }

        .carousel-control-prev-icon {
            background-color: #007bff;
        }

        .carousel-control-next-icon {
            background-color: #007bff;
        }

    .gallery {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .gallery a {
        position: relative;
        overflow: hidden;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .gallery img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 0;
    }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Detalles del Artículo</h2>
        <div>
            <img src="<?php echo $articulo['Imagen']; ?>" alt="<?php echo $articulo['Nombre']; ?>">
            <h3><?php echo $articulo['Nombre']; ?></h3>
            <p><?php echo $articulo['Descripcion']; ?></p>
            <p>Precio: <?php echo $articulo['Precio']; ?> €</p>
            <form action="carrito.php" method="post">
            <input type="hidden" name="codigo_articulo" value="<?php echo $articulo['Codigo']; ?>">
            <input type="number" name="cantidad" value="1" min="1">
            <button type="submit" name="agregar_carrito">
                <i class="fas fa-shopping-cart"></i> Agregar al Carrito
            </button>
        </form>
        </div>
    </div>

    
    <div class="related-products">
        <h2>Artículos Relacionados</h2>
        <div id="carouselExample" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                // Obtén la CategoriaID del artículo actual
                $categoriaActual = $articulo['CategoriaID'];

                // Realiza una consulta para obtener artículos relacionados con la misma CategoriaID
                $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE CategoriaID = :categoriaActual AND Codigo != :codigoActual LIMIT 3");
                $stmt->bindParam(':categoriaActual', $categoriaActual, PDO::PARAM_INT);
                $stmt->bindParam(':codigoActual', $codigo_articulo, PDO::PARAM_STR);
                $stmt->execute();
                $articulosRelacionados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($articulosRelacionados as $index => $articuloRelacionado) {
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <a href="detalle_articulo.php?codigo_articulo=<?php echo $articuloRelacionado['Codigo']; ?>">
                            <img src="<?php echo $articuloRelacionado['Imagen']; ?>" class="d-block w-100" alt="<?php echo $articuloRelacionado['Nombre']; ?>">
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <?php include 'footer.php'; ?>


</body>
</html>
