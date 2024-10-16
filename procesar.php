<?php
// Incluir PHPMailer y autoload de Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'vendor/autoload.php'; // Incluye PHPMailer
include 'conexion.php'; // Incluir conexión a la base de datos

session_start();

$mail->SMTPDebug = 2;  // Activa la depuración
$mail->Debugoutput = 'html';  // La salida de los mensajes de depuración será en HTML


// Función para generar un código MFA de 6 dígitos
function generarCodigoMFA() {
    return rand(100000, 999999);  // Genera un código aleatorio de 6 dígitos
}

// Función para sanitizar entradas del usuario
function validate_user_input($input) {
    return htmlspecialchars(trim(stripslashes($input)), ENT_QUOTES, 'UTF-8');
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            // Generar el código MFA
            $mfa_code = generarCodigoMFA();
            $_SESSION['mfa_code'] = $mfa_code;  // Guardar el código en la sesión
            $_SESSION['username'] = $username;  // Guardar el nombre de usuario en la sesión
            
            // Obtener el correo electrónico del usuario desde la base de datos
            $correo_usuario = $user['email'];

            // Enviar el código MFA al correo del usuario
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
                $mail->SMTPAuth = true;
                $mail->Username = 'yosivelasco123@gmail.com';  // Tu dirección de correo Gmail
                $mail->Password = 'qypvhcvllbveykcp';  // Tu App Password de Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;  // Puerto TLS

                // Configuración del correo
                $mail->setFrom('yosivelasco123@gmail.com', 'Tu Nombre');  // Remitente
                $mail->addAddress($correo_usuario);  // El correo del destinatario (usuario)
                $mail->Subject = 'Tu código de autenticación MFA';  // Asunto del correo
                $mail->Body = "Tu código de autenticación es: $mfa_code";  // Cuerpo del correo

                // Enviar el correo
                if ($mail->send()) {
                    // Redirigir al formulario donde se ingresa el código MFA
                    header("Location: verificar_mfa.php");
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
        echo "Usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
