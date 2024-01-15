<style>
  .articulos-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* O el método de justificación que prefieras */
  }

  .articulo {
    width: 48%; /* Ajusta el ancho según tus necesidades */
    margin-bottom: 20px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 8px;
    position: relative;
  }

  .articulo img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
  }

  .articulo h2,
  .articulo p,
  .articulo form {
    margin-top: 10px;
  }

  .articulo i {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: red; /* O el color que desees */
  }

  .articulo button {
    background-color: #333;
    color: white;
    cursor: pointer;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
  }

  .articulo button:hover {
    background-color: #555;
  }

</style>
<?php
// Consulta para obtener los artículos
$query = $pdo->query("SELECT * FROM Articulos");

if ($query) {
    echo "<div class='articulos-container'>";
    while ($articulo = $query->fetch(PDO::FETCH_ASSOC)) {
        // Mostramos cada artículo
        echo "<div class='articulo'>
                <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
                <h2>{$articulo['Nombre']}</h2>
                <p>{$articulo['Descripcion']}</p>
                <p>Precio: {$articulo['Precio']} euros</p>
                <i class='fas fa-heart' onclick='agregarFavorito({$articulo['Codigo']})'></i>
                <form action='carrito.php' method='post'>
                    <input type='hidden' name='codigo_articulo' value='{$articulo['Codigo']}'>
                    <input type='number' name='cantidad' value='1' min='1'>
                    <button type='submit' name='agregar_carrito'>
                        <i class='fas fa-shopping-cart'></i> Agregar al Carrito
                    </button>
                </form>
              </div>";
    }

    echo "</div>";
} else {
    echo "Error en la consulta de la base de datos.";
}
?>
