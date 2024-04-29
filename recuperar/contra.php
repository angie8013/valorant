<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "valorant";

$conexion = mysqli_connect($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar el nombre de usuario enviado desde el formulario
    $username = $_POST["username"];

    $query = "SELECT username, contrasena FROM jugador WHERE username = ?";
    
    // Utilizar consultas preparadas para evitar inyecciones SQL
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        // Verificar si se encontraron resultados
        if (mysqli_num_rows($result) > 0) {
            // El nombre de usuario existe en la base de datos
            $fila = mysqli_fetch_assoc($result);
            $contrasena = $fila['contrasena'];

            // Enviar el correo electrónico con la contraseña para restablecer
            $subject = "Recuperación de Contraseña";
            $message = "Hola, Tu contraseña actual es: $contrasena\n\n";
            $headers = "From: d0nosit06@gmail.com" . "\r\n" .
                       "Reply-To: $username" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            // Envía el correo electrónico
            if (mail($username, $subject, $message, $headers)) {
                echo '<script>alert("Revisa tu correo y sigue con la recuperación.");</script>';
                echo '<script>window.location.href = "res_contra.php";</script>';
            } else {
                echo "Hubo un problema al enviar el correo electrónico. Por favor, inténtalo de nuevo más tarde.";
            }
        } else {
            // El nombre de usuario no existe en la base de datos
            echo "El nombre de usuario no existe en la base de datos.";
        }
    } else {
        echo "Error en la consulta: " . mysqli_error($conexion);
    }
}
?>
