<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php'; 
require_once '../models/usuarios_model.php'; 
require_once '../models/mesas_model.php';

// Verificamos acceso a clientes
if (!isset($_SESSION['id_cliente']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../login.html");
    exit();
}

// Recoge los datos de sesión
$id_cliente = $_SESSION['id_cliente']; 
$nombre     = $_SESSION['nombre'];
$id_mesa    = isset($_SESSION['id_mesa']) ? $_SESSION['id_mesa'] : 0;

// Aseguraramos que exista un metodo de pago por defecto
if (!isset($_SESSION['metodo_pago'])) {
    $_SESSION['metodo_pago'] = 'Efectivo';
}

// Obtenemos el historial de pedidos activos para el cliente
$resultado_consumo = getProductosTicket($connection, $id_cliente, 0);

// Cargamos el listado de mesas disponibles y su estado para el selector de mesa
$resultado_mesas = getMesasConEstado($connection);

// Cargamos la vista panel de cliente
include '../views/panel_cliente.php';
?>