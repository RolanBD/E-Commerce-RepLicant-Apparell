<?php
session_start();

try {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.html");
        exit;
    }

    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        echo "<h2>Tu carrito está vacío</h2>";
        echo "<a href='productos.php'>Volver a productos</a>";
        exit;
    }

    $conexion = new PDO("sqlite:ventas.db");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    $codigo_factura = uniqid("FAC-");

    $stmt = $conexion->prepare("
        INSERT INTO facturas (codigo, cedula, total) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$codigo_factura, $_SESSION['usuario_id'], $total]);

    $factura_id = $conexion->lastInsertId();

    $stmt_detalle = $conexion->prepare("
        INSERT INTO factura_detalles 
        (factura_id, producto_codigo, nombre, cantidad, precio_unitario, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($_SESSION['carrito'] as $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $stmt_detalle->execute([
            $factura_id,
            $producto['codigo'],
            $producto['nombre'],
            $producto['cantidad'],
            $producto['precio'],
            $subtotal
        ]);
    }

    $_SESSION['carrito'] = [];

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Confirmación de Compra</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style_pagar.css"/>
        <link rel="icon" type="image/x-icon" href="img/ico/favicon.ico">
    </head>
    <body>
        <div class="card">
            <h1>¡Gracias por tu compra, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>!</h1>
            <h2>Factura: <?php echo $codigo_factura; ?></h2>
            <h2>Total pagado: $<?php echo number_format($total, 0, ',', '.'); ?></h2>
            <a href="perfil.php" class="btn">Ver historial de compras</a>
        </div>
    </body>
    </html>
    <?php

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
