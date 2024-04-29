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

// Verificar si el id_detalle está presente en la URL
if (isset($_GET['id_detalle'])) {
    $id_detalle = $_GET['id_detalle'];

    // Consultar si el id_detalle está presente en la tabla detalle_batalla
    $stmt_verificar_detalle = $con->prepare("SELECT COUNT(*) FROM detalle_batalla WHERE id_detalle = :id_detalle");
    $stmt_verificar_detalle->bindParam(':id_detalle', $id_detalle);
    $stmt_verificar_detalle->execute();
    $existe_detalle = $stmt_verificar_detalle->fetchColumn();

    if ($existe_detalle == 0) {
        // Si el id_detalle no se encuentra en la tabla detalle_batalla, mostrar un mensaje y redirigir
        echo '<script>
                alert("Usted ha sido eliminado.");
                window.location = "mapa.php";
              </script>';
        exit(); // Asegurar la salida después de la redirección
    }

    // Contar el número de registros para el id_sala correspondiente al id_detalle
    $stmt_contar_registros = $con->prepare("SELECT COUNT(*) FROM detalle_batalla WHERE id_sala = (SELECT id_sala FROM detalle_batalla WHERE id_detalle = :id_detalle)");
    $stmt_contar_registros->bindParam(':id_detalle', $id_detalle);
    $stmt_contar_registros->execute();
    $num_registros = $stmt_contar_registros->fetchColumn();

    if ($num_registros == 1) {
        // Si solo hay un registro para el id_sala, mostrar un mensaje de ganador
        echo '<script>
                alert("¡Felicidades! Usted es el ganador.");
                window.location = "ganador.php";
              </script>';
        // Eliminar el id_detalle después de mostrar el aviso de ganador
        $stmt_eliminar_detalle = $con->prepare("DELETE FROM detalle_batalla WHERE id_detalle = :id_detalle");
        $stmt_eliminar_detalle->bindParam(':id_detalle', $id_detalle);
        $stmt_eliminar_detalle->execute();
    }
    
} else {
    // Si no se proporcionó el id_detalle en la URL, redirigir a alguna página de error
    echo '<script>
            alert("ID de detalle no proporcionado.");
            window.location = "mapa.php";
            
          </script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/armas.css">
    <link rel="stylesheet" href="../../css/armas.css">
    <link rel="icon" href="../../img/icono_valo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <title>SELECCIÓN DE ARMAS</title>
</head>

<body>
    <div class="container">
        <h1>ARMAS</h1>

        <div class="card__container">
            <?php
            // Consultar las armas del mismo nivel que el jugador
            $username = $_SESSION['username'];
            $stmt = $con->prepare("SELECT jugador.id_puntos, puntos.nivel 
                                  FROM jugador 
                                  INNER JOIN puntos ON jugador.id_puntos = puntos.id_puntos
                                  WHERE jugador.username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $jugador = $stmt->fetch(PDO::FETCH_ASSOC);
            $nivel_jugador = $jugador['nivel'];

            $puntos = $con->prepare("SELECT * FROM arma WHERE nivel = :nivel_jugador");
            $puntos->bindParam(':nivel_jugador', $nivel_jugador);
            $puntos->execute();

            if ($puntos->rowCount() > 0) {
                // Mostrar las tarjetas de armas
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
                // Si no hay armas disponibles
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
   <script>
    // Recargar la página cada 3 segundos de manera suave
    setInterval(function() {
        // Obtener el scrollTop actual antes de recargar la página
        var scrollTop = window.scrollY || document.documentElement.scrollTop;

        // Recargar la página
        location.reload();

        // Restaurar el scrollTop después de la recarga para mantener la posición de desplazamiento
        window.scrollTo(0, scrollTop);
    }, 3000);
</script>

</body>

</html>
