<?php
session_start();
require_once '../config/connection.php'; 
require_once '../models/usuarios_model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // El modelo busca al usuario (debe devolver 'rol', 'nombre', e 'id')
    $usuario = buscarUsuarioPorEmail($connection, $email, $password);

    // Refactorización para un manejo más limpio
    if ($usuario) {
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol']    = $usuario['rol']; 

        // IDs específicos según el rol
        if ($usuario['rol'] === 'admin') {
            // Guardamos la llave de administrador
            $_SESSION['id_admin'] = $usuario['id_admin']; 
            header("Location: panel_admin_controller.php");
        } else {
            // Guardamos la llave de cliente y su mesa
            $_SESSION['id_cliente'] = $usuario['id_cliente']; 
            $_SESSION['id_mesa']    = $usuario['id_mesa'];
            
            if(isset($usuario['metodo_pago'])) {
                $_SESSION['metodo_pago'] = $usuario['metodo_pago'];
            }

            header("Location: panel_cliente_controller.php");
        }
    } else {
        // Error de credenciales. Redirección con mensaje de error sin usar JS
        echo "<script>alert('Usuario o contraseña incorrecto'); window.location.href='../views/login.html';</script>";
    }
    exit();
    
}