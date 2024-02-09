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
    $apellidos = $_POST['apellidos'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $pais = $_POST['pais'];
    $codpos = $_POST['codpos'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $activo =  $_POST['activo'];

    if (empty($dni) || empty($nombre) ||  empty($telefono) || empty($direccion) || empty($localidad) || empty($provincia) || empty($pais) || empty($codpos) || empty($email) || empty($contrasena)) {
        $errors[] = "Por favor, complete todos los campos.";
    } else {
        if (!preg_match('/^\d{8}[a-zA-Z]$/', $dni)) {
            $errors[] = "El formato del DNI no es válido.";
        } else {
            // Obtener la letra del DNI
            $letra = strtoupper(substr($dni, -1));
            $numeros = substr($dni, 0, -1);

            // Calcular la letra correcta
            $calculo_letra = "TRWAGMYFPDXBNJZSQVHLCKE";
            $posicion = $numeros % 23;
            $letra_correcta = $calculo_letra[$posicion];

            // Comprobar si la letra es correcta
            if ($letra_correcta !== $letra) {
                $errors[] = "La letra del DNI no es correcta.";
            }

            // Validar el teléfono
            if (!preg_match('/^\d{9}$/', $telefono)) {
                $errors[] = "El formato del teléfono no es válido.";
            } else {
                // Validar el correo electrónico
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "El formato del correo electrónico no es válido.";
                } else {
       
            $stmt_dni = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ?");
            $stmt_dni->execute([$dni]);
            $dni_exists = $stmt_dni->fetchColumn();

            if ($dni_exists) {
                $error_message = "El DNI ya está registrado.";
            } else {

                $stmt_insert = $pdo->prepare("INSERT INTO usuarios (dni, nombre, apellidos, direccion, localidad, provincia,pais,codpos, telefono, email, contrasena, rol, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->execute([$dni, $nombre,$apellidos, $direccion, $localidad, $provincia, $pais, $codpos, $telefono, $email, $contrasena, $rol, $activo]);

                header("Location: informe_usuarios.php");
                exit();
            }
        }}}
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Nuevo Usuario</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
   body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .container {
            max-width: 800px; 
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto; 
        }

        h2 {
            color: #495057;
        }

        form {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(2, 1fr); 
            grid-gap: 15px; 
        }

        input,
        select {
            margin-bottom: 15px;
            width: 100%; 
            box-sizing: border-box; 
        }

        input[type="submit"],
        .back-button {
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

        .back-button {
            background-color: #333;
            color: #ff0;
            display: block;
            text-align: center;
            text-decoration: none;
        }

        .error {
            color: #dc3545;
            margin-top: 10px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #000;
            color: #ffffff;
        }
        .nombre-tienda h1 {
            margin: 0;
            text-align: center;
            font-size: 28px;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        #menu-navegacion ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        #menu-navegacion ul li {
            margin-right: 20px;
        }

        #menu-navegacion ul li:last-child {
            margin-right: 0;
        }

        #menu-navegacion ul li a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.3s; 
}

#menu-navegacion ul li a:hover {
    color: #ffd700; 
}

    </style>
    </style>
</head>
<body>
<?php include 'header.php'; ?>
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
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" class="form-control" placeholder="Apellidos" required>
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
                <label for="pais">Pais:</label>
                <input type="text" name="pais" class="form-control" placeholder="Pais" required>
            </div>

            <div class="form-group">
                <label for="codpos">Código Postal:</label>
                <input type="text" name="codpos" class="form-control" placeholder="codigo postal" required>
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
                    <option value="cliente">Cliente</option>
                    <option value="administrador">Administrador</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="activo">Activo:</label>
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
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
