<?php
session_start();
require_once '../config/connection.php';
require_once '../models/productos_model.php'; 

// Verificamos acceso solo a administradores
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Definimos $id_admin 
$id_admin = $_SESSION['id_admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto     = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre']; 
    $precio          = $_POST['precio'];
    $categoria       = $_POST['categoria'];

    // Obtenemos datos actuales. Usamos el ID del admin de la sesión para asegurar que el producto le pertenece
    $producto_actual = getProductoPorId($connection, $id_producto, $id_admin);
    
    if (!$producto_actual) {
        // Redirigimos al controlador
        header("Location: ../controllers/panel_admin_controller.php?res=error_not_found");
        exit();
    }

    $imagen_actual = $producto_actual['imagen']; 
    $nombre_imagen = $imagen_actual; // Por defecto mantenemos la vieja

    // Lógica de para la subida de imágenes
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $archivo_nombre = time() . "_" . $_FILES['imagen']['name'];
        
        // Ruta destino de las imágenes 
        $ruta_destino = "../img/" . $archivo_nombre;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $nombre_imagen = $archivo_nombre;
            
            // Borramos la imagen anterior
            if (!empty($imagen_actual)) {
                $ruta_vieja = "../img/" . $imagen_actual;
                if (file_exists($ruta_vieja)) {
                    unlink($ruta_vieja);
                }
            }
        }
    }

    // Actualizamos la base de datos
    if (actualizarProducto($connection, $id_producto, $nombre_producto, $precio, $categoria, $id_admin, $nombre_imagen)) {
        header("Location: panel_admin_controller.php?res=updated");
    } else {
        header("Location: panel_admin_controller.php?res=error");
    }
    exit();
}