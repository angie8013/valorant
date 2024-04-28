<?php
session_start();

require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM puntos WHERE id_puntos = $id";
    $result = $con->query($sql);
    $puntos = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de puntos
    header('Location: puntos.php');
    exit();
}

if (isset($_POST["update"])) {
    $id_puntos = $_POST['id_puntos'];
    $puntos = $_POST['puntos'];
    $nivel = $_POST['nivel'];
    $rango = $_POST['rango'];
    $rango_img = $_FILES['rango_img'];

    // Verificar si se ha cargado una nueva imagen
    if (!empty($rango_img['name'])) {
        $rangoImgNombre = $rango_img['name'];
        $rangoImgTmp = $rango_img['tmp_name'];

        // Obtener el contenido binario de la imagen
        $rangoImgBinario = file_get_contents($rangoImgTmp);

        // Actualizar la imagen en la base de datos
        $updateRangoImgSQL = $con->prepare("UPDATE puntos SET rango_img = ? WHERE id_puntos = ?");
        $updateRangoImgSQL->bindParam(1, $rangoImgBinario, PDO::PARAM_LOB);
        $updateRangoImgSQL->bindParam(2, $id_puntos, PDO::PARAM_INT);
        $updateRangoImgSQL->execute();
    }

    // Actualizar los datos del punto
    $updatePuntosSQL = $con->prepare("UPDATE puntos SET puntos = ?, nivel = ?, rango = ? WHERE id_puntos = ?");
    $updatePuntosSQL->execute([$puntos, $nivel, $rango, $id_puntos]);

    echo '<script>alert("Actualización Exitosa");</script>';
    echo '<script>window.close();</script>';
} elseif (isset($_POST["delete"])) {
    $id_puntos = $_POST['id_puntos'];

    $deleteSQL = $con->prepare("DELETE FROM puntos WHERE id_puntos = ?");
    $deleteSQL->execute([$id_puntos]);
    echo '<script>alert("Registro Eliminado Exitosamente");</script>';
    header('Location: ../tablas/puntos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Puntos</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Actualizar Puntos - ID: <?php echo $id; ?></h2>
        <form autocomplete="off" name="frm_consulta" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label for="id_puntos" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_puntos" name="id_puntos" value="<?php echo $puntos['id_puntos'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="puntos" class="col-sm-2 col-form-label">Puntos</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="puntos" name="puntos" value="<?php echo $puntos['puntos'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="nivel" class="col-sm-2 col-form-label">Nivel</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="nivel" name="nivel" value="<?php echo $puntos['nivel'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="rango" class="col-sm-2 col-form-label">Rango</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="rango" name="rango" value="<?php echo $puntos['rango'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="rango_img" class="col-sm-2 col-form-label">Rango Img</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="rango_img" name="rango_img">
                </div>
            </div>
            <script>
                function confirmarEliminacion() {
                    if (confirm("¿Estás seguro de que deseas eliminar estos puntos?")) {
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
