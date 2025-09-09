<?php
session_start();
include("conexion.php");

// Verificar si el usuario es administrador
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin/login.php");
    exit();
}

// Verificar que los datos llegaron
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $categoria_id = $_POST['categoria_id'];
    $subcategoria_id = $_POST['subcategoria_id'];

    try {
        // Verificar si el código ya existe
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM productos WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            // Si ya existe, redirigir con mensaje de error
            header("Location: panel.php?error=El código '$codigo' ya existe. Ingresa uno diferente.");
            exit();
        }
        // Insertar producto
        $stmt = $conexion->prepare("INSERT INTO productos (codigo, nombre, descripcion, precio, imagen, categoria_id, subcategoria_id)
                                    VALUES (:codigo, :nombre, :descripcion, :precio, :imagen, :categoria_id, :subcategoria_id)");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':subcategoria_id', $subcategoria_id);

        if ($stmt->execute()) {
            // Redirigir al panel de administración con un mensaje
            header("Location: panel.php?msg=Producto agregado correctamente");
            exit();
        } else {
            echo "❌ Error al agregar el producto.";
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>
