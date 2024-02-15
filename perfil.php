<?php
include 'db_connection.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['cliente', 'empleado', 'administrador'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = array();

$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['nombre'];
    $new_apellidos = $_POST['apellidos'];
    $new_dni = $_POST['dni'];
    $new_direccion = $_POST['direccion'];
    $new_localidad = $_POST['localidad'];
    $new_provincia = $_POST['provincia'];
    $new_pais = $_POST['pais'];
    $new_codpos = $_POST['codpos'];
    $new_telefono = $_POST['telefono'];
    $new_email = $_POST['email'];
    $new_contrasena = $user['contrasena']; 

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El formato del correo electrónico no es válido.";
    }

    if (!preg_match('/^[0-9]{9}$/', $new_telefono)) {
        $errors[] = "El formato del número de teléfono no es válido.";
    }

    if (empty($errors)) {
        $update_stmt = $pdo->prepare("UPDATE usuarios SET 
            nombre = ?, apellidos = ?, dni = ?, direccion = ?, localidad = ?, provincia = ?, pais = ?, codpos = ?, telefono = ?, email = ?, contrasena = ? 
            WHERE id = ?");
        $update_stmt->execute([$new_name, $new_apellidos, $new_dni, $new_direccion, $new_localidad, $new_provincia, $new_pais, $new_codpos, $new_telefono, $new_email, $new_contrasena, $user_id]);

        $success_message = "¡Datos actualizados correctamente!";

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
                .form-column {
            display: inline-block;
            vertical-align: top; 
            margin-right: 20px; 
        }
        
        form {
            text-align: center; 
        }
        body {
            background-color: #f8f9fa;
        }

        #content {
    display: flex;
    flex-direction: column;
    align-items: center;
}
        #container {
            max-width: 800px;
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; 
        }

        #container h2 {
            color: #495057;
        }

        input[type="submit"] {
            background-color: #000;
            color: #ffc107;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .form-column {
            display: inline-block;
            vertical-align: top;
            margin-right: 20px;
        }

        button {
            background-color: #000;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px; 
        }

        button:hover {
            background-color: #333;
        }
        .password-input-container {
    position: relative;
}

.eye {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}
    </style>
<body>

<div id="container">
    <div id="content">
        <h2>Mi Perfil</h2>
        <?php if (!empty($success_message)): ?>
            <div style="color: green; margin-bottom: 10px;">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="color: red; margin-bottom: 10px;">
                <strong>Error(es):</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="perfil.php">
        <div class="form-column">

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>

    <label for="apellidos">Apellidos:</label>
    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($user['apellidos']) ?>" required>

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($user['dni']) ?>" required>

    <label for="direccion">Dirección:</label>
    <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($user['direccion']) ?>" required>

    <label for="localidad">Localidad:</label>
    <input type="text" id="localidad" name="localidad" value="<?= htmlspecialchars($user['localidad']) ?>" required>
    </div>
    <div class="form-column">

    <label for="provincia">Provincia:</label>
    <input type="text" id="provincia" name="provincia" value="<?= htmlspecialchars($user['provincia']) ?>" required>

    <label for="pais">País:</label>
    <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($user['pais']) ?>" required>

    <label for="codpos">Código Postal:</label>
    <input type="text" id="codpos" name="codpos" value="<?= htmlspecialchars($user['codpos']) ?>" required>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="contrasena">Contraseña:</label>
<div class="password-input-container">
    <input type="password" id="contrasena" name="contrasena" placeholder="********" required>
    <span toggle="#contrasena" class="eye field-icon toggle-password">
        <i class="fa fa-eye"></i>
    </span>
</div>
</div>

    <input type="submit" value="Guardar Cambios">
</form>

    </div>

</div>

<?php include 'footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var passwordInput = document.getElementById('contrasena');
    var togglePassword = document.querySelector('.toggle-password');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            var value = passwordInput.value;
            passwordInput.value = '';
            passwordInput.value = value;

            var eyeIcon = togglePassword.querySelector('i');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    }
});

</script>
</body>
</html>
