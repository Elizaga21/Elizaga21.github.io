<style>
.container {
    max-width: 400px;
    margin: 0 auto;
}

.container div {
    display: flex;
    flex-direction: column; /* Cambia la dirección del contenedor a columna */
    align-items: center; /* Centra los elementos en el eje horizontal */
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
    text-align: center;
}

.container .collection-info {
    margin-top: 10px; /* Agrega separación entre el nombre y la información de la colección */
    text-align: center;
}

.container p {
    font-size: 16px;
    margin: 5px 0; /* Ajusta el margen para separar los elementos */
    color: #555;
}

.container form {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px; /* Agrega separación entre la información y los botones */
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
    margin-right: 5px;
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
    margin-left: 5px;
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

    $query = "SELECT Articulos.* FROM Articulos 
              INNER JOIN Categorias ON Articulos.CategoriaID = Categorias.CategoriaID
              WHERE Articulos.Nombre LIKE :nombreArticulo";

    // Agrega condiciones adicionales según los filtros seleccionados
    if (!empty($escala)) {
        $query .= " AND Categorias.Escala = :escala";
    }

    if (!empty($marca)) {
        $query .= " AND Categorias.Marca = :marca";
    }

    if (!empty($tipoVehiculo)) {
        $query .= " AND Categorias.Nombre = :tipoVehiculo";
    }

    if (!empty($coleccion)) {
        $query .= " AND Categorias.Coleccion = :coleccion";
    }

    if (!empty($precio)) {
        $query .= " AND Articulos.Precio <= :precio";
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
        echo "<div class='container'>";  // Mueve la apertura del contenedor fuera del bucle
        while ($articulo = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Mostrar resultados
         
            echo "<div>";
            echo "<img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>";
            echo "<h3>{$articulo['Nombre']}</h3>";
            echo "<div class='collection-info'>";
            echo "<p>Miniatura de Colección a escala {$articulo['Escala']}</p>";
            echo "<p>Precio: {$articulo['Precio']} €</p>";
            echo "</div>";
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
        echo "</div>";  // Cierra el contenedor
    } else {
        echo "No se encontraron resultados para la búsqueda.";
    }
    $content = ob_get_clean();  // Obtiene el contenido del búfer y limpia el búfer de salida

    // Enviar el contenido generado de vuelta a la solicitud AJAX
    echo $content;
} 
?>
