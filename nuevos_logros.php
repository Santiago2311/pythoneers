<?php
session_start();

header('Content-Type: application/json');

$correo = $_SESSION['correo'];

$servidor = "127.0.0.1";
//puerto = 3307;
$usuario_db = "TC2005B_602_4";
$password_db = "pAssWd_894700";
$nombre_db = "R_602_4";

$conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

$logro_nivel_conseguido = 0;
$logro_racha_conseguido = 0;
$logro_lp_conseguido = 0;
$logro_puntaje_conseguido = 0;


// * Revisar si tienen un nuevo logro de niveles
$stmt = $conn->prepare("SELECT ult_leccion FROM usuario WHERE correo = ?"); // * Guarda su ult_leccion
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($ult_leccion);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT id_nivel FROM leccion WHERE id_leccion = ?"); // * Guarda su nivel actual
$stmt->bind_param("i", $ult_leccion);
$stmt->execute();
$stmt->bind_result($nivel_actual);
$stmt->fetch();
$stmt->close();

if ($ult_leccion % 4 == 0) {
    $nivel_actual++; //su siguiente nivel es mayor ya que completo la ultima leccion del anterior;
}

$stmt = $conn->prepare("SELECT MAX(id_logro) FROM usuario_logro WHERE id_logro BETWEEN 1 AND 6 AND correo = ?;"); // * Guarda su ultimo logro de nivel
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($logro_nivel_actual); // * el id del ultimo logro de nivel conseguido
$stmt->fetch();
$stmt->close();

if ($logro_nivel_actual == NULL) { //Si no lleva ningun nivel
    if ($nivel_actual > 1) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (1, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->close();
        $logro_nivel_conseguido = 1; //Porque consiguio un logro de un nivel
    }
} else { // si ya lleva un logro
    $siguiente_logro = $logro_nivel_actual + 1;
    $stmt = $conn->prepare("SELECT umbral FROM logro WHERE id_logro = ?;"); // * Guarda el umbral del siguiente logro
    $stmt->bind_param("i", $siguiente_logro);
    $stmt->execute();
    $stmt->bind_result($umbral_siguiente_logro);
    $stmt->fetch();
    $stmt->close();
    
    if ($nivel_actual >= $umbral_siguiente_logro) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (?, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("is", $siguiente_logro, $correo); //*Actualiza al siguiente logro
        $stmt->execute();
        $stmt->close();
        $logro_nivel_conseguido = $umbral_siguiente_logro;
    }
}

// * Revisa si hay un nuevo logro para racha
$stmt = $conn->prepare("SELECT racha FROM usuario WHERE correo = ?"); // * Guarda su racha actual
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($racha_actual);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT MAX(id_logro) FROM usuario_logro WHERE id_logro BETWEEN 7 AND 14 AND correo = ?;"); // * Guarda su ultimo logro de racha
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($logro_racha_actual); // * el id del ultimo logro de racha conseguido
$stmt->fetch();
$stmt->close();

if ($logro_racha_actual == NULL) { //Si no lleva ningun logro de racha
    if ($racha_actual >= 1) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (7, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->close();
        $logro_racha_conseguido = 1; //Porque consiguio un logro de racha
    }
} else { // si ya lleva un logro
    $siguiente_logro = $logro_racha_actual + 1;
    $stmt = $conn->prepare("SELECT umbral FROM logro WHERE id_logro = ?;"); // * Guarda el umbral del siguiente logro
    $stmt->bind_param("i", $siguiente_logro);
    $stmt->execute();
    $stmt->bind_result($umbral_siguiente_logro);
    $stmt->fetch();
    $stmt->close();
    
    if ($racha_actual >= $umbral_siguiente_logro) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (?, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("is", $siguiente_logro, $correo); //*Actualiza al siguiente logro
        $stmt->execute();
        $stmt->close();
        $logro_racha_conseguido = $umbral_siguiente_logro;
    }
}

// TODO: Revisa si hay un nuevo logro para tienda
$stmt = $conn->prepare("SELECT COUNT(*) FROM inventario WHERE correo = ?"); // * Guarda su inventario actual
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($tienda_actual);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT MAX(id_logro) FROM usuario_logro WHERE id_logro BETWEEN 15 AND 18 AND correo = ?;"); // * Guarda su ultimo logro de racha
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($logro_tienda_actual); // * el id del ultimo logro de racha conseguido
$stmt->fetch();
$stmt->close();

