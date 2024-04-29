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

// Verificar si se recibió el id_detalle a través de la URL
if (isset($_GET['id_detalle'])) {
    $id_detalle = $_GET['id_detalle'];
    $username = $_SESSION['username'];

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

    try {
        // Consulta SQL para obtener los jugadores con el mismo id_sala
        $stmt_jugadores = $con->prepare("SELECT DISTINCT id_jugador_atacante FROM detalle_batalla WHERE id_sala = (SELECT id_sala FROM detalle_batalla WHERE id_detalle = :id_detalle) AND id_jugador_atacante <> :username");
        $stmt_jugadores->bindParam(':id_detalle', $id_detalle);
        $stmt_jugadores->bindParam(':username', $username);
        $stmt_jugadores->execute();

        // Consulta SQL para obtener los agentes
        $stmt_agentes = $con->prepare("SELECT detalle_batalla.id_agente, agente.agente
                                       FROM detalle_batalla
                                       JOIN agente ON detalle_batalla.id_agente = agente.id_agente");
        $stmt_agentes->execute();
    } catch (PDOException $e) {
        // Si hay algún error, redireccionar a la página de error con un mensaje específico
        $error_message = $e->getMessage();
        header("Location: pagina_de_error.php?error_message=$error_message");
        exit();
    }
} else {
    // Si no se recibió el id_detalle, redireccionar a alguna página de error
    header("Location: pagina_de_error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Select Personalizado</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>

<body>
    <div class="contenedor">
        <form action="enemigo_up.php" method="post">
            <div class="selectbox">
                <div class="select" id="select">
                    <div class="contenido-select">
                        <h1 class="titulo">Selecciona a tu oponente</h1>
                    </div>
                    <i class="fas fa-angle-down"></i>
                </div>

                <div class="opciones" id="opciones">
                    <?php
                    // Obtener la información de los agentes
                    while ($info_agente = $stmt_agentes->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="contenido-opcion">
                            <?php
                            // Verificar si hay jugadores disponibles
                            if ($stmt_jugadores->rowCount() > 0) {
                                // Si hay jugadores, iterar sobre los datos obtenidos de la base de datos para generar opciones
                                while ($row = $stmt_jugadores->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                    <button class="boton" type="submit" name="jugador_atacante" value="<?php echo $row['id_jugador_atacante']; ?>">
                                        <?php echo "<img src='data:image/jpeg;base64," . base64_encode($info_agente['agente']) . "'>"; ?>
                                        <h1 class="titulo"><?php echo $row['id_jugador_atacante']; ?></h1>
                                    </button>
                            <?php
                                }
                            } else {
                                // Si no hay jugadores disponibles, mostrar un mensaje
                                echo "<p>No hay nadie disponible en este momento.</p>";
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>

                </div>
            </div>
            <input type="hidden" name="id_detalle" value="<?php echo $id_detalle; ?>">
        </form>
    </div>

    <script src="../../js/main_2.js"></script>
</body>

</html>