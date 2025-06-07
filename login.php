<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = htmlspecialchars($_POST['correo']);
    $contrasena_form = $_POST['contrasena'];

    $servidor = "127.0.0.1";
    $puerto = 3307;
    $usuario_db = "TC2005B_602_4";
    $password_db = "pAssWd_894700";
    $nombre_db = "R_602_4";

    try {
        $conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT contrasena FROM usuario WHERE correo=?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $stmt->store_result();
        $stmt->bind_result($contrasena_hash_db);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            if (password_verify($contrasena_form, $contrasena_hash_db)) {
                $_SESSION['correo'] = $correo;

                header("Location: niveles.html");
            } else {
                header("Location: login.html?error=contrasena");
                exit();
            }
        } else {
            header("Location: login.html?error=usuario");
            exit();
        }
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo "<h1>Error de sistema</h1>";
        echo "<p>Ocurrió un error inesperado: " . $e->getMessage() . "</p>";
    }

} else {
    echo "Error: no se recibió ningún dato.";
}
?>