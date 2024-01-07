<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $activo =  $_POST['activo'];

    if (!preg_match('/^\d{8}[a-zA-Z]$/', $dni)) {
        $error_message = "Formato de DNI incorrecto.";
    } else {
        $letra = strtoupper(substr($dni, -1));
        $numeros = substr($dni, 0, -1);
        $calculo_letra = "TRWAGMYFPDXBNJZSQVHLCKE";
        $posicion = $numeros % 23;

        if ($calculo_letra[$posicion] !== $letra) {
            $error_message = "Letra de DNI incorrecta.";
        } else {
       
            $stmt_dni = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ?");
            $stmt_dni->execute([$dni]);
            $dni_exists = $stmt_dni->fetchColumn();

            if ($dni_exists) {
                $error_message = "El DNI ya está registrado.";
            } else {
          
                $max_field_lengths = array(
                    'dni' => 9,
                    'nombre' => 255,
                    'direccion' => 255,
                    'localidad' => 255,
                    'provincia' => 255,
                    'telefono' => 9,
                    'email' => 255,
                    'contrasena' => 255,
                    'rol' => 20,
                );

                foreach ($_POST as $field => $value) {
                    if (strlen($value) > $max_field_lengths[$field]) {
                        $error_message = "El campo $field excede la longitud máxima permitida.";
                        break;
                    }
                }

                if (!preg_match('/^\d{9}$/', $telefono)) {
                    $error_message = "El teléfono debe contener 9 dígitos.";
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error_message = "Formato de correo electrónico incorrecto.";
                }

                $stmt_insert = $pdo->prepare("INSERT INTO usuarios (dni, nombre, direccion, localidad, provincia, telefono, email, contrasena, rol, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->execute([$dni, $nombre, $direccion, $localidad, $provincia, $telefono, $email, $contrasena, $rol, $activo]);

                header("Location: admin.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Nuevo Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
      body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
            margin-top: 20px; 
        }

        h2 {
            color: #495057;
        }

        form {
            margin-top: 20px;
        }

        input,
        select {
            margin-bottom: 15px;
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


        .error {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Nuevo Usuario</h2>
        <?php if (isset($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="registro_admin.php">
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" name="dni" class="form-control" placeholder="DNI" title="Formato válido: 8 dígitos seguidos de una letra"  required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" class="form-control" placeholder="Dirección" required>
            </div>

            <div class="form-group">
                <label for="localidad">Localidad:</label>
                <input type="text" name="localidad" class="form-control" placeholder="Localidad" required>
            </div>

            <div class="form-group">
                <label for="provincia">Provincia:</label>
                <input type="text" name="provincia" class="form-control" placeholder="Provincia" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" name="telefono" class="form-control" title="El teléfono debe contener 9 dígitos" placeholder="Teléfono" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select name="rol" class="form-control">
                    <option value="usuario">Cliente</option>
                    <option value="administrador">Administrador</option>
                    <option value="editor">Empleado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="activo">Rol:</label>
                <select name="activo" class="form-control">
                    <option value="1">Si</option>
                    <option value="2">No</option>
                </select>
            </div>

            <input type="submit" value="Crear Usuario" class="btn btn-success">
        </form>
        <?php if (isset($error_message)) : ?>
         <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <a href="admin.php">Volver al Panel de Administrador</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
