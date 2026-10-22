<?php
session_start();
require_once '../config/connection.php';
require_once '../models/productos_model.php';

// Validamos que exista una sesión de administrador activa 
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Recogemos los datos de sesión
$id_admin = $_SESSION['id_admin'];
$nombre_admin = $_SESSION['nombre'] ?? 'Admin';

// Obtenemos los productos por categoría mediante el modelo
$resultado = getProductosPorCategoria($connection, $id_admin);

$productos_por_categoria = [];
if ($resultado) {
    // Agrupamos los productos en un array usando la categoría
    while ($fila = $resultado->fetch_assoc()) {
        $productos_por_categoria[$fila['categoria']][] = $fila;
    }
}

// Cargamos la vista de panel de control del administrador 
include '../views/panel_admin.php';
?>