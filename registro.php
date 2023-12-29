<?php
require 'db_connection.php';
session_start();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = 'Cliente'; 

    if (empty($dni) || empty($nombre) || empty($direccion) || empty($localidad) || empty($provincia) || empty($telefono) || empty($email) || empty($contrasena)) {
        $errors[] = "Por favor, complete todos los campos.";
    } else {
        if (!preg_match('/^[0-9]{8}[A-Za-z]$/', $dni)) {
            $errors[] = "El formato del DNI no es válido.";
        } else {
            // Obtener la letra del DNI
            $letraDNI = strtoupper(substr($dni, -1));
            $numerosDNI = substr($dni, 0, -1);

            // Calcular la letra correcta
            $letrasPosibles = 'TRWAGMYFPDXBNJZSQVHLCKE';
            $letraCorrecta = $letrasPosibles[$numerosDNI % 23];

            // Comprobar si la letra es correcta
            if ($letraCorrecta !== $letraDNI) {
                $errors[] = "La letra del DNI no es correcta.";
            }

            // Validar el teléfono
            if (!preg_match('/^[0-9]{9}$/', $telefono)) {
                $errors[] = "El formato del teléfono no es válido.";
            } else {
                // Validar el correo electrónico
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "El formato del correo electrónico no es válido.";
                } else {
                    // Comprobar si el DNI ya existe
                    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE dni = ?");
                    $stmt->execute([$dni]);
                    $existingUser = $stmt->fetch();

                    if (!$existingUser) {
                        // Comprobar el tamaño de los campos
                        $maxSize = 255; 
                        if (
                            strlen($nombre) > $maxSize ||
                            strlen($direccion) > $maxSize ||
                            strlen($localidad) > $maxSize ||
                            strlen($provincia) > $maxSize ||
                            strlen($telefono) > $maxSize ||
                            strlen($email) > $maxSize
                        ) {
                            $errors[] = "El tamaño de uno o más campos excede el límite permitido.";
                        } else {
        // Insertar usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO Usuarios (DNI, Nombre, Direccion, Localidad, Provincia, Telefono, Email, Contrasena, Rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$dni, $nombre, $direccion, $localidad, $provincia, $telefono, $email, $contrasena, $rol]);

        $stmt_rol = $pdo->prepare("SELECT Rol FROM Usuarios WHERE DNI = ?");
        $stmt_rol->execute([$dni]);
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

//if (!empty($errors)) {
//foreach ($errors as $error) {
//echo '<p class="error">' . $error . '</p>';
//}
//}
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
            <input type="text" name="dni" placeholder="DNI">
            <input type="text" name="nombre" placeholder="Nombre">
            <input type="text" name="direccion" placeholder="Dirección">
            <input type="text" name="localidad" placeholder="Localidad">
            <input type="text" name="provincia" placeholder="Provincia">
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="email" placeholder="Email">
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
