<?php
// Función para registrar administradores
function registrarAdmin($connection, $nombre, $email, $password) {
    $sql = "INSERT INTO usuario_administrador (nombre, email, password) VALUES (?, ?, ?) ";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $password);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

// Función para registrar clientes
function registrarCliente($connection, $nombre, $email, $password, $id_mesa = null, $metodo_pago = "Efectivo" ) {
    $sql = "INSERT INTO usuario_cliente (nombre, email, password, id_mesa, metodo_pago) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $email, $password, $id_mesa, $metodo_pago);
    $res = $stmt->execute();
    $stmt->close();
    return $res;

}
 // Función para buscar usuarios administradores y clientes

function buscarUsuarioPorEmail($connection, $email, $password) {
    $sql = "SELECT id_cliente, nombre, id_mesa, metodo_pago, rol
            FROM usuario_cliente
            WHERE email = ? AND password = ?";
    
    $stmt = $connection->prepare($sql); 
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows ===1) {
        $usuario = $res->fetch_assoc();
        $stmt->close();
        return $usuario; 
    }
    $stmt->close(); 

    $sql = "SELECT id_admin, nombre, rol
            FROM usuario_administrador
            WHERE email = ? AND password = ?";
    
    $stmt =$connection->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $usuario = $res->fetch_assoc();
        $stmt->close();
        return $usuario; 
    }
    $stmt->close();

    return null; // Si no existe cliente o administrador
    
}

// Obtiene el consumo pendiente agrupando productos iguales
function getConsumoPendiente($connection, $id_cliente) {
    $sql = "SELECT 
                p.nombre_producto, 
                p.precio, 
                SUM(ped.cantidad) as cantidad_total, 
                (p.precio * SUM(ped.cantidad)) as subtotal_agrupado
            FROM pedidos ped
            INNER JOIN productos p ON ped.id_producto = p.id_producto
            WHERE ped.id_cliente = ? AND ped.estado_pago = 0
            GROUP BY p.id_producto, p.nombre_producto, p.precio"; 
            
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    return $stmt->get_result();
}

function getNombreCliente($connection, $id_cliente) {
    $sql = "SELECT nombre FROM usuario_cliente WHERE id_cliente = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res ? $res['nombre'] : "Cliente Desconocido";
}

// Actualiza los datos de perfil de un cliente incluyendo nombre, forma de pago y mesa
function actualizarPerfilCliente($connection, $id_usuario, $nuevo_nombre, $metodo_pago, $nueva_mesa) {
    $sql = "UPDATE usuario_cliente SET nombre = ?, metodo_pago = ?, id_mesa = ? WHERE id_cliente = ?";
    
    $stmt = $connection->prepare($sql);
    
    $stmt->bind_param("ssii", $nuevo_nombre, $metodo_pago, $nueva_mesa, $id_usuario);
    
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}
?>
