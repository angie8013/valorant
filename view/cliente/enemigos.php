<?php
// Establecer conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "valorant";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Generar el elemento select HTML
echo "<select name='jugadores'>";
echo "<option value=''>Seleccionar Jugador</option>";

if (isset($_GET['id_detalle']) && isset($_POST['id_sala'])) {
    $id_sala = $_POST["id_sala"];
    $id_detalle = $_GET["id_detalle"];
    $username = $_SESSION['username'];
    
    try { 
        // Consulta SQL para obtener los jugadores con el mismo id_sala
        $sql = "SELECT DISTINCT id_jugador_atacante FROM detalle_batalla WHERE id_sala = '$id_sala'";
        $result = $conn->query($sql);

        // Generar opciones para cada jugador
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id_jugador_atacante'] . "'>" . $row['id_jugador_atacante'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay jugadores disponibles</option>";
        }
    } catch (Exception $e) {
        echo "<option value=''>Error al cargar jugadores</option>";
    }
} else {
    // Mostrar mensaje cuando no se ha enviado el formulario o no se proporciona id_sala
    echo "<option value=''>No se ha seleccionado sala</option>";
}

echo "</select>";

// Cerrar la conexión
$conn->close();
?>
