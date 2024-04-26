<?php
// Conexión a la base de datos
require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

// Consulta SQL para obtener los estados
$sql = "SELECT * FROM estado";
$resultado = $con->query($sql);
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="../../../css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="../../../css/font-awesome.css" rel="stylesheet" />
    <!-- MORRIS CHART STYLES-->
    <link href="../../../js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="../../../css/custom.css" rel="stylesheet" />
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
                <img src="../../../img/Valorant_logo.svg.png" class="navbar-brand" href="index.html"></img>
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
                        <img src="../../../img/admin/find_user.png" class="user-image img-responsive" />
                    </li>

                    <li>
                        <a href="../../admin/index.php"><i class='bx bx-home fa-3x'></i> Inicio</a>
                    </li>
                    <li>
                        <a href="../../admin/jug_inac.php"><i class='bx bxs-user-x fa-3x'></i>Jugadores Inactivos</a>
                    </li>
                    <li>
                        <a href="../../admin/salas.php"><i class='bx bx-cube-alt fa-3x'></i> Salas</a>
                    </li>
                    <li>
                        <a class="active-menu" href="#"><i class='bx bx-sitemap fa-3x'></i> Tablas<span class='bx bx-chevron-down'></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="jugador.php">Jugadores</a>
                            </li>
                            <li>
                                <a href="jugador.php">Agente</a>
                            </li>
                            <li>
                                <a href="armas.php">Armas</a>
                            </li>
                            <li>
                                <a href="detalle.php">Detalle</a>
                            </li>
                            <li>
                                <a href="estado.php">Estado</a>
                            </li>
                            <li>
                                <a href="mundos.php">Mundos</a>
                            </li>
                            <li>
                                <a href="puntos.php">Puntos</a>
                            </li>
                            <li>
                                <a href="roles.php">Roles</a>
                            </li>
                            
                            <li>
                                <a href="tipo_armas.php">Tipos de armas</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class='bx bx-sitemap fa-3x'></i> Formularios<span class='bx bx-chevron-down'></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="../forms/jugador.php">Jugadores</a>
                            </li>
                            <li>
                                <a href="../forms/agente.php">Agente</a>
                            </li>
                            <li>
                                <a href="../forms/armas.php">Armas</a>
                            </li>
                            
                            <li>
                                <a href="../forms/estado.php">Estado</a>
                            </li>
                            <li>
                                <a href="../forms/mundos.php">Mundos</a>
                            </li>
                            <li>
                                <a href="../forms/puntos.php">Puntos</a>
                            </li>
                            <li>
                                <a href="../forms/roles.php">Roles</a>
                            </li>
                            
                            <li>
                                <a href="../forms/tipo_armas.php">Tipos de armas</a>
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
                    <h2   style="margin-bottom: 20px; color: #333;"><strong>Tabla de Estados</strong></h2>

                    </div>
                </div>
                <hr />
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h4> <strong> Estados:</strong></h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Verificar si se encontraron resultados
                                            if ($resultado->rowCount() > 0) {
                                                // Iterar sobre cada fila de resultados
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $fila['id_estado'] . "</td>";
                                                    echo "<td>" . $fila['estado'] . "</td>";
                                                    echo "<td><a href='../update/editar_estado.php?id=" . $fila['id_estado'] . "' class='btn btn-primary'  onclick=\"window.open('../update/editar_estado.php?id={$fila['id_estado']}','','width=500,height=500,toolbar=NO'); return false;\">Editar</a></td>"; // Botón de editar con enlace a la página de edición
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>No se encontraron estados</td></tr>";
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
        <script src="../../../js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS -->
        <script src="../../../js/bootstrap.min.js"></script>
        <!-- METISMENU SCRIPTS -->
        <script src="../../../js/jquery.metisMenu.js"></script>
        <!-- MORRIS CHART SCRIPTS -->
        <script src="../../../js/morris/raphael-2.1.0.min.js"></script>
        <script src="../../../js/morris/morris.js"></script>
        <!-- CUSTOM SCRIPTS -->
        <script src="../../../js/custom.js"></script>


</body>

</html>
