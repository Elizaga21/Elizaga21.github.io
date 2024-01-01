<?php
// Consulta para obtener los artículos
$query = $pdo->query("SELECT * FROM Articulos");

// Verificamos si hay resultados
if ($query) {
    // Iteramos sobre los resultados
    while ($articulo = $query->fetch(PDO::FETCH_ASSOC)) {
        // Mostramos cada artículo
        echo "<div class='articulo'>
                <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
                <h2>{$articulo['Nombre']}</h2>
                <p>{$articulo['Descripcion']}</p>
                <p>Precio: {$articulo['Precio']} euros</p>
              </div>";
    }
} else {
    echo "Error en la consulta de la base de datos.";
}
?>
