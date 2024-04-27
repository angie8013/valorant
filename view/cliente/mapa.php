<?php
include("../../db/conection.php");
$db = new Database();
$con = $db->conectar();
session_start();

if (!isset($_SESSION['username'])) {
    echo '<script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../../index.php";
          </script>';
    session_destroy();
    die();
}

try {
    // Consulta para obtener los IDs de las salas y sus respectivos mundos
    $sql = "SELECT sala.id_sala, mundo.nombre AS nombre_mundo, mundo.mundo AS imagen_mundo 
            FROM sala 
            INNER JOIN mundo ON sala.id_mundo = mundo.id_mundo 
            WHERE sala.nivel = (SELECT id_puntos FROM jugador WHERE username = :username)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();

    // Contar el número de filas devueltas
    $num_rows = $stmt->rowCount();

    // Generar tarjetas para cada ID de sala obtenido de la base de datos
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/mapa.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>MAPAS</h1>
        <div class="card__container">

            <?php
            // Generar tarjetas para cada ID de sala obtenido de la base de datos
            if ($num_rows > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_sala = $row["id_sala"];
                    $nombre_mundo = $row["nombre_mundo"];
                    $imagen_mundo = $row["imagen_mundo"];
            ?>
                    <article class="card__article">
                        <img src='data:image/jpeg;base64,<?php echo base64_encode($imagen_mundo); ?>' alt="Mapa de <?php echo $nombre_mundo; ?>">
                        <div class="card__data">
                            <div class="card__description">
                                <h2 class="card-title">Mapa nivel <?php echo $id_sala; ?></h2>
                                <strong class="card-text">Nombre: <?php echo $nombre_mundo; ?></strong> <br>
                                <form action="select_sala.php" method="post">
                                    <input type="hidden" name="id_sala" value="<?php echo $id_sala; ?>">
                                    <button class="card__button" type="submit" name="elegir_sala">Elegir sala</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php
                }
            } else {
                // No se encontraron resultados
                echo "No hay mapas registrados.";
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        // Cerrar la conexión
        $con = null; // Cerrar la conexión
        ?>
        </div>
    </div>
</body>

</html>
