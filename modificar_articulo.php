<?php
session_start();

require 'db_connection.php';

// Verifica si el usuario está autenticado y tiene el rol de administrador o editor
if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'administrador' && $_SESSION['rol'] !== 'empleado')) {
    header("Location: login.php");
    exit();
}

$articuloCodigo = isset($_GET['Codigo']) ? $_GET['Codigo'] : '';

$stmt = $pdo->prepare("SELECT A.*, C.Nombre AS NombreCategoria, C.Escala AS EscalaCategoria 
                        FROM Articulos A
                        LEFT JOIN Categorias C ON A.CategoriaID = C.CategoriaID
                        WHERE A.Codigo = ?");
$stmt->execute([$articuloCodigo]);
$articulo = $stmt->fetch();

if ($_SESSION['rol'] !== 'administrador' && $_SESSION['rol'] !== 'empleado') {
    header("Location: informe_articulos.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $nombreCategoria = $_POST['CategoriaNombre'];
    $escalaCategoria = $_POST['CategoriaEscala'];
    $precio = $_POST['precio'];
    $enOferta = isset($_POST['enOferta']) ? $_POST['enOferta'] : 0;
    $activo = isset($_POST['activo']) ? $_POST['activo'] : 0;
    $anyo = $_POST['anyo'];

    // Validar la nueva imagen
    $nuevaImagen = $articulo['Imagen']; // Por defecto, usa la imagen actual

    if (!empty($_FILES["imagen"]["name"])) {
        $nuevaImagen = "img/" . $_FILES["imagen"]["name"];
        $imagenSize = $_FILES["imagen"]["size"];

        if ($imagenSize > 300000) {
            echo "La imagen no debe superar los 300 KB";
            exit();
        }

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $nuevaImagen);

        // Obtener las dimensiones de la imagen
        $dimensiones = getimagesize($nuevaImagen);
        $ancho = $dimensiones[0];
        $alto = $dimensiones[1];

        if ($ancho > 200 || $alto > 200) {
            echo "Las dimensiones de la imagen no deben superar los 200x200 píxeles";
            exit();
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE Articulos SET Nombre = ?, Descripcion = ?, CategoriaID = ?, Precio = ?, Imagen = ?, enOferta = ?, Activo = ?, Anyo = ?  WHERE Codigo = ?");
        $stmt->execute([$nombre, $descripcion, $articulo['CategoriaID'], $precio, $nuevaImagen, $enOferta, $activo, $anyo, $codigo]);

        header("Location: informe_articulos.php?mensaje=Datos actualizados correctamente");
        exit();
    } catch (PDOException $e) {
        echo "Error al actualizar los datos en la base de datos: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Artículo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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

        .container-articulo {
            text-align: center;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            background-color: #fff; 
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            margin: auto;
            margin-top: 50px;
        }

        .container-articulo h2 {
            color: #000;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            color: #495057;
        }

        input[type="text"],
        textarea,
        input[type="file"],
        input[type="submit"] {
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #000;
            color: #ff0;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        a {
            color: #007bff;
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container-articulo">
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <h2>Editar Artículo</h2>
                <label for="codigo">Código:</label>
                <input type="text" name="codigo" value="<?php echo $articulo['Codigo']; ?>" required class="form-control" readonly>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $articulo['Nombre']; ?>" required class="form-control">

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" required class="form-control"><?php echo $articulo['Descripcion']; ?></textarea>
             
               
               <label for="CategoriaNombre">Categoría:</label>
               <input type="text" name="CategoriaNombre" value="<?php echo isset($articulo['NombreCategoria']) ? $articulo['NombreCategoria'] : ''; ?>" required class="form-control">
               
               <label for="CategoriaEscala">Escala:</label>
               <input type="text" name="CategoriaEscala" value="<?php echo isset($articulo['EscalaCategoria']) ? $articulo['EscalaCategoria'] : ''; ?>" required class="form-control" >


                <label for="precio">Precio:</label>
                <input type="text" name="precio" value="<?php echo $articulo['Precio']; ?>" required class="form-control">

                <label for="imagen">Nueva Imagen (jpg, jpeg, gif, png | Max 300 KB | Max 200x200 px):</label>
                <input type="file" name="imagen" accept="image/jpeg, image/png, image/gif" class="form-control">

                <label for="enOferta">En Oferta:</label>
            <select name="enOferta" class="form-control">
                <option value="1" <?php echo ($articulo['enOferta'] == '1') ? 'selected' : ''; ?>>Sí</option>
                <option value="0" <?php echo ($articulo['enOferta'] == '0') ? 'selected' : ''; ?>>No</option>
            </select>

            <label for="activo">Activo:</label>
            <select name="activo" class="form-control">
                <option value="1" <?php echo ($articulo['Activo'] == '1') ? 'selected' : ''; ?>>Sí</option>
                <option value="0" <?php echo ($articulo['Activo'] == '0') ? 'selected' : ''; ?>>No</option>
            </select>

            <label for="anyo">Año:</label>
            <input type="text" name="anyo" pattern="\d{4}" value="<?php echo $articulo['Anyo']; ?>" required class="form-control">
            <br>



                <input type="submit" value="Guardar Cambios" class="btn btn-success">

                <div class="button-container">
                    <a href="informe_articulos.php">Volver</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
