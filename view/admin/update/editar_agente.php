<?php
session_start();

require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM agente WHERE id_agente = $id";
    $result = $con->query($sql);
    $agente = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de agentes
    header('Location: agente.php');
    exit();
}

if (isset($_POST["update"])) {
    $id_agente = $_POST['id_agente'];
    $nombre = $_POST['nombre'];
    $agente = $_FILES['agente'];

    // Verificar si se ha cargado un nuevo agente
    if (!empty($agente['name'])) {
        $agenteNombre = $agente['name'];
        $agenteTmp = $agente['tmp_name'];

        // Obtener el contenido binario del archivo
        $agenteBinario = file_get_contents($agenteTmp);

        // Actualizar el agente en la base de datos
        $updateAgenteSQL = $con->prepare("UPDATE agente SET agente = ? WHERE id_agente = ?");
        $updateAgenteSQL->bindParam(1, $agenteBinario, PDO::PARAM_LOB);
        $updateAgenteSQL->bindParam(2, $id_agente, PDO::PARAM_INT);
        $updateAgenteSQL->execute();
    }

    // Actualizar el nombre del agente
    $updateNombreSQL = $con->prepare("UPDATE agente SET nombre = ? WHERE id_agente = ?");
    $updateNombreSQL->execute([$nombre, $id_agente]);

    echo '<script>alert("Actualización Exitosa");</script>';
    echo '<script>window.close();</script>';
} elseif (isset($_POST["delete"])) {
    $id_agente = $_POST['id_agente'];

    $deleteSQL = $con->prepare("DELETE FROM agente WHERE id_agente = ?");
    $deleteSQL->execute([$id_agente]);
    echo '<script>alert("Registro Eliminado Exitosamente");</script>';
    header('Location: ../tablas/agente.php');
    exit;
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
        <h2 class="mt-5 mb-3">Actualizar datos del agente</h2>
        <form autocomplete="off" name="frm_consulta" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label for="id_agente" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_agente" name="id_agente" value="<?php echo $agente['id_agente'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $agente['nombre'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="agente" class="col-sm-2 col-form-label">Agente</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="agente" name="agente">
                </div>
            </div>
            <script>
                function confirmarEliminacion() {
                    if (confirm("¿Estás seguro de que deseas eliminar este agente?")) {
                        document.forms["frm_consulta"].submit();
                        // Limpiar el formulario
                        document.getElementById("frm_consulta").reset();
                    } else {
                        // Cancelar la eliminación
                        return false;
                    }
                }
            </script>
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
