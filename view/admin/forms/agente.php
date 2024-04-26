<?php
// Conectar bd
require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();


// Inicializar mensajes
$success_message = "";
$error_message = "";

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se cargó una foto y se proporcionó un nombre
    if (isset($_POST['nombre']) && isset($_FILES['foto'])) {
        // Recuperar los datos del formulario
        $nombre = $_POST['nombre'];
        $foto = $_FILES['foto'];

        // Verificar si ya existe un agente con el mismo nombre
        $stmtCheckName = $con->prepare("SELECT * FROM agente WHERE nombre = ?");
        $stmtCheckName->execute([$nombre]);
        if ($stmtCheckName->rowCount() > 0) {
            // Ya existe un agente con este nombre
            $error_message = "Ya existe un agente con este nombre.";
        } else {
            // Obtener el contenido binario de la imagen
            $fotoBinario = file_get_contents($foto['tmp_name']);

            // Preparar la consulta SQL para insertar los datos en la tabla agente
            $sql = "INSERT INTO agente (nombre, foto) VALUES (:nombre, :foto)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':foto', $fotoBinario, PDO::PARAM_LOB);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Mensaje de confirmación
                $success_message = "El agente se agregó correctamente.";
            } else {
                // Error en la ejecución de la consulta
                $error_message = "Error al ingresar el agente.";
            }
        }
    } else {
        // Si no se proporcionaron todos los datos necesarios
        $error_message = "Por favor, complete todos los campos.";
    }
}
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

    <style>
        /* Estilos para el formulario */
        .form-container {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        /* Estilos para la tabla */
        .table-container {
            margin-top: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
        }

        th,
        td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only"></span>
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
                header('location:../../../index.php');
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
                        <a href="../index.php"><i class='bx bx-home fa-3x'></i> Inicio</a>
                    </li>
                    <li>
                        <a href="../jug_inac.php"><i class='bx bxs-user-x fa-3x'></i>Jugadores Inactivos</a>
                    </li>
                    <li>
                        <a href="../salas.php"><i class='bx bx-cube-alt fa-3x'></i> Salas</a>
                    </li>
                    <li>
                        <a  href="#"><i class='bx bx-sitemap fa-3x'></i> Tablas<span class='bx bx-chevron-down'></span></a>
                        <ul class="nav nav-second-level">
                        <li>
                                <a href="../tablas/jugador.php">Jugadores</a>
                            </li>
                            <li>
                                <a href="../tablas/agente.php">Agente</a>
                            </li>
                            <li>
                                <a href="../tablas/armas.php">Armas</a>
                            </li>
                            <li>
                                <a href="../tablas/estado.php">Estado</a>
                            </li>
                            <li>
                                <a href="../tablas/mundos.php">Mundos</a>
                            </li>
                            <li>
                                <a href="../tablas/puntos.php">Puntos</a>
                            </li>
                            <li>
                                <a href="../tablas/roles.php">Roles</a>
                            </li>
                
                            <li>
                                <a href="../tablas/tipo_armas.php">Tipos de armas</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="active-menu" href="#"><i class='bx bx-sitemap fa-3x'></i> Formularios<span class='bx bx-chevron-down'></span></a>
                        <ul class="nav nav-second-level">
                        <li>
                                <a href="jugador.php">Jugadores</a>
                            </li>
                            <li>
                                <a href="agente.php">Agente</a>
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
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                    <h2   style="margin-bottom: 20px; color: #333;"><strong>Formulario Agente</strong></h2>

                    </div>
                </div>
                <hr />
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">

                        <div class="panel panel-default">
                            <div class="panel-body">
                            <h3>Registrar Agente</h3>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="foto">Foto:</label>
                                        <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Guardar Agente</button>
                                </form>
                            </div>
                        </div>

                        <!-- Mensaje de éxito -->
                        <?php if (!empty($success_message)) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Mensaje de error -->
                        <?php if (!empty($error_message)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
        </div>
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
