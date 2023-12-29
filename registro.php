<?php
require 'db_connection.php';
session_start();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DNI = $_POST['DNI'];
    $Nombre = $_POST['Nombre'];
    $Direccion = $_POST['Direccion'];
    $Localidad = $_POST['Localidad'];
    $Provincia = $_POST['Provincia'];
    $Telefono = $_POST['Telefono'];
    $Email = $_POST['Email'];
    $Contrasena = password_hash($_POST['Contrasena'], PASSWORD_DEFAULT);
    $Rol = 'Cliente'; 

    if (empty($DNI) || empty($Nombre) || empty($Direccion) || empty($Localidad) || empty($Provincia) || empty($Telefono) || empty($Email) || empty($Contrasena)) {
        $errors[] = "Por favor, complete todos los campos.";
    } else {
        if (!preg_match('/^[0-9]{8}[A-Za-z]$/', $DNI)) {
            $errors[] = "El formato del DNI no es válido.";
        } else {
            // Obtener la letra del DNI
            $letraDNI = strtoupper(substr($DNI, -1));
            $numerosDNI = substr($DNI, 0, -1);

            // Calcular la letra correcta
            $letrasPosibles = 'TRWAGMYFPDXBNJZSQVHLCKE';
            $letraCorrecta = $letrasPosibles[$numerosDNI % 23];

            // Comprobar si la letra es correcta
            if ($letraCorrecta !== $letraDNI) {
                $errors[] = "La letra del DNI no es correcta.";
            }

            // Validar el teléfono
            if (!preg_match('/^[0-9]{9}$/', $Telefono)) {
                $errors[] = "El formato del teléfono no es válido.";
            } else {
                // Validar el correo electrónico
                if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "El formato del correo electrónico no es válido.";
                } else {
                    // Comprobar si el DNI ya existe
                    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE DNI = ?");
                    $stmt->execute([$DNI]);
                    $existingUser = $stmt->fetch();

                    if (!$existingUser) {
                        // Comprobar el tamaño de los campos
                        $maxSize = 255; 
                        if (
                            strlen($Nombre) > $maxSize ||
                            strlen($Direccion) > $maxSize ||
                            strlen($Localidad) > $maxSize ||
                            strlen($Provincia) > $maxSize ||
                            strlen($Telefono) > $maxSize ||
                            strlen($Email) > $maxSize
                        ) {
                            $errors[] = "El tamaño de uno o más campos excede el límite permitido.";
                        } else {
        // Insertar usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO Usuarios (DNI, Nombre, Direccion, Localidad, Provincia, Telefono, Email, Contrasena, Rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$DNI, $Nombre, $Direccion, $Localidad, $Provincia, $Telefono, $Email, $Contrasena, $Rol]);

        $stmt_rol = $pdo->prepare("SELECT Rol FROM Usuarios WHERE DNI = ?");
        $stmt_rol->execute([$DNI]);
        $rol_usuario = $stmt_rol->fetchColumn();

        
        if ($rol_usuario === 'administrador') {
            header("Location: admin.php");
        } else {
            header("Location: login.php");
        }
        exit();
    }
} else {
    $errors[] = "El DNI ya está registrado.";
}
}
}
}
}

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
         body {
            font-family: "Helvetica Now Text", Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            max-width: 300px; 
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 50px;
            padding: 20px; 
            position: relative; 
        }

        .logoRegister {
            position: absolute;
            top: 10px; 
            left: 10px; 
            max-width: 100px; 
        }
        h2 {
            font-size: 24px;
            margin-top: 50px;
            margin-bottom: 30px;
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

        .error {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="/logo/logo.svg" alt="Logo" class="logoRegister"> 

        <h2>Registro de usuario</h2>
        <form method="POST">
            <input type="text" name="DNI" placeholder="DNI">
            <input type="text" name="Nombre" placeholder="Nombre">
            <input type="text" name="Direccion" placeholder="Dirección">
            <input type="text" name="Localidad" placeholder="Localidad">
            <input type="text" name="Provincia" placeholder="Provincia">
            <input type="text" name="Telefono" placeholder="Teléfono">
            <input type="text" name="Email" placeholder="Email">
            <input type="password" name="contrasena" placeholder="Contraseña">
            <input type="submit" value="Registrarse" class="btn btn-success">
        </form>
        <?php if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="error">' . $error . '</p>';
            }
        } ?>
    </div>
</body>
</html>
