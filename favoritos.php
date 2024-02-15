<?php
session_start();
require 'db_connection.php';


if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario actual
$usuarioID = $_SESSION['user_id'];

// Manejar la actualización de favoritos si se envía un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['codigo_articulo'], $_POST['accion'])) {
        $codigoArticulo = $_POST['codigo_articulo'];
        $accion = $_POST['accion'];
        
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'cliente') {
            http_response_code(401); // No autorizado
            exit();
        }

        $usuarioID = $_SESSION['user_id'];

        if ($accion === 'agregar') {
            // Agregar a favoritos
            $stmtAdd = $pdo->prepare("INSERT INTO Favoritos (UsuarioID, ArticuloCodigo) VALUES (?, ?)");
            $stmtAdd->execute([$usuarioID, $codigoArticulo]);
        } elseif ($accion === 'quitar') {
            // Quitar de favoritos
            $stmtRemove = $pdo->prepare("DELETE FROM Favoritos WHERE UsuarioID = ? AND ArticuloCodigo = ?");
            $stmtRemove->execute([$usuarioID, $codigoArticulo]);
        } else {
            http_response_code(400); // Solicitud incorrecta
            exit();
        }
        
        echo "Operación realizada con éxito";
        exit();
    }
}

// Obtener los artículos marcados como favoritos por el usuario
$stmt = $pdo->prepare("SELECT Articulos.* FROM Articulos
                      JOIN Favoritos ON Articulos.Codigo = Favoritos.ArticuloCodigo
                      WHERE Favoritos.UsuarioID = ?");
$stmt->execute([$usuarioID]);
$favoritos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Favoritos</title>
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

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
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
            flex: 1; 
        }

        .favoritos-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .favorito {
        width: 300px; 
        margin: 10px;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .favorito img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 15px;
    }


        .favorito-icon, .carrito-icon {
            cursor: pointer;
            transition: color 0.3s;
        }

        .favorito-icon:hover, .carrito-icon:hover {
            color: red;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="wrapper">
    <div class="container">
        <h2>Tus Artículos Favoritos</h2>

        <?php if (!empty($favoritos)) : ?>
            <div class="favoritos-container">
                <?php foreach ($favoritos as $articulo) : ?>
                    <div class="favorito">
                        <strong><?php echo $articulo['Nombre']; ?></strong>
                        <p><?php echo $articulo['Descripcion']; ?></p>
                        <p>Precio: $<?php echo number_format($articulo['Precio'], 2); ?></p>
                        <img src="<?php echo $articulo['Imagen']; ?>" alt="Imagen del artículo">
                        <form action="carrito.php" method="post" style="display: inline;">
                            <input type="hidden" name="codigo_articulo" value="<?php echo $articulo['Codigo']; ?>">
                            <input type="number" name="cantidad" value="1" min="1">
                            <button type="submit" name="agregar_carrito">
                                <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>No tienes ningún artículo marcado como favorito.</p>
        <?php endif; ?>
        </div>
        </div>
 
        <?php include 'footer.php'; ?>
</body>
</html>
  
