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

$stmt = $conn->prepare("SELECT monedas FROM usuario WHERE correo = ?;");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($monedas);
$stmt->fetch();
$stmt->close();



$conn->close();

echo json_encode([
    'puntaje' => $puntaje,
    'monedas' => $monedas,
    'racha' => $racha,
    'vidas' => $vidas
]);
?>