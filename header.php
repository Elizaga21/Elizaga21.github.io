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
            $rolUsuario = $_SESSION['rol'];

            switch ($rolUsuario) {
                case 'administrador':
                    echo '<li><a href="informes.php"><strong>Informes</strong></a></li>';
                    echo '<li><a href="estadisticas.php"><strong>Estadísticas</strong></a></li>';
                    echo '<li><a href="productos.php"><strong>Productos</strong></a></li>';
                    echo '<li><a href="ventas.php"><strong>Ventas</strong></a></li>';
                    break;
                case 'empleado':
                    // Opciones específicas para empleados
                    break;
                case 'cliente':
                    // Opciones específicas para clientes
                    break;
                // Puedes agregar más casos según sea necesario

                default:
                    // Opciones por defecto o manejo de error
                    break;
            }
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

