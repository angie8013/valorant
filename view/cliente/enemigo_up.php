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

// Verificar si se recibieron los datos del formulario
if (isset($_POST['jugador_atacante']) && isset($_POST['id_detalle'])) {
    $id_detalle = $_POST["id_detalle"];
    $id_jugador_atacado = $_POST["jugador_atacante"];

    try {
        // Actualizar la columna 'id_jugador_atacado' en la tabla 'detalle_batalla'
        $stmt_actualizar = $con->prepare("UPDATE detalle_batalla SET id_jugador_atacado = :id_jugador_atacado WHERE id_detalle = :id_detalle");
        $stmt_actualizar->bindParam(':id_jugador_atacado', $id_jugador_atacado);
        $stmt_actualizar->bindParam(':id_detalle', $id_detalle);
        $stmt_actualizar->execute();

        // Redireccionar a alguna página de éxito o a donde sea necesario
        header("Location: armas.php?id_detalle=$id_detalle");
        exit();
    } catch(PDOException $e) {
        // Si hay algún error, redireccionar a la página de error con un mensaje específico
        $error_message = $e->getMessage();
        header("Location: pagina_de_error.php?error_message=$error_message");
        exit();
    }
} else {
    // Si no se recibieron los datos del formulario, redireccionar a alguna página de error
    header("Location: pagina_de_error.php");
    exit();
}
?>
