<?php
session_start();

// ✅ Validar si es admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

try {
    // 🔹 Conexión a ventas
    $dbVentas = new PDO("sqlite:../ventas.db");
    $dbVentas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔹 Conexión a usuarios
    $dbUsuarios = new PDO("sqlite:../usuarios.db");
    $dbUsuarios->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Obtener todas las facturas
    $stmt = $dbVentas->query("SELECT * FROM facturas ORDER BY fecha DESC");
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ventas - Administrador</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
  />
  <link rel="stylesheet" href="style_ventas.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>
  <header>
      <h1>Ventas</h1>
      <div>
        <a href="panel.php" class="volver">
          <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
      </div>
  </header>

  <div class="contenedor">
      <h2>Ventas Realizadas</h2>
      <?php if (count($facturas) > 0): ?>
          <table>
              <tr>
                  <th>Código Factura</th>
                  <th>Cliente</th>
                  <th>Cédula</th>
                  <th>Fecha</th>
                  <th>Total</th>
                  <th>Acciones</th>
              </tr>
              <?php foreach ($facturas as $factura): ?>
              <?php
                  // 🔹 Buscar nombre del cliente en usuarios.db
                  $stmtUser = $dbUsuarios->prepare("SELECT nombre FROM usuarios WHERE cedula = ?");
                  $stmtUser->execute([$factura['cedula']]);
                  $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
              ?>
              <tr>
                  <td><?php echo htmlspecialchars($factura['codigo']); ?></td>
                  <td><?php echo htmlspecialchars($usuario['nombre'] ?? "Desconocido"); ?></td>
                  <td><?php echo htmlspecialchars($factura['cedula']); ?></td>
                  <td><?php echo $factura['fecha']; ?></td>
                  <td>$<?php echo number_format($factura['total'], 0, ',', '.'); ?></td>
                  <td class="acciones">
                      <a href="detalle_factura.php?id=<?php echo $factura['id']; ?>"><i class="fas fa-eye"></i> Ver</a>
                      <a href="descargar_factura.php?id=<?php echo $factura['id']; ?>"><i class="fas fa-file-pdf"></i> PDF</a>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>
      <?php else: ?>
          <p>No hay ventas registradas.</p>
      <?php endif; ?>
  </div>
</body>
</html>
