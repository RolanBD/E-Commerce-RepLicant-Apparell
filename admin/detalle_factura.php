<?php
session_start();

// ✅ Validar que sea administrador
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("❌ ID de factura no proporcionado.");
}

$idFactura = $_GET['id'];

try {
    // 🔹 Conexión a ventas
    $dbVentas = new PDO("sqlite:../ventas.db");
    $dbVentas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔹 Conexión a usuarios
    $dbUsuarios = new PDO("sqlite:../usuarios.db");
    $dbUsuarios->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Obtener la factura
    $stmt = $dbVentas->prepare("SELECT * FROM facturas WHERE id = ?");
    $stmt->execute([$idFactura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("❌ Factura no encontrada.");
    }

    // ✅ Obtener datos del cliente
    $stmtUser = $dbUsuarios->prepare("SELECT nombre, correo FROM usuarios WHERE cedula = ?");
    $stmtUser->execute([$factura['cedula']]);
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // ✅ Obtener los detalles de la factura
    $stmtDet = $dbVentas->prepare("SELECT * FROM factura_detalles WHERE factura_id = ?");
    $stmtDet->execute([$idFactura]);
    $detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle Factura <?php echo htmlspecialchars($factura['codigo']); ?></title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
  />
  <link rel="stylesheet" href="style_ventas.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>
  <header>
      <h1>Detalle de Factura</h1>
      <div>
        <a href="ver_ventas.php" class="volver">
          <i class="fas fa-arrow-left"></i> Volver
        </a>
      </div>
  </header>

  <div class="contenedor">
      <h2>Factura: <?php echo htmlspecialchars($factura['codigo']); ?></h2>
      <p><strong>Cliente:</strong> <?php echo htmlspecialchars($usuario['nombre'] ?? "Desconocido"); ?></p>
      <p><strong>Cédula:</strong> <?php echo htmlspecialchars($factura['cedula']); ?></p>
      <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['correo'] ?? "No registrado"); ?></p>
      <p><strong>Fecha:</strong> <?php echo $factura['fecha']; ?></p>
      <p><strong>Total:</strong> $<?php echo number_format($factura['total'], 0, ',', '.'); ?></p>

      <h3>Productos Comprados</h3>
      <table>
          <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio Unitario</th>
              <th>Subtotal</th>
          </tr>
          <?php foreach ($detalles as $det): ?>
          <tr>
              <td><?php echo htmlspecialchars($det['nombre']); ?></td>
              <td><?php echo $det['cantidad']; ?></td>
              <td>$<?php echo number_format($det['precio_unitario'], 0, ',', '.'); ?></td>
              <td>$<?php echo number_format($det['subtotal'], 0, ',', '.'); ?></td>
          </tr>
          <?php endforeach; ?>
      </table>

      <div class="acciones">
          <a class="btn-descargar" href="descargar_factura.php?id=<?php echo $factura['id']; ?>"><i class="fas fa-file-pdf"></i> Descargar PDF</a>
      </div>
  </div>
</body>
</html>
