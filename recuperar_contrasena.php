<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se proporcionaron tanto DNI como correo electrónico
    if (isset($_POST['dni'], $_POST['email'])) {
        $dni = $_POST['dni'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE DNI = ? AND Email = ?");
        $stmt->execute([$dni, $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mostrar el formulario para establecer una nueva contraseña
            echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Recuperar y Establecer Contraseña</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
                <style>
                    body {
                        background-color: #f8f9fa; 
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: flex-start;
                        height: 100vh;
                        margin: 0;
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
                    }
            
                    form {
                        margin-top: 20px;
                    }
            
                    input {
                        margin-bottom: 10px;
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ddd; 
                        border-radius: 4px;
                    }
            
                    input[type="submit"] {
                        background-color: #28a745; 
                        color: #fff;
                        padding: 10px 15px;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                        transition: background-color 0.3s;
                    }
            
                    input[type="submit"]:hover {
                        background-color: #218838; 
                    }
                </style>
            </head>
            <body>
                <h2>Recuperar y Establecer Contraseña</h2>
                <form method="POST">
                    <input type="hidden" name="id" value="' . $user['UsuarioID'] . '">
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

        $stmt = $pdo->prepare("UPDATE Usuarios SET Contrasena = ? WHERE UsuarioID = ?");
        $stmt->execute([$nueva_contrasena, $id]);

        echo 'Contraseña actualizada correctamente. <a href="login.php">Inicia sesión</a>.';
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
            font-size: 22px; /* Ajusta el tamaño del título */
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
            background-color: #000; /* Cambia el color del botón a negro */
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #333; /* Cambia el color del botón al pasar el mouse */
        }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Recuperar Contraseña</h2>
            <form method="POST">
                <input type="text" name="dni" placeholder="DNI">
                <input type="text" name="email" placeholder="Correo electrónico">
                <input type="submit" value="Recuperar Contraseña">
            </form>
        </div>
    </body>
    </html>';
}
?>
