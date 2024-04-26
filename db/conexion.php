<?php
$servidor = "localhost";
$usuario_bd = "root";
$contraseña_bd = "";
$nombre_bd = "valorant";

// Establece la conexión con la base de datos
$conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>