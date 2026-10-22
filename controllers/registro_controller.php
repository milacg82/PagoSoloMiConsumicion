<?php
session_start();
require_once '../config/connection.php'; 
require_once '../models/usuarios_model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rol      = $_POST['rol']; 
    $nombre   = $_POST['nombre'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Gestionamos el registro según el rol administrador
    if ($rol === 'admin') {
        // Registsramos al usuario en la base de datos
        if (registrarAdmin($connection, $nombre, $email, $password)) {
            
            // Creamos la sesión con los datos recién creados
            $_SESSION['id_admin'] = $connection->insert_id; 
            $_SESSION['nombre']     = $nombre;
            $_SESSION['rol']        = 'admin';

            // Redireccionamos al controlador del panel de bienvenida
            header("Location: panel_control_controller.php");
            exit();
        }
    } else {
        // Registro de cliente con valores por defecto para mesa y método de pago
        if (registrarCliente($connection, $nombre, $email, $password, null, 'Efectivo')) {
            
            // Establecemos contexto de sesión para el nuevo cliente
            $_SESSION['id_cliente'] = $connection->insert_id; 
            $_SESSION['nombre']     = $nombre;
            $_SESSION['rol']        = 'cliente';
            $_SESSION['id_mesa']    = 0; 
            $_SESSION['metodo_pago'] = 'Efectivo';

            header("Location: panel_control_controller.php");
            exit();
        }
    }
}