<?php
    // Verificar si el usuario ha iniciado sesión
    session_start();
    
    // Si no existe la sesión de usuario, redirigir al login
    if(!isset($_SESSION['id_usuario'])) {
        $mensaje = "Error: Debes iniciar sesión";
        
        // Redirección absoluta al index.php con el mensaje y el icono explícito
        header("Location: /mimesa/index.php?mensaje=" . urlencode($mensaje) . "&icono=error");
        exit();
    }
?> 