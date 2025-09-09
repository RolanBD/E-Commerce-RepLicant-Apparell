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
    <title>Agregar Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>
<div class="form-container">
    <a class="volver" href="panel.php"><i class="fas fa-arrow-left">
    </i> Volver al Panel</a>
    <h2>Agregar Nuevo Administrador</h2>
    <form action="procesar_agregar_admin.php" method="POST">
        <label for="cedula">Cédula</label>
        <input type="text" name="cedula" id="cedula" required>

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" required>

        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" required>

        <button type="submit">Agregar Administrador</button>
    </form>
</div>
</body>
</html>
