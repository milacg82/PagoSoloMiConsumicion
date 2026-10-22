<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php';

// Validamos que exista una sesión activa, independientemente del rol
if (!isset($_SESSION['id_admin']) && !isset($_SESSION['id_cliente'])) {
    header("Location: ../login.html");
    exit();
}

// Si es admin, el ID viene por POST (desde la lista de cobros)
// Si es cliente, el ID es su propia sesión 'id_cliente'.
$id_cliente = ($_SESSION['rol'] === 'admin') ? $_POST['id_cliente'] : $_SESSION['id_cliente'];

$_SESSION['ultimo_cliente_cobrado'] = $id_cliente;

//  Ejecutamos la lógica de cobro y redirigimos según el rol del usuario
if (procesarPagoCliente($connection, $id_cliente)) {
    
    if ($_SESSION['rol'] === 'admin') {
        // Éxito para Admin: va al ticket (Funcionalidad eliminada de la intefaz)
        header("Location: cobros_controller.php?res=success");
    } else {
        // Éxito para Cliente: va a ver su ticket/comprobante
        header("Location: ver_ticket_controller.php");
    }
} else {
    // Si falla, redirigimos a cobros_controller.php
    $location = ($_SESSION['rol'] === 'admin') ? "cobros_controller.php?error=1" : "panel_cliente_controller.php?error=1";
    header("Location: $location");
}
exit();