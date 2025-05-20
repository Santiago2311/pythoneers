<?php
session_start();

header('Content-Type: application/json');

$correo = $_SESSION['correo'];

$nombre_producto = $_SESSION['nombre_producto'] ?? null;

// var_dump($_SESSION); // Verifica la sesión
// var_dump($_POST);    // Verifica los datos del formulario
// exit();

if (empty($nombre_producto)) {
    echo json_encode(['error' => 'No se recibió el nombre del producto']);
    exit();
}

$servidor = "127.0.0.1";
$puerto = 3307;
$usuario_db = "TC2005B_602_4";
$password_db = "pAssWd_894700";
$nombre_db = "R_602_4";

$conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

$stmt = $conn->prepare("SELECT monedas FROM usuario WHERE correo = ?;");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($monedas);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT precio FROM item WHERE nombre_producto = ?;");
$stmt->bind_param("s", $nombre_producto);
$stmt->execute();
$stmt->bind_result($precio);
$stmt->fetch();
$stmt->close();

if ($monedas < $precio) {
    echo json_encode(['compra_exitosa' => 0]);
} else {
    // var_dump("El problema nunca fue economico");
    // exit();
    $stmt = $conn->prepare("INSERT INTO inventario (nombre_producto, correo, activo) VALUES (?, ?, 0);");
    $stmt->bind_param("ss", $nombre_producto, $correo);
    $stmt->execute();
    $stmt->close();

    $nuevas_monedas = $monedas - $precio;
    $stmt = $conn->prepare("UPDATE usuario SET monedas = ? WHERE correo = ?;");
    $stmt->bind_param("is", $nuevas_monedas, $correo);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'compra_exitosa' => 1,
        'nombre_producto' => $nombre_producto]);
}

$conn->close();
?>