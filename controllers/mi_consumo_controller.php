<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php';

// Verificamos que el usuario tenga una sesión de cliente activa 
if (!isset($_SESSION['id_cliente']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../views/login.html");
    exit();
}

// Obtenemos datos del contexto de la sesión actual
$id_cliente = $_SESSION['id_cliente'];
$nombre     = $_SESSION['nombre'];
$id_mesa    = $_SESSION['id_mesa'] ?? '0';

// Recuperamos los productos asociados al ticket del cliente
$resultado = getProductosTicket($connection, $id_cliente, 0);

// Calculamos el total de consumo e iteramos los resultados para la vista 
$total_consumo = 0;
$items = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $total_consumo += $fila['subtotal'];
        $items[] = $fila; 
    }
}

// Cargamos la vista de consumo con los datos procesados
include '../views/mi_consumo.php';
?>