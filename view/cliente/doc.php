<?php
include("../../db/conexion.php");
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    echo '<script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../index.php";
          </script>';
    session_destroy();
    die();
}

// Verificar si se reciben los parámetros necesarios
if(isset($_POST['valor'])) {
    $id_avatar = $_POST['valor'];
    $username = $_SESSION['username'];

    // Actualizar la tabla de usuarios con el id del avatar seleccionado
    $consulta = $conexion->prepare("UPDATE jugador SET id_avatar = ? WHERE username = ?");
    if ($consulta === false) {
        die("Error de preparación de consulta: " . $conexion->error);
    }
    
    $consulta->bind_param('is', $id_avatar, $username);
    if (!$consulta->execute()) {
        die("Error al ejecutar la consulta: " . $consulta->error);
    }
    
    echo '<script>
    alert("se guardo correctamente");
    window.location = "index.php";
  </script>';
    exit();
} else {
    // Si no se reciben los parámetros necesarios, redireccionar a una página de error o manejar el error de otra manera.
    echo "Error: Parámetros faltantes";
}
?>