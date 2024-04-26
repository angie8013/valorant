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
    $foto = $_FILES['foto'];

    // Verificar si se ha cargado una nueva foto
    if (!empty($foto['name'])) {
        $fotoNombre = $foto['name'];
        $fotoTmp = $foto['tmp_name'];

        // Obtener el contenido binario de la imagen
        $fotoBinario = file_get_contents($fotoTmp);

        // Actualizar la foto en la base de datos
        $updateFotoSQL = $con->prepare("UPDATE agente SET foto = ? WHERE id_agente = ?");
        $updateFotoSQL->bindParam(1, $fotoBinario, PDO::PARAM_LOB);
        $updateFotoSQL->bindParam(2, $id_agente, PDO::PARAM_INT);
        $updateFotoSQL->execute();
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
                <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="foto" name="foto">
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