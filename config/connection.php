<?php 
// Configuración de la BBDD
$server = "localhost";
$user = "root";
$password = "";
$db = "pago_solo_mi_consumicion_db";

// Creamos la conexión de la BBDD
$connection = mysqli_connect($server, $user, $password, $db);

// Definimos rutas globales para las imágenes
define('RUTA_IMG', '../img/productos/');

// Comprobamos si la conexión es exitosa
if (!$connection) {
    die ("Error al conectar con la Base de Datos: " .mysqli_connect_error());
}
?>