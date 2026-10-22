<?php
session_start();
require_once '../config/connection.php';
require_once '../models/productos_model.php'; 

// Verificamos acceso solo a administradores
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$id_admin = $_SESSION['id_admin'];
$nombre_admin = $_SESSION['nombre'] ?? 'Admin';

// Capturamos el ID que viene por la URL (GET)
$id_producto = $_GET['id'] ?? null;

if (!$id_producto) { 
    // Si no hay ID, lo mandamos de vuelta al panel del administrador
    header("Location: ../controllers/panel_admin_controller.php"); 
    exit(); 
}

// Pedimos al modelo los datos de ese producto específico
$producto = getProductoPorId($connection, $id_producto, $id_admin);

if (!$producto) {
    exit("Lo sentimos, el producto con ID $id_producto no existe en la base de datos.");
}

// Cargamos la vista de la página editar producto 
require_once '../views/editar_producto.php';
?>