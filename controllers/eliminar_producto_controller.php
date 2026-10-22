<?php
session_start();
require_once '../config/connection.php';
require_once '../models/productos_model.php';

// Verificamos acceso solo a administradores
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    exit("Acceso denegado");
}

$id = $_GET['id'] ?? null;
$id_admin = $_SESSION['id_admin'];

// Validamos que el ID existe, en caso afirmativo se elimina el producto y redirige según el resultado
if ($id) {
    if (eliminarProducto($connection, $id, $id_admin)) {
        header("Location: panel_admin_controller.php?res=deleted");
    } else {
        header("Location: panel_admin_controller.php?res=error_delete");
    }
} else {
    header("Location: panel_admin_controller.php");
}
exit();
