<?php
session_start();

require 'db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] !== 'cliente' && $_SESSION['rol'] !== 'administrador' && $_SESSION['rol'] !== 'empleado')) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

    if ($_SESSION['rol'] === 'cliente' && $usuarioId != $_SESSION['user_id']) {
        header("Location: cliente.php");
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$usuarioId]);

    if ($_SESSION['rol'] === 'administrador' && $usuarioId != $_SESSION['user_id']) {
        header("Location: informe_usuarios.php?mensaje=Usuario eliminado correctamente");
    } else {
        session_destroy();
        header("Location: login.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Cuenta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
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
            text-align: center;
        }

        h2 {
            color: #495057;
        }

        p {
            color: #495057; 
        }

        form {
            margin-top: 20px;
        }

        input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #c82333;
        }

        a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #28a745; 
            transition: color 0.3s;
        }

        a:hover {
            color: #218838; 
        }
    </style>
</head>
<body>
    <h2>Eliminar Cuenta</h2>
    <p>¿Estás seguro de que deseas eliminar este usuario? Esta acción es irreversible.</p>
    <form method="POST">
        <input type="submit" value="Confirmar Eliminación">
    </form>
    <a href="<?php echo ($_SESSION['rol'] === 'cliente') ? 'cliente.php' : 'informe_usuarios.php'; ?>">Volver</a>
</body>
</html>
