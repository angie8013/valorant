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

// Obtener el nivel del jugador
$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT id_puntos FROM jugador WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$jugador = $stmt->fetch(PDO::FETCH_ASSOC);
$nivel_jugador = $jugador['id_puntos'];

// Consulta para obtener las armas del mismo nivel que el jugador
$puntos = $con->prepare("SELECT * FROM arma WHERE nivel <= :nivel_jugador");
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
                                <strong class="card-text">Daño: <?php echo $info['daño']; ?></strong> <br>
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
        <form method="post" action="tu_script.php">
    <!-- Otros elementos del formulario aquí -->

    <!-- Select dentro del formulario -->
    <select name='usuarios'>
        <?php
        try {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_jugador_atacante = $row['id_jugador_atacante'];
                echo "<option value='$id_jugador_atacante'>$id_jugador_atacante</option>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </select>

    <!-- Otros elementos del formulario aquí -->

    <!-- Botón de enviar -->
    <button type="submit">Enviar</button>
</form>
    </div>
</body>

</html>
