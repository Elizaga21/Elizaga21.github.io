<?php
session_start();

require 'db_connection.php';

if (isset($_GET['codigo_articulo'])) {
    $codigo_articulo = $_GET['codigo_articulo'];

    $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE Codigo = ?");
    $stmt->execute([$codigo_articulo]);
    $articulo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$articulo) {
        // Manejar el caso cuando el artículo no se encuentra
        header("Location: index.php");
        exit();
    }
} else {
    // Manejar el caso cuando no se proporciona un código de artículo
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

    <?php include 'footer.php'; ?>


</body>
</html>
