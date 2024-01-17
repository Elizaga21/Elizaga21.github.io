<style>
.container {
    max-width: 800px;
    margin: 0 auto;
}

.container h2 {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

.container div {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.container img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.container h3 {
    font-size: 18px;
    margin: 10px 0;
    color: #333;
}

.container p {
    font-size: 16px;
    margin-bottom: 15px;
    color: #555;
}

.container form {
    display: flex;
    align-items: center;
}

.container form input {
    width: 60px;
    padding: 5px;
    margin-right: 10px;
}

.container form button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.container form button:hover {
    background-color: #45a049;
}

.btn-favorito {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

.btn-favorito:hover {
    background-color: #c0392b;
}

</style>

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
        while ($articulo = $query->fetch(PDO::FETCH_ASSOC)) {
            // Mostramos cada artículo
            echo "<div class='container'>";
            echo "<h2>Detalles del Artículo</h2>";
            echo "<div>";
            echo "<img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>";
            echo "<h3>{$articulo['Nombre']}</h3>";
            echo "<p>{$articulo['Descripcion']}</p>";
            echo "<p>Precio: {$articulo['Precio']} €</p>";
            echo "<form action='carrito.php' method='post'>";
            echo "<input type='hidden' name='codigo_articulo' value='{$articulo['Codigo']}'>";
            echo "<input type='number' name='cantidad' value='1' min='1'>";
            echo "<button type='submit' name='agregar_carrito'>";
            echo "<i class='fas fa-shopping-cart'></i> Agregar al Carrito";
            echo "</button>";
            echo "</form>";
            // Agrega el icono de favorito (corazón)
            echo "<button type='button' class='btn-favorito'>";
            echo "<i class='fas fa-heart'></i> Favorito";
            echo "</button>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "No se encontraron resultados para la búsqueda.";
    }
} 
?>
