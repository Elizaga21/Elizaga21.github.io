<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
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

         .oferta::before {
         content: "Oferta"; 
         position: absolute; 
         top: 10px; 
         right: 10px; 
         background-color: black; 
         color: yellow; 
         padding: 5px; 
         border-radius: 5px; 
     }
     
     
     .precio-oferta del {
         color: red; 
     }
     
  
     .precio-oferta {
         color: green; 
     }


</style>

<?php
$articulosPorPagina = 10;

// Calcular el número total de artículos
$totalArticulos = $pdo->query("SELECT COUNT(*) FROM Articulos")->fetchColumn();

// Calcular el número total de páginas
$totalPaginas = ceil($totalArticulos / $articulosPorPagina);

// Obtener el número de página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcular el índice inicial del primer artículo en la página actual
$indiceInicio = ($paginaActual - 1) * $articulosPorPagina;

// Consulta SQL con limit y offset para la paginación
$sql = "SELECT A.*, C.Nombre AS NombreCategoria, C.Escala AS EscalaCategoria FROM Articulos A
        LEFT JOIN Categorias C ON A.CategoriaID = C.CategoriaID";

// Aplicar filtro de búsqueda si está definido
if (!empty($busqueda)) {
    $sql .=  " WHERE TRIM(A.Nombre) LIKE '%" . trim($busqueda) . "%'";
}

$sql .= " LIMIT $indiceInicio, $articulosPorPagina";

$stmt = $pdo->query($sql);

// Obtener artículos marcados como favoritos
$articulosFavoritos = isset($_SESSION['articulos_favoritos']) ? $_SESSION['articulos_favoritos'] : [];

if ($stmt) {
    echo "<div class='articulos-container'>";
    while ($articulo = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Verificar si el artículo está marcado como favorito
        $esFavorito = in_array($articulo['Codigo'], $articulosFavoritos);
        $esOferta = $articulo['enOferta'] == 1;

        // Calcular el precio con descuento (10% menos)
        $precioConDescuento = number_format($articulo['Precio'] * 0.90, 2);

        // Mostrar cada artículo
        echo "<div class='articulo" . ($esOferta ? ' oferta' : '') . "'>"; 
        echo "<a href='detalle_articulo.php?codigo_articulo={$articulo['Codigo']}&oferta={$articulo['enOferta']}'>
                <img src='{$articulo['Imagen']}' alt='{$articulo['Nombre']}'>
                <h2>{$articulo['Nombre']}</h2>
              </a>
              <p>{$articulo['Descripcion']}</p>";
        
        if ($esOferta) {
            // Si el artículo está en oferta, mostrar precio antiguo tachado y precio con descuento
            echo "<p class='precio-oferta'>Precio Antiguo: <del>{$articulo['Precio']} euros</del></p>";
            echo "<p class='precio-oferta'>Precio Nuevo: {$precioConDescuento} euros</p>";
        } else {
            // Si no está en oferta, mostrar solo el precio normal
            echo "<p>Precio: {$articulo['Precio']} euros</p>";
        }
        
        echo "<div class='iconos'>
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

    // Agregar enlaces de paginación
    echo "<div class='paginacion'>";
    for ($i = 1; $i <= $totalPaginas; $i++) {
        echo "<a href='?pagina=$i'>$i</a>";
    }
    echo "</div>";

} else {
    echo "Error en la consulta de la base de datos.";
}

?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const heartIcons = document.querySelectorAll('.heart-icon');

    heartIcons.forEach(icon => {
        icon.addEventListener('click', function () {
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
        xhr.onload = function () {
            // Manejar la respuesta si es necesario
            console.log(xhr.responseText);
        };
        xhr.send(`codigo_articulo=${codigoArticulo}&accion=${icon.classList.contains('fas') ? 'agregar' : 'quitar'}`);
    }
});
</script>
