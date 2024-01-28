<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica el rol de administrador o editor
if ($_SESSION['rol'] !== 'administrador' && $_SESSION['rol'] !== 'empleado') {
    header("Location: informe_articulos.php");
    exit();
}

require 'db_connection.php';

// Verifica si se proporciona un ID de artículo
if (!isset($_GET['Codigo']) || empty($_GET['Codigo'])) {
    header("Location: informe_articulos.php");
    exit();
}

$articuloCodigo = $_GET['Codigo'];

if ($_SESSION['rol'] === 'empleado' || $_SESSION['rol'] === 'administrador') {

    $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE Codigo = ?");
    $stmt->execute([$articuloCodigo]);
    $articulo = $stmt->fetch();

    if (!$articulo) {
        header("Location: informe_articulos.php");
        exit();
    }
}

// Proceso de eliminación (baja lógica)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("UPDATE Articulos SET Activo = false WHERE Codigo = ?");
    $stmt->execute([$articuloCodigo]);
    header("Location: eliminar_articulo.php?eliminado=true");
    exit();
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Artículo</title>
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

        

        .container-delete {
    max-width: 400px;
    width: 100%;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: auto;
    margin-top: 50px;
}

.container-delete h2{
    text-align: center;

}

        p {
            text-align: center;
            color: #495057;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        form input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #c82333;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container-delete">
        <h2>Eliminar Artículo</h2>

        <p>¿Estás seguro de que deseas eliminar este artículo de los activos?</p>

        <!-- Formulario de confirmación -->
        <form method="POST">
            <input type="submit" value="Sí, eliminar">
        </form>

        <br>
        <a href="informe_articulos.php">Cancelar</a>
    </div>

    <?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
