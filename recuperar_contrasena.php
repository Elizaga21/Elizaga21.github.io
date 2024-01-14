<?php
require 'db_connection.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se proporcionaron tanto DNI como correo electrónico
    if (isset($_POST['DNI'], $_POST['Email'])) {
        $_SESSION['contrasena_actualizada'] = false;

        $DNI = $_POST['DNI'];
        $Email = $_POST['Email'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE dni = ? AND email = ?");
        $stmt->execute([$DNI, $Email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mostrar el formulario para establecer una nueva contraseña
            echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Recuperar y Establecer Contraseña</title>
                <link rel="stylesheet" href="styles.css">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
                <style>
                body {
                    font-family: "Helvetica Now Text", Helvetica, Arial, sans-serif;
                    background-color: #f4f4f4;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                    color: #333;
                }
        
                #container {
                    text-align: center;
                    max-width: 400px;
                    width: 100%;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    margin-top: 50px;
                }
        
                h2 {
                    color: #495057;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
        
                form {
                    margin-top: 20px;
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
                    transition: background-color 0.3s;
                }
        
                input[type="submit"]:hover {
                    background-color: #555;
                }
                </style>
                <script>
                // JavaScript para mostrar mensaje emergente y redirigir
                function mostrarMensaje() {
                    alert("Contraseña actualizada correctamente.");
                    window.location.href = "login.php";
                }
                </script>
            </head>
            <body>
                <h2>Recuperar y Establecer Contraseña</h2>
                <form method="POST" onsubmit="mostrarMensaje()">
                    <input type="hidden" name="id" value="' . $user['id'] . '">
                    <input type="password" name="nueva_contrasena" placeholder="Nueva Contraseña">
                    <input type="submit" name="guardar_contraseña" value="Guardar Nueva Contraseña">
                </form>
            </body>
            </html>';

        } else {
            echo 'La combinación de DNI y correo electrónico no es válida.';
        }
    } elseif (isset($_POST['guardar_contraseña'])) {
        // Procesar el formulario para establecer una nueva contraseña

        $id = $_POST['id'];
        $nueva_contrasena = password_hash($_POST['nueva_contrasena'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
        $stmt->execute([$nueva_contrasena, $id]);

        session_write_close();

        // Redirigir a login.php después de actualizar la contraseña
        header("Location: login.php");
    } else {
        echo 'Por favor, proporciona tanto el DNI como el correo electrónico.';
    }
} else {
    // Mostrar el formulario para solicitar DNI y correo electrónico
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Recuperar Contraseña</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
        <style>
        body {
            font-family: "Helvetica Now Text", Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            text-align: center;
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: #fff; 
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            margin-top: 50px; 
        }

        h2 {
            color: #495057; 
            font-size: 22px; 
        }

        form {
            margin-top: 20px;
        }

        input {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #000;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #333; 
        }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Recuperar Contraseña</h2>
            <form method="POST">
                <input type="text" name="DNI" placeholder="DNI">
                <input type="text" name="Email" placeholder="Correo electrónico">
                <input type="submit" value="Recuperar Contraseña">
            </form>
        </div>
    </body>
    </html>';
}
session_write_close();

?>
