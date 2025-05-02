<?php
include("database/conexion.php");
$objeto= new Conexion();
$conexion=$objeto->Conectar();

if(isset($_POST['sesion'])){
    if(!empty($_POST['usuario']) && !empty($_POST['password'])){
        $nombre_usuario = $_POST['usuario'];
        $password = $_POST['password'];

        $sentencia=$conexion->prepare(" SELECT id_usuario, nombre, usuario, password, foto_usuario, estado
                                        FROM usuarios
                                        WHERE usuario=:usuario");
        $sentencia->bindParam(":usuario",$nombre_usuario);
        $sentencia->execute();
        $usuario=$sentencia->fetch(PDO::FETCH_LAZY);

        // Verificar si existe el usuario y si la contraseña coincide
        if($usuario){
            if($usuario['estado'] === 'activo') {
                if(password_verify($password, $usuario['password'])){
                    session_start();

                    // Variables de Sesión
                    $_SESSION['id_usuario']=$usuario['id_usuario'];
                    $_SESSION['nombre']=$usuario['nombre'];
                    $_SESSION['foto_usuario'] = $usuario['foto_usuario'];
                    
                    // Actualizar última conexión
                    $ultima_conexion = $conexion->prepare(" UPDATE usuarios
                                                            SET ultima_conexion = NOW() 
                                                            WHERE id_usuario = :id_usuario");
                    $ultima_conexion->bindParam(":id_usuario", $usuario['id_usuario']);
                    $ultima_conexion->execute();
                    
                    header("Location: vistas/productos/vista_productos.php");
                    exit(); 
                } else {
                    $mensaje = "Error: Usuario y/o contraseña incorrecta.";
                    header("Location: index.php?mensaje=" . urlencode($mensaje));
                    exit();
                }
            } else {
                $mensaje = "Error: Usuario Inactivo";
                header("Location: index.php?mensaje=" . urlencode($mensaje));
                exit();
            }
        } else {
            $mensaje = "Error: Usuario y/o contraseña incorrecta.";
            header("Location: index.php?mensaje=" . urlencode($mensaje));
            exit();
        }
    }
    else{
        $mensaje = "Ingresa el usuario y/o contraseña";
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit();
    }
}
?>

