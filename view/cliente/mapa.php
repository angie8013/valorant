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

// Obtener el nivel del jugador
$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT id_puntos FROM jugador WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$jugador = $stmt->fetch(PDO::FETCH_ASSOC);
$nivel_jugador = $jugador['id_puntos'];

// Consulta para obtener las armas del mismo nivel que el jugador
$puntos = $con->prepare("SELECT * FROM mundo WHERE nivel <= :nivel_jugador");

// Bind parameters
$puntos->bindParam(':nivel_jugador', $nivel_jugador);

// Ejecutamos la consulta
$puntos->execute();
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
            if ($puntos->rowCount() > 0) {
                // Iteramos sobre cada resultado para mostrar las tarjetas
                while ($info = $puntos->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <article class="card__article">
                        <?php echo "<img src='data:image/jpeg; base64," . base64_encode($info['mundo']) . "'>"; ?>
                        <div class="card__data">
                            <div class="card__description">
                                <h2 class="card-title">Mapa nivel <?php echo $info['nivel']; ?></h2>
                                <strong class="card-text">Nombre: <?php echo $info['nombre']; ?></strong> <br>
                                   
                                <!-- Agrega más información de ser necesario -->
                                <form action="mapa_select.php" method="post">
                    <button class="card__button" type="submit" name="valor" value="<?php echo $info['id_mundo']; ?>">Elegir mapa</button>
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
     <center><button class="valorant-btn" >
        <span class="underlay">
         <a> </a>
        </span>
      </button></center>
</body>

</html>