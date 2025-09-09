<?php
// Conectar con la base de datos SQLite
$db = new SQLite3('admin.db');  // Asegúrate de que la ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave   = $_POST["clave"];

    // Buscar el usuario en la base de datos
    $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario AND clave = :clave");
    $stmt->bindValue(':usuario', $usuario, SQLITE3_TEXT);
    $stmt->bindValue(':clave', $clave, SQLITE3_TEXT);
    $result = $stmt->execute();
    $admin = $result->fetchArray(SQLITE3_ASSOC);

    if ($admin) {
        // Guardar sesión de administrador
        session_start();
        $_SESSION["admin"] = $admin["nombre"] . " " . $admin["apellido"];
        $_SESSION["cedula_admin"] = $admin["cedula"];
        header("Location: panel.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style_login.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>

<div class="login-box">
    <h2>Administrador</h2>
    
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div class="input-group">
            <label for="clave">Contraseña</label>
            <input type="password" id="clave" name="clave" required>
        </div>
        <button type="submit" class="btn">Ingresar</button>
    </form>

    <a href="../index.php" class="home-link">
        <i class="fas fa-home"></i> Ir al inicio
    </a>
</div>

</body>
</html>
