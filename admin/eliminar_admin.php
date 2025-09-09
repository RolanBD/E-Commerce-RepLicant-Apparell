<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style_eliminar.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>

    <div class="form-container">
        <a class="volver" href="panel.php"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        <h2>Eliminar Administrador</h2>
        <form method="POST" action="procesar_eliminar_admin.php">
            <label for="cedula" class="form-label">Cédula del Administrador</label>
            <input type="text" name="cedula" id="cedula" class="form-control" required>
            <button type="submit" name="eliminar">Eliminar</button>
        </form>
    </div>

</body>
</html>
