<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    try {
        $stmt = $conexion->prepare("UPDATE productos SET descripcion = :descripcion, precio = :precio WHERE codigo = :codigo");
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':codigo', $codigo);

        if ($stmt->execute()) {
            header("Location: panel.php?msg=Producto actualizado correctamente");
            exit();
        } else {
            echo "❌ Error al actualizar el producto.";
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>
