<?php
session_start();
require_once("../librerias/fpdf/fpdf.php"); // Asegúrate de que FPDF esté en esta ruta

// ✅ Validar que sea administrador
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("❌ ID de factura no proporcionado.");
}

$idFactura = $_GET['id'];


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

    // ----------------------
    // 📄 Generar PDF con FPDF
    // ----------------------
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);

    // Encabezado
    $pdf->Cell(0,10,'Factura - RepLicant Apparel',0,1,'C');
    $pdf->Ln(5);

    // Datos de la factura
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'Codigo: '.$factura['codigo'],0,1);
    $pdf->Cell(0,8,'Fecha: '.$factura['fecha'],0,1);
    $pdf->Cell(0,8,'Cliente: '.($usuario['nombre'] ?? "Desconocido"),0,1);
    $pdf->Cell(0,8,'Cedula: '.$factura['cedula'],0,1);
    $pdf->Cell(0,8,'Correo: '.($usuario['correo'] ?? "No registrado"),0,1);
    $pdf->Ln(10);

    // Tabla de productos
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(80,8,'Producto',1);
    $pdf->Cell(30,8,'Cantidad',1);
    $pdf->Cell(40,8,'Precio_unitario',1);
    $pdf->Cell(40,8,'Subtotal',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',12);
    foreach ($detalles as $det) {
        $pdf->Cell(80, 8, iconv('UTF-8', 'ISO-8859-1', $det['nombre']), 1);
        $pdf->Cell(30,8,$det['cantidad'],1);
        $pdf->Cell(40,8,"$".number_format($det['precio_unitario'],0,',','.'),1);
        $pdf->Cell(40,8,"$".number_format($det['subtotal'],0,',','.'),1);
        $pdf->Ln();
    }

    // Total
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(150,8,'Total',1);
    $pdf->Cell(40,8,"$".number_format($factura['total'],0,',','.'),1);

    // Salida
    ob_end_clean(); // Limpia cualquier salida previa
    $pdf->Output("I","Factura_".$factura['codigo'].".pdf");
exit;