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

if (isset($_POST['valor'])) {
    $id_mapa = $_POST["valor"];
    $username = $_SESSION['username'];

    try {
        // Obtener los puntos del jugador
        $stmt = $con->prepare("SELECT id_puntos FROM jugador WHERE username = :username");
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $puntos = $row["id_puntos"];

        // Insertar el nuevo registro en la tabla "sala"
        $stmt_sala = $con->prepare("INSERT INTO sala (id_mundo, nivel) VALUES (:id_mundo, :nivel)");
        $stmt_sala->bindParam(':id_mundo', $id_mapa);
        $stmt_sala->bindParam(':nivel', $puntos); // Usar los puntos como nivel
        $stmt_sala->execute();

        // Obtener el id_sala recién insertado
        $id_sala = $con->lastInsertId();

        // Insertar el nuevo registro en la tabla "detalle_batalla"
        $stmt_detalle = $con->prepare("INSERT INTO detalle_batalla (id_sala, id_mundo, id_jugador_atacante) VALUES (:id_sala, :id_mundo, :username)");
        $stmt_detalle->bindParam(':id_sala', $id_sala);
        $stmt_detalle->bindParam(':id_mundo', $id_mapa);
        $stmt_detalle->bindParam(':username', $_SESSION['username']);
        $stmt_detalle->execute();

        // Obtener el id_detalle recién insertado
        $id_detalle = $con->lastInsertId();

        // Cerrar la conexión
        $con = null;

        // Redireccionar a la página de selección de armas, pasando el id_detalle como parámetro
        header("Location: armas.php?id_detalle=$id_detalle");
        exit();
    } catch(PDOException $e) {
        // Si hay algún error, redireccionar a una página de error
        header("Location: pagina_de_error.php");
        exit();
    }
} else {
    // Si no se recibió la solicitud del formulario, redireccionar a alguna página de error
    header("Location: pagina_de_error.php");
    exit();
}
?>





