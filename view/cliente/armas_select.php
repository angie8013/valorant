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
if(isset($_POST['valor'])) {
    $id_arma = $_POST["valor"];
    $username = $_SESSION['username'];
    // Obtener el id_detalle pasado en la URL
    if(isset($_GET['id_detalle'])) {
        $id_detalle = $_GET['id_detalle'];

        try {
            // Actualizar el registro en la tabla "detalle_arma" con el ID del arma seleccionado
            $stmt_actualizar_arma = $con->prepare("UPDATE detalle_batalla SET id_arma = :id_arma WHERE id_detalle = :id_detalle");
            $stmt_actualizar_arma->bindParam(':id_arma', $id_arma);
            $stmt_actualizar_arma->bindParam(':id_detalle', $id_detalle);
            $stmt_actualizar_arma->execute();

            // Obtener el valor de 'dano' del arma seleccionado
            $stmt_dano_arma = $con->prepare("SELECT dano FROM arma WHERE id_arma = :id_arma");
            $stmt_dano_arma->bindParam(':id_arma', $id_arma);
            $stmt_dano_arma->execute();
            $dano_arma = $stmt_dano_arma->fetchColumn();

            // Obtener el id_jugador_atacado
            $stmt_jugador_atacado = $con->prepare("SELECT id_jugador_atacado FROM detalle_batalla WHERE id_detalle = :id_detalle");
            $stmt_jugador_atacado->bindParam(':id_detalle', $id_detalle);
            $stmt_jugador_atacado->execute();
            $id_jugador_atacado = $stmt_jugador_atacado->fetchColumn();

            // Obtener la vida actual del jugador atacado
            $stmt_vida_jugador_atacado = $con->prepare("SELECT puntos_vida FROM detalle_batalla WHERE id_jugador_atacado = :id_jugador_atacado AND id_detalle = :id_detalle");
            $stmt_vida_jugador_atacado->bindParam(':id_jugador_atacado', $id_jugador_atacado);
            $stmt_vida_jugador_atacado->bindParam(':id_detalle', $id_detalle);
            $stmt_vida_jugador_atacado->execute();
            $vida_jugador_atacado = $stmt_vida_jugador_atacado->fetchColumn();

            // Calcular la nueva vida del jugador atacado después del ataque
            $nueva_vida_jugador_atacado = $vida_jugador_atacado - $dano_arma;

            // Actualizar la vida del jugador atacado en la tabla 'detalle_batalla'
            $stmt_actualizar_vida = $con->prepare("UPDATE detalle_batalla SET puntos_vida = :nueva_vida_jugador_atacado WHERE id_jugador_atacado = :id_jugador_atacado AND id_detalle = :id_detalle");
            $stmt_actualizar_vida->bindParam(':nueva_vida_jugador_atacado', $nueva_vida_jugador_atacado);
            $stmt_actualizar_vida->bindParam(':id_jugador_atacado', $id_jugador_atacado);
            $stmt_actualizar_vida->bindParam(':id_detalle', $id_detalle);
            $stmt_actualizar_vida->execute();

            // Mostrar mensaje de alerta
            echo '<script>
                    alert("Se ha hecho ' . $dano_arma . ' de daño a ' . $id_jugador_atacado . '");
                    window.location = "armas.php?id_detalle=' . $id_detalle . '";
                  </script>';
            exit(); // Asegurarse de salir del script después de la redirección
        } catch(PDOException $e) {
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
