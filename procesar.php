<?php
// Incluir PHPMailer y autoload de Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir los archivos de PHPMailer
require 'vendor/autoload.php';

// Habilita visualización de errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

            // Crear instancia de PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();  // Usar SMTP
                $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
                $mail->SMTPAuth = true;  // Habilitar autenticación SMTP
                $mail->Username = 'yosivelasco123@gmail.com';  // Tu dirección de Gmail
                $mail->Password = 'volkway16';  // Tu contraseña normal de Gmail (NO App Password si no tienes 2FA)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Protocolo de encriptación TLS
                $mail->Port = 587;  // Puerto TLS de Gmail

                // Activar el modo de depuración para ver los detalles de la conexión SMTP
                $mail->SMTPDebug = 2;  // Modo de depuración (verifica los logs del servidor)

                // Configuración del correo
                $mail->setFrom('yosivelasco123@gmail.com', 'Tu Nombre');  // Remitente (asegúrate que sea el mismo correo de Gmail)
                $mail->addAddress($email);  // El correo del destinatario (usuario)
                $mail->Subject = 'Tu código de autenticación MFA';  // Asunto del correo
                $mail->Body = "Tu código de autenticación es: $mfa_code";  // Cuerpo del correo

                // Enviar el correo
                if ($mail->send()) {
                    // Redirigir al formulario MFA
                    header("Location: mfa.html");
                    exit();
                } else {
                    echo "Error al enviar el correo: " . $mail->ErrorInfo;
                }

            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
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
