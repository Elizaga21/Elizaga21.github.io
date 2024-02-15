<?php
require 'db_connection.php';
include 'header.php';
session_start();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DNI = $_POST['DNI'];
    $Nombre = $_POST['Nombre'];
    $Telefono = $_POST['Telefono'];
    $Direccion = $_POST['Direccion'];
    $Localidad = $_POST['Localidad'];
    $Provincia = $_POST['Provincia'];
    $Pais = $_POST['Pais'];
    $Codpos = $_POST['Codpos'];
    $Email = $_POST['Email'];
    $Contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $Rol = 'Cliente'; 

    if (empty($DNI) || empty($Nombre) ||  empty($Telefono)|| empty($Direccion) || empty($Localidad) || empty($Provincia) || empty($Pais) || empty($Codpos) || empty($Email) || empty($Contrasena)) {
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
                    // Comprobar si el correo electrónico ya existe
                    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
                    $stmt->execute([$Email]);
                    $existingEmail = $stmt->fetch();
                }

                    if ($existingEmail) {
                        $errors[] = "El correo electrónico ya está registrado.";
                    } else {
                    // Comprobar si el DNI ya existe
                    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE dni = ?");
                    $stmt->execute([$DNI]);
                    $existingUser = $stmt->fetch();

                    if (!$existingUser) {
                        // Comprobar el tamaño de los campos
                        $maxSize = 255; 
                        if (
                            strlen($Nombre) > $maxSize ||
                            strlen($Telefono) > $maxSize ||
                            strlen($Email) > $maxSize
                        ) {
                            $errors[] = "El tamaño de uno o más campos excede el límite permitido.";
                        } else {
        // Insertar usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (dni, nombre, telefono, direccion, localidad, provincia, pais, codpos, email, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?)");
        $stmt->execute([$DNI, $Nombre, $Telefono, $Direccion, $Localidad, $Provincia, $Pais, $Codpos, $Email, $Contrasena, $Rol]);
        
        header("Location: login.php");
        exit();
    }
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
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .container {
            max-width: 300px; 
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto; 
        }

        h2 {
            font-size: 24px;
            margin-top: 50px;
            margin-bottom: 30px;
        }

        form {
            margin-top: 20px;
         
        }

        input,
        select {
            margin-bottom: 15px;
            width: 100%; 
            box-sizing: border-box; 
        }

        input[type="submit"]{
            width: 100%; 
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"] {
            background-color: #000;
            color: #ff0;
        }

        input[type="submit"]:hover {
            background-color: #333;
        }


        .error {
            color: #dc3545;
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="container">

        <h2>Registro de usuario</h2>
        <form method="POST">
            <input type="text" name="DNI" placeholder="DNI">
            <input type="text" name="Nombre" placeholder="Nombre">
            <input type="text" name="Telefono" placeholder="Teléfono">
            <input type="text" name="Direccion" placeholder="Dirección">
            <input type="text" name="Localidad" placeholder="Localidad">
            <input type="text" name="Provincia" placeholder="Provincia">
            <input type="text" name="Pais" placeholder="País">
            <input type="text" name="Codpos" placeholder="Código Postal">
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
    <?php include 'footer.php'; ?>
</body>
</html>
