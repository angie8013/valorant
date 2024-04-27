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

// Verificar si se recibió el id_detalle a través de la URL
if(isset($_GET['id_detalle'])) {
    $id_detalle = $_GET['id_detalle'];
    $username = $_SESSION['username'];

    try {
        // Obtener los jugadores atacantes con el mismo id_sala excluyendo al usuario que inició sesión
        $stmt_jugadores = $con->prepare("SELECT DISTINCT id_jugador_atacante FROM detalle_batalla WHERE id_sala = (SELECT id_sala FROM detalle_batalla WHERE id_detalle = :id_detalle) AND id_jugador_atacante <> :username");
        $stmt_jugadores->bindParam(':id_detalle', $id_detalle);
        $stmt_jugadores->bindParam(':username', $username);
        $stmt_jugadores->execute();

        // Generar el elemento select HTML con los jugadores atacantes
        echo '<select name="jugadores_atacantes">';
        while ($row = $stmt_jugadores->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $row['id_jugador_atacante'] . '">' . $row['id_jugador_atacante'] . '</option>';
        }
        echo '</select>';
    } catch(PDOException $e) {
        // Si hay algún error, redireccionar a la página de error con un mensaje específico
        $error_message = $e->getMessage();
        header("Location: pagina_de_error.php?error_message=$error_message");
        exit();
    }
} else {
    // Si no se recibió el id_detalle, redireccionar a alguna página de error
    header("Location: pagina_de_error.php");
    exit();
}
?>
