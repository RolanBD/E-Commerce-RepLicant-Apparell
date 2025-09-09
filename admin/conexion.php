<?php
try {
    // Ruta de la Base de Datos
    $conexion = new PDO("sqlite:../productos.db");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>
