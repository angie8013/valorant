<?php
session_start();



// Verifica si se recibieron los datos necesarios
if (isset($_POST['userId'], $_POST['currentState'])) {
    // Obtiene los datos enviados por la solicitud AJAX
    $userId = $_POST['userId'];
    $currentState = $_POST['currentState'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "valorant";
    
    // Crear conexión
    $con = new mysqli($servername, $username, $password, $dbname);
    

    if ($currentState === 'Activar') {
        $sql = "UPDATE jugador SET id_estado = 1 WHERE username = ?";
    } else {
        $sql = "UPDATE jugador SET id_estado = 2 WHERE username = ?";
    }
    

    $stmt = $con->prepare($sql);
    $stmt->execute([$userId]);

    // Retorna el nuevo estado del jugador
    echo ($currentState === 'Activar') ? 'Desactivar' : 'Activar';
} else {
    // Si no se recibieron los datos necesarios, retorna un mensaje de error
    echo 'Error: No se recibieron los datos necesarios.';
}

