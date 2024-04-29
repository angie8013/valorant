<?php
include("../../db/conection.php");
$db = new Database();
$con = $db->conectar();

try {
    // Obtener el nombre de usuario que ha iniciado sesión
    session_start();
    $username = $_SESSION['username'];

    // Consultar los usuarios en la tabla detalle_batalla que tengan el mismo id_sala y excluyendo al usuario que ha iniciado sesión
    $stmt = $con->prepare("SELECT DISTINCT id_jugador_atacante FROM detalle_batalla WHERE id_sala IN (SELECT id_sala FROM detalle_batalla WHERE id_jugador_atacante = :username) AND id_jugador_atacante != :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Mostrar los resultados de la consulta
    echo "<select name='usuarios'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id_jugador_atacante'] . "'>" . $row['id_jugador_atacante'] . "</option>";
    }
    echo "</select>";
} catch(PDOException $e) {
    // Manejar errores si es necesario
    echo "Error: " . $e->getMessage();
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
	<link rel="stylesheet" href="../../css/batalla.css">
	<link rel="icon" href="../../img/icono_valo.png" type="image/x-icon">

</head>
<body>
	
	<div class="contenedor">
		<form action="">
			<div class="selectbox">
				<div class="select" id="select">
					<div class="contenido-select">
						 
						<p class="descripcion">Lorem ipsum dolor sit.</p>
					</div>
					<i class="fas fa-angle-down"></i>
				</div>
        <?php
                // Aquí puedes incluir tu código PHP para generar las opciones dinámicamente
                $paises = array("México", "España", "Argentina", "Colombia");
                foreach ($paises as $pais) {
                    echo '<a href="#" class="opcion">';
                    echo '<div class="contenido-opcion">';
                    echo '<img src="img/' . strtolower($pais) . '.png" alt="' . $pais . '">';
                    echo '<div class="textos">';
                    echo '<h1 class="titulo">' . $pais . '</h1>';
                    echo '<p class="descripcion">Descripción de ' . $pais . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }
                ?>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/spain.png" alt="">
							<div class="textos">
								<h1 class="titulo">España</h1>
								<p class="descripcion">Consectetur adipiscing elit.</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/colombia.png" alt="">
							<div class="textos">
								<h1 class="titulo">Colombia</h1>
								<p class="descripcion">Suspendisse eleifend venenatis.</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/argentina.png" alt="">
							<div class="textos">
								<h1 class="titulo">Argentina</h1>
								<p class="descripcion">Sed posuere laoreet dui.</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/chile.png" alt="">
							<div class="textos">
								<h1 class="titulo">Chile</h1>
								<p class="descripcion">Dignissim hendrerit odio rhoncus.</p>
							</div>
						</div>
					</a>
				</div>
			</div>

			<input type="hidden" name="pais" id="inputSelect" value="">
		</form>

	</div>

	<script src="../../js/main.js"></script>
</body>
</html>