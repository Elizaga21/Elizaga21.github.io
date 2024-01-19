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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreArticulo = $_POST['nombreArticulo'];
    $escala = $_POST['escala'];
    $marca = $_POST['marca'];
    $tipoVehiculo = $_POST['tipoVehiculo'];
    $coleccion = $_POST['coleccion'];
    $precio = $_POST['precio'];

    $query = "SELECT * FROM Articulos WHERE Nombre LIKE :nombreArticulo";

    // Agrega condiciones adicionales según los filtros seleccionados
    if (!empty($escala)) {
        $query .= " AND Escala = :escala";
    }

    if (!empty($marca)) {
        $query .= " AND Marca = :marca";
    }

    if (!empty($tipoVehiculo)) {
        $query .= " AND TipoVehiculo = :tipoVehiculo";
    }

    if (!empty($coleccion)) {
        $query .= " AND Coleccion = :coleccion";
    }

    if (!empty($precio)) {
        $query .= " AND Precio <= :precio";
    }

    $stmt = $pdo->prepare($query);

    $stmt->bindValue(':nombreArticulo', "%$nombreArticulo%", PDO::PARAM_STR);

    // Enlaza los parámetros adicionales según sea necesario
    if (!empty($escala)) {
        $stmt->bindValue(':escala', $escala, PDO::PARAM_STR);
    }

    if (!empty($marca)) {
        $stmt->bindValue(':marca', $marca, PDO::PARAM_STR);
    }

    if (!empty($tipoVehiculo)) {
        $stmt->bindValue(':tipoVehiculo', $tipoVehiculo, PDO::PARAM_STR);
    }

    if (!empty($coleccion)) {
        $stmt->bindValue(':coleccion', $coleccion, PDO::PARAM_STR);
    }

    if (!empty($precio)) {
        $stmt->bindValue(':precio', $precio, PDO::PARAM_INT);
    }

    $stmt->execute();

    ob_start();  // Inicia el almacenamiento en búfer de salida
    if ($stmt->rowCount() > 0) {
        while ($articulo = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Mostrar resultados
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
            // Agrega el icono de favorito (corazón)
            echo "<button type='button' class='btn-favorito'>";
            echo "<i class='fas fa-heart'></i> Favorito";
            echo "</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "No se encontraron resultados para la búsqueda.";
    }
    $content = ob_get_clean();  // Obtiene el contenido del búfer y limpia el búfer de salida

    // Enviar el contenido generado de vuelta a la solicitud AJAX
    echo $content;
} 
?>
