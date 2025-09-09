<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>
<div class="form-container">
    <a class="volver" href="panel.php"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
    <h2>Eliminar Usuario</h2>
    <form action="procesar_eliminar_usuario.php" method="POST">
        <label for="cedula">Cédula del Cliente</label>
        <input type="text" name="cedula" id="cedula" required>
        <button type="submit" name="buscar">Buscar Usuario</button>
    </form>
</div>
</body>
</html>