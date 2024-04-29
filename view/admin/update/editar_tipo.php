<?php
session_start();

require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM tipo_arma WHERE id_tipo_arma = $id";
    $result = $con->query($sql);
    $tipo_arma = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de roles
    header('Location: roles.php');
    exit();
}

if (isset($_POST["update"])) {
    $id_tipo_arma = $_POST['id_tipo_arma'];
    $tipo_arma_nombre = $_POST['tipo_arma'];

    // Actualizar los datos del tipo de arma
    $updateTipoArmaSQL = $con->prepare("UPDATE tipo_arma SET tipo_arma = ? WHERE id_tipo_arma = ?");
    if ($updateTipoArmaSQL->execute([$tipo_arma_nombre, $id_tipo_arma])) {
        echo '<script>alert("Actualización Exitosa");</script>';
        echo'<script>window.close();</script>';
    } else {
        echo '<script>alert("Error al actualizar el tipo de arma");</script>';
    }
} elseif (isset($_POST["delete"])) {
    $id_tipo_arma = $_POST['id_tipo_arma'];

    // Eliminar el tipo de arma
    $deleteSQL = $con->prepare("DELETE FROM tipo_arma WHERE id_tipo_arma = ?");
    if ($deleteSQL->execute([$id_tipo_arma])) {
        echo '<script>alert("Registro Eliminado Exitosamente");</script>';
        echo'<script>window.close();</script>';
    } else {
        echo '<script>alert("Error al eliminar el tipo de arma");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar tipo de arma</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../../img/icono_valo.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Actualizar tipo de arma</h2>
        <form autocomplete="off" name="frm_consulta" method="POST">
            <div class="form-group row">
                <label for="id_tipo_arma" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_tipo_arma" name="id_tipo_arma" value="<?php echo $tipo_arma['id_tipo_arma'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="tipo_arma" class="col-sm-2 col-form-label">Tipo de arma</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="tipo_arma" name="tipo_arma" value="<?php echo $tipo_arma['tipo_arma'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <input type="submit" class="btn btn-primary" name="update" value="Actualizar">
                    <!-- Botón de eliminar con confirmación -->
                    <button class="btn btn-danger" name="delete" onclick="return confirmarEliminacion()">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        function confirmarEliminacion() {
            if (confirm("¿Estás seguro de que deseas eliminar este tipo de arma?")) {
                document.forms["frm_consulta"].submit();
            } else {
                return false;
            }
        }
    </script>
</body>

</html>
