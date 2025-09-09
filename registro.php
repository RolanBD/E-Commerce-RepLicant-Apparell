<?php
// Conectar con SQLite
$db = new SQLite3('usuarios.db');

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];
$direccion = $_POST['direccion'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Insertar en la base de datos
$query = $db->prepare("INSERT INTO usuarios (cedula, nombre, apellido, direccion, correo, contrasena)
                       VALUES (:cedula, :nombre, :apellido, :direccion, :correo, :contrasena)");

$query->bindValue(':cedula', $cedula, SQLITE3_TEXT);
$query->bindValue(':nombre', $nombre, SQLITE3_TEXT);
$query->bindValue(':apellido', $apellido, SQLITE3_TEXT);
$query->bindValue(':direccion', $direccion, SQLITE3_TEXT);
$query->bindValue(':correo', $correo, SQLITE3_TEXT);
$query->bindValue(':contrasena', $contrasena, SQLITE3_TEXT);

$result = $query->execute();

if ($result) {
    // Mostrar mensaje
    echo "<script>
            alert('✅ Registro exitoso');
            window.location.href = 'login.html'; // Redirige al inicio de sesión
          </script>";
} else {
    echo "❌ Error al registrar el usuario";
}
$db->close(); // Cierra la conexión para evitar bloqueos
exit();
?>
