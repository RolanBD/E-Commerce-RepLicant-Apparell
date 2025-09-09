<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['msg'])) {
    echo "<p style='color: green; font-weight: bold; text-align:center;'>" . htmlspecialchars($_GET['msg']) . "</p>";
}
if (isset($_GET['error'])) {
    echo "<p style='color: red; font-weight: bold; text-align:center;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style_panel.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>

<header class="header">
    <h1><?php echo $_SESSION["admin"]; ?></h1>
    <a href="logout_admin.php" title="Cerrar sesión">
        <i class="fas fa-right-from-bracket"></i>
    </a>
    <a href="../index.php"><i class="fas fa-home"></i> Inicio</a>
</header>

<h1>Panel de Administración</h1>

<div class="container">
  <div class="card">
    <i class="fas fa-plus-circle"></i>
    <a href="agregar_producto.php">Agregar Productos</a>
  </div>
  <div class="card">
    <i class="fas fa-edit"></i>
    <a href="modificar_producto.php">Modificar Productos</a>
  </div>
  <div class="card">
    <i class="fas fa-trash"></i>
    <a href="eliminar_producto.php">Eliminar Productos</a>
  </div>
      <div class="card">
    <i class="fas fa-shopping-cart"></i>
    <a href="ver_ventas.php">Ventas</a>
  </div>
  <div class="card">
    <i class="fas fa-user-slash"></i>
    <a href="eliminar_usuario.php">Eliminar Usuarios</a>
  </div>
  <div class="card">
    <i class="fas fa-user-plus"></i>
    <a href="agregar_admin.php">Agregar Administradores</a>
  </div>
  <div class="card">
    <i class="fas fa-user-minus"></i>
    <a href="eliminar_admin.php">Eliminar Administradores</a>
  </div>
</div>

</body>
</html>
