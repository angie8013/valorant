<?php
include("../db/conexion.php");

date_default_timezone_set('America/Bogota');

$sql = "SELECT username, ult_ini FROM jugador WHERE id_estado = 1";
$result = $conexion->query($sql);

while ($fila = $result->fetch_assoc()) {
    $ult_inicio = strtotime($fila['ult_ini']);
    $username= $fila['username'];
    $tiempo_actual = time();
    $diferencia_tiempo = $tiempo_actual - $ult_inicio;
    $dias_inactivos = floor($diferencia_tiempo / (60 * 60 * 24)); 
    if ($dias_inactivos >= 10) {
        // Cambiar id_estado a 2 si han pasado 10 días de inactividad
        $sql_update = "UPDATE jugador SET id_estado = 2 WHERE username= ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("s", $username);
        $stmt_update->execute();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $contrasena = $_POST['contrasena'];
  $contrasena = hash('sha512', $contrasena);

  $sql = "SELECT * FROM jugador WHERE jugador.username = ? AND jugador.contrasena = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ss", $username, $contrasena);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows == 1) {
    session_start();
    $fila = $resultado->fetch_assoc();
    $_SESSION['username'] = $username;
    
    
 
    if ($fila['id_rol'] == 2 && $fila['id_estado'] == 2) {

      echo '<script>
      alert("Tu cuenta está pendiente de activación. Por favor, espera a que sea activada por un administrador.");
      window.location = "../index.php";
    </script>';
      exit();
    }
    
    
    $fecha_inicio = date('Y-m-d H:i:s'); 
    $username= $fila['username'];
      
    // Actualizar la fecha y hora actual
    $sql_update = "UPDATE jugador SET ult_ini = ? WHERE username= ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("si", $fecha_inicio, $id_jugador);
    $stmt_update->execute();
      
    // Redirigir según el id_rol
    if ($fila['id_rol'] == 1) {
      header("location: ../view/admin/index.php");
    } elseif ($fila['id_rol'] == 2) {
      header("location: ../view/cliente/index.php");
    } else {
      header("location: ../index.php"); 
    }
    exit();
  } else {
    $mensaje_error = "Credenciales incorrectas";
    header("location: ../index.php");
    exit();
  }
}
?>

