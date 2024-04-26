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
    
    // Obtener el id_detalle pasado en la URL
    if(isset($_GET['id_detalle'])) {
        $id_detalle = $_GET['id_detalle'];

        try {
            // Actualizar el registro en la tabla "detalle_arma" con el ID del arma seleccionado
            $stmt_actualizar_arma = $con->prepare("UPDATE detalle_batalla SET id_arma = :id_arma WHERE id_detalle = :id_detalle");
            $stmt_actualizar_arma->bindParam(':id_arma', $id_arma);
            $stmt_actualizar_arma->bindParam(':id_detalle', $id_detalle);
            $stmt_actualizar_arma->execute();

            // Redireccionar a una página de éxito o cualquier otra página
            header("Location: armas.php?id_detalle=$id_detalle");
            exit();
        } catch(PDOException $e) {
            // Si hay algún error, redireccionar a una página de error
            header("Location: pagina_de_error.php");
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

$stmt = $con->prepare("SELECT DISTINCT id_jugador_atacante FROM detalle_batalla WHERE id_sala IN (SELECT id_sala FROM detalle_batalla WHERE id_jugador_atacante = :username) AND id_jugador_atacante != :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
?>
