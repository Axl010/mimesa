<?php
    session_start();    
    include("../database/conexion.php");

    // Verificar si la sesión está activa y tiene el usuario
    if (isset($_SESSION['nombre'])) {
        $usuario_id = $_SESSION['id_usuario'];

        // Actualizar última conexión
        $objeto = new Conexion();
        $conexion = $objeto->Conectar();

        $ultima_conexion = $conexion->prepare(" UPDATE usuarios 
                                                SET ultima_conexion = NOW() 
                                                WHERE id_usuario = :id_usuario");
        $ultima_conexion->bindParam(":id_usuario", $usuario_id);
        $ultima_conexion->execute();
    }
    
    // Limpiar y destruir la sesión
    session_unset();
    session_destroy();

    // Eliminar cookie de la sesión
    if(ini_get("session.use_cookies")){
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 4200,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    header("Location:../index.php");
    exit;
?>