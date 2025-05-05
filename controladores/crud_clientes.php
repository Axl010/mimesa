<?php 
    include_once("../../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $mensaje = '';
    // Lista de Usuarios
    $consulta_cliente = $conexion->prepare("SELECT * FROM clientes ORDER BY id_cliente DESC");
    $consulta_cliente->execute();
    $lista_clientes = $consulta_cliente->fetchAll(PDO::FETCH_ASSOC);

    // Crear nuevo cliente
    if(isset($_POST['crear_cliente'])) {
        // Obtener datos del formulario
        $nombre = trim($_POST['nombre']);
        $razon_social = trim($_POST['razon_social'] ?? '');
        $documento_tipo = trim($_POST['documento_tipo']);
        $documento_numero = trim($_POST['documento_numero']);
        $direccion = trim($_POST['direccion']);
        $region = trim($_POST['region']);
        $telefono = trim($_POST['telefono']);
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $tipo_cliente = isset($_POST['tipo_cliente']) ? trim($_POST['tipo_cliente']) : 'regular';
        $estado = isset($_POST['estado']) ? trim($_POST['estado']) : 'activo';
        $notas = isset($_POST['notas']) ? trim($_POST['notas']) : '';
        
        // Verificar si el número de documento ya existe
        $verificar_documento = "SELECT COUNT(*) FROM clientes WHERE documento_tipo = :documento_tipo AND documento_numero = :documento_numero";
        $stmt_verificar = $conexion->prepare($verificar_documento);
        $stmt_verificar->bindParam(':documento_tipo', $documento_tipo, PDO::PARAM_STR);
        $stmt_verificar->bindParam(':documento_numero', $documento_numero, PDO::PARAM_STR);
        $stmt_verificar->execute();
        
        if($stmt_verificar->fetchColumn() > 0) {
            $mensaje = "El documento ya existe en la base de datos";
            header("Location: ../clientes/vista_clientes.php?mensaje=".urlencode($mensaje));
            exit();
        }
        
        // Insertar cliente
        $crear_cliente = "INSERT INTO clientes (
                          nombre, 
                          razon_social,
                          documento_tipo,
                          documento_numero,
                          direccion, 
                          region, 
                          telefono, 
                          email, 
                          tipo_cliente, 
                          estado, 
                          notas, 
                          fecha_registro) 
                          VALUES (
                          :nombre, 
                          :razon_social,
                          :documento_tipo,
                          :documento_numero,
                          :direccion, 
                          :region, 
                          :telefono, 
                          :email, 
                          :tipo_cliente, 
                          :estado, 
                          :notas, 
                          NOW())";
        
        $stmt_crear = $conexion->prepare($crear_cliente);
        $stmt_crear->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt_crear->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
        $stmt_crear->bindParam(':documento_tipo', $documento_tipo, PDO::PARAM_STR);
        $stmt_crear->bindParam(':documento_numero', $documento_numero, PDO::PARAM_STR);
        $stmt_crear->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt_crear->bindParam(':region', $region, PDO::PARAM_STR);
        $stmt_crear->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt_crear->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt_crear->bindParam(':tipo_cliente', $tipo_cliente, PDO::PARAM_STR);
        $stmt_crear->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt_crear->bindParam(':notas', $notas, PDO::PARAM_STR);
        
        $resultado = $stmt_crear->execute();
        
        if($resultado) {
            $mensaje = "Cliente creado exitosamente";
        } else {
            $mensaje = "Error al crear el cliente";
        }
        
        header("Location: ../clientes/vista_clientes.php?mensaje=".urlencode($mensaje));
        exit();
    }

    // Editar cliente
    if(isset($_POST['editar_cliente'])) {
        $id_cliente = $_POST['id_cliente'];
        $nombre = trim($_POST['nombre']);
        $razon_social = trim($_POST['razon_social'] ?? '');
        $documento_tipo = trim($_POST['documento_tipo']);
        $documento_numero = trim($_POST['documento_numero']);
        $direccion = trim($_POST['direccion']);
        $region = trim($_POST['region']);
        $telefono = trim($_POST['telefono']);
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $tipo_cliente = isset($_POST['tipo_cliente']) ? trim($_POST['tipo_cliente']) : 'regular';
        $estado = isset($_POST['estado']) ? trim($_POST['estado']) : 'activo';
        $notas = isset($_POST['notas']) ? trim($_POST['notas']) : '';
        
        // Verificar si el documento ya existe (excepto para este cliente)
        $verificar_documento = "SELECT COUNT(*) FROM clientes WHERE documento_tipo = :documento_tipo AND documento_numero = :documento_numero AND id_cliente != :id_cliente";
        $stmt_verificar = $conexion->prepare($verificar_documento);
        $stmt_verificar->bindParam(':documento_tipo', $documento_tipo, PDO::PARAM_STR);
        $stmt_verificar->bindParam(':documento_numero', $documento_numero, PDO::PARAM_STR);
        $stmt_verificar->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt_verificar->execute();
        
        if($stmt_verificar->fetchColumn() > 0) {
            $mensaje = "El documento ya existe en la base de datos";
            header("Location: ../clientes/vista_clientes.php?mensaje=".urlencode($mensaje));
            exit();
        }
        
        // Actualizar cliente
        $actualizar_cliente = "UPDATE clientes SET 
                              nombre = :nombre, 
                              razon_social = :razon_social,
                              documento_tipo = :documento_tipo,
                              documento_numero = :documento_numero, 
                              direccion = :direccion, 
                              region = :region, 
                              telefono = :telefono, 
                              email = :email, 
                              tipo_cliente = :tipo_cliente, 
                              estado = :estado, 
                              notas = :notas 
                              WHERE id_cliente = :id_cliente";
        
        $stmt_actualizar = $conexion->prepare($actualizar_cliente);
        $stmt_actualizar->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':documento_tipo', $documento_tipo, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':documento_numero', $documento_numero, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':region', $region, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':tipo_cliente', $tipo_cliente, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':notas', $notas, PDO::PARAM_STR);
        $stmt_actualizar->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        
        $resultado = $stmt_actualizar->execute();
        
        if($resultado) {
            $mensaje = "Cliente actualizado exitosamente";
        } else {
            $mensaje = "Error al actualizar el cliente";
        }
        
        header("Location: ../clientes/vista_clientes.php?mensaje=".urlencode($mensaje));
        exit();
    }
?>