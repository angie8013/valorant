<?php
include("../../db/conection.php");
$db = new Database();
$con = $db->conectar();
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    echo '<script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../../index.php";
          </script>';
    session_destroy();
    die();
}

// Obtener el usuario de la sesión
$username = $_SESSION['username'];

// Consulta SQL para obtener la información del usuario y su agente, incluyendo la imagen del agente
$sql = "SELECT j.username, a.nombre AS nombre_agente, a.agente AS imagen_agente 
        FROM jugador j 
        INNER JOIN agente a ON j.id_avatar = a.id_agente 
        WHERE j.username = :username";

// Preparar la consulta
$stmt = $con->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener los datos del resultado
$username = $result['username'];
$nombre_agente = $result['nombre_agente'];
$imagen_agente = $result['imagen_agente'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Profile Card UI Design | CoderGirl</title>
  <!---Custom Css File!--->
  <link rel="stylesheet" href="../../css/ganador.css">
  <style>
    /* Estilo para el GIF */
    #gif {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      z-index: 9999; /* Asegura que el GIF esté en la parte superior */
    }
  </style>
  <script>
    // Función para ocultar el GIF y mostrar la interfaz después de un cierto tiempo
    window.onload = function() {
      var gif = document.getElementById('gif');
      gif.src = gif.dataset.src; // Carga el GIF
      gif.style.display = 'block'; // Muestra el GIF

      // Oculta el GIF después de 5 segundos
      setTimeout(function() {
        gif.style.display = 'none'; // Oculta el GIF
        document.body.style.overflow = 'auto'; // Restaura el desplazamiento de la página
      }, 5000); // 5000 milisegundos = 5 segundos
    };
  </script>
</head>
<body style="overflow: hidden;"> <!-- Oculta el desplazamiento de la página mientras se muestra el GIF -->
  <!-- GIF que ocupa toda la pantalla -->
  <img id="gif" src="" alt="GIF" data-src="../../img/finish.gif" style="display: none;">

  <!-- Resto de tu interfaz aquí -->
  <section class="main">
    <div class="profile-card">
      <!-- Mostrar la imagen del agente -->
      <img src="data:image/jpeg;base64,<?php echo base64_encode($imagen_agente); ?>" alt="Imagen del Agente" width="200" height="200">
      <div class="data">
        <!-- Mostrar el texto "GANADOR" de manera exótica -->
        <h2 style="font-family: 'Poppins', sans-serif; font-size: 48px; font-weight: bold; color: #fff; margin-top: 20px;">GANADOR</h2>
        <!-- Mostrar el username del ganador -->
        <h2><?php echo $username; ?></h2>
        <!-- Mostrar el nombre del Agente -->
        <span><?php echo $nombre_agente; ?></span>
        <a href="index.php"><button style="margin-top: 20px;">Continuar</button></a>

      </div>
    </div>
  </section>
</body>
</html>
</body>
</html>

