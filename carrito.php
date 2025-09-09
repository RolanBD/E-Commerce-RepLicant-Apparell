<?php
session_start();

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto
if (isset($_POST['agregar'])) {
    $codigo = $_POST['codigo'];

    // Si ya existe, aumentar cantidad
    if (isset($_SESSION['carrito'][$codigo])) {
        $_SESSION['carrito'][$codigo]['cantidad']++;
    } else {
        $_SESSION['carrito'][$codigo] = [
            'codigo' => $codigo,
            'nombre' => $_POST['nombre'],
            'precio' => $_POST['precio'],
            'imagen' => $_POST['imagen'],
            'cantidad' => 1
        ];
    }
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $codigo = $_GET['eliminar'];
    unset($_SESSION['carrito'][$codigo]);
}

// Vaciar carrito
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="icon" type="image/x-icon" href="img/ico/favicon.ico">
    <link rel="stylesheet" href="style_carrito.css"/>
</head>
<body>
<header class="header">
      <h1> <i class="fas fa-shopping-cart"></i> Carrito de Compras</h1>
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
      </div>
</header>
<!-- Barra de navegación -->
<nav class="nav">
    <a href="index.php">Inicio</a>
    <a href="productos.php">Productos</a>
</nav>

<div class="contenido">
    <?php if (empty($_SESSION['carrito'])): ?>
        <p>Tu carrito está vacío</p>
    <?php else: ?>
        <?php foreach ($_SESSION['carrito'] as $item): ?>
            <div class="item-carrito">
                <img src="<?php echo htmlspecialchars($item['imagen']); ?>" width="80">
                <div>
                    <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                    <p>Precio: $<?php echo number_format($item['precio'], 0, ',', '.'); ?></p>
                    <p>Cantidad: <?php echo $item['cantidad']; ?></p>
                </div>
                <a href="carrito.php?eliminar=<?php echo $item['codigo']; ?>" class="btn-eliminar">
                    Eliminar
                </a>
            </div>
        <?php endforeach; ?>

        <h2>Total: $<?php echo number_format($total, 0, ',', '.'); ?></h2>
        <div class="acciones-carrito">
            <a href="carrito.php?vaciar=1" class="btn-vaciar">Vaciar carrito</a>
            <a href="pagar.php" class="btn-pagar">Pagar</a>
        </div>
    <?php endif; ?>
</div>
  <!-- Footer -->
  <footer>
      <p>&copy; 2025 RepLicant Apparell | Grupo 3 ADSO</p>
      <p>
        <a>Edinson Balaguera</a> |
        <a>Miguel Jimenez</a> |
        <a>Sebastian Sanchez</a>
      </p>
  </footer>
</body>
</html>
