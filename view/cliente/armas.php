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

// Verificar si el estado de la batalla ha superado el límite de tiempo
$stmt_update_estado = $con->prepare("UPDATE detalle_batalla SET id_estado = 4 WHERE hora_acc <= NOW() - INTERVAL 5 MINUTE");
$stmt_update_estado->execute();

// Verificar si el estado es igual a 4
$stmt_estado_actualizado = $con->prepare("SELECT id_estado FROM detalle_batalla WHERE id_detalle = :id_detalle");
$stmt_estado_actualizado->bindParam(':id_detalle', $id_detalle);
$stmt_estado_actualizado->execute();
$id_estado_actualizado = $stmt_estado_actualizado->fetchColumn();

if ($id_estado_actualizado == 4) {
    // Si el estado es igual a 4, significa que el límite de tiempo ha sido superado
    echo '<script>
            alert("Te hemos expulsado de la sala. Has pasado el límite de tiempo.");
            window.location = "mapa.php"; 
          </script>';
    exit(); // Asegurarse de salir del script después de la redirección
}

// Verificar si el id_detalle está presente en la tabla detalle_batalla
$stmt_verificar_detalle = $con->prepare("SELECT COUNT(*) FROM detalle_batalla WHERE id_detalle = :id_detalle");
$stmt_verificar_detalle->bindParam(':id_detalle', $id_detalle);
$stmt_verificar_detalle->execute();
$existe_detalle = $stmt_verificar_detalle->fetchColumn();

if ($existe_detalle == 0) {
    // Si el id_detalle no se encuentra en la tabla detalle_batalla, redirigir a mapa.php
    echo '<script>
            alert("Lo siento, has sido eliminado.");
            window.location = "mapa.php"; 
          </script>';
    exit(); // Asegurarse de salir del script después de la redirección
}

// Obtener el nivel del jugador
$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT jugador.id_puntos, puntos.nivel 
                      FROM jugador 
                      INNER JOIN puntos ON jugador.id_puntos = puntos.id_puntos
                      WHERE jugador.username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$jugador = $stmt->fetch(PDO::FETCH_ASSOC);
$nivel_jugador = $jugador['nivel'];

// Consulta para obtener las armas del mismo nivel que el jugador
$puntos = $con->prepare("SELECT * FROM arma WHERE nivel = :nivel_jugador");
$puntos->bindParam(':nivel_jugador', $nivel_jugador);
$puntos->execute();

// Consulta para obtener los id_detalle de la tabla detalle_batalla
$punto = $con->prepare("SELECT * FROM detalle_batalla WHERE id_sala = :username");
$punto->bindParam(':username', $username);
$punto->execute();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/armas.css">
    <link rel="stylesheet" href="../../css/armas.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>ARMAS</h1>

        <div class="card__container">
            <?php
            if ($puntos->rowCount() > 0) {
                // Iteramos sobre cada resultado para mostrar las tarjetas
                while ($info = $puntos->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <article class="card__article">
                        <?php echo "<img src='data:image/jpeg; base64," . base64_encode($info['arma']) . "'>"; ?>
                        <div class="card__data">
                            <div class="card__description">
                                <h2 class="card-title"><?php echo $info['nombre']; ?></h2>
                                <strong class="card-text">Daño: <?php echo $info['dano']; ?></strong> <br>
                                <strong class="card-text">Balas: <?php echo $info['balas']; ?></strong><br>
                                <strong class="card-text">Nivel: <?php echo $info['nivel']; ?></strong>
                                <!-- Agrega más información de ser necesario -->
                                <form method="post" action="armas_select.php?id_detalle=<?php echo $_GET['id_detalle']; ?>">
                                    <input type="hidden" name="id_detalle" value="<?php echo $_GET['id_detalle']; ?>">
                                    <button class="btn-av" type="submit" name="valor" value="<?php echo $info['id_arma']; ?>"> elegir </button>
                                </form>
                            </div>
                        </div>
                    </article>
            <?php
                }
            } else {
                // No se encontraron resultados
                echo "No hay armas registradas.";
            }
            ?>


        </div>

    </div>
    <center>
        <button class="valorant-btn" id="redireccionarBtn">
            <span class="underlay">
                <span class="label">Seleccionar oponente</span>
            </span>
        </button>
    </center>

    <script>
        // Función para redireccionar al mismo id_detalle
        document.getElementById("redireccionarBtn").addEventListener("click", function() {
            // Obtener el id_detalle de la URL actual
            var urlParams = new URLSearchParams(window.location.search);
            var id_detalle = urlParams.get('id_detalle');

            // Redireccionar a enemigos.php con el mismo id_detalle
            window.location.href = "enemigos.php?id_detalle=" + id_detalle;
        });
    </script>
</body>

</html>