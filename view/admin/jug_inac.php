<?php
session_start();



require_once("../../db/conection.php");
$db = new Database();
$con = $db->conectar();

$sql = "SELECT  j.correo, j.nombre, j.username, j.contrasena, r.rol, e.estado, j.ult_ini  
        FROM jugador j
        INNER JOIN rol r ON j.id_rol = r.id_rol
        INNER JOIN estado e ON j.id_estado = e.id_estado
        WHERE j.id_rol = 2";

$result = $con->query($sql);

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

    <style>
        .table-responsive td:nth-child(5) {
            word-wrap: break-word;
            max-width: 300px;
            /* Puedes ajustar el ancho máximo según tus necesidades */
        }

        .table-container {
            max-width: 1000px;
            /* Puedes ajustar el ancho máximo según tus necesidades */
            margin: 0 auto;
            /* Centra el contenedor en la página */
        }
    </style>

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
                        <a href="index.php"><i class='bx bx-home fa-3x'></i> Inicio</a>
                    </li>
                    <li>
                        <a class="active-menu" href="jug_inac.php"><i class='bx bxs-user-x fa-3x'></i>Jugadores Inactivos</a>
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
                        <h2><strong>JUGADORES</strong></h2>
                       
                    </div>
                </div>
                <hr />
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                    <h4><strong>Recuerde activar nuevos jugadores</strong></h4>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-container">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Correo</th>
                                                    <th>Nombre</th>
                                                    <th>Username</th>
                                                    <th>Estado</th>
                                                    <th>Fecha</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                                                    <tr>
                                                        <td><?php echo $row['username']; ?></td>
                                                        <td><?php echo $row['correo']; ?></td>
                                                        <td><?php echo $row['nombre']; ?></td>
                                                        <td><?php echo $row['username']; ?></td>
                                                        <td><?php echo $row['estado']; ?></td>
                                                        <td><?php echo $row['ult_ini']; ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-<?php echo ($row['estado'] === 'Activo') ? 'danger' : 'success'; ?> btn-activate" data-id="<?php echo $row['username']; ?>" data-current-state="<?php echo $row['estado']; ?>" onclick="return confirmAction(this);">
                                                                <?php echo ($row['estado'] === 'Activo') ? 'Desactivar' : 'Activar'; ?>
                                                            </button>

                                                        </td>

                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>





                                    </div>
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
        <script>
            var activateButtons = document.querySelectorAll('.btn-activate');


            activateButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var userId = button.getAttribute('data-id');
                    var currentState = button.textContent.trim();

                    // Envía una solicitud AJAX al servidor
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'activacion.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            // Actualiza el texto del botón según la respuesta del servidor
                            button.textContent = xhr.responseText;
                        }
                    };
                    xhr.send('userId=' + userId + '&currentState=' + currentState);
                });
            });

            
        </script>

</body>

</html>