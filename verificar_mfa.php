<?php
session_start();

// Verificar el código MFA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mfa_code = $_POST['mfa_code'];

    // Comparar el código ingresado con el guardado en la sesión
    if ($mfa_code == $_SESSION['mfa_code']) {
        // MFA exitoso, redirigir al sistema
        echo "Autenticación completada. Redirigiendo...";
        header("Location: indexsin.html"); // Redirige a la página principal
        exit();
    } else {
        echo "Código MFA incorrecto.";
    }
}
