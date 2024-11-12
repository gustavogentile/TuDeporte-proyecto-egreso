<?php 
// Configuración para mostrar errores en pantalla (útil en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Muestra todos los errores (advertencias, errores, etc.)

// Datos de conexión a la base de datos MySQL
$servername = "localhost"; // Nombre del servidor (local en este caso)
$username = "root"; // Nombre de usuario de la base de datos (predeterminado en XAMPP)
$password = ""; // Contraseña del usuario (vacío por defecto en XAMPP)
$dbname = "registration"; // Nombre de la base de datos que se va a utilizar

// Crear la conexión con la base de datos usando mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    // Termina el script y muestra un mensaje de error si la conexión falla
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado (solo procesa si el método es POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger y sanitizar los datos del formulario para evitar problemas de seguridad
    $user = htmlspecialchars($_POST['username']); // Obtiene el nombre de usuario del formulario y convierte caracteres especiales en HTML seguro
    $pass = htmlspecialchars($_POST['password']); // Obtiene la contraseña y pone caracteres especiales por seguridad

    // Hashear la contraseña para guardarla de forma segura
    $hashed_password = password_hash($pass, PASSWORD_BCRYPT); // Crea un hash de la contraseña usando el algoritmo bcrypt

    // Preparar la sentencia SQL para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    // Asocia los valores del formulario a la sentencia preparada
    $stmt->bind_param("ss", $user, $hashed_password); // "ss" indica que ambos valores son de tipo string

    // Ejecutar la sentencia SQL
    if ($stmt->execute()) {
        // Si el registro es exitoso, redirige al usuario a una página de confirmación
        header("Location: confirmacion.html");
        exit(); // Detiene el script después de la redirección para evitar que se ejecute más código
    } else {
        // Si hay un error al ejecutar, muestra un mensaje de error
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración preparada y la conexión a la base de datos
    $stmt->close();
    $conn->close();
}
?>
