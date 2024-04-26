<?php
session_start();

if (!isset($_SESSION['id_jugador'], $_SESSION['username'])) {
    // Si la sesión no está iniciada, retorna un mensaje de error
    echo 'Error: Debes iniciar sesión antes de acceder a esta funcionalidad.';
    exit();
}

// Verifica si se recibieron los datos necesarios
if (isset($_POST['userId'], $_POST['currentState'])) {
    // Obtiene los datos enviados por la solicitud AJAX
    $userId = $_POST['userId'];
    $currentState = $_POST['currentState'];

    // Realiza la actualización en la base de datos según el estado actual
    require_once("../../db/conection.php");
    $db = new Database();
    $con = $db->conectar();

    if ($currentState === 'Activar') {
        $sql = "UPDATE jugador SET id_estado = 1 WHERE id_jugador = ?";
    } else {
        $sql = "UPDATE jugador SET id_estado = 2 WHERE id_jugador = ?";
    }
    

    $stmt = $con->prepare($sql);
    $stmt->execute([$userId]);

    // Retorna el nuevo estado del jugador
    echo ($currentState === 'Activar') ? 'Desactivar' : 'Activar';
} else {
    // Si no se recibieron los datos necesarios, retorna un mensaje de error
    echo 'Error: No se recibieron los datos necesarios.';
}

