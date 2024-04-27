    <?php
    session_start();

    require_once("../../../db/conection.php");
    $db = new Database();
    $con = $db->conectar();

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM jugador WHERE username = $id";
        $result = $con->query($sql);
        $jugador = $result->fetch(PDO::FETCH_ASSOC);
    } else {
        // Si no se proporciona un ID válido, redirigir a la página de jugadores
        header('Location: jugador.php');
        exit();
    }

    if (isset($_POST["update"])) {
        $correo = $_POST['correo'];
        $nombre = $_POST['nombre'];
        $username = $_POST['username'];
        $id_rol = $_POST['id_rol'];
        $id_estado = $_POST['id_estado'];


        $updateSQL = $con->prepare("UPDATE jugador SET correo = ?, nombre = ?, username = ?, id_rol = ?, id_estado = ? WHERE username = '$username'");
        $updateSQL->execute([$correo, $nombre, $username, $id_rol, $id_estado ]);


        echo '<script>alert("Actualización Exitosa");</script>';
        echo '<script>window.close();</script>';
    } elseif (isset($_POST["delete"])) {
        $username = $_POST['username'];
        echo "ID del jugador a eliminar: " . $username; // Depuración
    
        // Eliminar registros relacionados en la tabla detalle_batalla
        $deleteDetalleSQL = $con->prepare("DELETE FROM detalle_batalla WHERE id_jugador_atacante = ? OR id_jugador_atacado = ?");
        $deleteDetalleSQL->execute([$id_jugador, $id_jugador]);
    
        // Verificar si se eliminaron los registros relacionados
        $deletedRowCount = $deleteDetalleSQL->rowCount();
        if ($deletedRowCount > 0) {
            echo "Se han eliminado $deletedRowCount registros relacionados de la tabla detalle_batalla."; // Depuración
        }
    
        // Eliminar el registro del jugador
        $deleteJugadorSQL = $con->prepare("DELETE FROM jugador WHERE username = ?");
        if ($deleteJugadorSQL->execute([$username])) {
            echo "Registro Eliminado Exitosamente"; // Depuración
            header('Location: ../tablas/jugador.php');
            exit;
        } else {
            echo "Error al eliminar el registro"; // Depuración
            print_r($deleteJugadorSQL->errorInfo()); // Mostrar información de error de la consulta SQL
        }
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
            <h2 class="mt-5 mb-3">Actualizar datos del jugador</h2>
            <form autocomplete="off" name="frm_consulta" method="POST">
                <div class="form-group row">
                    <label for="correo" class="col-sm-2 col-form-label">Correo</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $jugador['correo'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $jugador['nombre'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $jugador['username'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_rol" class="col-sm-2 col-form-label">Rol</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="id_rol" name="id_rol">
                            <?php
                            $control = $con->prepare("SELECT * FROM rol");
                            $control->execute();
                            while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($fila['id_rol'] == $jugador['id_rol']) ? "selected" : "";
                                echo "<option value='" . $fila['id_rol'] . "' $selected>" . $fila['rol'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="id_estado" class="col-sm-2 col-form-label">Estado</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="id_estado" name="id_estado">
                            <?php
                            $control = $con->prepare("SELECT * FROM estado where id_estado <=    2");
                            $control->execute();
                            while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($fila['id_estado'] == $jugador['id_estado']) ? "selected"  : "";
                                echo "<option value='" . $fila['id_estado'] . "' $selected>" . $fila['estado'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <script>
                    function confirmarEliminacion() {
                        if (confirm("¿Estás seguro de que deseas eliminar este jugador?")) {
                            document.forms["frm_consulta"].submit();
                            // Limpiar el formulario
                            document.getElementById("frm_consulta").reset();
                            return true; // Añadimos esto para que el formulario se envíe después de la confirmación
                        } else {
                            // Cancelar la eliminación
                            return false;
                        }
                    }
                </script>
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <input type="submit" class="btn btn-primary" name="update" value="Actualizar">
                        <button type="submit" class="btn btn-danger" name="delete" onclick="return confirmarEliminacion()">Eliminar</button>
                    </div>
                </div>




            </form>
        </div>
    </body>

    </html>