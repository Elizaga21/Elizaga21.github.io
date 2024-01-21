<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require 'db_connection.php'; 

function obtenerNombreDeUsuario() {


  if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];

      $stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
      $stmt->execute([$user_id]);
      $result = $stmt->fetch();

      if ($result) {
          return $result['nombre'];
      }
  }

  // Si no se puede obtener el nombre de la base de datos, puedes devolver un valor por defecto
  return "UsuarioEjemplo";
}

// Si $_SESSION['nombre'] no está definido, intenta obtenerlo
if (!isset($_SESSION['nombre'])) {
  $_SESSION['nombre'] = obtenerNombreDeUsuario();
}
?>

<header>
  <div class="logo">
    <a href="index.php">
      <img src="/logo/logo.svg" alt="Logo de Miniaturas y Colecciones">
    </a>
  </div>
  
  <div class="nombre-tienda">
    <h1>Miniaturas y Colecciones</h1>
  </div>

  <div class="header-content">
    <nav id="menu-navegacion">
      <ul>
        <?php
      if (isset($_SESSION['user_id'])) {

        if (!empty($_SESSION['nombre'])) {
            echo '<li><strong>Hola, ' . $_SESSION['nombre'] . '</strong></li>';
        } else {
            echo '<li><strong>Hola, [nombre]</strong></li>';
        }
    
        $rolUsuario = $_SESSION['rol'];
    
        switch ($rolUsuario) {
            case 'administrador':
                echo '<li><a href="admin.php"><strong>Mantenimiento</strong></a></li>';            
                echo '<li><a href="estadisticas_pedidos.php"><strong>Pedidos</strong></a></li>';
                echo '<li><a href="ventas.php"><strong>Ventas</strong></a></li>';
                echo '<li><a href="perfil.php"><strong>Mi Perfil</strong></a></li>';
                break;
            case 'empleado':
              echo '<li><a href="empleado.php"><strong>Mantenimiento</strong></a></li>';            
                echo '<li><a href="enviar_mailings.php"><strong>Mailings</strong></a></li>';
                echo '<li><a href="perfil.php"><strong>Mi Perfil</strong></a></li>';
                break;
            case 'cliente':
                echo '<li><a href="mis_pedidos.php"><strong>Mis Pedidos</strong></a></li>';
                echo '<li><a href="cliente.php"><strong>Mi Perfil</strong></a></li>';
                echo '<li><a href="favoritos.php"><strong>Mis Favoritos</strong></a></li>';

                break;
    
            // Puedes agregar más casos según sea necesario
    
            default:
                break;
        }
    
        echo '<li><a href="cerrar_sesion.php"><strong>Cerrar Sesión</strong></a></li>';
    } else {
        // Menú predeterminado para usuarios no autenticados
        echo '<li><a href="login.php"><strong>Iniciar Sesión</strong></a></li>';
        echo '<li><a href="quienes_somos.php"><strong>Quiénes Somos</strong></a></li>';
        echo '<li><a href="contacto.php"><strong>Contacto</strong></a></li>';
    }
    
        ?>
      </ul>
    </nav>
  </div>
</header>

