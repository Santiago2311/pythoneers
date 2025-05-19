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

$stmt = $conn->prepare("SELECT puntaje, nombre, apellidos FROM usuario ORDER BY puntaje DESC;");
$stmt->execute();
$result = $stmt->get_result(); 

$usuarios = [];
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $usuarios[] = $fila; // Agrega cada fila al arreglo
    }
}

// Cerrar conexión
$conn->close();

// Retornar como JSON
echo json_encode($usuarios);
?>