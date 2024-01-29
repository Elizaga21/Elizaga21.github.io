
<style>
  .articulos-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }

  .articulo {
    width: 42%;
    margin-bottom: 20px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 8px;
    position: relative;
    display: flex;
    flex-direction: column;
  }

  .articulo img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-bottom: 10px;
  }

  .articulo h2,
  .articulo p {
    margin-top: 10px;
  }

  .articulo .iconos {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
  }

  .articulo .iconos i {
    cursor: pointer;
  }

  .articulo button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
  }

  .articulo button:hover {
    background-color: #45a049;
  }

  .paginacion a {
    display: inline-block;
    padding: 10px;
    margin-right: 5px;
    background-color: #000;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 10px;
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
    $precioMin = isset($_POST['precioMin']) ? $_POST['precioMin'] : null;
    $precioMax = isset($_POST['precioMax']) ? $_POST['precioMax'] : null;

    $query = "SELECT Articulos.* FROM Articulos 
              INNER JOIN Categorias ON Articulos.CategoriaID = Categorias.CategoriaID
              WHERE Articulos.Nombre LIKE :nombreArticulo";

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

    if (!empty($precioMin) && !empty($precioMax)) {
        $query .= " AND Articulos.Precio BETWEEN :precioMin AND :precioMax";
    }
    

    $stmt = $pdo->prepare($query);

    $stmt->bindValue(':nombreArticulo', "%$nombreArticulo%", PDO::PARAM_STR);

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

    if (!empty($precioMin) && !empty($precioMax)) {
        $stmt->bindValue(':precioMin', $precioMin, PDO::PARAM_INT);
        $stmt->bindValue(':precioMax', $precioMax, PDO::PARAM_INT);
    }

    $stmt->execute();

    ob_start();  // Inicia el almacenamiento en búfer de salida
    if ($stmt->rowCount() > 0) {
        echo "<div class='articulos-container'>";  
        while ($articulo = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Mostrar resultados
            echo "<div class='articulo'>"; 
            echo "  <a href='detalle_articulo.php?codigo_articulo={$articulo['Codigo']}'>
            <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
             <h2>{$articulo['Nombre']}</h2>
             </a>'>";
            echo "<p>{$articulo['Descripcion']}</p>";
            echo "<p>Precio: {$articulo['Precio']} €</p>";
            echo "<div class='iconos'>";
            // Agrega el icono de favorito (corazón)
            echo "<i class='far fa-heart heart-icon'></i>";
            echo "<form action='carrito.php' method='post'>";
            echo "<input type='hidden' name='codigo_articulo' value='{$articulo['Codigo']}'>";
            echo "<input type='number' name='cantidad' value='1' min='1'>";
            echo "<button type='submit' name='agregar_carrito'>";
            echo "<i class='fas fa-shopping-cart'></i> Agregar al Carrito";
            echo "</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>"; 
    } else {
        echo "No se encontraron resultados para la búsqueda.";
    }
    $content = ob_get_clean();  // Obtiene el contenido del búfer y limpia el búfer de salida

    echo $content;
} 
?>
