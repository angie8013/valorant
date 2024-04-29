<?php
session_start();

// Verificar si la sesión no está iniciada
if (!isset($_SESSION["id_jugador"])) {
    // Mostrar un alert y redirigir utilizando JavaScript
    echo '<script>alert("Debes iniciar sesión antes de acceder a la interfaz de administrador.");</script>';
    echo '<script>window.location.href = "../../../index.php";</script>';
    exit();
}

require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

// Obtener todos los agentes y armas para los selectores desplegables
$agentes = $con->query("SELECT id_agente, nombre FROM agente")->fetchAll(PDO::FETCH_ASSOC);
$armas = $con->query("SELECT id_arma, nombre FROM arma")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM detalle_batalla WHERE id_detalle = $id";
    $result = $con->query($sql);
    $detalle = $result->fetch(PDO::FETCH_ASSOC);

    // Obtener el nombre del jugador atacante
    $jugadorAtacanteSQL = $con->prepare("SELECT nombre FROM jugador WHERE id_jugador = ?");
    $jugadorAtacanteSQL->execute([$detalle['id_jugador_atacante']]);
    $jugadorAtacante = $jugadorAtacanteSQL->fetchColumn();

    // Obtener el nombre del jugador atacado
    $jugadorAtacadoSQL = $con->prepare("SELECT nombre FROM jugador WHERE id_jugador = ?");
    $jugadorAtacadoSQL->execute([$detalle['id_jugador_atacado']]);
    $jugadorAtacado = $jugadorAtacadoSQL->fetchColumn();


} else {
    // Si no se proporciona un ID válido, redirigir a la página de armas
    header('Location: armas.php');
    exit();
}

if (isset($_POST["update"])) {
    // Aquí va tu código para actualizar los detalles de batalla
} elseif (isset($_POST["delete"])) {
    // Aquí va tu código para eliminar los detalles de batalla
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar datos</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../../img/icono_valo.png" type="image/x-icon">

</head>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Actualizar detalles de batalla</h2>
        <form autocomplete="off" name="frm_consulta" method="POST">
            <div class="form-group row">
                <label for="id_detalle" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_detalle" name="id_detalle" value="<?php echo $detalle['id_detalle'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="jugador_atacante" class="col-sm-2 col-form-label">Jugador Atacante</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="jugador_atacante" name="jugador_atacante" value="<?php echo $jugadorAtacante ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="jugador_atacado" class="col-sm-2 col-form-label">Jugador Atacado</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="jugador_atacado" name="jugador_atacado" value="<?php echo $jugadorAtacado ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="sala" class="col-sm-2 col-form-label">Sala</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="sala" name="sala" value="<?php echo $sala ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="arma" class="col-sm-2 col-form-label">Arma</label>
                <div class="col-sm-10">
                    <select class="form-control" id="arma" name="id_arma">
                        <?php foreach ($armas as $arma): ?>
                            <option value="<?php echo $arma['id_arma']; ?>" <?php if ($detalle['id_arma'] == $arma['id_arma']) echo 'selected'; ?>><?php echo $arma['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="agente" class="col-sm-2 col-form-label">Agente</label>
                <div class="col-sm-10">
                    <select class="form-control" id="agente" name="id_agente">
                        <?php foreach ($agentes as $agente): ?>
                            <option value="<?php echo $agente['id_agente']; ?>" <?php if ($detalle['id_agente'] == $agente['id_agente']) echo 'selected'; ?>><?php echo $agente['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="puntos_vida" class="col-sm-2 col-form-label">Puntos de Vida</label>
                <div class="col-sm-10">
                    <input readonly type="number" class="form-control" id="puntos_vida" name="puntos_vida" value="<?php echo $detalle['puntos_vida'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <input type="submit" class="btn btn-primary" name="update" value="Actualizar">
                    <button class="btn btn-danger" name="delete" onclick="return confirmarEliminacion()">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
