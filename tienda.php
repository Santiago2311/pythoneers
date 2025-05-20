<?php
session_start();

$correo = $_SESSION['correo'];

$nombre_producto = $_POST['nombre_producto'] ?? null;

// var_dump($_SESSION); // Verifica la sesión
// var_dump($_POST);    // Verifica los datos del formulario
// exit();

if (empty($nombre_producto)) {
    echo '<h1>No se recibió el nombre del producto</h1>';
    exit();
}

$servidor = "127.0.0.1";
$puerto = 3307;
$usuario_db = "TC2005B_602_4";
$password_db = "pAssWd_894700";
$nombre_db = "R_602_4";

$conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);
if ($conn->connect_error) {
    echo '<h1>Error de conexión</h1>';
    exit();
}

$stmt = $conn->prepare("SELECT 1 FROM inventario WHERE correo = ? AND nombre_producto = ?;");
$stmt->bind_param("ss", $correo, $nombre_producto);
$stmt->execute();
$stmt->bind_result($existencia);
$stmt->fetch();
$stmt->close();

if ($existencia) {
    $stmt = $conn->prepare("SELECT activo FROM inventario WHERE correo = ? AND nombre_producto = ?;");
    $stmt->bind_param("ss", $correo, $nombre_producto);
    $stmt->execute();
    $stmt->bind_result($activo);
    $stmt->fetch();
    $stmt->close();
    if ($activo) {
        header("Location: quitar.php");
        exit();
    } else {
        header("Location: usar.php");
        exit();
    }
} else {
    $_SESSION['nombre_producto'] = $nombre_producto;
    header("Location: comprar.php");
    exit();
}

$conn->close();
?>