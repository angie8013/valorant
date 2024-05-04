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

// Obtener el usuario de la sesión
$username = $_SESSION['username'];

// Consulta SQL para obtener los datos con nombres en lugar de IDs
$sql = "SELECT db.id_detalle, db.id_jugador_atacante, db.id_jugador_atacado, db.id_sala, 
               m.nombre AS nombre_mundo, a.nombre AS nombre_agente, 
               ar.nombre AS nombre_arma, db.puntos_vida, e.estado
        FROM detalles db
        LEFT JOIN mundo m ON db.id_mundo = m.id_mundo
        LEFT JOIN agente a ON db.id_agente = a.id_agente
        LEFT JOIN arma ar ON db.id_arma = ar.id_arma
        LEFT JOIN estado e ON db.id_estado = e.id_estado
        WHERE db.id_jugador_atacante = :username
        ORDER BY db.id_detalle DESC";

// Preparar la consulta
$stmt = $con->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/tabla_detalle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/icono_valo.png" type="image/x-icon">
    <title>Estadisticas</title>
</head>

<body>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Atacante</th>
                    <th>Atacado</th>
                    <th>Sala</th>
                    <th>Mundo</th>
                    <th>Arma</th>
                    <th>Agente</th>
                    <th>Vida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Iterar sobre los resultados de la consulta
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>".$row['id_detalle']."</td>";
                    echo "<td>".$row['id_jugador_atacante']."</td>";
                    echo "<td>".$row['id_jugador_atacado']."</td>";
                    echo "<td>".$row['id_sala']."</td>";
                    echo "<td>".$row['nombre_mundo']."</td>";
                    echo "<td>".$row['nombre_arma']."</td>";
                    echo "<td>".$row['nombre_agente']."</td>";
                    echo "<td>".$row['puntos_vida']."</td>";
                    echo "<td>".$row['estado']."</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
