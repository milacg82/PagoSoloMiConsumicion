<?php
session_start();
require_once '../config/connection.php';
require_once '../models/pedidos_model.php';
require_once '../models/productos_model.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validamos sesión de cliente
    if (!isset($_SESSION['id_cliente']) || $_SESSION['rol'] !== 'cliente') {
        header("Location: ../login.html");
        exit();
    }

    // Recogemos datos de la sesión
    $id_cliente   = $_SESSION['id_cliente'];
    $id_mesa      = $_SESSION['id_mesa'];
    
    // Si no hay método de pago en la sesión, ponemos 'efectivo' por defecto
    $metodo_pago  = $_SESSION['metodo_pago'] ?? 'efectivo'; 
    
    // Rocogemos datos del formulario
    $id_producto  = $_POST['id_producto'];
    $cantidad     = 1; 

    // Obtenemos el precio, validamos que el precio llegue. Si no llega, lo ponemos a 0.00 para evitar el error Fatal de MySQL
    $precio = isset($_POST['precio']) ? $_POST['precio'] : 0.00;

    // Llamamos al modelo con los 7 argumentos, los mismos que hemos definidos en pedidos_models.php
    if (crearPedido($connection, $id_cliente, $id_mesa, $id_producto, $cantidad, $precio, $metodo_pago)) {
        // Creacción de pedido exitosa, redirigimos con confirmación
        header("Location: ver_carta_controller.php?success=1");
    } else {
        // Creacción de pedido erróneo
        header("Location: ver_carta_controller.php?error=1");
    }
    exit();

} else {
    // Si se accede directamente al archivo sin POST
    header("Location: ver_carta_controller.php");
    exit();
}