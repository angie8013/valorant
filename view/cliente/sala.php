<?php
session_start();
//conectar bd
require_once("../../db/conection.php");
$db = new Database();
$con = $db->conectar();
date_default_timezone_set('America/Bogota');

$tiempo = date("Y-m-d H:i:s", strtotime("-5 minutes"));

try {
    // Eliminar las salas que hayan excedido el límite de tiempo
    $stmt_delete = $con->prepare("DELETE FROM sala WHERE tiempo < :tiempo");
    $stmt_delete->bindParam(':tiempo', $tiempo);
    $stmt_delete->execute();
} catch(PDOException $e) {
    // Manejar errores si es necesario
    echo "Error: " . $e->getMessage();
}

try {

    // Consulta para obtener los IDs de las salas
    $sql = "SELECT sala.id_sala, mundo.nombre AS nombre FROM sala INNER JOIN mundo ON sala.id_mundo = mundo.id_mundo WHERE sala.nivel = (SELECT id_puntos FROM jugador WHERE username = :username)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $_SESSION['username']); // Aquí debes proporcionar el ID del jugador
    $stmt->execute();

    // Contar el número de filas devueltas
    $num_rows = $stmt->rowCount();

    // Generar tarjetas para cada ID de sala obtenido de la base de datos

?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Botones Rojos</title>
<link rel="stylesheet" href="../../css/sala.css">
</head>
<body>

<a href="mapa.php"><button>CREAR SALA</button></a>

<div class="container">
    <?php
    // Generar tarjetas para cada ID de sala obtenido de la base de datos
    if ($num_rows > 0) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_sala = $row["id_sala"];
            $nombre = $row["nombre"];
            echo '<div class="card">';
            echo '<h2>Sala ' . $id_sala . '</h2>';
            // Puedes agregar más detalles de la sala aquí si es necesario
            echo '<p>Detalles de la sala ' . $nombre . '.</p>';
            echo '<form action="select_sala.php" method="post">';
            echo '<input type="hidden" name="id_sala" value="' . $id_sala . '">';
            echo '<button class="card__button" type="submit" name="elegir_sala">Elegir sala</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "No se encontraron salas.";
    }
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

// Cerrar la conexión
$con = null; // Cerrar la conexión
    ?>
</div>

</body>
</html>
