<?php
session_start();

// Verificar si el código fue ingresado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_ingresado = $_POST['mfa_code'];

    // Verificar si el código ingresado coincide con el código guardado en la sesión
    if ($codigo_ingresado == $_SESSION['mfa_code']) {
        echo "Código correcto. Acceso concedido.";
        // Redirigir al usuario a la página principal (indexsin.html o similar)
        header("Location: indexsin.html");
        exit();
    } else {
        echo "Código incorrecto. Inténtalo de nuevo.";
    }
}
?>

<!-- Formulario para ingresar el código MFA -->
<form action="verificar_mfa.php" method="post">
    <label for="mfa_code">Código de verificación:</label>
    <input type="text" name="mfa_code" required>
    <input type="submit" value="Verificar">
</form>
