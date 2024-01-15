<?php
include 'db_connection.php';

// Obtener categorías
$stmtCategorias = $pdo->prepare("SELECT * FROM Categorias WHERE Activo = 1 AND CategoriaPadreID IS NULL");
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Contenido del menú izquierdo (categorías y subcategorías) -->
<nav id="menu-izquierda">


    <!-- Buscar Artículo por Nombre -->
    <label for="buscar-articulo">Buscar Artículo...</label>
    <input type="text" id="buscar-articulo" name="buscar-articulo" placeholder="Nombre del Artículo">

    <!-- Escala -->
    <label for="escala">Escala:</label>
    <select id="escala" name="escala">
        <option value="1:18">1:18</option>
        <option value="1:24">1:24</option>
        <option value="1:43">1:43</option>
    </select>

    <!-- Marca -->
    <label for="marca">Marca:</label>
    <select id="marca" name="marca">
        <!-- Llenar opciones de marcas desde la base de datos -->
        <?php
        $stmtMarcas = $pdo->prepare("SELECT DISTINCT Marca FROM Categorias WHERE Activo = 1 AND Marca IS NOT NULL");
        $stmtMarcas->execute();
        $marcas = $stmtMarcas->fetchAll(PDO::FETCH_COLUMN);
        foreach ($marcas as $marca): ?>
            <option value="<?php echo $marca; ?>"><?php echo $marca; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Tipo de Vehículo -->
    <label for="tipo-vehiculo">Tipo de Vehículo:</label>
    <select id="tipo-vehiculo" name="tipo-vehiculo">
        <option value="Coche">Coche</option>
        <option value="Camion">Camión</option>
        <option value="Moto">Moto</option>
    </select>

    <!-- Colecciones -->
    <label for="colecciones">Colecciones:</label>
    <select id="colecciones" name="colecciones">
        <!-- Llenar opciones de colecciones desde la base de datos -->
        <?php
        $stmtColecciones = $pdo->prepare("SELECT DISTINCT Coleccion FROM Categorias WHERE Activo = 1 AND Coleccion IS NOT NULL");
        $stmtColecciones->execute();
        $colecciones = $stmtColecciones->fetchAll(PDO::FETCH_COLUMN);
        foreach ($colecciones as $coleccion): ?>
            <option value="<?php echo $coleccion; ?>"><?php echo $coleccion; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Precio -->
    <label for="precio">Precio:</label>
    <input type="range" id="precio" name="precio" min="0" max="1000" value="0" step="10">
    <span id="precio-valor">0 €</span>

    <button id="button" onclick="aplicarFiltros()">Aplicar</button>
</nav>

<!-- Script para manejar la búsqueda y aplicar filtros -->
<script>
    function aplicarFiltros() {
        // Obtener valores seleccionados
        var escala = $('#escala').val();
        var marca = $('#marca').val();
        var tipoVehiculo = $('#tipo-vehiculo').val();
        var coleccion = $('#colecciones').val();
        var precio = $('#precio').val();

        // Obtener nombre del artículo a buscar
        var nombreArticulo = $('#buscar-articulo').val();

        // Realizar la petición AJAX
        $.ajax({
            type: 'POST',
            url: 'buscar_articulos.php', // Reemplaza con la URL correcta para manejar la búsqueda
            data: {
                escala: escala,
                marca: marca,
                tipoVehiculo: tipoVehiculo,
                coleccion: coleccion,
                precio: precio,
                nombreArticulo: nombreArticulo
            },
            success: function(response) {
                // Actualizar el contenido de la página con los resultados de la búsqueda
                $('#content').html(response);
            },
            error: function(error) {
                console.error('Error en la petición AJAX', error);
            }
        });
    }
</script>
