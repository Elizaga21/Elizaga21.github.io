<?php
session_start();

// Verifica si se proporcionó un código de artículo válido
if (isset($_GET['codigo_articulo'])) {
    $codigo_articulo = $_GET['codigo_articulo'];

    // Elimina el artículo del carrito si existe
    if (isset($_SESSION['carrito'][$codigo_articulo])) {
        unset($_SESSION['carrito'][$codigo_articulo]);
    }
}

// Redirecciona de nuevo a carrito.php
header("Location: carrito.php");
exit();
?>
