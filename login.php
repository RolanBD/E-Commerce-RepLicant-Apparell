<?php
session_start(); // Iniciamos sesión

$db = new SQLite3('usuarios.db');

// Capturamos los datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Verificamos si existe en la base de datos
$stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = :correo AND contrasena = :contrasena");
$stmt->bindValue(':correo', $correo, SQLITE3_TEXT);
$stmt->bindValue(':contrasena', $contrasena, SQLITE3_TEXT);
$result = $stmt->execute();
$usuario = $result->fetchArray(SQLITE3_ASSOC);

if ($usuario) {
    // Guardamos datos en la sesión
    $_SESSION['usuario_id'] = $usuario['cedula'];  
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    $_SESSION['usuario_correo'] = $usuario['correo'];

    // Redirigir a la página de inicio
    header("Location: index.php");
    exit();
} else {
    echo "<script>
            alert('Correo o contraseña incorrectos');
            window.location.href = 'login.html';
          </script>";
}
$db->close();
exit();
