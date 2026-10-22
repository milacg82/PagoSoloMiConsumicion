<?php
// Reanudamos la sesión existente 
session_start();
// Liberamos todas las variables de sesión cargadas en memoria
session_unset();
// Eliminamos la sesión en el servidor
session_destroy();

header("Location: ../index.php?logout=success"); 
exit();

?>