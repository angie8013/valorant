<?php
session_start();
//conectar bd
require_once("../../db/conection.php");
$db = new Database();
$con = $db->conectar();
?>
<?php


if((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg")){
    $correo= $_POST['correo'];
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $contrasena = $_POST['contrasena'];
    $contrasena = hash('sha512', $contrasena);

    $sql=$con -> prepare ("SELECT*FROM jugador where correo = '$correo' or username = '$username'");
    $sql -> execute();
    $fila = $sql -> fetchALL(PDO::FETCH_ASSOC);

    if ($correo=="" || $nombre=="" || $username=="" || $contrasena=="")
    {
    echo '<script>alert("EXISTEN DATOS VACIOS"); </script>';
    echo '<script>window.location = "register.php"</script>';
    }
    else if($fila){
    echo '<script>alert("correo o username ya registrado");</script>';
    echo '<script>window.location = "register.php"</script>';
    }
    else {
  
    $insertSQL = $con->prepare ("INSERT INTO jugador (correo, nombre,username,contrasena)
     VALUES ('$correo', '$nombre', '$username','$contrasena')");
    $insertSQL->execute();
    echo '<script>alert("Registro exitoso"); </script>';
    echo '<script>window.location = "../index.php"</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Registro</title>
  <link rel="stylesheet" href="../../css/register.css">
  <link rel="icon" href="../../img/icono_valo.png" type="image/x-icon">

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  
</head>
<body class="registro">
  <div class="recuadro">
    <form action="#" method="post">
      <img src="../../img/Valorant-Logo_rojo.png" alt="" class="img">
      <div class="input-box">
        <input type="email" id="correo" name="correo" placeholder="Correo" required>
        <i class='bx bx-envelope'></i>  
        </div>
        <div class="input-box">
        <input type="text" id="nombre" name="nombre" placeholder="Nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+" title="Solo se permiten letras" required>
          <i class='bx bxs-user'></i>
        </div>
      <div class="input-box">
        <input type="text" id="username" name="username" placeholder="Username" required>
        <i class='bx bxs-game'></i>
      </div>
      <div class="input-box">
        <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
        <i class='bx bxs-lock-alt' ></i>
      </div>

      <button class="valorant-btn" type="submit" name="validar">
        <span class="underlay">
         <span class="label"><input type="hidden" name="MM_insert" value="formreg">REGISTRARSE</span>
        </span>
      </button>

      <div class="register-link">
        <p>Ya tienes cuenta? <a href="../../index.php">Iniciar sesión</a></p>
      </div>
    </form>
  </div>
</body>
</html>