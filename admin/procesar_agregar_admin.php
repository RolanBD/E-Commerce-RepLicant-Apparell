<?php
session_start();
include("conexion_admin.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $usuario = trim($_POST['usuario']);
    $clave = trim($_POST['clave']);

    // Validar campos vacíos
    if (empty($cedula) || empty($nombre) || empty($apellido) || empty($usuario) || empty($clave)) {
        header("Location: panel.php?error=Todos los campos son obligatorios.");
        exit();
    }

    // Validar que no exista cédula o usuario duplicado
    $check = $conexion->prepare("SELECT * FROM administradores WHERE cedula = :cedula OR usuario = :usuario LIMIT 1");
    $check->bindParam(':cedula', $cedula);
    $check->bindParam(':usuario', $usuario);
    $check->execute();

    if ($check->fetch(PDO::FETCH_ASSOC)) {
        header("Location: panel.php?error=Ya existe un administrador con esa cédula o usuario.");
        exit();
    }

    // Encriptar la clave
    // $hash = password_hash($clave, PASSWORD_DEFAULT);

    // Insertar en la BD
    $stmt = $conexion->prepare("INSERT INTO administradores (cedula, nombre, apellido, usuario, clave) 
                                VALUES (:cedula, :nombre, :apellido, :usuario, :clave)");
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':clave', $clave);

    if ($stmt->execute()) {
        header("Location: panel.php?msg=Administrador agregado correctamente.");
        exit();
    } else {
        header("Location: panel.php?error=Error al agregar el administrador.");
        exit();
    }
}
?>
