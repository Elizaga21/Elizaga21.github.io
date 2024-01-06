<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'empleado' && $_SESSION['rol'] !== 'administrador')) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

// Configuración de paginación
$articulosPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $articulosPorPagina;

// Configuración de orden y búsqueda
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'Nombre';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$sql = "SELECT A.*, C.Nombre AS NombreCategoria FROM Articulos A
        LEFT JOIN Categorias C ON A.CategoriaID = C.CategoriaID";
if (!empty($busqueda)) {
    $sql .=  " WHERE TRIM(A.Nombre) LIKE '%" . trim($busqueda) . "%'";
}
$sql .= " ORDER BY A.$orden LIMIT $inicio, $articulosPorPagina";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$Articulos = $stmt->fetchAll();

// Consulta para obtener el total de artículos (para la paginación)
$totalArticulos = $pdo->query("SELECT COUNT(*) FROM Articulos")->fetchColumn();
$totalPaginas = ceil($totalArticulos / $articulosPorPagina);

// Verificar si hay un parámetro 'eliminado' en la URL
if (isset($_GET['eliminado']) && $_GET['eliminado'] == 'true') {
    echo '<p style="color: green;">El artículo se ha eliminado correctamente.</p>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Artículos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
    body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
}

.container {
    max-width: 400px;
    width: 100%;
    padding: 20px;
    background-color: #fff; 
    border: 1px solid #ddd; 
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
}

.main-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-top: 50px; /* Ajusta según sea necesario */
}

h2, h3 {
    color: #495057;
}

    .formulario {
        width: 40%;
        margin: 0 auto;
    }

    label {
        color: #495057;
    }

    input[type="text"],
    textarea,
    input[type="file"],
    input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn-success {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: #333;
}

    .error {
        color: #dc3545;
    }

    .table-container {
        margin-top: 20px;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .table th {
        background-color: #000; /* Cambiado a negro */
         color: #ff0; /* Cambiado a amarillo */
    }

    .table img {
        max-width: 50px;
        max-height: 50px;
        margin-right: 10px;
    }

    .table .edit-icon,
    .table .delete-icon {
        cursor: pointer;
    }

    .table .edit-icon:hover,
    .table .delete-icon:hover {
        opacity: 0.8;
    }

    .footer-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
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

    a {
        color: #fff;
    }

    .button-container {
        text-decoration: none;
        text-align:center;
        background-color: #000;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
        }

        .button-container :hover {
            background-color: #333; 
        }

        .edit-icon {
    font-size: 30px; 
    color: #000; 
    text-decoration: none; 
}

.delete-icon{
    font-size: 30px; 
    color: #000; 
    text-decoration: none; 
}
.edit-icon:hover {
    opacity: 0.8;
}
.delete-icon:hover {
    opacity: 0.8;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-container">
    <div class="container">
        <h2>Alta de Artículos</h2>

        <form action="procesar_imagen.php" method="post" enctype="multipart/form-data">
        <label for="Codigo">Código:</label>
        <input type="text" name="Codigo" pattern="[a-zA-Z]{3}[0-9]{1,5}" required>
        <br>

        <label for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" required>
        <br>

        <label for="Descripcion">Descripción:</label>
        <textarea name="Descripcion" required></textarea>
        <br>

        <label for="CategoriaID">Categoría:</label>
        <input type="text" name="CategoriaID" required>
        <br>

        <label for="Precio">Precio:</label>
        <input type="number" name="Precio" min="0" step="0.01" required>
        <br>

        <label for="Imagen">Imagen (jpg, jpeg, gif, png | Max 300 KB | Max 200x200 px):</label>
        <input type="file" name="Imagen" accept="image/jpeg, image/png, image/gif" required>
        <br>

        <label for="enOferta">En Oferta:</label>
            <input type="checkbox" name="enOferta" value="1">
            <br>

            <label for="Activo">Activo:</label>
            <input type="checkbox" name="Activo" value="1" checked>
            <br>

        <input type="submit" value="Enviar">
    </form>
    </div>
    </div>

    <br>

    <div class="main-container">
    <h2>Lista de Artículos</h2>

    <!-- Formulario para ordenar -->
    <form method="GET" class="formulario">
        <label for="orden">Ordenar por:</label>
        <select name="orden" id="orden" class="form-control">
            <option value="Nombre" <?php echo ($orden == 'Nombre') ? 'selected' : ''; ?>>Nombre</option>
            <option value="CategoriaID" <?php echo ($orden == 'CategoriaID') ? 'selected' : ''; ?>>Categoría</option>
            <option value="Precio" <?php echo ($orden == 'Precio') ? 'selected' : ''; ?>>Precio</option>
        </select>
        <input type="submit" value="Ordenar" class="btn btn-success">
    </form>

    <!-- Formulario para búsqueda -->
    <div class="formulario" style="margin-top: 20px;">
    <form method="GET" class="formulario">
        <label for="busqueda">Buscar:</label>
        <input type="text" name="busqueda" id="busqueda" value="<?php echo $busqueda; ?>" class="form-control">
        <input type="submit" value="Buscar" class="btn btn-success">
    </form>
    </div>

    <!-- Tabla de artículos -->
    <div class="table-container">
    <table class="table">
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Imagen</th>
            <th>En Oferta</th>
            <th>Activo</th>
            <th>Edición</th>
        </tr>
        <?php foreach ($Articulos as $articulo): ?>
            <tr>
                <td><?php echo $articulo['Codigo']; ?></td>
                <td><?php echo $articulo['Nombre']; ?></td>
                <td><?php echo $articulo['Descripcion']; ?></td>
                <td><?php echo $articulo['NombreCategoria']; ?></td>
                <td><?php echo $articulo['Precio']; ?></td>
                <td><img src="<?php echo $articulo['Imagen']; ?>" alt="Imagen"></td>
                <td><?php echo $articulo['enOferta'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $articulo['Activo'] ? 'Sí' : 'No'; ?></td>
                    <td>
            <?php if ($_SESSION['rol'] === 'empleado' || $_SESSION['rol'] === 'administrador') : ?>
                <a href="modificar_articulo.php?Codigo=<?php echo $articulo['Codigo']; ?>" class="edit-icon">
                     <span class="material-icons">edit</span>
                         </a>

                         <a href="eliminar_articulo.php?Codigo=<?php echo $articulo['Codigo']; ?>" class="delete-icon">
                      <span class="material-icons">delete</span> 
                          </a>

               <?php endif; ?>
             </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>

    <div class="footer-container">
    <div class="paginacion">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
            <a href="?pagina=<?php echo $i; ?>&orden=<?php echo $orden; ?>&busqueda=<?php echo $busqueda; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <br>
    <div class="button-container">
            <a href="admin.php">Volver atrás</a>
        </div>
</div>
</div>
<?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
