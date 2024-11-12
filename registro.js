// Archivo JavaScript para la validación del formulario

// Obtiene el formulario con el id "registerForm" y agrega un evento que escucha el envío del formulario
document.getElementById("registerForm").addEventListener("submit", function(event) {
    
    // Validación básica para asegurar que el nombre de usuario y la contraseña cumplan ciertos criterios
    
    // Obtiene el valor del campo de nombre de usuario
    let username = document.getElementById("username").value;
    
    // Obtiene el valor del campo de contraseña
    let password = document.getElementById("password").value;

    // Verifica que el nombre de usuario tenga al menos 5 caracteres
    if (username.length < 5) {
        alert("El nombre de usuario debe tener al menos 5 caracteres."); // Muestra una alerta si el nombre es muy corto
        event.preventDefault(); // Evita el envío del formulario para que el usuario pueda corregir el error

    // Verifica que la contraseña tenga al menos 8 caracteres
    } else if (password.length < 8) {
        alert("La contraseña debe tener al menos 8 caracteres."); // Muestra una alerta si la contraseña es muy corta
        event.preventDefault(); // Evita el envío del formulario para que el usuario pueda corregir el error

    // Verifica que la contraseña tenga al menos una letra mayúscula y un número
    } else if (!/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
        alert("La contraseña debe contener al menos una letra mayúscula y un número."); // Muestra una alerta si la contraseña no cumple los requisitos
        event.preventDefault(); // Evita el envío del formulario para que el usuario pueda corregir el error
    }
});
