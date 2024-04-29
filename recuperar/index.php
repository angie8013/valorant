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
    <form method="post" action="contra.php">
    <center><h2>Recuperar Contraseña</h2></center>
      <div class="input-box">
        <input type="text" id="username" name="username" placeholder="username" required>
        <i class='bx bxs-game'></i>  
        </div>
      <div class="input-box">
        <input type="password" placeholder="Contraseña" name="contrasena" required>
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <button class="valorant-btn" type="submit" name="validar">
        <span class="underlay">
         <span class="label"><input type="hidden" name="validar">Enviar </span>
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