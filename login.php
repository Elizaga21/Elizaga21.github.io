<?php
require 'db_connection.php';

session_start();

// Si el usuario ya está conectado, redirige a la página de inicio correspondiente
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['rol']) {
        case 'Administrador':
            header("Location: admin.php");
            break;
        case 'Empleado':
            header("Location: empleado.php");
            break;
        case 'Cliente':
            header("Location: cliente.php");
            break;
        default:
            header("Location: index.php");
            break;
    }
    exit();
}

// Procesar el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Contrasena = $_POST['Contrasena'];
    $Email = $_POST['Email'];

    $stmt = $pdo->prepare("SELECT UsuarioID, Email, Contrasena, Rol FROM Usuarios WHERE Email = ?");
    $stmt->execute([$Email]);
    $user = $stmt->fetch();

    if ($user && password_verify($Contrasena, $user['Contrasena'])) {
        // Iniciar sesión y redirigir según el rol
        $_SESSION['user_id'] = $user['UsuarioID'];
        $_SESSION['rol'] = $user['Rol'];

        switch ($user['Rol']) {
            case 'Administrador':
                header("Location: admin.php");
                break;
            case 'Empleado':
                header("Location: empleado.php");
                break;
            case 'Cliente':
                header("Location: cliente.php");
                break;
            default:
                header("Location: index.php");
                break;
        }

        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}

function iniciarSesion($usuario) {
    $_SESSION['user_id'] = $usuario['UsuarioID'];
    $_SESSION['rol'] = $usuario['Rol'];
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos para el formulario de login */


#login-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.login-container {
    max-width: 300px;
    width: 100%;
    margin: 0 auto;
}

.logoLogin {
    text-align: center;
    margin-bottom: 20px;
}

.logoLogin img {
    max-width: 100%; /* Adjust the max-width as needed */
    height: auto;
    transition: transform 0.3s ease;
}

.form-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #333;
    color: white;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #555;
}

.links {
    margin-top: 15px;
    color: #888;
    text-align: center;
}

.links a {
    color: #555;
    text-decoration: none;
    display: block;
    text-align: center;
    margin-bottom: 10px;
}

.links a:hover {
    text-decoration: underline;
}

.logoLogin:hover img {
    transform: scale(1.1);
}

#footer-container {
    background-color: black;
    color: white;
    padding: 10px;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
}

    </style>
</head>
<body>
<div id="header-container">
    <?php include 'header.php'; ?>
</div>

    <div class="login-container">
        <div class="logoLogin">
            <img src="/logo/logo.svg" alt="Logo de Miniaturas y Colecciones">
        </div>
        <div class="form-container">
            <h2>Iniciar sesión</h2>
            <?php if (isset($error)) { echo '<p style="color: #e74c3c;">' . $error . '</p>'; } ?>
            <form method="POST">
                <input type="text" name="Email" placeholder="Email">
                <input type="password" name="Contrasena" placeholder="Contraseña">
                <input type="submit" value="Iniciar sesión">
            </form>
            <div class="links">
                <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
                <p><a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a></p>
            </div>
        </div>
    </div>

    <div id="footer-container">
    <?php include 'footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>