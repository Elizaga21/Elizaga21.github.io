<?php
include 'db_connection.php';

if (isset($_POST['nombreArticulo'])) {
    $nombreArticulo = $_POST['nombreArticulo'];

    // Consulta para obtener los artículos que coinciden con la búsqueda
    $query = $pdo->prepare("SELECT * FROM Articulos WHERE Nombre LIKE :busqueda");
    $query->bindValue(':busqueda', "%$nombreArticulo%", PDO::PARAM_STR);
    $query->execute();

    // Verificamos si hay resultados
    if ($query->rowCount() > 0) {
        // Iteramos sobre los resultados
        echo "<div class='articulos-container'>";
        while ($articulo = $query->fetch(PDO::FETCH_ASSOC)) {
            // Mostramos cada artículo
            echo "<div class='articulo'>
                    <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
                    <h2>{$articulo['Nombre']}</h2>
                    <p>{$articulo['Descripcion']}</p>
                    <p>Precio: {$articulo['Precio']} euros</p>
                </div>";
        }
        echo "</div>";
    } else {
        echo "No se encontraron resultados para la búsqueda.";
    }
} 
?>
