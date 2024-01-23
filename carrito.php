<?php
session_start();

require 'db_connection.php';

if (isset($_POST['agregar_carrito'])) {
    $codigo_articulo = $_POST['codigo_articulo'];
    $cantidad = $_POST['cantidad'];

    $_SESSION['carrito'][$codigo_articulo] = $cantidad;
}

// Obtener detalles de artículos en el carrito
$carrito_detalles = [];
if (!empty($_SESSION['carrito'])) {
    $codigo_articulos = array_keys($_SESSION['carrito']);
    $placeholders = str_repeat('?,', count($codigo_articulos) - 1) . '?';

    $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE Codigo IN ($placeholders)");
    $stmt->execute($codigo_articulos);
    $carrito_detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
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
     text-align: center;
     max-width: 800px;
     width: 100%;
     margin: auto;
     padding: 20px;
 }

 </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Carrito de Compras</h2>
        <?php if (!empty($carrito_detalles)): ?>
            <form action="realizar_compra.php" method="post">
                <ul>
                    <?php foreach ($carrito_detalles as $articulo): ?>
                        <li>
                            <img src="<?php echo $articulo['Imagen']; ?>" alt="<?php echo $articulo['Nombre']; ?>">
                            <h3><?php echo $articulo['Nombre']; ?></h3>
                            <p>Cantidad: <?php echo $_SESSION['carrito'][$articulo['Codigo']]; ?></p>
                            <p>Precio: <?php echo $articulo['Precio']; ?> €</p>
                            <a href="eliminar_del_carrito.php?codigo_articulo=<?php echo $articulo['Codigo']; ?>">Eliminar</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="submit" name="realizar_compra">Realizar Compra</button>
            </form>
        <?php else: ?>
            <p>El carrito está vacío.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>


</body>
</html>
