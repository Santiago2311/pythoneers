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

$activo = 1;
$productos = [];

$categorias = ['cabeza', 'cuello', 'lentes', 'bolsos'];

foreach ($categorias as $categoria) {
    $stmt = $conn->prepare("SELECT nombre_producto FROM inventario NATURAL JOIN item WHERE correo = ? AND activo = ? AND categoria= ?");
    $stmt->bind_param("sis", $correo, $activo, $categoria); // el último es int, no string
    $stmt->execute();
    $stmt->bind_result($nombre_producto);

    $found = false;
    
    if ($stmt->fetch()) {
        $productos[] = [
            'categoria' => $categoria,
            'nombre_producto' => $nombre_producto
        ];
        $found = true;
    }

    if (!$found) {
        $productos[] = [
            'categoria' => $categoria,
            'nombre_producto' => 'vacio'
        ];
    }

    $stmt->close();
}

$stmt = $conn->prepare("SELECT ult_leccion FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($ult_leccion);
$stmt->fetch();
$stmt->close();

if (is_null($ult_leccion)) {
    $ult_leccion = 0;
}

$progreso = floor(($ult_leccion * 100) / 96);


//*TODO: Checar que logros estan disponibles
$stmt = $conn->prepare("SELECT id_logro, categoria FROM usuario_logro NATURAL JOIN logro WHERE correo = ?;");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($id_logro, $categoria);
$id_logros = []; 

while ($stmt->fetch()) {
    $id_logros[intval($id_logro)] = $categoria;
}

$stmt->close();


$conn->close();

echo json_encode([
    'nombre' => $nombre,
    'apellidos' => $apellidos,
    'correo' => $correo,
    'productos' => $productos,
    'progreso' => $progreso,
    'logros_conseguidos' => $id_logros
]);
?>