<?php
// Incluir archivo de conexión a la base de datos
include("../../db/conection.php");
$db = new Database();
$conexion = $db->conectar();
// Iniciar sesión
session_start();

// Verificar si se proporcionó el parámetro 'username' a través de la solicitud GET
if (isset($_GET["username"])) {
    // Obtener el username de la solicitud GET
    $username = $_GET["username"];

    try {
        // Consulta para obtener el correo electrónico asociado al username
        $consulta = $conexion->prepare("SELECT correo, id_estado FROM jugador WHERE username = :username");
        $consulta->bindParam(':username', $username);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        // Si se encontró el resultado
        if ($resultado) {
            // Obtener el correo electrónico y el estado actual del jugador
            $correo = $resultado['correo'];
            $id_estado_actual = $resultado['id_estado'];

            // Determinar el nuevo estado del jugador
            $nuevo_id_estado = ($id_estado_actual == 1) ? 2 : 1; // Si está activo (1), lo desactivamos (2), y viceversa

            // Actualizar el id_estado en la tabla 'jugador'
            $statement = $conexion->prepare("UPDATE jugador SET id_estado = :nuevo_estado WHERE correo = :correo");
            $statement->bindParam(':nuevo_estado', $nuevo_id_estado);
            $statement->bindParam(':correo', $correo);
            $statement->execute();

            // Verificar si se actualizó correctamente
            if ($statement->rowCount() > 0) {
                // Configurar detalles del correo electrónico
                $titulo = ($nuevo_id_estado == 1) ? "Activar cuenta" : "Desactivar cuenta";
                $msj = ($nuevo_id_estado == 1) ? "La cuenta ha sido activada" : "La cuenta ha sido desactivada";
                $tucorreo = "From: gutierrezleyvaangietatiana@gmail.com";

                // Enviar correo electrónico
                if (mail($correo, $titulo, $msj, $tucorreo)) {
                    // Mostrar mensaje de éxito y redirigir a la página correspondiente
                    $mensaje_exito = ($nuevo_id_estado == 1) ? "activada" : "desactivada";
                    echo '<script>alert("La cuenta ' . $correo . ' ha sido ' . $mensaje_exito . '."); window.location = "jug_inac.php";</script>';
                    exit(); // Salir del script después de redirigir
                } else {
                    echo "Error al enviar el correo.";
                }
            } else {
                echo "No se realizó la actualización.";
            }
        } else {
            echo "El username no se encuentra en la base de datos.";
        }
    } catch (PDOException $e) {
        // Manejo de excepciones de PDO
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Se necesita el username del jugador!";
}
?>

