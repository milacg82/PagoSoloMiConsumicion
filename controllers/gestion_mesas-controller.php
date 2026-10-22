<?php
session_start();
require_once '../config/connection.php';
require_once '../models/mesas_model.php'; 

// Verificamos acceso solo a administradores
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Guardamos el ID y el Nombre de la sesión para vincular la mesa correctamente
$id_admin = $_SESSION['id_admin']; 
$nombre_admin = $_SESSION['nombre'];
$mensaje_html = ""; 

// Lógica para añadir mesa
if (isset($_POST['add_mesa'])) {
    // Usamos el nombre unificado 'numero_mesa'
    $numero_mesa = $_POST['numero_mesa'];
    
    if (agregarMesa($connection, $numero_mesa, $id_admin)) {
        // Redirigimos con éxito para que el script.js maneje la alerta
        header("Location: gestion_mesas_controller.php?res=success");
        exit();
    } else {
        // En caso de error (ej: número duplicado), mostramos mensaje en la vista actual
        $mensaje_html = "<div class='alert alert-error'>❌ Error: El número de mesa ya existe o hubo un problema técnico.</div>";
    }
}

// Obtenemos los datos para la vista. Esta función contará los pedidos pendientes porque las mesas tendrán id_admin 
$resultado = getMesasConEstado($connection);

// Cargamos la vista
include '../views/gestion_mesas.php';
?>