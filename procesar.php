<?php
// Incluir archivo de conexión
include 'conexion.php';
session_start();

// Función para sanitizar y validar entradas
function validate_user_input($input) {
    return htmlspecialchars(trim(stripslashes($input)), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = validate_user_input($_POST['username']);
    $password = validate_user_input($_POST['password']);

    // Verificar usuario y obtener el correo electrónico
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];  // Obtener el correo electrónico desde la base de datos

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Contraseña correcta, generar código MFA
            $mfa_code = rand(100000, 999999); // Generar un código de 6 dígitos
            $_SESSION['mfa_code'] = $mfa_code;  // Guardar el código en la sesión
            $_SESSION['username'] = $username;  // Guardar el usuario en la sesión

            // Enviar el código MFA por correo electrónico
            $subject = "Tu código de autenticación MFA";
            $message = "Tu código de autenticación es: $mfa_code";
            $headers = "From: yosivelasco123@gmail.com";

            // Usar la función mail() para enviar el correo
            if (mail($email, $subject, $message, $headers)) {
                // Redirigir al formulario MFA
                header("Location: mfa.html");
                exit();
            } else {
                echo "Error al enviar el correo.";
            }
        } else {
            // Contraseña incorrecta
            echo "Usuario o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario no encontrado.";
    }
    $stmt->close();
    $conn->close();
}
?>