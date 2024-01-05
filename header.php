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

            echo '<li><a href="perfil.php"><strong>Mi Perfil</strong></a></li>';
            echo '<li><a href="manual.php"><strong>Manual Usuario</strong></a></li>';
            echo '<li><a href="logout.php"><strong>Cerrar Sesión</strong></a></li>';

            switch ($rolUsuario) {
                case 'administrador':
                    echo '<li><a href="informes.php"><strong>Informes</strong></a></li>';
                    echo '<li><a href="estadisticas.php"><strong>Estadísticas</strong></a></li>';
                    echo '<li><a href="mantenimiento_categorias.php"><strong>Categorías</strong></a></li>';
                    echo '<li><a href="mantenimiento_articulos.php"><strong>Productos</strong></a></li>';
                    echo '<li><a href="mantenimiento_pedidos.php"><strong>Pedidos</strong></a></li>';
                    echo '<li><a href="ventas.php"><strong>Ventas</strong></a></li>';
                    break;
                case 'empleado':
                  echo '<li><a href="mantenimiento_articulos.php"><strong>Artículos</strong></a></li>';
                  echo '<li><a href="mantenimiento_categorias.php"><strong>Categorías</strong></a></li>';
                  echo '<li><a href="mantenimiento_pedidos.php"><strong>Pedidos</strong></a></li>';
                  echo '<li><a href="enviar_mailings.php"><strong>Mailings</strong></a></li>';
                    break;
                case 'cliente':
                  echo '<li><a href="mis_pedidos.php"><strong>Mis Pedidos</strong></a></li>';
                  echo '<li><a href="modificar_perfil.php"><strong>Modificar Perfil</strong></a></li>';
                    break;
                // Puedes agregar más casos según sea necesario

                default:
                  
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

