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
   
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
        <h2>Panel de Administrador</h2>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <?php
            $stmt_user = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
            $stmt_user->execute([$_SESSION['user_id']]);
            $admin = $stmt_user->fetch();
            ?>
            <p class="textoBienvenida">Bienvenido, <?php echo $admin['nombre']; ?></p>
        <?php endif; ?>

        <h3>Usuarios registrados:</h3>
        <table class="user-table">
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?php echo $usuario['dni']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['direccion']; ?></td>
                    <td><?php echo $usuario['localidad']; ?></td>
                    <td><?php echo $usuario['provincia']; ?></td>
                    <td><?php echo $usuario['telefono']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td class="actions">
                        <?php if ($_SESSION['user_id'] !== $usuario['id']) : ?>
                            <a href="editar_datos.php?id=<?php echo $usuario['id']; ?>" class="action-link">
                                <img src="icons8-edit-30.png" alt="Editar">
                            </a>
                            <a href="eliminar_cuenta.php?id=<?php echo $usuario['id']; ?>" class="action-link">
                                <img src="icons8-x-48.png" alt="Eliminar">
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

        <div class="admin-links">
            <a href="informe_usuarios.php" class="admin-link">Informe de Usuarios</a>
            <a href="informe_articulos.php" class="admin-link">Informe de Artículos</a>
            <a href="estadisticas_pedidos.php" class="admin-link">Estadísticas de Pedidos</a>
            <a href="productos_mas_vendidos.php" class="admin-link">Productos más Vendidos</a>
            <a href="ventas_del_mes.php" class="admin-link">Ventas del Mes</a>
            <a href="cerrar_sesion.php" class="admin-link">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Agrega más secciones según tus necesidades -->

    <?php include 'footer.php'; ?>
</body>
</html>



    
  