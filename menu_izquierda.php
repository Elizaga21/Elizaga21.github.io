<?php
include 'db_connection.php';

// Obtener categorías
$stmtCategorias = $pdo->prepare("SELECT * FROM Categorias WHERE Activo = 1 AND CategoriaPadreID IS NULL");
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Contenido del menú izquierdo (categorías y subcategorías) -->
<nav id="menu-izquierda" style="background-color: #f4f4f4; padding: 15px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 440px; margin-left: 10px; margin-right: 5px;">

    <!-- Buscar Artículo por Nombre -->
    <label for="nombreArticulo">Buscar Artículo...</label>
    <input type="text" id="nombreArticulo" name="nombreArticulo" placeholder="Nombre del Artículo">

    <!-- Escala -->
    <label for="escala">Escala:</label>
    <select id="escala" name="escala">
        <option value="" selected></option>
        <option value="1:18">1:18</option>
        <option value="1:24">1:24</option>
        <option value="1:43">1:43</option>
    </select>

    <!-- Marca -->
    <label for="marca">Marca:</label>
    <select id="marca" name="marca">
        <option value="" selected></option>

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
        <option value="" selected></option>

        <!-- Llenar opciones de tipos de vehículos desde la base de datos -->
        <?php
        $stmtTiposVehiculo = $pdo->prepare("SELECT DISTINCT Nombre FROM Categorias WHERE Activo = 1 AND CategoriaPadreID IS NOT NULL");
        $stmtTiposVehiculo->execute();
        $tiposVehiculo = $stmtTiposVehiculo->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tiposVehiculo as $tipoVehiculo): ?>
            <option value="<?php echo $tipoVehiculo; ?>"><?php echo $tipoVehiculo; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Colecciones -->
    <label for="colecciones">Colecciones:</label>
    <select id="colecciones" name="colecciones">
        <option value="" selected></option>
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
    <input type="range" id="precio" name="precio" min="0" max="1000" value="0" step="10" oninput="actualizarPrecio()">
    <span id="precio-valor">0 €</span>

    <button id="button" onclick="aplicarFiltros()">Aplicar</button>
</nav>

<!-- Script para manejar la búsqueda y aplicar filtros -->
<script>
    function actualizarPrecio() {
        var precio = $('#precio').val();
        $('#precio-valor').text(precio + ' €');
    }

    function aplicarFiltros() {
        var escala = $('#escala').val();
        var marca = $('#marca').val();
        var tipoVehiculo = $('#tipo-vehiculo').val();
        var coleccion = $('#colecciones').val();
        var precio = $('#precio').val();

        // Obtener nombre del artículo a buscar
        var nombreArticulo = $('#nombreArticulo').val();

        // Realizar la petición AJAX
        $.ajax({
            type: 'POST',
            url: 'buscar_articulo.php',
            data: {
                nombreArticulo: nombreArticulo, // Pass the search term
                escala: escala,
                marca: marca,
                tipoVehiculo: tipoVehiculo,
                coleccion: coleccion,
                precio: precio
            },
            success: function (response) {
                // Actualizar el contenido de la página con los resultados de la búsqueda
                $('#content').html(response);
            },
            error: function (error) {
                console.error('Error en la petición AJAX', error);
            }
        });
    }
</script>



<style>
    #menu-izquierda label,
    #menu-izquierda select,
    #menu-izquierda input {
        width: 100%;
        margin-bottom: 10px;
    }

    #menu-izquierda button {
        border: none;
        color: #fff;
        background-image: linear-gradient(30deg, #0400ff, #4ce3f7);
        border-radius: 20px;
        background-size: 100% auto;
        font-family: inherit;
        cursor: pointer;
        font-size: 17px;
        padding: 0.6em 1.5em;
        margin-top: 10px;
        margin-left: auto;
        /* Esto alinea el botón a la derecha */
    }

    #menu-izquierda button:hover {
        background-position: right center;
        background-size: 200% auto;
        -webkit-animation: pulse 2s infinite;
        animation: pulse512 1.5s infinite;
    }

    @keyframes pulse512 {
        0% {
            box-shadow: 0 0 0 0 #05bada66;
        }

        70% {
            box-shadow: 0 0 0 10px rgb(218 103 68 / 0%);
        }

        100% {
            box-shadow: 0 0 0 0 rgb(218 103 68 / 0%);
        }
    }
</style>