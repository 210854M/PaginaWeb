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

    // Verificar usuario y obtener el número de teléfono
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $phone = $user['phone'];  // Obtener el número de teléfono desde la base de datos

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Contraseña correcta, generar código MFA
            $mfa_code = rand(100000, 999999); // Generar un código de 6 dígitos
            $_SESSION['mfa_code'] = $mfa_code;  // Guardar el código en la sesión
            $_SESSION['username'] = $username;  // Guardar el usuario en la sesión

            // Enviar el código MFA al número de teléfono usando Textbelt
            $url = 'https://textbelt.com/text';

            // Datos para enviar el mensaje
            $data = [
                'phone' => '+52' . $phone,  // Asegúrate de incluir el código de país
                'message' => "Tu código de autenticación es: $mfa_code",
                'key' => 'textbelt'  // Clave API gratuita
            ];

            // Configurar los headers y enviar la solicitud POST
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ],
            ];

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            echo $result; 
            
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