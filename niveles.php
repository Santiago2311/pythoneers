<?php
session_start();

header('Content-Type: application/json');

$correo = $_SESSION['correo'];

$servidor = "127.0.0.1";
//$puerto = 3307;
$usuario_db = "TC2005B_602_4";
$password_db = "pAssWd_894700";
$nombre_db = "R_602_4";

$conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

$stmt = $conn->prepare("SELECT ult_leccion FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($ult_leccion);
$stmt->fetch();
$stmt->close();

if ($ult_leccion == NULL) {
    $nivel_actual = 1;
} else {
    $stmt = $conn->prepare("SELECT id_nivel FROM leccion WHERE id_leccion = ?");
    $stmt->bind_param("i", $ult_leccion);
    $stmt->execute();
    $stmt->bind_result($nivel_actual);
    $stmt->fetch();
    $stmt->close();
}

if ($ult_leccion % 4 == 0 && $ult_leccion != NULL) {
    $nivel_actual += 1;
}

if ($ult_leccion == NULL) {
    $leccion_actual = 1;
} else {
    $leccion_actual = $ult_leccion + 1;
}

$conn->close();

echo json_encode([
    'nivel_actual' => $nivel_actual,
    'leccion_actual' => $leccion_actual]);
?>