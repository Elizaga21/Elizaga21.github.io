<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'empleado' && $_SESSION['rol'] !== 'administrador')) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

// Función para obtener todas las categorías
function obtenerCategorias($pdo) {
    $stmt = $pdo->query("SELECT * FROM Categorias");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener una categoría por su ID
function obtenerCategoriaPorID($pdo, $categoriaID) {
    $stmt = $pdo->prepare("SELECT * FROM Categorias WHERE CategoriaID = ?");
    $stmt->execute([$categoriaID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para actualizar una categoría
function actualizarCategoria($pdo, $categoriaID, $nombre, $descripcion, $categoriaPadreID, $activo, $escala, $marca, $coleccion) {
    $stmt = $pdo->prepare("UPDATE Categorias SET 
                           Nombre = ?,
                           Descripcion = ?,
                           CategoriaPadreID = ?,
                           Activo = ?,
                           Escala = ?,
                           Marca = ?,
                           Coleccion = ?
                           WHERE CategoriaID = ?");
    return $stmt->execute([$nombre, $descripcion, $categoriaPadreID, $activo, $escala, $marca, $coleccion, $categoriaID]);
}

// Función para insertar una nueva categoría
function insertarCategoria($pdo, $nombre, $descripcion, $categoriaPadreID, $activo, $escala, $marca, $coleccion) {
    $stmt = $pdo->prepare("INSERT INTO Categorias (Nombre, Descripcion, CategoriaPadreID, Activo, Escala, Marca, Coleccion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nombre, $descripcion, $categoriaPadreID, $activo, $escala, $marca, $coleccion]);
}

// Función para eliminar una categoría por su ID
function eliminarCategoria($pdo, $categoriaID) {
    $stmt = $pdo->prepare("DELETE FROM Categorias WHERE CategoriaID = ?");
    return $stmt->execute([$categoriaID]);
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'actualizar':
                // Procesar formulario de actualización
                $categoriaID = $_POST['categoria_id'];
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $categoriaPadreID = $_POST['categoria_padre_id'];
                $activo = isset($_POST['activo']) ? 1 : 0;
                $escala = $_POST['escala'];
                $marca = $_POST['marca'];
                $coleccion = $_POST['coleccion'];

                if (actualizarCategoria($pdo, $categoriaID, $nombre, $descripcion, $categoriaPadreID, $activo, $escala, $marca, $coleccion)) {
                } else {
                    echo "Error al actualizar la categoría.";
                }
                break;

            case 'insertar':
                // Procesar formulario de inserción
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $categoriaPadreID = $_POST['categoria_padre_id'];
                $activo = isset($_POST['activo']) ? 1 : 0;
                $escala = $_POST['escala'];
                $marca = $_POST['marca'];
                $coleccion = $_POST['coleccion'];

                if (insertarCategoria($pdo, $nombre, $descripcion, $categoriaPadreID, $activo, $escala, $marca, $coleccion)) {
                    $_SESSION['mensaje_exito'] = "Categoría insertada correctamente.";
                    echo "<script>window.location.href = 'mantenimiento_categorias.php';</script>"; // Redirige para evitar reenvío del formulario
                    exit();
                } else {
                    echo "Error al insertar la categoría.";
                }
                
                break;

            case 'eliminar':
                // Procesar formulario de eliminación
                $categoriaID = $_POST['categoria_id'];

                if (eliminarCategoria($pdo, $categoriaID)) {
                } else {
                    echo "Error al eliminar la categoría.";
                }
                break;

            default:
                echo "Acción no reconocida.";
        }
    }
}

// Obtener todas las categorías
$categorias = obtenerCategorias($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Categorías</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos generales */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
}

.container {
    text-align: center;
    max-width: 800px;
    width: 100%;
    margin: auto;
    padding: 20px;
}

h2, h3 {
    color: #495057;
}

/* Estilos del formulario */
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input,
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

form input[type="checkbox"] {
    margin-top: 5px;
}

form input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 12px 20px; 
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}


form input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Estilos de la lista de categorías */
.lista-categoria {
    list-style: none;
    padding: 0;
}

.lista-categoria li {
    margin-bottom: 20px;
    background-color: #fff;
    padding: 15px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lista-categoria li div {
    flex-grow: 1; 
}

/* Estilos de los botones de actualizar y eliminar */
.lista-categoria li form[style="display: inline;"] {
    display: flex;
}


/* Estilos de los botones de actualizar y eliminar */
.lista-categoria li form[style="display: inline;"] input[type="submit"] {
    background-color: #dc3545;
    color: #fff;
    padding: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
    margin-right: 10px;
}

/* Estilos de los botones de actualizar y eliminar */
<!-- Estilos de los botones de actualizar y eliminar -->
.lista-categoria li form[style="display: inline;"] input[type="submit"][value="Actualizar"] {
    background-color: #000;
    color: #fff;
    padding: 12px 20px;
}

.lista-categoria li form[style="display: inline;"] input[type="submit"][value="Eliminar"] {
    background-color: #dc3545;
    color: #fff;
    padding: 12px 20px;
}

/* Estilo para el botón "Actualizar" */
.lista-categoria li form[style="display: inline;"] .btn-actualizar {
    background-color: #336699;
    color: #fff;
    padding: 12px 20px;
}

/* Estilo para el botón "Eliminar" */
.lista-categoria li form[style="display: inline;"] .btn-eliminar {
    background-color: #cc0000;
    color: #fff;
    padding: 12px 20px;
}




    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Mantenimiento de Categorías</h2>

        <!-- Formulario para insertar una nueva categoría -->
        <form method="post">
            <h3>Nueva Categoría</h3>
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            <label>Descripción:</label>
            <textarea name="descripcion" rows="3"></textarea>
            <label>Categoría Padre ID:</label>
            <input type="number" name="categoria_padre_id">
            <label>Activo:</label>
            <input type="checkbox" name="activo" checked>
            <label>Escala:</label>
            <input type="text" name="escala">
            <label>Marca:</label>
            <input type="text" name="marca">
            <label>Colección:</label>
            <input type="text" name="coleccion">
            <input type="hidden" name="accion" value="insertar">
            <input type="submit" value="Insertar Categoría">
        </form>

       <!-- Lista de categorías existentes con opciones de actualización y eliminación -->
       <h3>Categorías Existentes</h3>
<ul class="lista-categoria">
    <?php foreach ($categorias as $categoria) : ?>
        <li>
            <div style="display: flex; align-items: center;">
                <div>
                    <strong>Nombre:</strong> <?php echo $categoria['Nombre']; ?><br>
                    <strong>Escala:</strong> <?php echo $categoria['Escala']; ?><br>
                    <strong>Marca:</strong> <?php echo $categoria['Marca']; ?>
                </div>
                <!-- Formulario para actualizar la categoría actual -->
                <form method="post">
                    <input type="hidden" name="categoria_id" value="<?php echo $categoria['CategoriaID']; ?>">
                    <input type="hidden" name="nombre_actual" value="<?php echo $categoria['Nombre']; ?>">
                    <input type="hidden" name="descripcion_actual" value="<?php echo $categoria['Descripcion']; ?>">
                    <input type="hidden" name="categoria_padre_id_actual" value="<?php echo $categoria['CategoriaPadreID']; ?>">
                    <input type="hidden" name="activo_actual" value="<?php echo $categoria['Activo']; ?>">
                    <input type="hidden" name="escala_actual" value="<?php echo $categoria['Escala']; ?>">
                    <input type="hidden" name="marca_actual" value="<?php echo $categoria['Marca']; ?>">
                    <input type="hidden" name="coleccion_actual" value="<?php echo $categoria['Coleccion']; ?>">

                    <input type="hidden" name="accion" value="actualizar">
                    <!-- Campos de entrada actualizados con los valores actuales -->
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo $categoria['Nombre']; ?>" required>
                    <label>Descripción:</label>
                    <textarea name="descripcion" rows="3"><?php echo $categoria['Descripcion']; ?></textarea>
                    <label>Categoría Padre ID:</label>
                    <input type="number" name="categoria_padre_id" value="<?php echo $categoria['CategoriaPadreID']; ?>">
                    <label>Activo:</label>
                    <input type="checkbox" name="activo" <?php echo $categoria['Activo'] ? 'checked' : ''; ?>>
                    <label>Escala:</label>
                    <input type="text" name="escala" value="<?php echo $categoria['Escala']; ?>">
                    <label>Marca:</label>
                    <input type="text" name="marca" value="<?php echo $categoria['Marca']; ?>">
                    <label>Colección:</label>
                    <input type="text" name="coleccion" value="<?php echo $categoria['Coleccion']; ?>">

                    <input type="submit" class="btn-actualizar" value="Actualizar">
                </form>

                <form method="post">
                    <input type="hidden" name="categoria_id" value="<?php echo $categoria['CategoriaID']; ?>">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="submit" class="btn-eliminar" value="Eliminar" onclick="return confirm('¿Estás seguro?')">
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

    </div>

    <?php include 'footer.php'; ?>

    <script>
    <?php
    if (isset($_SESSION['mensaje_exito'])) {
        echo "alert('{$_SESSION['mensaje_exito']}');";
        unset($_SESSION['mensaje_exito']); // Limpiar la variable de sesión después de mostrar el mensaje
    }
    ?>
</script>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
