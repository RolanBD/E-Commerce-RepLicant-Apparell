<?php
session_start();
include("conexion_usuarios.php");

if (!isset($_SESSION['admin'])) {
    header("Location: ../admin/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula']);

    // Paso 1: Buscar usuario
    if (isset($_POST['buscar'])) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE cedula = :cedula LIMIT 1");
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            header("Location: panel.php?error=El usuario con cédula '$cedula' no existe.");
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
            <a class="volver" href="panel.php"><i class="fas fa-arrow-left"></i> Atrás</a>
            <h2>Confirmar Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar al usuario 
               <b><?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></b> 
               con cédula <b><?php echo $usuario['cedula']; ?></b>?</p>
            
            <form action="procesar_eliminar_usuario.php" method="POST">
                <input type="hidden" name="cedula" value="<?php echo $usuario['cedula']; ?>">
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
        $delete = $conexion->prepare("DELETE FROM usuarios WHERE cedula = :cedula");
        $delete->bindParam(':cedula', $cedula);

        if ($delete->execute()) {
            header("Location: panel.php?msg=Usuario eliminado correctamente.");
            exit();
        } else {
            header("Location: panel.php?error=Error al intentar eliminar el usuario.");
            exit();
        }
    }
}
?>
