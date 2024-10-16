<?php
// Incluir archivo de conexión
include 'conexion.php';

// Función para sanitizar y validar entradas
function validate_user_input($input) {
    $input = trim($input); // Eliminar espacios innecesarios
    $input = stripslashes($input); // Eliminar barras invertidas
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); // Convertir caracteres especiales en entidades HTML
    return $input;
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = validate_user_input($_POST['username']);
    $password = validate_user_input($_POST['password']);

    // Consulta para verificar el usuario
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        // Obtener datos del usuario
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña usando password_verify()
        if (password_verify($password, $user['password'])) {
            // Contraseña correcta
            // Redirigir al usuario a index.html
            header("Location: indexsin.html");
            exit();
        } else {
            // Contraseña incorrecta
            echo "Usuario o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>