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

// Verificar si se recibió el valor del arma seleccionada enviado desde el formulario
if (isset($_POST['valor'])) {
    $id_arma = $_POST["valor"];
    $username = $_SESSION['username'];
    // Obtener el id_detalle pasado en la URL
    if (isset($_GET['id_detalle'])) {
        $id_detalle = $_GET['id_detalle'];

        try {
            // Actualizar el registro en la tabla "detalle_arma" con el ID del arma seleccionado
            $stmt_actualizar_arma = $con->prepare("UPDATE detalle_batalla SET id_arma = :id_arma WHERE id_detalle = :id_detalle");
            $stmt_actualizar_arma->bindParam(':id_arma', $id_arma);
            $stmt_actualizar_arma->bindParam(':id_detalle', $id_detalle);
            $stmt_actualizar_arma->execute();

            $stmt_info_jugador = $con->prepare("SELECT id_jugador_atacado FROM detalle_batalla WHERE id_detalle = :id_detalle");
            $stmt_info_jugador->bindParam(':id_detalle', $id_detalle);
            $stmt_info_jugador->execute();
            $info_jugador = $stmt_info_jugador->fetch(PDO::FETCH_ASSOC);

            // Obtener el valor de 'dano' y 'puntos' del arma seleccionado
            $stmt_info_arma = $con->prepare("SELECT dano, puntos FROM arma WHERE id_arma = :id_arma");
            $stmt_info_arma->bindParam(':id_arma', $id_arma);
            $stmt_info_arma->execute();
            $info_arma = $stmt_info_arma->fetch(PDO::FETCH_ASSOC);

            // Obtener el id_jugador_atacado
            $stmt_jugador_atacado = $con->prepare("SELECT id_jugador_atacado FROM detalle_batalla WHERE id_detalle = :id_detalle");
            $stmt_jugador_atacado->bindParam(':id_detalle', $id_detalle);
            $stmt_jugador_atacado->execute();
            $id_jugador_atacado = $stmt_jugador_atacado->fetchColumn();

            // Obtener la vida actual del jugador atacado
            $id_jugador_atacado = $info_jugador['id_jugador_atacado'];
            $dano_arma = $info_arma['dano'];
            $puntos_arma = $info_arma['puntos'];

            // Actualizar la vida del jugador atacado
            $stmt_restar_vida = $con->prepare("UPDATE detalle_batalla SET puntos_vida = puntos_vida - :dano_arma WHERE id_jugador_atacante = :id_jugador_atacado");
            $stmt_restar_vida->bindParam(':dano_arma', $dano_arma);
            $stmt_restar_vida->bindParam(':id_jugador_atacado', $id_jugador_atacado);
            $stmt_restar_vida->execute();

            // Sumar los puntos del arma al puntaje del jugador que inició sesión
            $stmt_sumar_puntos = $con->prepare("UPDATE jugador SET puntos = puntos + :puntos_arma WHERE username = :username");
            $stmt_sumar_puntos->bindParam(':puntos_arma', $puntos_arma);
            $stmt_sumar_puntos->bindParam(':username', $username);
            $stmt_sumar_puntos->execute();

            // Actualizar el estado de la batalla si ha pasado el límite de tiempo
            $stmt_update_estado = $con->prepare("UPDATE detalle_batalla SET id_estado = 4 WHERE hora_acc <= NOW() - INTERVAL 5 MINUTE");
            $stmt_update_estado->execute();

            // Verificar si el estado es igual a 4
            $stmt_estado_actualizado = $con->prepare("SELECT id_estado FROM detalle_batalla WHERE id_detalle = :id_detalle");
            $stmt_estado_actualizado->bindParam(':id_detalle', $id_detalle);
            $stmt_estado_actualizado->execute();
            $id_estado_actualizado = $stmt_estado_actualizado->fetchColumn();

            if ($id_estado_actualizado == 4) {
                // Si el estado es igual a 4, significa que el límite de tiempo ha sido superado
                echo '<script>
                        alert("Te hemos expulsado de la sala. Has pasado el límite de tiempo.");
                        window.location = "mapa.php"; 
                      </script>';
                exit(); // Asegurarse de salir del script después de la redirección
            }

            // Mostrar mensaje de alerta
            echo '<script>
                    alert("Se ha hecho ' . $dano_arma . ' de daño a ' . $id_jugador_atacado . '");
                    window.location = "armas.php?id_detalle=' . $id_detalle . '";
                  </script>';
            exit(); // Asegurarse de salir del script después de la redirección
        } catch (PDOException $e) {
            // Si hay algún error, redireccionar a una página de error y mostrar el mensaje de error
            $error_message = $e->getMessage();
            header("Location: pagina_de_error.php?error_message=$error_message");
            exit();
        }
    } else {
        // Si no se recibió el id_detalle, mostrar un mensaje de error o redireccionar a alguna página de error
        echo "Error: ID de detalle no proporcionado";
        exit();
    }
} else {
    // Si no se recibió la solicitud del formulario, redireccionar a alguna página de error
    header("Location: pagina_de_error.php");
    exit();
}
?>
