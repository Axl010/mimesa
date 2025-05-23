<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Obtener la ruta absoluta del proyecto
    $ruta_proyecto = $_SERVER['DOCUMENT_ROOT'] . '/mimesa';
    include_once($ruta_proyecto . "/database/conexion.php");
    
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Lista de Usuarios
    $consulta_user = $conexion->prepare("   SELECT id_usuario, nombre, usuario, foto_usuario, estado, telefono, ultima_conexion, fecha_creacion 
                                            FROM usuarios ORDER BY id_usuario DESC");
    $consulta_user->execute();
    $lista_usuarios = $consulta_user->fetchAll(PDO::FETCH_ASSOC);

    // Insertar Datos
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_usuario'])) {
        $nombre = $_POST['nombre'];
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $telefono = $_POST['telefono'];
        $estado = $_POST['estado'];

        if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
            $nombre_foto = $_FILES['foto']['name'];
            $temp_foto = $_FILES['foto']['tmp_name'];

            $ruta_destino = '../../photos/usuarios/' . time() . "_" . $nombre_foto;
            move_uploaded_file($temp_foto, $ruta_destino);
        } else {
            $ruta_destino = '../../photos/usuarios/default_user.png'; // Si no se subio imagen
        }

        // Agregar encriptacion sha256
        $password_hash= password_hash($contrasena, PASSWORD_DEFAULT);
        error_log("Contraseña encriptada: ".$password_hash);

        try{
            $insert_user = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, password, foto_usuario, telefono, estado) VALUES (:nombre, :usuario, :password_hash, :foto, :telefono, :estado)");
            $insert_user->bindParam(":nombre", $nombre);
            $insert_user->bindParam(":usuario", $usuario);
            $insert_user->bindParam(":password_hash", $password_hash);
            $insert_user->bindParam(":foto", $ruta_destino);
            $insert_user->bindParam(":telefono", $telefono);
            $insert_user->bindParam(":estado", $estado);
            $insert_user->execute();

            $mensaje = "Usuario agregado exitosamente.";
        }catch(Exception $e) {
            $mensaje = "Error " . $e->getMessage();
        }

        header("Location: vista_usuarios.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    // Verificar si el usuario existe
    if(isset($_GET['id_usuario']) && is_numeric($_GET['id_usuario'])){
        $id_usuario = $_GET['id_usuario'];

        $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = :id_usuario");
        $consulta->bindParam(":id_usuario", $id_usuario);
        $consulta->execute();
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if(!$usuario){
            header("Location: vista_usuarios.php?mensaje=" . urlencode('Error: Usuario no encontrado'));
            exit();
        }
    }

    // Editar Usuario
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_usuario'])){
        $id_usuario = $_GET['id_usuario'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $estado = $_POST['estado'];

        $consulta_edit = $conexion->prepare("SELECT password, foto_usuario FROM usuarios WHERE id_usuario = :id_usuario");
        $consulta_edit->bindParam("id_usuario", $id_usuario);
        $consulta_edit->execute();
        $datos_usuario = $consulta_edit->fetch(PDO::FETCH_ASSOC);

        if($datos_usuario) {
            $password_actual = $datos_usuario['password'];
            $foto_actual = $datos_usuario['foto_usuario'];

            //Verificar si se ingresó una nueva contraseña 
            if(!empty($contrasena)){
                $password_hash = password_hash($contrasena, PASSWORD_DEFAULT); // Generar nuevo hash
            } else {
                $password_hash = $password_actual;
            }

            // Verificar si se subió una nueva foto
            if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $nombre_foto = $_FILES['foto']['name'];
                $temp_foto = $_FILES['foto']['tmp_name'];

                $ruta_destino = '../../photos/usuarios/' . time() . "_" . $nombre_foto;
                move_uploaded_file($temp_foto, $ruta_destino);

                // Eliminar la foto anterior si no es la predeterminada
                if (!empty($foto_actual) && file_exists($foto_actual) && strpos($foto_actual, 'default_user') === false) {
                    unlink($foto_actual);
                }
            } else {
                $ruta_destino = $foto_actual;
            }

            try {
                $update_user = $conexion->prepare(" UPDATE usuarios SET nombre = :nombre, telefono = :telefono, usuario = :usuario, password = :password_hash, estado = :estado, foto_usuario = :foto
                                                    WHERE id_usuario = :id_usuario");
                $update_user->bindParam(":nombre", $nombre);
                $update_user->bindParam(":telefono", $telefono);
                $update_user->bindParam(":usuario", $usuario);
                $update_user->bindParam(":password_hash", $password_hash);
                $update_user->bindParam(":estado", $estado);
                $update_user->bindParam(":foto", $ruta_destino);
                $update_user->bindParam(":id_usuario", $id_usuario);
                $update_user->execute();

                $mensaje = "$nombre actualizado exitosamente.";
            } catch(Exception $e) {
                $mensaje = 'Error al actualizar ' . $e->getMessage();
            }

            header("Location: vista_usuarios.php?mensaje=" . urlencode($mensaje));
            exit();
        } else {
            $mensaje = 'Error: Usuario no encontrado.';
            header('Location: vista_usuarios.php?mensaje=' . urlencode($mensaje));
            exit();
        }
    }

    // Verificar contraseña del usuario
    if(isset($_POST['action']) && $_POST['action'] === 'verificar_password') {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['password']) || empty($_POST['password'])) {
                throw new Exception("La contraseña es requerida");
            }

            if (!isset($_POST['id_usuario']) || empty($_POST['id_usuario'])) {
                throw new Exception("ID de usuario requerido");
            }

            $password = $_POST['password'];
            $id_usuario = $_POST['id_usuario'];
            
            // Obtener la contraseña del usuario que se está editando
            $sql = "SELECT password FROM usuarios WHERE id_usuario = :id_usuario";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$usuario) {
                throw new Exception("Usuario no encontrado");
            }
            
            // Verificar la contraseña
            if (password_verify($password, $usuario['password'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Contraseña incorrecta']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'mensaje' => $e->getMessage()]);
        }
        exit;
    }
?>  