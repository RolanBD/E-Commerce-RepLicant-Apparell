<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = trim($_POST['codigo']);

    // Paso 1: Buscar producto
    if (isset($_POST['buscar'])) {
        $stmt = $conexion->prepare("SELECT * FROM productos WHERE codigo = :codigo LIMIT 1");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            header("Location: panel.php?error=El producto con código '$codigo' no existe.");
            exit();
        }
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Confirmar Eliminación</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
            <link rel="stylesheet" href="style_eliminar.css">
            <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
        </head>
        <body>
        <div class="form-container">
            <a class="volver" href="eliminar_producto.php"><i class="fas fa-arrow-left"></i> Atrás</a>
            <h2>Confirmar Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar el producto <b><?php echo $producto['nombre']; ?></b> con código <b><?php echo $producto['codigo']; ?></b>?</p>
            
            <form action="procesar_eliminar_producto.php" method="POST">
                <input type="hidden" name="codigo" value="<?php echo $producto['codigo']; ?>">
                <button type="submit" name="confirmar">Sí, eliminar</button>
            </form>
        </div>
        </body>
        </html>
        <?php
        exit();
    }

    // Paso 2: Confirmar eliminación
    if (isset($_POST['confirmar'])) {
        $delete = $conexion->prepare("DELETE FROM productos WHERE codigo = :codigo");
        $delete->bindParam(':codigo', $codigo);

        if ($delete->execute()) {
            header("Location: panel.php?msg=Producto eliminado correctamente.");
            exit();
        } else {
            header("Location: panel.php?error=Error al intentar eliminar el producto.");
            exit();
        }
    }
}
?>
