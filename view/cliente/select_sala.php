<?php
include("../../db/conection.php");
$db = new Database();
$con = $db->conectar();
session_start();
date_default_timezone_set('America/Bogota');
// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    echo '<script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../../index.php";
          </script>';
    session_destroy();
    die();
}

if (isset($_POST['elegir_sala']) && isset($_POST['id_sala'])) {
    $id_sala = $_POST["id_sala"];
    $username = $_SESSION['username'];

    try {
        // Contar cuántos registros hay en la tabla detalle_batalla para esta sala
        $stmt_count = $con->prepare("SELECT COUNT(*) AS count FROM detalle_batalla WHERE id_sala = :id_sala");
        $stmt_count->bindParam(':id_sala', $id_sala);
        $stmt_count->execute();
        $row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
        $registro_count = $row_count["count"];

        // Verificar si ya hay 5 registros para esta sala
        if ($registro_count >= 5) {
            // Si hay 5 registros, mostrar un mensaje de error y redireccionar
            echo '<script>
                    alert("La sala ya está llena. Por favor, elija otra sala.");
                    window.location = "mapa.php";
                  </script>';
            exit();
        }

        // Si no hay 5 registros, proceder con el registro del usuario en la sala

        // Obtener los puntos del jugador
        $stmt_puntos = $con->prepare("SELECT id_puntos FROM jugador WHERE username = :username");
        $stmt_puntos->bindParam(':username', $username);
        $stmt_puntos->execute();
        $row = $stmt_puntos->fetch(PDO::FETCH_ASSOC);
        $puntos = $row["id_puntos"];

        // Obtener id_mundo de la tabla sala
        $stmt_id_mundo = $con->prepare("SELECT id_mundo FROM sala WHERE id_sala = :id_sala");
        $stmt_id_mundo->bindParam(':id_sala', $id_sala);
        $stmt_id_mundo->execute();
        $id_mundo = $stmt_id_mundo->fetchColumn();

        // Insertar el nuevo registro en la tabla "detalle_batalla"
        $stmt_id_avatar = $con->prepare("SELECT id_avatar FROM jugador WHERE username = :username");
        $stmt_id_avatar->bindParam(':username', $username);
        $stmt_id_avatar->execute();
        $id_avatar = $stmt_id_avatar->fetchColumn();

        // Insertar el nuevo registro en la tabla "detalle_batalla"
        $stmt_detalle = $con->prepare("INSERT INTO detalle_batalla (id_sala, id_mundo, id_jugador_atacante, id_agente, hora_acc) VALUES (:id_sala, :id_mundo, :username, :id_avatar, NOW())");
        $stmt_detalle->bindParam(':id_sala', $id_sala);
        $stmt_detalle->bindParam(':id_mundo', $id_mundo);
        $stmt_detalle->bindParam(':username', $username);
        $stmt_detalle->bindParam(':id_avatar', $id_avatar);
        $stmt_detalle->execute();

        // Obtener el id_detalle recién insertado
        $id_detalle = $con->lastInsertId();

        // Actualizar el estado de los registros que han pasado más de 5 minutos desde su registro
        $stmt_update_estado = $con->prepare("UPDATE detalle_batalla SET id_estado = 4 WHERE id_sala = :id_sala AND hora_acc <= NOW() - INTERVAL 5 MINUTE");
        $stmt_update_estado->bindParam(':id_sala', $id_sala);
        $stmt_update_estado->execute();

        // Cerrar la conexión
        $con = null;

        // Redireccionar a la página de selección de armas, pasando el id_detalle como parámetro
        header("Location: enemigos.php?id_detalle=$id_detalle");
        exit();
    } catch(PDOException $e) {
        // Si hay algún error, redireccionar a la página de error con un mensaje específico
        $error_message = $e->getMessage();
        header("Location: pagina_de_error.php?error_message=$error_message");
        exit();
    }
} else {
    // Si no se recibió la solicitud del formulario, redireccionar a alguna página de error
    header("Location: pagina_de_error.php");
    exit();
}
?>
