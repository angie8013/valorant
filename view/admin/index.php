<?php



require_once("../../db/conection.php");
$db = new Database();
$con = $db->conectar();
session_start();
// Consulta para obtener los datos de detalle_batalla
$sql = "SELECT * FROM detalle_batalla";
$resultado = $con->query($sql);

// Crear una matriz para almacenar los resultados de la consulta
$detalle_batalla = array();

// Verificar si hay resultados
if ($resultado === false) {
    // Si la consulta falla, mostrar el mensaje de error
    echo "Error al ejecutar la consulta SQL: " . $con->errorInfo()[2];
} else {
    // Verificar si hay resultados válidos
    if ($resultado->rowCount() > 0) {
        // Iterar sobre los resultados y almacenarlos en la matriz
        while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $detalle_batalla[] = $fila;
        }
    } else {
        // Si no hay resultados, mostrar un mensaje
        echo "No se encontraron resultados.";
    }
}

// Función para obtener el nombre del jugador por su ID
function obtenerNombreJugador($username, $con)
{
    $sql = "SELECT nombre FROM jugador WHERE username = :username";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    // Verificar si la consulta devolvió resultados
    if ($stmt->rowCount() > 0) {
        // Fetch the result
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['nombre'];
    } else {
        // Si la consulta no devuelve resultados, puedes devolver un valor predeterminado o lanzar una excepción según tus necesidades
        return "Nombre no encontrado"; // o lanzar una excepción
    }
}
// Función para obtener el nombre del arma por su ID
function obtenerNombreArma($id_arma, $con)
{
    $sql = "SELECT nombre FROM arma WHERE id_arma = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id_arma);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verificar si la consulta devolvió resultados
    if ($resultado) {
        return $resultado['nombre'];
    } else {
        // Si la consulta no devuelve resultados, puedes devolver un valor predeterminado o lanzar una excepción según tus necesidades
        return "Nombre de arma no encontrado"; // o lanzar una excepción
    }
}

// Función para obtener el nombre del agente por su ID
function obtenerNombreAgente($id_agente, $con)
{
    $sql = "SELECT nombre FROM agente WHERE id_agente = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id_agente);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verificar si la consulta devolvió resultados
    if ($resultado) {
        return $resultado['nombre'];
    } else {
        // Si la consulta no devuelve resultados, puedes devolver un valor predeterminado o lanzar una excepción según tus necesidades
        return "Nombre de agente no encontrado"; // o lanzar una excepción
    }
}


// Cerrar la conexión
//$con = null; // No cierres la conexión aquí para usarla en las funciones
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="../../css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="../../css/font-awesome.css" rel="stylesheet" />
    <!-- MORRIS CHART STYLES-->
    <link href="../../js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="../../css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="../../img/Valorant_logo.svg.png" class="navbar-brand" href="index.html"></img>
            </div>
            <form method="post" action="">
                <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> Last access : 30 May 2014 &nbsp;
                    <input type="submit" name="btncerrar" class="btn btn-danger square-btn-adjust" value="Cerrar sesión">
                </div>
            </form>
            <?php

            if (isset($_POST['btncerrar'])) {
                session_destroy();
                header('location:../../index.php');
            }

            ?>
        </nav>

        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="../../img/admin/find_user.png" class="user-image img-responsive" />
                    </li>


                    <li>
                        <a class="active-menu" href="index.php"><i class='bx bx-home fa-3x'></i> Inicio</a>
                    </li>
                    <li>
                        <a href="jug_inac.php"><i class='bx bxs-user-x fa-3x'></i>Jugadores Inactivos</a>
                    </li>
             
                    <li>
                        <a href="#"><i class='bx bx-sitemap fa-3x'></i> Tablas<span class='bx bx-chevron-down'></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="./tablas/jugador.php">Jugadores</a>
                            </li>
                            <li>
                                <a href="./tablas/agente.php">Agente</a>
                            </li>
                            <li>
                                <a href="./tablas/armas.php">Armas</a>
                            </li>
                            <li>
                                <a href="./tablas/detalle.php">Detalle</a>
                            </li>
                            <li>
                                <a href="./tablas/estado.php">Estado</a>
                            </li>
                            <li>
                                <a href="./tablas/mundos.php">Mundos</a>
                            </li>
                            <li>
                                <a href="./tablas/puntos.php">Puntos</a>
                            </li>
                            <li>
                                <a href="./tablas/roles.php">Roles</a>
                            </li>
                            <li>
                                <a href="./tablas/tipo_armas.php">Tipos de armas</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class='bx bx-sitemap fa-3x'></i> Formularios<span class='bx bx-chevron-down'></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="./forms/jugador.php">Jugadores</a>
                            </li>
                            <li>
                                <a href="./forms/agente.php">Agente</a>
                            </li>
                            <li>
                                <a href="./forms/armas.php">Armas</a>
                            </li>
                            <li>
                                <a href="./forms/estado.php">Estado</a>
                            </li>
                            <li>
                                <a href="./forms/mundos.php">Mundos</a>
                            </li>
                            <li>
                                <a href="./forms/puntos.php">Puntos</a>
                            </li>
                            <li>
                                <a href="./forms/roles.php">Roles</a>
                            </li>
                            <li>
                                <a href="./forms/tipo_armas.php">Tipos de armas</a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Bienvenido admin</h2>
                        
                    </div>
                </div>
                <hr />
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">

                        <div class="panel panel-default">
                            <div class="panel-body">
                            <h4> <strong> Detalle de partidas:</strong></h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jugador Atacante</th>
                                                <th>Jugador Atacado</th>
                                                <th>Sala</th>
                                                <th>Arma</th>
                                                <th>Agente</th>
                                                <th>Vida</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Iterar sobre los datos de detalle_batalla y mostrar cada fila en la tabla
                                            foreach ($detalle_batalla as $detalle) {
                                                echo '<tr>
                                                        <td>' . $detalle['id_detalle'] . '</td>
                                                        <td>' . obtenerNombreJugador($detalle['id_jugador_atacante'], $con) . '</td>
                                                        <td>' . obtenerNombreJugador($detalle['id_jugador_atacado'], $con) . '</td>
                                                        <td>' . $detalle['id_sala'] . '</td>
                                                        <td>' . obtenerNombreArma($detalle['id_arma'], $con) . '</td>
                                                        <td>' . obtenerNombreAgente($detalle['id_agente'], $con) . '</td>
                                                        <td>' . $detalle['puntos_vida'] . '</td>
                                                    </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
        </div>
        <!-- /. WRAPPER  -->
        <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
        <!-- JQUERY SCRIPTS -->
        <script src="../../js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS -->
        <script src="../../js/bootstrap.min.js"></script>
        <!-- METISMENU SCRIPTS -->
        <script src="../../js/jquery.metisMenu.js"></script>
        <!-- MORRIS CHART SCRIPTS -->
        <script src="../../js/morris/raphael-2.1.0.min.js"></script>
        <script src="../../js/morris/morris.js"></script>
        <!-- CUSTOM SCRIPTS -->
        <script src="../../js/custom.js"></script>


</body>

</html>
