<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php'; 
require_once '../models/mesas_model.php';   

// Validamos privilegios de administrador antes de permitir el acceso a la gestión de mesas
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Validamos ID de la mesa 
$id_mesa = $_GET['id'] ?? null;
if (!$id_mesa) {
    header("Location: gestion_mesas_controller.php");
    exit();
}

// Recuperamos la identidad del administrador para personalización de la vista
$nombre_admin = $_SESSION['nombre'];

// Obtenemos los detalles de la mase y sus pedidos pendientes dades la base de datos
$datos_mesa = getMesaPorId($connection, $id_mesa); 

$resultado_pedidos = getPedidosPendientesPorMesa($connection, $id_mesa);

// Inicializamos variables de estado para el resumen de consumo en la vista
$total_mesa = 0;
$cliente_actual = "";

// Cargamos la vista con el detalle completo de la mesa seleccionada
include '../views/ver_detalle_mesa.php';
?>