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
// Configuración de paginación
$articulosPorPagina = 8;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $articulosPorPagina;

// Configuración de orden y búsqueda
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'Nombre';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';



// Consulta SQL
$sql = "SELECT A.*, C.Nombre AS NombreCategoria, C.Escala AS EscalaCategoria FROM Articulos A
        LEFT JOIN Categorias C ON A.CategoriaID = C.CategoriaID";

// Aplicar filtro de búsqueda si está definido
if (!empty($busqueda)) {
    $sql .=  " WHERE TRIM(A.Nombre) LIKE '%" . trim($busqueda) . "%'";
}

$sql .= " ORDER BY A.$orden LIMIT :inicio, :articulosPorPagina";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':articulosPorPagina', $articulosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$Articulos = $stmt->fetchAll();

// Obtener artículos marcados como favoritos
$articulosFavoritos = isset($_SESSION['articulos_favoritos']) ? $_SESSION['articulos_favoritos'] : [];

// Consulta para obtener el total de artículos (para la paginación)
$totalArticulos = $pdo->query("SELECT COUNT(*) FROM Articulos")->fetchColumn();
$totalPaginas = ceil($totalArticulos / $articulosPorPagina);

if ($stmt) {
    echo "<div class='articulos-container'>";
    foreach ($Articulos as $articulo) {
        // Verificar si el artículo está marcado como favorito
        $esFavorito = in_array($articulo['Codigo'], $articulosFavoritos);
        
        // Mostrar cada artículo
        echo "<div class='articulo'>
                <a href='detalle_articulo.php?codigo_articulo={$articulo['Codigo']}'>
                    <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
                    <h2>{$articulo['Nombre']}</h2>
                </a>
                <p>{$articulo['Descripcion']}</p>
                <p>Precio: {$articulo['Precio']} euros</p>
                <div class='iconos'>
                    <i class='far fa-heart heart-icon " . ($esFavorito ? 'fas' : '') . "' data-codigo='{$articulo['Codigo']}'></i>
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

    // Mostrar enlaces de paginación
    echo "<div class='paginacion'>";
    for ($i = 1; $i <= $totalPaginas; $i++) {
        $activeClass = $i == $paginaActual ? 'active' : '';
        echo "<a href='home.php?pagina={$i}&orden={$orden}&busqueda={$busqueda}' class='pagination-link {$activeClass}'>{$i}</a>";
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
        // Cambiar el color del corazón
        icon.classList.toggle('fas');
        icon.classList.toggle('far');
        icon.style.color = icon.classList.contains('fas') ? 'red' : 'inherit';

        // Realizar una petición AJAX para actualizar la base de datos
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'favoritos.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            // Manejar la respuesta si es necesario
            console.log(xhr.responseText);
        };
        xhr.send(`codigo_articulo=${codigoArticulo}&accion=${icon.classList.contains('fas') ? 'agregar' : 'quitar'}`);
    }
});

</script>
