<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <link rel="stylesheet" href="css/register.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="login">
  <div class="recuadro">
    <form method="post" action="controller/inicio.php">
      <img src="img/Valorant-Logo_rojo.png" alt="" class="img">
      <div class="input-box">
        <input type="text" id="username" name="username" placeholder="username" required>
        <i class='bx bxs-game'></i>  
        </div>
      <div class="input-box">
        <input type="password" placeholder="Contraseña" name="contrasena" required>
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <div class="remember-forgot">
        <a href="#">Contraseña olvidada</a>
      </div>
      <button class="valorant-btn" type="submit" name="validar">
        <span class="underlay">
         <span class="label"><input type="hidden" name="validar">Iniciar sesión</span>
        </span>
      </button>
      <div class="register-link">
        <p>No tienes cuenta? <a href="./view/cliente/register.php">Registrarse</a></p>
      </div>
    </form>
  </div>
</body>
</html>