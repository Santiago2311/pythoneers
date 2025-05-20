<?php
session_start();
header('Content-Type: application/json');

$correo = $_SESSION['correo'];

$nombre_producto = $_POST['nombre_producto'] ?? null;

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

$stmt = $conn->prepare("SELECT 1 FROM inventario WHERE correo = ? AND nombre_producto = ?;");
$stmt->bind_param("ss", $correo, $nombre_producto);
$stmt->execute();

$result = $stmt->get_result();
$existencia = $result->num_rows > 0;
$stmt->close();

// var_dump($existencia);
// exit();

if ($existencia === true) {
    $stmt = $conn->prepare("SELECT activo FROM inventario WHERE correo = ? AND nombre_producto = ?;");
    $stmt->bind_param("ss", $correo, $nombre_producto);
    $stmt->execute();
    $stmt->bind_result($activo);
    $stmt->fetch();
    $stmt->close();
    if ($activo === 1) { //lo tengo puesto 
        $stmt = $conn->prepare("UPDATE inventario SET activo = ? WHERE correo = ? and nombre_producto = ?;");
        $activo = 0;
        $stmt->bind_param("iss", $activo, $correo, $nombre_producto);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
        exit();
    } else { //me lo quiero poner
        $stmt = $conn->prepare("SELECT categoria FROM item WHERE nombre_producto = ?;"); //veo de que categoria es mi producto
        $stmt->bind_param("s", $nombre_producto);
        $stmt->execute();
        $stmt->bind_result($categoria);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("SELECT inventario.nombre_producto
        FROM inventario
        JOIN item ON inventario.nombre_producto = item.nombre_producto
        WHERE inventario.correo = ?
        AND item.categoria = ?
        AND inventario.activo = 1;"); 
        $stmt->bind_param("ss", $correo, $categoria);
        $stmt->execute();
        $stmt->bind_result($producto_activo);
        $stmt->fetch();
        $stmt->close();

        if ($producto_activo) {
            $stmt = $conn->prepare("UPDATE inventario SET activo = ? WHERE nombre_producto = ? AND correo = ?;"); //veo de que categoria es mi producto
            $quitar = 0;
            $stmt->bind_param("iss", $quitar, $producto_activo, $correo);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("UPDATE inventario SET activo = ? WHERE correo = ? and nombre_producto = ?;");
        $activo = 1;
        $stmt->bind_param("iss", $activo, $correo, $nombre_producto);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
        exit();
    }
} else {
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
        echo json_encode(['fondos_insuficientes' => $nombre_producto]);
        exit();
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
        echo json_encode(['success' => true]);
        exit();
    }
}

$conn->close();
?>