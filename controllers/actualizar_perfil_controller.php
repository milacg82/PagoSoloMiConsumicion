<?php
session_start();
require_once '../config/connection.php';
require_once '../models/usuarios_model.php'; 

// Verificamos acceso a clientes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_SESSION['id_cliente']; 
    
    // Recogemos datos actualizado del formulario
    $nuevo_nombre = $_POST['nombre'];
    $nueva_mesa = $_POST['nueva_mesa']; 
    $metodo_pago = $_POST['metodo_pago'];

    // Intentamos actualizar la información en la base de datos
    if (actualizarPerfilCliente($connection, $id_cliente, $nuevo_nombre, $metodo_pago, $nueva_mesa)) {
        
        // Sincronizamos los cambios en la sesión para mantener la consistencia en la interfaz
        $_SESSION['nombre'] = $nuevo_nombre;
        $_SESSION['id_mesa'] = $nueva_mesa;
        $_SESSION['metodo_pago'] = $metodo_pago; 

        header("Location: panel_cliente_controller.php?actualizado=success");
    } else {
        header("Location: panel_cliente_controller.php?actualizado=error");
    }
    
    // El cierre de conexión se encarga el modelo o al final del script automáticamente
    exit(); 
}
?>