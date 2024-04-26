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

    $sql = "SELECT * FROM rol WHERE id_rol = $id";
    $result = $con->query($sql);
    $rol = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de roles
    header('Location: roles.php');
    exit();
}

if (isset($_POST["update"])) {
    $id_rol = $_POST['id_rol'];
    $rol_nombre = $_POST['rol'];

    // Actualizar los datos del rol
    $updateRolSQL = $con->prepare("UPDATE rol SET rol = ? WHERE id_rol = ?");
    if ($updateRolSQL->execute([$rol_nombre, $id_rol])) {
        echo '<script>alert("Actualización Exitosa");</script>';
        echo'<script>window.close();</script>';
    } else {
        echo '<script>alert("Error al actualizar el rol");</script>';
    }
} elseif (isset($_POST["delete"])) {
    $id_rol = $_POST['id_rol'];

    // Eliminar el rol
    $deleteSQL = $con->prepare("DELETE FROM rol WHERE id_rol = ?");
    if ($deleteSQL->execute([$id_rol])) {
        echo '<script>alert("Registro Eliminado Exitosamente");</script>';
        echo'<script>window.close();</script>';
    } else {
        echo '<script>alert("Error al eliminar el rol");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar rol</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Actualizar rol</h2>
        <form autocomplete="off" name="frm_consulta" method="POST">
            <div class="form-group row">
                <label for="id_rol" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_rol" name="id_rol" value="<?php echo $rol['id_rol'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="rol" name="rol" value="<?php echo $rol['rol'] ?>">
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
            if (confirm("¿Estás seguro de que deseas eliminar este rol?")) {
                document.forms["frm_consulta"].submit();
            } else {
                return false;
            }
        }
    </script>
</body>

</html>
