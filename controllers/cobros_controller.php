<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php'; 

// Seguridad, solo acceso administradores. Si un cliente intenta entrar aquí, lo mandamos al login
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$nombre_admin = $_SESSION['nombre'];

$resultado = getCobrosResumen($connection);

// Cargamos la vista
include '../views/cobros.php'; 
?>