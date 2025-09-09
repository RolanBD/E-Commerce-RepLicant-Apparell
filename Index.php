<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Librería de iconos Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RepLicant Apparell</title>
    <link rel="icon" type="image/x-icon" href="img/ico/favicon.ico">
    <link rel="stylesheet" href="style_index.css"/>
  </head>
  <body>
    <header class="header">
      <h1>RepLicant Apparell</h1>
      <div class="acciones">
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <a><?php echo $_SESSION['usuario_nombre']; ?></a>
          <a href="perfil.php" title=<?php echo $_SESSION['usuario_nombre']; ?>>
            <i class="fas fa-user"></i>
          </a>
          <a href="logout_cliente.php" title="Cerrar sesión">
            <i class="fas fa-right-from-bracket"></i>
          </a>
        <?php else: ?>
          <a href="login.html" title="Iniciar Sesión">
            <i class="fas fa-user"></i>
          </a>
        <?php endif; ?>
        <a href="carrito.php" title="Carrito de Compras">
          <i class="fas fa-shopping-cart"></i>
        </a>
      </div>
    </header>

    <!-- Barra de navegación -->
    <nav class="nav">
      <a href="index.php">Inicio</a>
      <a href="productos.php">Productos</a>
    </nav>

    <!-- Contenido principal -->
    <div class="contenido">
      <p>¡Explora nuestros productos y encuentra lo que buscas!</p>
    </div>

    <!-- Pie de página -->
    <footer class="footer">
      <?php if (isset($_SESSION['admin'])): ?>
        <!-- Si el admin YA inició sesión -->
        <a href="admin/panel.php">
          <i class="fas fa-crown"></i>
        </a>
      <?php else: ?>
        <!-- Si el admin NO ha iniciado sesión -->
        <a href="admin/login.php">
          <i class="fas fa-crown"></i>
        </a>
      <?php endif; ?>
      <p>&copy; 2025 RepLicant Apparell | Grupo 3 ADSO</p>
      <p>
        <a>Edinson Balaguera</a> |
        <a>Miguel Jimenez</a> |
        <a>Sebastian Sanchez</a>
      </p>
    </footer>
  </body>
</html>
