<?php
// Conectar a la base de datos
require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

// Consultar todos los jugadores
$sql = "SELECT * FROM jugador";
$resultado = $con->query($sql);

// Consultar los roles disponibles
$sql_roles = "SELECT * FROM rol";
$resultado_roles = $con->query($sql_roles);

// Consultar los estados disponibles
$sql_estados = "SELECT * FROM estado";
$resultado_estados = $con->query($sql_estados);

// Inicializar mensajes de éxito y error
$success_message = "";
$error_message = "";

// Procesar el formulario de registro de jugadores
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han recibido todos los datos necesarios
    if (isset($_POST['correo']) && isset($_POST['nombre']) && isset($_POST['username']) && isset($_POST['contrasena']) && isset($_POST['id_rol']) && isset($_POST['id_estado'])) {
        // Obtener los datos del formulario
        $correo = $_POST["correo"];
        $nombre = $_POST["nombre"];
        $username = $_POST["username"];
        $contrasena = $_POST["contrasena"];
        $contrasena = hash('sha512', $contrasena); // Hash de la contraseña
        $id_rol = $_POST["id_rol"];
        $id_estado = $_POST["id_estado"];

        // Verificar si ya existe un usuario con el mismo correo electrónico o nombre de usuario
        $sqlCheckUser = "SELECT * FROM jugador WHERE correo = :correo OR username = :username";
        $stmtCheckUser = $con->prepare($sqlCheckUser);
        $stmtCheckUser->bindParam(':correo', $correo);
        $stmtCheckUser->bindParam(':username', $username);
        $stmtCheckUser->execute();

        if ($stmtCheckUser->rowCount() > 0) {
            // Ya existe un usuario con este correo electrónico o nombre de usuario
            $error_message = "Ya existe un usuario con este correo electrónico o nombre de usuario.";
        } else {
            // No existe un usuario con el mismo correo electrónico o nombre de usuario, proceder con el ingreso
            // Insertar los datos del formulario en la base de datos
            $insertSQL = $con->prepare("INSERT INTO jugador (correo, nombre, username, contrasena, id_rol, id_estado) VALUES (?, ?, ?, ?, ?, ?)");
            $insertSQL->execute([$correo, $nombre, $username, $contrasena, $id_rol, $id_estado]);

            // Verificar si el registro se realizó correctamente
            if ($insertSQL->rowCount() > 0) {
                // Registro exitoso
                $success_message = "La persona se agregó correctamente.";
            } else {
                // Error al ingresar el usuario
                $error_message = "Error al ingresar el usuario.";
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
    <link rel="icon" href="../../../img/icono_valo.png" type="image/x-icon">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                        <a class="" ><i class='bx bx-sitemap fa-3x'></i> Tablas<span class='bx bx-chevron-down'></span></a>
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
                                <a href="../tablas/detalle.php">Detalle</a>
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
                    <h2   style="margin-bottom: 20px; color: #333;"><strong>Formulario Jugadores o Usuarios</strong></h2>
                    </div>
                </div>
                <hr />
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h3>Registrar Jugador</h3>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-group">
                                        <label for="correo">Correo:</label>
                                        <input type="email" class="form-control" id="correo" name="correo" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" pattern="[A-Za-z]+" title="Solo se permiten letras" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="username">Username:</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contrasena">Contraseña:</label>
                                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_rol">Rol:</label>
                                        <select class="form-control" id="id_rol" name="id_rol" required>
                                            <?php
                                            // Iterar sobre los roles disponibles
                                            while ($fila_rol = $resultado_roles->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $fila_rol['id_rol'] . "'>" . $fila_rol['rol'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="id_estado">Estado:</label>
                                        <select class="form-control" id="id_estado" name="id_estado" required>
                                            <?php
                                            // Iterar sobre los roles disponibles
                                            while ($fila_rol = $resultado_estados->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $fila_rol['id_estado'] . "'>" . $fila_rol['estado'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Registrar</button>
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