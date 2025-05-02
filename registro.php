<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellidos = htmlspecialchars($_POST['apellidos']);
    $correo = htmlspecialchars($_POST['correo']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Usando bcrypt

    // Configuración de la conexión a la base de datos a través del túnel SSH
    $servidor = "127.0.0.1";
    $puerto = 3307;
    $usuario_db = "TC2005B_602_4";
    $password_db = "pAssWd_894700";
    $nombre_db = "TC2005B_602_4";

    try {
        // Crear conexión a la base de datos a través del túnel SSH
        $conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db, $puerto);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO proyecto_usuario (nombre, apellidos, correo, contrasena) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $apellidos, $correo, $contrasena);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header("Location: registro.html");
        } else {
            echo "<h1>Error al registrar</h1>";
            echo "<p>No se pudo completar el registro: " . $stmt->error . "</p>";
        }

        // Cerrar conexiones
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