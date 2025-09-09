<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

try {
    $conexion = new PDO("sqlite:ventas.db");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Obtener facturas del usuario según cédula
    $stmt = $conexion->prepare("
        SELECT * FROM facturas 
        WHERE cedula = ? 
        ORDER BY fecha DESC
    ");
    $stmt->execute([$_SESSION['usuario_id']]);
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil - RepLicant Apparell</title>
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
  <link rel="stylesheet" href="style_perfil.css"/>
  <link rel="icon" type="image/x-icon" href="img/ico/favicon.ico">
</head>
<body>
  <!-- Header con navegación -->
  <header class="header">
      <h1><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> </h1>
      <div class="acciones">
          <a href="carrito.php" title="Carrito de Compras">
                <i class="fas fa-shopping-cart"></i>
            </a>
          <a href="logout_cliente.php" title="Cerrar sesión">
              <i class="fas fa-right-from-bracket"></i>
          </a>
      </div>
  </header>
    <nav class="nav">
      <a href="index.php">Inicio</a>
      <a href="productos.php" title="Productos">Productos</a>
    </nav>

  <!-- Contenido -->
  <div class="contenedor">
      <h2>Historial de Compras</h2>
      <?php if (count($facturas) > 0): ?>
          <?php foreach ($facturas as $factura): ?>
              <div class="factura">
                  <h3>
                      Factura: <?php echo htmlspecialchars($factura['codigo']); ?> 
                      <a class="btn-descargar" href="factura_pdf.php?id=<?php echo $factura['id']; ?>" title="Descargar">
                          <i class="fas fa-file-pdf"></i> Descargar
                      </a>
                  </h3>
                  <p><strong>Fecha:</strong> <?php echo $factura['fecha']; ?></p>
                  <p><strong>Total:</strong> $<?php echo number_format($factura['total'], 0, ',', '.'); ?></p>
                      <?php
                          $stmt_det = $conexion->prepare("SELECT * FROM factura_detalles WHERE factura_id = ?");
                          $stmt_det->execute([$factura['id']]);
                          $detalles = $stmt_det->fetchAll(PDO::FETCH_ASSOC);
                          foreach ($detalles as $det):
                      ?>
                          <li><?php echo htmlspecialchars($det['nombre']); ?> (x<?php echo $det['cantidad']; ?>) - $<?php echo number_format($det['subtotal'], 0, ',', '.'); ?></li>
                      <?php endforeach; ?>
                  </ul>
              </div>
          <?php endforeach; ?>
      <?php else: ?>
          <p>No tienes compras registradas.</p>
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
