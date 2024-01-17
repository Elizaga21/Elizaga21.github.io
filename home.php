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
  }

  .articulo img {
    max-width: 90%;
    height: auto;
    border-radius: 4px;
  }

  .articulo h2,
  .articulo p {
    margin-top: 10px;
  }

  .articulo .iconos {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
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
  }

  .articulo button:hover {
    background-color: #45a049;

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
                <a href='detalle_articulo.php?codigo_articulo={$articulo['Codigo']}'>
                    <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
                    <h2>{$articulo['Nombre']}</h2>
                </a>
                <p>{$articulo['Descripcion']}</p>
                <p>Precio: {$articulo['Precio']} euros</p>
                <div class='iconos'>
                    <i class='far fa-heart heart-icon' data-codigo='{$articulo['Codigo']}'></i>
                    <form action='carrito.php' method='post'>
                        <input type='hidden' name='codigo_articulo' value='{$articulo['Codigo']}'>
                        <input type='number' name='cantidad' value='1' min='1'>
                        <button type='submit' name='agregar_carrito'>
                            <i class='fas fa-shopping-cart'></i> Agregar al Carrito
                        </button>
                    </form>
                </div>
              </div>";
    }

    echo "</div>";
} else {
    echo "Error en la consulta de la base de datos.";
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heartIcons = document.querySelectorAll('.heart-icon');

    heartIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            const codigoArticulo = this.getAttribute('data-codigo');
            agregarFavorito(codigoArticulo, this);
        });
    });

    function agregarFavorito(codigoArticulo, icon) {
        // Lógica para agregar a favoritos (puedes hacer una petición AJAX aquí)
        // ...

        // Cambiar el color del corazón y guardar en favoritos.php
        icon.classList.toggle('fas');
        icon.classList.toggle('far');
        icon.style.color = icon.classList.contains('fas') ? 'red' : 'inherit';
    }
});
</script>
