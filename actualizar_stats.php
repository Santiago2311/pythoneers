<?php
session_start();

// Permitir acceso desde navegador GET también para ver un mensaje de prueba
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $datos = json_decode(file_get_contents('php://input'), true);
    if ($datos) {
        $vidas = intval($datos['vidas']);
        $leccion_completada = filter_var($datos['leccion_completada'], FILTER_VALIDATE_BOOLEAN);
        $fecha_finalizacion = $datos['fecha_finalizacion'];
        $respuestas_correctas = $datos['respuestas_correctas'];
        $tiempo_cronometro = intval($datos['tiempo_cronometro']);

        $racha = 0;
        $monedas_nuevas = 0;
        $estrellas_nuevas = 0;
        $calificacion = 0;

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

        $leccion_completada = filter_var($datos['leccion_completada'], FILTER_VALIDATE_BOOLEAN);

        $fecha_finalizacion_dt = new DateTime($fecha_finalizacion, new DateTimeZone('UTC'));
        $fecha_finalizacion_str = $fecha_finalizacion_dt->format('Y-m-d H:i:s');
        if ($vidas < 5) {
            $stmt = $conn->prepare("UPDATE usuario SET vidas = ? WHERE correo = ?"); // * Actualiza las vidas en la base de datos
            $stmt->bind_param("is", $vidas, ($_SESSION['correo']));
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE usuario SET ult_error = ? WHERE correo = ?"); //* Actualiza el ultimo error en la base de datos
            $stmt->bind_param("ss", $fecha_finalizacion_str, ($_SESSION['correo']));
            $stmt->execute();
            $stmt->close();
        }

        if ($leccion_completada == 1) {
            $stmt = $conn->prepare("SELECT ult_leccion FROM usuario WHERE correo = ?"); //* Busca la ult_leccion
            $stmt->bind_param("s", ($_SESSION['correo']));
            $stmt->execute();
            $stmt->bind_result($ult_leccion);
            $stmt->fetch();
            $stmt->close();

            if ($ult_leccion == NULL) {
                $stmt = $conn->prepare("UPDATE usuario SET ult_leccion = 1 WHERE correo = ?"); //* Actualiza la ult_leccion a 1 si no lleva ninguna
                $stmt->bind_param("s", ($_SESSION['correo']));
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("UPDATE usuario SET ult_leccion = ult_leccion + 1 WHERE correo = ?"); //* Actualiza la ult_leccion a la siguiente
                $stmt->bind_param("s", ($_SESSION['correo']));
                $stmt->execute();
                $stmt->close();
            }

            $stmt = $conn->prepare("SELECT ult_dia FROM usuario WHERE correo = ?"); //* Busca el ult_dia
            $stmt->bind_param("s", ($_SESSION['correo']));
            $stmt->execute();
            $stmt->bind_result($ult_dia);
            $stmt->fetch();
            $stmt->close();

            $ult_dia_dt = new DateTime($ult_dia, new DateTimeZone('America/Mexico_City'));
            $diferencia_segundos = abs($fecha_finalizacion_dt->getTimestamp() - $ult_dia_dt->getTimestamp());
            $dia_ult_dia = $ult_dia_dt->format('Y-m-d');
            $dia_finalizacion = $fecha_finalizacion_dt->format('Y-m-d');

            if ($diferencia_segundos < 86400 && $dia_ult_dia !== $dia_finalizacion) { //Revisa que hayan pasado menos 24 horas pero que sean dias diferentes
                $stmt = $conn->prepare("UPDATE usuario SET racha = racha + 1 WHERE correo = ?"); //* Actualiza la racha a un dia mas
                $stmt->bind_param("s", ($_SESSION['correo']));
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("UPDATE usuario SET racha = 1 WHERE correo = ?"); //* Actualiza la racha a un dia mas
                $stmt->bind_param("s", ($_SESSION['correo']));
                $stmt->execute();
                $stmt->close();
            }

            $stmt = $conn->prepare("SELECT racha FROM usuario WHERE correo = ?"); //* Selecciona la racha para enviarla al js
            $stmt->bind_param("s", ($_SESSION['correo']));
            $stmt->execute();
            $stmt->bind_result($racha);
            $stmt->fetch();
            $stmt->close();
            
            if($respuestas_correctas == 10) { // * La leccion esta perfecta
                $stmt = $conn->prepare("UPDATE usuario SET lecciones_perfectas = lecciones_perfectas + 1 WHERE correo = ?"); //* Actualiza lecciones perfectas
                $stmt->bind_param("s", ($_SESSION['correo']));
                $stmt->execute();
                $stmt->close();

                $calificacion = 100;
                if ($tiempo_cronometro < 300) {
                    $estrellas_nuevas = 1000;
                    $monedas_nuevas = 500;
                } else {
                    $segundos_extras = $tiempo_cronometro - 300;
                    $estrellas_nuevas = 1000 - $segundos_extras;
                    $minutos_extras = floor($segundos_extras / 60);
                    $monedas_nuevas = 500 - ($minutos_extras * 10);
                }

            } else {
                $calificacion = $respuestas_correctas * 10;
                if ($tiempo_cronometro < 300) {
                    $estrellas_nuevas = 1000;
                    $monedas_nuevas = 500;
                } else {
                    $segundos_extras = $tiempo_cronometro - 300;
                    $estrellas_nuevas = 1000 - $segundos_extras;
                    $minutos_extras = $segundos_extras / 60;
                    $monedas_nuevas = 500 - ($minutos_extras * 10);
                }

                $respuestas_incorrectas = 10 - $respuestas_correctas;
                $monedas_nuevas -= $respuestas_incorrectas * 50;
                $estrellas_nuevas -= $respuestas_incorrectas * 100;
            }

            $monedas_nuevas = max(10, $monedas_nuevas);
            $estrellas_nuevas = max(50, $estrellas_nuevas);

            $stmt = $conn->prepare("UPDATE usuario SET monedas = monedas + ? WHERE correo = ?"); //* Actualiza las monedas
            $stmt->bind_param("is", $monedas_nuevas, ($_SESSION['correo']));
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE usuario SET puntaje = puntaje + ? WHERE correo = ?"); //* Actualiza las estrellas
            $stmt->bind_param("is", $estrellas_nuevas, ($_SESSION['correo']));
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE usuario SET ult_dia = ? WHERE correo = ?"); //* Actualiza las estrellas
            $stmt->bind_param("ss", $fecha_finalizacion_str, ($_SESSION['correo']));
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['racha'] = $racha;
        $_SESSION['monedas_nuevas'] = $monedas_nuevas;
        $_SESSION['estrellas'] = $estrellas_nuevas;
        $_SESSION['calificacion'] = $calificacion;
    } else {
        echo json_encode(['error' => 'No se recibieron datos']);
    }
} else {
    // Mensaje más amigable si accedes directamente en el navegador
    header('Content-Type: text/html');
    echo json_encode([
        'racha' => $_SESSION['racha'] ?? 0, // envía la racha o 0 si no existe
        'monedas' => $_SESSION['monedas_nuevas'] ?? 0,
        'estrellas' => $_SESSION['estrellas'] ?? 0,
        'calificacion' => $_SESSION['calificacion'] ?? 0,
        'mensaje' => 'Datos actualizados exitosamente'
    ]);

}
?>

