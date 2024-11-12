<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);          // Activa la visualización de errores
ini_set('display_startup_errors', 1);  // Muestra errores que ocurren al iniciar PHP
error_reporting(E_ALL);                // Reporta todos los tipos de errores

// Configuración de la conexión a la base de datos
$servername = "localhost";     // Nombre del servidor, comúnmente "localhost" en un entorno local
$dbname = "registration";      // Nombre de la base de datos
$db_username = "root";         // Nombre de usuario para acceder a la base de datos (por defecto en XAMPP es "root")
$db_password = "";             // Contraseña de la base de datos (por defecto en XAMPP está en blanco)

// Crear la conexión
$conn = new mysqli($servername, $db_username, $db_password, $dbname);  // Crea un objeto de conexión a la base de datos

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);  // Si falla, muestra el error y termina el script
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {  // Comprueba si la solicitud es POST (cuando se envía el formulario)

    // Obtener y verificar los datos ingresados por el usuario
    $username = $_POST['username'];    // Obtiene el valor del campo "username" del formulario
    $password = $_POST['password'];    // Obtiene el valor del campo "password" del formulario

    // Si algún campo está vacío, redirige al usuario a una página de error con un mensaje
    if (empty($username) || empty($password)) {
        header("Location: error.html?error=Por favor, rellena todos los campos");
        exit();  // Termina el script después de redirigir
    }

    // Preparar la consulta SQL para obtener el usuario
    $sql = "SELECT * FROM users WHERE username = ?";  // Consulta para encontrar el usuario en la tabla "users"
    $stmt = $conn->prepare($sql);                     // Prepara la consulta para evitar inyecciones SQL
    $stmt->bind_param("s", $username);                // Asocia el parámetro "$username" a la consulta
    $stmt->execute();                                 // Ejecuta la consulta
    $result = $stmt->get_result();                    // Obtiene el resultado de la consulta

    // Verificar si el usuario existe
    if ($result->num_rows === 1) {                    // Si hay un usuario con ese nombre...
        $user = $result->fetch_assoc();               // Obtiene los datos del usuario como un arreglo

        // Verificar la contraseña usando password_verify()
        if (password_verify($password, $user['password'])) {  // Verifica si la contraseña coincide con la guardada en la BD
            // Iniciar sesión y redirigir al usuario
            session_start();                          // Inicia una nueva sesión o reanuda la actual
            $_SESSION['username'] = $user['username'];  // Guarda el nombre de usuario en la sesión
            header("Location: inicio.html");            // Redirige al usuario a la página de inicio o dashboard
            exit();                                     // Termina el script después de redirigir
        } else {
            // Contraseña incorrecta
            header("Location: error.html?error=Contraseña incorrecta");  // Redirige con mensaje de error si la contraseña es incorrecta
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: error.html?error=El usuario no existe");  // Redirige si no se encuentra al usuario
        exit();
    }

    // Cerrar la conexión y la declaración preparada
    $stmt->close();  // Cierra la declaración preparada
    $conn->close();  // Cierra la conexión a la base de datos
}
?>
