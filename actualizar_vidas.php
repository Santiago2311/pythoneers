<?php
// Misma lógica de regeneración que antes, pero devuelve JSON
$max_vidas = 5;
$regeneracion_minutos = 5;

session_start();

header('Content-Type: application/json');

date_default_timezone_set('America/Mexico_City'); 

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

$stmt = $conn->prepare("SELECT vidas, ult_error FROM usuario WHERE correo = ?"); // * Guarda la cantidad de vida actuales
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($vidas, $ult_error_str);
$stmt->fetch();
$stmt->close();

if($vidas < $max_vidas) {
    $ult_error = new DateTime($ult_error_str);
    $ahora = new DateTime();

    $diferencia = $ult_error->diff($ahora);
    $minutos_pasados = ($diferencia->days * 24 * 60) + ($diferencia->h * 60) + $diferencia->i;

    $vidas_recuperadas = floor($minutos_pasados / $regeneracion_minutos);

    if ($vidas_recuperadas > 0) {
        $vidas += $vidas_recuperadas;
        if ($vidas > $max_vidas) {
            $vidas = $max_vidas;
        }

        // Aquí está el cambio clave
        $minutos_a_restar = $vidas_recuperadas * $regeneracion_minutos;
        $nueva_ult_error = $ult_error->add(new DateInterval('PT' . $minutos_a_restar . 'M'));

        $stmt = $conn->prepare("UPDATE usuario SET vidas = ?, ult_error = ? WHERE correo = ?");
        $nueva_ult_error_str = $nueva_ult_error->format('Y-m-d H:i:s');
        $stmt->bind_param("iss", $vidas, $nueva_ult_error_str, $correo);
        $stmt->execute();
        $stmt->close();
    }
}


echo json_encode(['vidas' => $vidas]);