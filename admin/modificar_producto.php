<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Producto</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>

<div class="contenedor-principal">
    <div class="form-container">
        <a href="panel.php" class="volver">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
        <h2>Modificar Producto</h2>
        <form method="POST">
            <label>Ingrese código del producto:</label>
            <input type="text" name="codigo" required>
            <button type="submit">Buscar</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codigo'])) {
        $codigo = $_POST['codigo'];

        $stmt = $conexion->prepare("SELECT * FROM productos WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            ?>
            <div class="form-container">
                <h3>Editando producto: <?= htmlspecialchars($producto['nombre']) ?></h3>
                <form method="POST" action="procesar_modificar_producto.php">
                    <input type="hidden" name="codigo" value="<?= $producto['codigo'] ?>">
                    <label>Descripción:</label><br>
                    <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea><br><br>
                    <label>Precio:</label>
                    <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required><br><br>
                    <button type="submit">Guardar cambios</button>
                </form>
            </div>
            <?php
        } else {
            header("Location: panel.php?error=El Producto con el codigo '$codigo' No se Encontró");
            exit();
        }
    }
    ?>
</div>
