<?php
session_start();

require_once("../../db/conexion_2.php");
$db = new Database();
$con = $db->conectar();

function obtenerNombreJugador($username, $con)
{
    $sql = "SELECT nombre FROM jugador WHERE username = :username";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nombre'] : null;
}

function obtenerNombreArma($id_arma, $con)
{
    $sql = "SELECT nombre FROM arma WHERE id_arma = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id_arma);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nombre'] : null;
}

function obtenerNombreAgente($id_agente, $con)
{
    $sql = "SELECT nombre FROM agente WHERE id_agente = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id_agente);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nombre'] : null;
}

$sql = "SELECT * FROM detalle_batalla";
$resultado = $con->query($sql);

$detalle_batalla = [];

if ($resultado === false) {
    echo "Error al ejecutar la consulta SQL: " . $con->errorInfo()[2];
} else {
    if ($resultado->rowCount() > 0) {
        while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $detalle_batalla[] = $fila;
        }
    } else {
        echo "No se encontraron resultados.";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link href="../../css/bootstrap.css" rel="stylesheet" />
    <link href="../../css/font-awesome.css" rel="stylesheet" />
    <link href="../../js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="../../css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <!-- Navbar content -->
        </nav>

        <nav class="navbar-default navbar-side" role="navigation">
            <!-- Sidebar content -->
        </nav>

        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Bienvenido admin</h2>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h4><strong>Detalle de partidas:</strong></h4>
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
            </div>
        </div>
    </div>
    <script src="../../js/jquery-1.10.2.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.metisMenu.js"></script>
    <script src="../../js/morris/raphael-2.1.0.min.js"></script>
    <script src="../../js/morris/morris.js"></script>
    <script src="../../js/custom.js"></script>
</body>

</html>
