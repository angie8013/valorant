<?php
require_once("../../db/conection.php");

$db = new Database();
$con = $db->conectar();

session_start();

// Manejo del cierre de sesión
if (isset($_POST['btncerrar'])) {
    session_destroy();
    header('Location: ../../index.php');
    exit(); 
}

// Conexión y validación de sesión
$db = new Database();
$conexion = $db->conectar();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    echo '<script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../../index.php";
          </script>';
    session_destroy();
    die();
}
try {
    // Crear una instancia de la clase Database
    $db = new Database();
    // Establecer la conexión
    $conexion = $db->conectar();

    // Preparar y ejecutar la consulta
    $consultaUsuario = $conexion->prepare("SELECT username FROM jugador WHERE username = :username");

    $consultaUsuario->bindParam(':username', $_SESSION['username']);
    $consultaUsuario->execute();
    $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

    // Comprobar si se obtuvo el usuario correctamente
    if (!$usuario) {
        throw new Exception("El usuario no fue encontrado en la base de datos");
    }

    $nombreUsuario = $usuario['username'];
} catch (PDOException $e) {
    // Manejar errores de PDO
    echo "Error de PDO: " . $e->getMessage();
} catch (Exception $e) {
    // Manejar otros tipos de errores
    echo "Error: " . $e->getMessage();
}

$puntos = $conexion->prepare("SELECT jugador.puntos AS puntos_jugador, agente.*, jugador.*, puntos.* FROM jugador INNER JOIN puntos ON jugador.id_puntos = puntos.id_puntos INNER JOIN agente ON jugador.id_avatar = agente.id_agente WHERE jugador.username = :username");
$puntos->bindParam(':username', $_SESSION['username']);
$puntos->execute();
$info = $puntos->fetch(PDO::FETCH_ASSOC);

$foto = $info['agente'];
$rango = $info['rango_img'];
$puntos_jugador = $info['puntos_jugador']; // Aquí están los puntos del jugador de la tabla "jugador"

// Verificar si los puntos del jugador coinciden con los puntos en la tabla puntos
if ($puntos_jugador != $info['puntos']) {
    // Obtener el id_puntos correspondiente
    $id_puntos_stmt = $conexion->prepare("SELECT id_puntos FROM puntos WHERE puntos = :puntos");
    $id_puntos_stmt->bindParam(':puntos', $puntos_jugador);
    $id_puntos_stmt->execute();
    $id_puntos = $id_puntos_stmt->fetchColumn();

    if ($id_puntos) {
        // Actualizar la columna id_puntos en la tabla jugador
        $update_id_puntos_stmt = $conexion->prepare("UPDATE jugador SET id_puntos = :id_puntos WHERE username = :username");
        $update_id_puntos_stmt->bindParam(':id_puntos', $id_puntos);
        $update_id_puntos_stmt->bindParam(':username', $_SESSION['username']);
        $update_id_puntos_stmt->execute();
    }
}

$conexion = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VALORANT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>
    <video src="../../img/lobby.mp4" autoplay="true" muted="true" loop="true" geter="../img/lobby.mp4"></video>
    <div class="sidebar">
        <div class="top-section">
            <div class="logo">
            <?php echo "<img src='data:image/jpeg; base64," . base64_encode($foto) . "'>"; ?>
                <h1 class="logo-text">
                <?php echo $usuario['username'] ?>
                </h1>               
            </div>
            <div class="sidebar-toggle-btn">
                <i class="fas fa-angles-right"></i>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="top-menu">
             <a href="mapa.php" class="sidebar-link">
                <i class='bx bxs-joystick' ></i>
                    <span>Jugar</span>
             </a>
          
             <a href="avatar.php" class="sidebar-link">
                <i class='bx bxs-bot'></i>
                <span>Agentes</span>
             </a> 
             <a href="" class="sidebar-link">
             <i class='bx bx-line-chart'></i>
                <span>Estadisticas</span>
             </a>
           
            </div>

            <form action="" method="post" class="sidebar-link">
        <button type="submit" name="btncerrar" class="btn-cerrar">
            <i class="fas fa-arrow-right-from-bracket"></i>
            <span>Cerrar Sesión</span>
        </button>
    </form>

          </div>
        
        </div>
    
    </div>
    <div class="puntos">
    <div class="puntos_v">
        <h1>Puntos:
        <?php echo $info['puntos_jugador']; ?>
        </h1>
    </div>

    <div class="puntos_v">
        <h1>Nivel:
        <?php echo $info['id_puntos']; ?>
        </h1>
    </div>
    <div class="puntos_v">
        <h1>Rango:
        <?php echo $info['rango']; ?>
        </h1>
    </div>
        <div class="rango_img">
        <?php echo "<img src='data:image/jpeg; base64," . base64_encode($rango) . "'>"; ?>
        </div>

</div>


    <script type="text/javascript">

        document.querySelector(".sidebar-toggle-btn").addEventListener("click", () => {
            document.querySelector(".sidebar").classList.toggle("active");
        });

    </script>
</body>
</html>