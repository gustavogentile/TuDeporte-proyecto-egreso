<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos MySQL
$servername = "localhost";
$username = "root"; // Usuario de XAMPP
$password = ""; // Contraseña por defecto en XAMPP
$dbname = "registration";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $user = htmlspecialchars($_POST['username']);
    $pass = htmlspecialchars($_POST['password']);

    // Hashear la contraseña por seguridad
    $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

    // Preparar la sentencia SQL para evitar SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $user, $hashed_password);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redirigir a la página de confirmación si el registro es exitoso
        header("Location: confirmacioneng.html");
        exit(); // Asegurarse de que el script se detenga aquí
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
