<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php';
require_once '../models/usuarios_model.php';

// Identificación de usuario para la visualización 
if (isset($_SESSION['id_admin']) && $_SESSION['rol'] === 'admin' && isset($_SESSION['ultimo_cliente_cobrado'])) {
    
    $id_visualizar = $_SESSION['ultimo_cliente_cobrado'];
    $estado = 1; // Ya está pagado en la base de datos

} elseif (isset($_SESSION['id_cliente']) && $_SESSION['rol'] === 'cliente') {
    
    // CLIENTE: Está viendo su propio consumo (su cuenta actual)
    $id_visualizar = $_SESSION['id_cliente']; 
    $estado = 0; // Sigue pendiente hasta que el admin lo cobre

} else {
    // Si no es admin cobrando ni cliente logueado, fuera
    header("Location: ../login.html"); 
    exit();
}

// Obtenemos los datos para la generación del ticket
$nombre_mostrado = getNombreCliente($connection, $id_visualizar);
$resultado = getProductosTicket($connection, $id_visualizar, $estado);
$total_ticket = 0;

// Cargamos la vista del ticket con los datos procesado
require_once '../views/ver_ticket.php';