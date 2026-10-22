<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: ../views/login.html");
    exit();
}

$nombre = $_SESSION['nombre'];
$rol    = $_SESSION['rol'];

// "No asignada" para evitar errores si el cliente no tiene mesa
$id_mesa = isset($_SESSION['id_mesa']) ? $_SESSION['id_mesa'] : "No asignada";

include '../views/panel_control.php';
?>