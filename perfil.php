<?php
session_start();

header('Content-Type: application/json');

$correo = $_SESSION['correo'];

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

$stmt = $conn->prepare("SELECT nombre, apellidos FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($nombre, $apellidos);
$stmt->fetch();
$stmt->close();
$conn->close();

echo json_encode([
    'nombre' => $nombre,
    'apellidos' => $apellidos,
    'correo' => $correo,
]);
?>