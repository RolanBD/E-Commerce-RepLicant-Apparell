<?php
session_start();
require('librerias/fpdf/fpdf.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['id'])) {
    die("❌ No se especificó la factura.");
}

$factura_id = intval($_GET['id']);

try {
    $conexion = new PDO("sqlite:ventas.db");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Obtener la factura
    $stmt = $conexion->prepare("
        SELECT * FROM facturas 
        WHERE id = ? AND cedula = ?
    ");
    $stmt->execute([$factura_id, $_SESSION['usuario_id']]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("❌ Factura no encontrada o no pertenece a tu cuenta.");
    }

    // ✅ Obtener los detalles
    $stmt_det = $conexion->prepare("SELECT * FROM factura_detalles WHERE factura_id = ?");
    $stmt_det->execute([$factura_id]);
    $detalles = $stmt_det->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Crear PDF con FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Encabezado
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Factura de Compra',0,1,'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,'Codigo: ' . $factura['codigo'],0,1);
    $pdf->Cell(0,10,'Cliente (Cedula): ' . $factura['cedula'],0,1);
    $pdf->Cell(0,10,'Fecha: ' . $factura['fecha'],0,1);
    $pdf->Ln(10);

    // Tabla de productos
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(60,10,'Producto',1);
    $pdf->Cell(30,10,'Cantidad',1);
    $pdf->Cell(40,10,'Precio Unitario',1);
    $pdf->Cell(40,10,'Subtotal',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',12);
    foreach ($detalles as $det) {
        $pdf->Cell(60,10,$det['nombre'],1);
        $pdf->Cell(30,10,$det['cantidad'],1,0,'C');
        $pdf->Cell(40,10,'$'.number_format($det['precio_unitario'],0,',','.'),1,0,'R');
        $pdf->Cell(40,10,'$'.number_format($det['subtotal'],0,',','.'),1,0,'R');
        $pdf->Ln();
    }

    // Total
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(130,10,'TOTAL',1);
    $pdf->Cell(40,10,'$'.number_format($factura['total'],0,',','.'),1,0,'R');

    $pdf->Output("D","Factura-".$factura['codigo'].".pdf");

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
