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

$stmt = $conn->prepare("SELECT puntaje, nombre, apellidos, correo FROM usuario ORDER BY puntaje DESC;");
$stmt->execute();
$result = $stmt->get_result(); 

$usuarios = [];
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $usuarios[] = $fila; // Agrega cada fila al arreglo
    }
}

$stmt = $conn->prepare("SELECT correo, nombre_producto, categoria FROM inventario NATURAL JOIN usuario NATURAL JOIN item WHERE activo = 1 ORDER BY puntaje DESC;");
$stmt->execute();
$result = $stmt->get_result(); 

$accesorios = [];
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $accesorios[] = $fila; // Agrega cada fila al arreglo
    }
}

// Cerrar conexión
$accesorios_por_usuario = [];
foreach ($accesorios as $acc) {
    $correo = $acc['correo'];
    // Quitamos el campo 'correo' del accesorio para no duplicar
    unset($acc['correo']);
    $accesorios_por_usuario[$correo][] = $acc;
}

// Agregar accesorios a cada usuario
foreach ($usuarios as &$usuario) {
    $correo = $usuario['correo'];
    $usuario['accesorios'] = $accesorios_por_usuario[$correo] ?? [];
}
unset($usuario); // buena práctica al usar referencias

$conn->close();

// Retornar el arreglo de usuarios ya combinados
echo json_encode(['usuarios' => $usuarios]);