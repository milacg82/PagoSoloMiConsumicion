<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php';

// Verificamos el acceso del cliente
if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../views/login.html");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$nombre = $_SESSION['nombre'];

// Obtenemos el historial de pagos realizados por el cliente
$resultado = getHistorialPagos($connection, $id_cliente);

// Procesamos los resultados y lo convertimos en un array asociativo
$pagos = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $pagos[] = $fila;
    }
}
// Cargamos la vista encargada de mostrar el historial
include '../views/historial_pagos.php';
?>