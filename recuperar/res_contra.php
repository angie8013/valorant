    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "valorant";

    $conexion = mysqli_connect($servername, $username, $password, $dbname);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $username = $_POST["username"];
        $contrasena_enviada = $_POST["contrasena"];


        // Validar si los campos no están vacíos
        if (empty($username) || empty($contrasena_enviada)) {
            echo "Por favor, completa todos los campos.";
            exit;
        }

        if (!is_numeric($username)) {
            echo "Jugador no encontrado";
            exit;
        }

        // Verificar si los datos coinciden con la base de datos
        $query = "SELECT username, contrasena FROM jugador WHERE username = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            // Los datos coinciden, obtener el ID de usuario
            $fila = mysqli_fetch_assoc($result);
            $username = $fila['username'];
            $contrasena_bd = $fila['contrasena'];

            // Verificar si la contraseña enviada coincide con la almacenada en la base de datos
            if (password_verify($contrasena_enviada, $contrasena_bd)) {
                // Contraseña válida, enviar correo de recuperación
                $subject = "Recuperación de Contraseña";
                $message = "Hola $username,\n\nTu contraseña actual es: $contrasena_bd\n\n";
                $headers = "From: d0nosit06@gmail.com" . "\r\n" .
                       "Reply-To: $username" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

                // Envía el correo electrónico
                if (mail($username, $subject, $message, $headers)) {
                    echo '<script>alert("Revisa tu correo y sigue con la recuperación.");</script>';
                    echo '<script>window.location.href = "../login.html";</script>';
                } else {
                    echo "Hubo un problema al enviar el correo electrónico. Por favor, inténtalo de nuevo más tarde.";
                }
            } else {
                // Contraseña incorrecta
                echo '<script>alert("La contraseña proporcionada es incorrecta.");</script>';
            }
        } else {
            // Nombre de usuario no encontrado en la base de datos
            echo '<script>alert("El nombre de usuario no existe en la base de datos.");</script>';
        }

        mysqli_close($conexion);
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../css/register.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <body class="login">
    <div class="recuadro">
        <form method="post">
        <center><h2>Recuperar Contraseña</h2></center>
        <div class="input-box">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <i class='bx bxs-user'></i>  
        </div>
        <div class="input-box">
            <input type="password" placeholder="Contraseña" name="contrasena_enviada" id="contrasena_enviada" required>
            <i class='bx bxs-lock-alt' ></i>
        </div>
        <div class="input-box">
            <input type="password" placeholder="Contraseña" name="nueva_contrasena" id="nueva_contrasena" required>
            <i class='bx bxs-lock-alt' ></i>
        </div>
        <button class="valorant-btn" type="submit" name="validar">
            <span class="underlay">
            <span class="label"><input type="hidden" name="validar">Iniciar</span>
            </span>
        </button>
        <div class="register-link">
            <p><a href="../index.php">Iniciar sesión</a></p>  
            <p><a href="../view/cliente/register.php">Registrarse</a></p>
        </div>
        </form>
    </div>
    </body>
    </html>
