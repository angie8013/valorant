<?php
// Verificar si se ha iniciado la sesión
session_start();



// Incluir el archivo de conexión a la base de datos
require_once("../../../db/conection.php");
$db = new Database();
$con = $db->conectar();

// Verificar si se proporcionó un ID de mundo válido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar los datos del mundo con el ID proporcionado
    $sql = "SELECT * FROM mundo WHERE id_mundo = $id";
    $result = $con->query($sql);
    $mundo = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de mundos
    header('Location: mundos.php');
    exit();
}

// Procesar el formulario cuando se envíe
if (isset($_POST["update"])) {
    // Obtener los datos del formulario
    $id_mundo = $_POST['id_mundo'];
    $nombre = $_POST['nombre'];
    $mundo = $_FILES['mundo'];

    // Verificar si se cargó una nueva foto
    if (!empty($mundo['name'])) {
        // Obtener el nombre y el archivo temporal de la foto
        $mundoNombre = $mundo['name'];
        $mundoTmp = $mundo['tmp_name'];

        // Leer el contenido binario del mundo
        $mundoBinario = file_get_contents($mundoTmp);

        // Actualizar el mundo en la base de datos
        $updateMundoSQL = $con->prepare("UPDATE mundo SET mundo = ? WHERE id_mundo = ?");
        $updateMundoSQL->bindParam(1, $mundoBinario, PDO::PARAM_LOB);
        $updateMundoSQL->bindParam(2, $id_mundo, PDO::PARAM_INT);
        $updateMundoSQL->execute();
    }

    // Actualizar el nombre del mundo
    $updateNombreSQL = $con->prepare("UPDATE mundo SET nombre = ? WHERE id_mundo = ?");
    $updateNombreSQL->execute([$nombre, $id_mundo]);

    // Mostrar un mensaje de actualización exitosa y cerrar la ventana
    echo '<script>alert("Actualización Exitosa");</script>';
    echo '<script>window.close();</script>';
} elseif (isset($_POST["delete"])) {
    // Si se hace clic en el botón de eliminar
    $id_mundo = $_POST['id_mundo'];

    // Ejecutar la consulta para eliminar el mundo
    $deleteSQL = $con->prepare("DELETE FROM mundo WHERE id_mundo = ?");
    $deleteSQL->execute([$id_mundo]);

    // Mostrar un mensaje y cerrar la ventana
    echo '<script>alert("Registro Eliminado Exitosamente");</script>';
    echo '<script>window.close();</script>';
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
        <h2 class="mt-5 mb-3">Actualizar datos del mundo</h2>
        <form autocomplete="off" name="frm_consulta" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label for="id_mundo" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_mundo" name="id_mundo" value="<?php echo $mundo['id_mundo'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $mundo['nombre'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="mundo" class="col-sm-2 col-form-label">Mundo</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="mundo" name="mundo">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary" name="update">Actualizar</button>
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
