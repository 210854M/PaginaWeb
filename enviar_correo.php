<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = htmlspecialchars($_POST['correo']);
    $comentarios = htmlspecialchars($_POST['comentarios']);

    // Validar que todos los campos estén completos
    if (!empty($nombre) && !empty($correo) && !empty($comentarios)) {
        // Configurar el correo
        $destinatario = "yosivelasco123@gmail.com"; // Cambia por el correo donde recibirás los mensajes
        $asunto = "Nuevo mensaje de contacto de: $nombre";
        $mensaje = "Nombre: $nombre\n";
        $mensaje .= "Correo: $correo\n";
        $mensaje .= "Comentarios: $comentarios\n";

        // Encabezados del correo
        $headers = "From: $correo\r\n";
        $headers .= "Reply-To: $correo\r\n";
        $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

        // Enviar el correo
        
    } 
}
?>
