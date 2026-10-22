<?php
require_once '../config/connection.php'; 
require_once '../models/productos_model.php'; 
session_start();

// Verificamos el acceso del administrador
if (!isset($_SESSION['id_admin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$id_admin = $_SESSION['id_admin']; 
$nombre_admin = $_SESSION['nombre'] ?? 'Administrador';
$mensaje_resultado = "";

// Lógica de procesamiento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_producto'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    
    // Gestión de la imagen
    $nombre_archivo = basename($_FILES['imagen']['name']); 
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $directorio_destino = "../img/productos/";

    if (move_uploaded_file($ruta_temporal, $directorio_destino . $nombre_archivo)) {
        if (insertarProducto($connection, $nombre_producto, $categoria, $precio, $nombre_archivo, $id_admin)) {
            header("Location: panel_admin_controller.php?res=success");
            exit();
        } else {
            $mensaje_resultado = "<div class='alert alert-danger'>❌ Error en la base de datos al insertar.</div>";
        }
    } else {
        $mensaje_resultado = "<div class='alert alert-danger'>❌ Error al subir la imagen. Verifica que la carpeta ../img/productos/ exista.</div>";
    }
}

// Cargar la vista al final
require_once '../views/gestion_productos.php';
?>