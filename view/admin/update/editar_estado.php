<?php
session_start();


require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM estado WHERE id_estado = $id";
    $result = $con->query($sql);
    $estado = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de estados
    header('Location: estado.php');
    exit();
}

if (isset($_POST["update"])) {
    $id_estado = $_POST['id_estado'];
    $estado_nombre = $_POST['estado'];

    // Actualizar los datos del estado
    $updateEstadoSQL = $con->prepare("UPDATE estado SET estado = ? WHERE id_estado = ?");
    if ($updateEstadoSQL->execute([$estado_nombre, $id_estado])) {
        echo '<script>alert("Actualización Exitosa");</script>';
        echo'<script>window.close();</script>';
    } else {
        echo '<script>alert("Error al actualizar el estado");</script>';
    }
} elseif (isset($_POST["delete"])) {
    $id_estado = $_POST['id_estado'];

    // Eliminar el estado
    $deleteSQL = $con->prepare("DELETE FROM estado WHERE id_estado = ?");
    if ($deleteSQL->execute([$id_estado])) {
        echo '<script>alert("Registro Eliminado Exitosamente");</script>';
        echo'<script>window.close();</script>';
    } else {
        echo '<script>alert("Error al eliminar el estado");</script>';
    }
    header('Location: ../tablas/estado.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar estado</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Actualizar estado</h2>
        <form autocomplete="off" name="frm_consulta" method="POST">
            <div class="form-group row">
                <label for="id_estado" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_estado" name="id_estado" value="<?php echo $estado['id_estado'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="estado" name="estado" value="<?php echo $estado['estado'] ?>">
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
    <script>
        function confirmarEliminacion() {
            if (confirm("¿Estás seguro de que deseas eliminar este estado?")) {
                document.forms["frm_consulta"].submit();
            } else {
                return false;
            }
        }
    </script>
</body>

</html>
