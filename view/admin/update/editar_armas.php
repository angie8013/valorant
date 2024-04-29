<?php
session_start();

require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM arma WHERE id_arma = $id";
    $result = $con->query($sql);
    $arma = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de armas
    header('Location: armas.php');
    exit();
}

if (isset($_POST["update"])) {
    $id_arma = $_POST['id_arma'];
    $nombre = $_POST['nombre'];
    $foto = $_FILES['foto'];
    $balas = $_POST['balas'];
    $dano = $_POST['dano'];
    $tipo_arma = $_POST['tipo_arma'];

    // Verificar si se ha cargado una nueva foto
    if (!empty($foto['name'])) {
        $fotoNombre = $foto['name'];
        $fotoTmp = $foto['tmp_name'];

        // Obtener el contenido binario de la imagen
        $fotoBinario = file_get_contents($fotoTmp);

        // Actualizar la foto en la base de datos
        $updateFotoSQL = $con->prepare("UPDATE arma SET arma = ? WHERE id_arma = ?");
        $updateFotoSQL->bindParam(1, $fotoBinario, PDO::PARAM_LOB);
        $updateFotoSQL->bindParam(2, $id_arma, PDO::PARAM_INT);
        $updateFotoSQL->execute();
    }

    // Actualizar los datos del arma
    $updateArmaSQL = $con->prepare("UPDATE arma SET nombre = ?, balas = ?, dano = ?, tipo_arma = ? WHERE id_arma = ?");
    $updateArmaSQL->execute([$nombre, $balas, $dano, $tipo_arma, $id_arma]);

    echo '<script>alert("Actualización Exitosa");</script>';
    echo '<script>window.close();</script>';
} elseif (isset($_POST["delete"])) {
    $id_arma = $_POST['id_arma'];

    $deleteSQL = $con->prepare("DELETE FROM arma WHERE id_arma = ?");
    $deleteSQL->execute([$id_arma]);
    echo '<script>alert("Registro Eliminado Exitosamente");</script>';
    header('Location: ../tablas/armas.php');
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
        <h2 class="mt-5 mb-3">Actualizar datos del arma</h2>
        <form autocomplete="off" name="frm_consulta" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label for="id_arma" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_arma" name="id_arma" value="<?php echo $arma['id_arma'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $arma['nombre'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="foto" name="foto">
                </div>
            </div>
            <div class="form-group row">
                <label for="balas" class="col-sm-2 col-form-label">Balas</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="balas" name="balas" value="<?php echo $arma['balas'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="dano" class="col-sm-2 col-form-label">Daño</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="dano" name="dano" value="<?php echo $arma['dano'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="tipo_arma" class="col-sm-2 col-form-label">Tipo de arma</label>
                <div class="col-sm-10">
                    <select class="form-control" id="tipo_arma" name="tipo_arma">
                        <?php
                        $control = $con->prepare("SELECT * FROM tipo_arma");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($fila['id_tipo_arma'] == $arma['tipo_arma']) ? "selected" : "";
                            echo "<option value='" . $fila['id_tipo_arma'] . "' $selected>" . $fila['tipo_arma'] . "</option>";
                        }
                        ?>
                    </select>
                </div>


            </div>
            <script>
                function confirmarEliminacion() {
                    if (confirm("¿Estás seguro de que deseas eliminar esta arma?")) {
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