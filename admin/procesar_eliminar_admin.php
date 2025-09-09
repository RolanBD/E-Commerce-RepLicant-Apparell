<?php
session_start();
require 'conexion_admin.php'; // tu archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];

    // Validamos si está intentando eliminarse a sí mismo
    if ($cedula == $_SESSION['cedula_admin']) {
        header("Location: panel.php?error=No Puede Eliminar este Administrador.");
        exit();
    }

    try {
        // Verificar si existe el administrador
        $stmt = $conexion->prepare("SELECT * FROM administradores WHERE cedula = :cedula");
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Eliminar
            $delete = $conexion->prepare("DELETE FROM administradores WHERE cedula = :cedula");
            $delete->bindParam(':cedula', $cedula);
            $delete->execute();

            header("Location: panel.php?msg=Administrador Eliminado Correctamente.");
        } else {
            header("Location: panel.php?error=No se Encontró el Administrador.");
        }

        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "❌ Error: " . $e->getMessage();
        header("Location: panel.php");
        exit();
    }
}
?>
