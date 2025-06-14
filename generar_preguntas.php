<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = json_decode(file_get_contents('php://input'), true);

    if ($datos && isset($datos['nivel_num'])) {
        $_SESSION['nivel_num'] = $datos['nivel_num'];
        echo "Lección recibida: " . htmlspecialchars($_SESSION['nivel_num']);
    } else {
        http_response_code(400);
        echo "Datos no válidos.";
    }
} else {
    // Esta parte se ejecuta cuando entras desde window.location.href
    if (isset($_SESSION['nivel_num'])) {
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

        // echo "Mostrando preguntas del nivel: " . htmlspecialchars($_SESSION['nivel_num']);
        $stmt = $conn->prepare("SELECT id_tema, id_dificultad FROM nivel WHERE id_nivel = ?");
        $stmt->bind_param("i", ($_SESSION['nivel_num']));
        $stmt->execute();
        $stmt->bind_result($id_tema, $id_dificultad);
        $stmt->fetch();
        $stmt->close();
        // Aquí podrías incluir HTML con preguntas y opciones

        $stmt = $conn->prepare("SELECT nombre FROM tema WHERE id_tema = ?");
        $stmt->bind_param("i", $id_tema);
        $stmt->execute();
        $stmt->bind_result($tema);
        $stmt->fetch();
        $stmt->close();
        // echo "El tema y dificultad es: $tema";

        $stmt = $conn->prepare("SELECT nombre FROM dificultad WHERE id_dificultad = ?");
        $stmt->bind_param("i", $id_dificultad);
        $stmt->execute();
        $stmt->bind_result($dificultad);
        $stmt->fetch();
        $stmt->close();
        // echo "El tema y dificultad es: $tema y $dificultad";

        $stmt = $conn->prepare("SELECT vidas FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", ($_SESSION['correo']));
        $stmt->execute();
        $stmt->bind_result($vidas);
        $stmt->fetch();
        $stmt->close();

        $conn->close();

        // TODO: Aquí seria la conexión con la otra base de datos
        $servidor = "127.0.0.1";
        //$puerto = 3307;
        $usuario_db = "TC2005B_602_4"; //
        $password_db = "pAssWd_894700";
        $nombre_db = "R_602_5";

        $conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);
        if ($conn->connect_error) {
            echo json_encode(['error' => 'Error de conexión']);
            exit();
        }

        $stmt = $conn->prepare("SELECT id_pregunta, id_tipo FROM Pregunta WHERE id_dificultad = ? AND id_tema=? ORDER BY RAND() LIMIT 10");
        $stmt->bind_param("ii", $id_dificultad, $id_tema);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $num_preguntas = array();
        while ($row = $resultado->fetch_assoc()) {
            $num_preguntas[] = $row;
        }
        $_SESSION['num_preguntas'] = $num_preguntas;
        $stmt->close();

        if (isset($_SESSION['num_preguntas'])) {
            foreach ($_SESSION['num_preguntas'] as $num_pregunta) {
            //     echo "ID de pregunta: " . $num_pregunta['id_pregunta'] . "<br>";
            //     echo "ID de tipo: " . $num_pregunta['id_tipo'] . "<br><br>";
            }
        } else {
            echo "No hay preguntas guardadas en la sesión.";
        }

        //TODO: Crear un mapa con la información de las preguntas y sus respuestas
        $preguntas_map = []; // * El mapa con información de las preguntas
        foreach ($num_preguntas as $pregunta) {
            $id_pregunta = $pregunta['id_pregunta'];
            $id_tipo = $pregunta['id_tipo'];

            if ($id_tipo == 3) {
                $stmt = $conn->prepare("SELECT Pregunta.id_tipo, Pregunta.texto, Pregunta.imagen AS imagen_pregunta, respuestas_validas.respuesta
                FROM Pregunta
                JOIN respuestas_validas ON Pregunta.id_pregunta = respuestas_validas.id_pregunta
                WHERE Pregunta.id_pregunta = ?");
                $stmt->bind_param("i", $id_pregunta);
                $stmt->execute();
                $res = $stmt->get_result();

                while ($fila = $res->fetch_assoc()) {
                    if (!isset($preguntas_map[$id_pregunta])) {
                        $preguntas_map[$id_pregunta] = [
                            "id_tipo" => $fila["id_tipo"],
                            "texto" => $fila["texto"],
                            "imagen_pregunta" => $fila["imagen_pregunta"],
                            "respuestas" => []
                        ];
                    }

                    $preguntas_map[$id_pregunta]["respuestas"][] = $fila["respuesta"];
                }
                $stmt->close();
            } else {
                $stmt = $conn->prepare("SELECT Pregunta.id_tipo, Pregunta.texto, Pregunta.imagen AS imagen_pregunta, Respuesta.contenido, Respuesta.correcta, Respuesta.imagen AS imagen_respuesta
                FROM Pregunta
                JOIN Respuesta ON Pregunta.id_pregunta = Respuesta.id_pregunta
                WHERE Pregunta.id_pregunta = ?");
                $stmt->bind_param("i", $id_pregunta);
                $stmt->execute();
                $res = $stmt->get_result();

                while ($fila = $res->fetch_assoc()) {
                    if (!isset($preguntas_map[$id_pregunta])) {
                        $preguntas_map[$id_pregunta] = [
                            "id_tipo" => $fila["id_tipo"],
                            "texto" => $fila["texto"],
                            "imagen_pregunta" => $fila["imagen_pregunta"],
                            "respuestas" => []
                        ];
                    }

                    $preguntas_map[$id_pregunta]["respuestas"][] = [
                        "contenido" => $fila["contenido"],
                        "correcta" => $fila["correcta"],
                        "imagen_respuesta" => $fila["imagen_respuesta"]
                    ];
                }
                $stmt->close();
            }
        }
        $_SESSION['preguntas_map'] = $preguntas_map;
        
        $response = [
            "vidas" => $vidas,
            "preguntas_map" => $preguntas_map,
            "num_preguntas" => $num_preguntas,
            "tema" => $tema,
            "dificultad" => $dificultad
        ];

        header('Content-Type: application/json');

// Enviamos los datos como JSON al cliente (JavaScript)
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();



    } else {
        echo "No se ha seleccionado ninguna lección.";
    }
}
?>