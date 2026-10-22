<?php
session_start();
require_once '../config/connection.php';
require_once '../models/mesas_model.php'; 

// Verificamos acceso solo a administradores
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['id'])) {
    $id_mesa = $_GET['id'];

    // Usamos el modelo para verificar ocupación
    if (contarPedidosPendientesMesa($connection, $id_mesa) > 0) {
        header("Location: gestion_mesas_controller.php?res=error_ocupada");
    } else {
        // Usamos el modelo para eliminar mesa
        if (eliminarMesa($connection, $id_mesa)) {
            header("Location: gestion_mesas_controller.php?res=deleted");
        } else {
            header("Location: gestion_mesas_controller.php?res=error_historial");
        }
    }
    exit();
} else {
    header("Location: gestion_mesas_controller.php");
    exit();
}