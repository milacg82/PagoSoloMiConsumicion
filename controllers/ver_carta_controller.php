<?php
session_start();
require_once '../config/connection.php';
require_once '../models/productos_model.php';
require_once '../models/mesas_model.php';

// Identificar el administrador (id_admin)
$id_admin = null;
$id_mesa_sesion = $_SESSION['id_mesa'] ?? 0;

if ($id_mesa_sesion > 0) {
    // Si hay mesa, buscamos al dueño de esa mesa
    $id_admin = getIdAdminPorMesa($connection, $id_mesa_sesion);
} 

// Si no hay mesa o no se encontró admin, usamos el admin por defecto (Cien Vinos)
if (!$id_admin) {
    $id_admin = 1; 
}

// Obtener productos ($id_admin siempre tendrá un valor)
$resultado = getProductosPorCategoria($connection, $id_admin);

// Convertimos el resultado a un array para su uso en la vista
$productos = [];
while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
}

// Cargamos la vista de la carta
require_once '../views/ver_carta.php';