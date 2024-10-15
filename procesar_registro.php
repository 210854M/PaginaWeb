<?php
// Incluir el archivo de conexión
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
    // Obtener los datos del formulario
    $username = validate_user_input($_POST['username']);
    $password = validate_user_input($_POST['password']);

    // Verificar que el nombre de usuario no exista ya
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
        $sql = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

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
