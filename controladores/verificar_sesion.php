<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Verificar si el usuario ha iniciado sesión
    if(!isset($_SESSION['id_usuario'])) {
        $mensaje = "Error: Debes iniciar sesión";
        
        // Redirección absoluta al index.php con el mensaje y el icono explícito
        header("Location: /mimesa/index.php?mensaje=" . urlencode($mensaje) . "&icono=error");
        exit();
    }
?> 