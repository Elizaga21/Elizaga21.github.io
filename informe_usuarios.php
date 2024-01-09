<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

// Configuración de la ordenación
$ordenarPor = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : 'nombre';
$ordenAscendente = isset($_GET['orden_ascendente']) ? $_GET['orden_ascendente'] : true;

// Obtener la lista de usuarios ordenada
$orden = $ordenAscendente ? 'ASC' : 'DESC';
$stmt = $pdo->prepare("SELECT * FROM usuarios ORDER BY $ordenarPor $orden");
$stmt->execute();
$usuarios = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

   <style>
 
 body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
}

.container {
    text-align: center;
    max-width: 100%; /* Change to 100% to occupy the full width */
    margin: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.user-table {
    width: 100%; /* Change to 100% to occupy the full width of the container */
    border-collapse: collapse;
    margin: 20px auto;
    text-align: center;
    display: table;
}




h2, h3 {
    color: #495057;
}

.welcome-text {
    color: #28a745;
}





.user-table th, .user-table td {
    border: 1px solid #dee2e6;
    padding: 8px;
    text-align: left;
}

.user-table th {
    background-color: #000; /* Cambiado a negro */
    color: #ff0; /* Cambiado a amarillo */
}

.actions {
    display: flex;
    justify-content: space-around;
}

.action-link {
    text-decoration: none;
    color: #495057;
}

.action-link img {
    width: 20px;
    height: 20px;
}

.action-link:hover {
    color: #ff0; /* Cambiado a amarillo */
}

.order-form {
    margin-top: 20px;
}

.order-button {
    background-color: #000; /* Cambiado a negro */
    color: #fff; /* Cambiado a blanco */
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.order-button:hover {
    background-color: #333; /* Puedes ajustar este color según tus preferencias */
}

.admin-links {
    margin-top: 20px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column; /* Añadido para cambiar la dirección del eje principal a columna */
    align-items: center; /* Añadido para centrar en el eje transversal */
}

.admin-link {
    text-decoration: none;
    color: #495057;
    padding: 10px;
    margin: 5px;
    border: 1px solid #000;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
}

.admin-link:hover {
    background-color: #000;
    color: #fff;
}

.admin-links::before {
    content: "Mantenimiento";
    font-weight: bold;
    margin-bottom: 10px;
    color: #000;
}
.back-button-container {
            text-align: center;
            margin-top: 20px; /* Ajusta según tus necesidades */
        }

        .back-button {
            text-decoration: none;
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-button:hover {
            background-color: #333; /* Puedes ajustar este color según tus preferencias */
        }

        .edit-icon {
    font-size: 30px; 
    color: #000; 
    text-decoration: none; 
}

.delete-icon{
    font-size: 30px; 
    color: #000; 
    text-decoration: none; 
}
.edit-icon:hover {
    opacity: 0.8;
}
.delete-icon:hover {
    opacity: 0.8;
}

.admin-links_users {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end; /* Alinea los elementos al final del contenedor */
}

.admin-link_user {
    text-decoration: none;
    color: #ff0; /* Amarillo */
    padding: 10px;
    margin: 5px;
    background-color: #000; /* Negro */
    border: 1px solid #000;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
}

.admin-link_user:hover {
    background-color: #333; /* Puedes ajustar este color según tus preferencias */
    color: #fff; /* Blanco */
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">

    <?php if (isset($_SESSION['user_id'])) : ?>
            <?php
            $stmt_user = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
            $stmt_user->execute([$_SESSION['user_id']]);
            $admin = $stmt_user->fetch();
            ?>
        <?php endif; ?>

        <h2>Informe de Usuarios</h2>
        <h3>Usuarios registrados:</h3>
        
        <div class="admin-links_users">
    <a href="registro_admin.php" class="admin-link_user">Crear nuevo usuario</a>
    <a href="buscarCliente.php" class="admin-link_user">Buscar usuarios</a>
</div>
        <table class="user-table">
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Dirección</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>País</th>
                <th>Código Postal</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?php echo $usuario['dni']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['apellidos']; ?></td>
                    <td><?php echo $usuario['direccion']; ?></td>
                    <td><?php echo $usuario['localidad']; ?></td>
                    <td><?php echo $usuario['provincia']; ?></td>
                    <td><?php echo $usuario['pais']; ?></td>
                    <td><?php echo $usuario['codpos']; ?></td>
                    <td><?php echo $usuario['telefono']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td class="actions">
                        <?php if ($_SESSION['user_id'] !== $usuario['id']) : ?>
                            <a href="editar_datos.php?id=<?php echo $usuario['id']; ?>" class="action-link">
                            <span class="material-icons">edit</span>
                            </a>
                            <a href="eliminar_cuenta.php?id=<?php echo $usuario['id']; ?>" class="action-link">
                            <span class="material-icons">delete</span> 
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <form class="order-form" method="get">
            <input type="hidden" name="ordenar_por" value="<?php echo $ordenarPor; ?>">
            <input type="hidden" name="orden_ascendente" value="<?php echo !$ordenAscendente; ?>">
            <button type="submit" class="order-button">Cambiar Orden</button>
        </form>

    </div>
    <div class="back-button-container">
            <a href="<?php echo ($_SESSION['rol'] === 'cliente') ? 'cliente.php' : 'admin.php'; ?>" class="back-button">Volver</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
