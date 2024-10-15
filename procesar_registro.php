<?php
// Incluir archivo de conexión
include 'conexion.php';

// Función para sanitizar y validar entradas
function validate_user_input($input) {
    return htmlspecialchars(trim(stripslashes($input)), ENT_QUOTES, 'UTF-8');
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = validate_user_input($_POST['username']);
    $password = validate_user_input($_POST['password']);
    $email = validate_user_input($_POST['email']);  // Obtener el correo electrónico

    // Verificar si el nombre de usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario ya existe
        echo "El nombre de usuario ya está registrado.";
    } else {
        // Insertar los datos en la base de datos con la contraseña encriptada
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $email);  // Incluyendo el correo

        if ($stmt->execute()) {
            // Redirigir al usuario a la página de inicio de sesión después del registro exitoso
            header("Location: login.html");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