if ($logro_tienda_actual == NULL) { //Si no lleva ningun logro de racha
    if ($tienda_actual >= 9) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (15, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->close();
        $logro_tienda_conseguido = 1; //Porque consiguio un logro de racha
    }
} else { // si ya lleva un logro
    $siguiente_logro = $logro_tienda_actual + 1;
    $stmt = $conn->prepare("SELECT umbral FROM logro WHERE id_logro = ?;"); // * Guarda el umbral del siguiente logro
    $stmt->bind_param("i", $siguiente_logro);
    $stmt->execute();
    $stmt->bind_result($umbral_siguiente_logro);
    $stmt->fetch();
    $stmt->close();
    
    if ($tienda_actual >= $umbral_siguiente_logro) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (?, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("is", $siguiente_logro, $correo); //*Actualiza al siguiente logro
        $stmt->execute();
        $stmt->close();
        $logro_racha_conseguido = $umbral_siguiente_logro;
    }
}

// * Revisa si hay un nuevo logro de lecciones perfectas
$stmt = $conn->prepare("SELECT lecciones_perfectas FROM usuario WHERE correo = ?"); // * Guarda su num de lecciones perfectas
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($lp_actual);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT MAX(id_logro) FROM usuario_logro WHERE id_logro BETWEEN 19 AND 25 AND correo = ?;"); // * Guarda su ultimo logro de racha
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($logro_lp_actual); // * el id del ultimo logro de lecciones perfectas conseguido
$stmt->fetch();
$stmt->close();

if ($logro_lp_actual == NULL) { //Si no lleva ningun logro de lecciones perfectas
    if ($lp_actual >= 1) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (19, ?);"); // *Actualiza el logro de lp
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->close();
        $logro_lp_conseguido = 1; //Porque consiguio un logro de lecciones perfectas
    }
} else { // si ya lleva un logro
    $siguiente_logro = $logro_lp_actual + 1;
    $stmt = $conn->prepare("SELECT umbral FROM logro WHERE id_logro = ?;"); // * Guarda el umbral del siguiente logro
    $stmt->bind_param("i", $siguiente_logro);
    $stmt->execute();
    $stmt->bind_result($umbral_siguiente_logro);
    $stmt->fetch();
    $stmt->close();
    
    if ($lp_actual >= $umbral_siguiente_logro) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (?, ?);"); // *Actualiza el logro de nivel
        $stmt->bind_param("is", $siguiente_logro, $correo); //*Actualiza al siguiente logro
        $stmt->execute();
        $stmt->close();
        $logro_lp_conseguido = $umbral_siguiente_logro;
    }
}

//* Revisa si hay un nuevo logro de puntaje
$stmt = $conn->prepare("SELECT puntaje FROM usuario WHERE correo = ?"); // * Guarda su puntaje
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($puntaje_actual);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT MAX(id_logro) FROM usuario_logro WHERE id_logro BETWEEN 26 AND 30 AND correo = ?;"); // * Guarda su ultimo logro de racha
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($logro_puntaje_actual); // * el id del ultimo logro de puntaje
$stmt->fetch();
$stmt->close();

if ($logro_puntaje_actual == NULL) { //Si no lleva ningun logro de puntaje
    if ($puntaje_actual >= 700) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (26, ?);"); // *Actualiza el logro de puntaje
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->close();
        $logro_puntaje_conseguido = 700; //Porque consiguio un logro de puntaje
    }
} else { // si ya lleva un logro
    $siguiente_logro = $logro_puntaje_actual + 1;
    $stmt = $conn->prepare("SELECT umbral FROM logro WHERE id_logro = ?;"); // * Guarda el umbral del siguiente logro
    $stmt->bind_param("i", $siguiente_logro);
    $stmt->execute();
    $stmt->bind_result($umbral_siguiente_logro);
    $stmt->fetch();
    $stmt->close();
    
    if ($puntaje_actual >= $umbral_siguiente_logro) {
        $stmt = $conn->prepare("INSERT INTO usuario_logro (id_logro, correo) VALUES (?, ?);"); // *Actualiza el logro de puntaje
        $stmt->bind_param("is", $siguiente_logro, $correo); //*Actualiza al siguiente logro
        $stmt->execute();
        $stmt->close();
        $logro_puntaje_conseguido = $umbral_siguiente_logro;
    }
}

$conn->close();

echo json_encode([
    'logro_nivel_conseguido' => $logro_nivel_conseguido,
    'logro_racha_conseguido' => $logro_racha_conseguido,
    'logro_tienda_conseguido' => $logro_tienda_conseguido,  
    'logro_lp_conseguido' => $logro_lp_conseguido,
    'logro_puntaje_conseguido' => $logro_puntaje_conseguido]);
?>