<?php 
    include_once("../../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Lista de Productos
    $consulta = $conexion->prepare("    
        SELECT id_producto, sku, nombre, descripcion, foto, peso, estado, stock, cantidad_por_paleta, fecha_creacion
        FROM productos
    ");
    $consulta->execute();
    $lista_productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    // Desactivar o activar producto
    if (isset($_GET['id']) && isset($_GET['nuevoEstado'])) {
        include_once("../database/conexion.php");
        $objeto = new Conexion();
        $conexion = $objeto->Conectar();

        $id = $_GET['id'];
        $nuevoEstado = $_GET['nuevoEstado'];
    
        $sentencia = $conexion->prepare("UPDATE productos SET estado = :nuevoEstado WHERE id_producto = :id");
        $sentencia->bindParam(':nuevoEstado', $nuevoEstado);
        $sentencia->bindParam(':id', $id);

        if ($sentencia->execute()) {
            $mensaje = "El estado del producto ha sido actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el estado del producto.";
        }

        header("Location: view_productos.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    // Agregar Nuevo Producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_producto'])) {
        $sku = $_POST['sku'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $peso = $_POST['peso'];
        $estado = $_POST['estado'];
        $stock = $_POST['stock'];
        $cantidad_por_paleta = $_POST['cantidad_por_paleta'];
        
        // Manejo de la foto
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
            $nombre_foto = $_FILES['foto']['name'];
            $temp_foto = $_FILES['foto']['tmp_name'];
            
            // Crear directorio si no existe
            $directorio = "../../photos/productos/";
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            
            // Generar nombre único para la foto
            $extension = pathinfo($nombre_foto, PATHINFO_EXTENSION);
            $nombre_unico = uniqid() . '.' . $extension;
            $ruta_destino = $directorio . $nombre_unico;
            
            // Mover la foto
            if (move_uploaded_file($temp_foto, $ruta_destino)) {
                $foto = "../../photos/productos/" . $nombre_unico;
            } else {
                $foto = null;
            }
        } else {
            $foto = null;
        }
        
        // Crear el producto
        $create = $conexion->prepare("  
            INSERT INTO productos (
                sku, 
                nombre, 
                descripcion, 
                foto, 
                peso, 
                estado,
                stock,
                cantidad_por_paleta
            ) VALUES (
                :sku, 
                :nombre, 
                :descripcion, 
                :foto, 
                :peso, 
                :estado,
                :stock,
                :cantidad_por_paleta
            )");
            
        $create->bindParam(':sku', $sku);
        $create->bindParam(':nombre', $nombre);
        $create->bindParam(':descripcion', $descripcion);
        $create->bindParam(':foto', $foto);
        $create->bindParam(':peso', $peso);
        $create->bindParam(':estado', $estado);
        $create->bindParam(':stock', $stock);
        $create->bindParam(':cantidad_por_paleta', $cantidad_por_paleta);

        try {
            $create->execute();
            $mensaje = "Producto agregado exitosamente";
        }catch(Exception $e) {
            $mensaje = "Error" . $e->getMessage();
        }
        header("Location: vista_productos.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    // Verificar si se recibió un ID válido
    if (isset($_GET['id_producto']) && is_numeric($_GET['id_producto'])) {
        $id_producto = $_GET['id_producto'];

        // Obtener datos del producto
        $consulta = $conexion->prepare("
            SELECT id_producto, sku, nombre, descripcion, foto, peso, estado, stock, cantidad_por_paleta
            FROM productos
            WHERE id_producto = :id_producto
        ");
        $consulta->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->execute();
        $producto = $consulta->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            echo "Producto no encontrado.";
            exit();
        }
    }

    // Editar Producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_producto'])) { 
        $id_producto = $_GET['id_producto'];
        $sku = $_POST['sku'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $peso = $_POST['peso'];
        $estado = $_POST['estado'];
        $stock = $_POST['stock'];
        $cantidad_por_paleta = $_POST['cantidad_por_paleta'];
        $foto = $_FILES['foto'];

        $ruta_destino = $producto['foto'];
        if(!empty($foto['name'])) {
            $nombre_foto = $_FILES['foto']['name'];
            $temp_foto = $_FILES['foto']['tmp_name'];
            
            // Crear directorio si no existe
            $directorio = "../../photos/productos/";
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            
            // Generar nombre único para la foto
            $extension = pathinfo($nombre_foto, PATHINFO_EXTENSION);
            $nombre_unico = uniqid() . '.' . $extension;
            $ruta_destino = $directorio . $nombre_unico;
            
            // Mover la foto
            if (move_uploaded_file($temp_foto, $ruta_destino)) {
                $foto = "../../photos/productos/" . $nombre_unico;
            } else {
                $foto = $producto['foto']; // Mantener la foto anterior si hay error
            }
            
            // Actualizar con nueva foto
            $consulta = $conexion->prepare("
                UPDATE productos 
                SET nombre = :nombre, 
                    sku = :sku, 
                    descripcion = :descripcion, 
                    foto = :foto, 
                    peso = :peso, 
                    estado = :estado,
                    stock = :stock,
                    cantidad_por_paleta = :cantidad_por_paleta
                WHERE id_producto = :id_producto
            ");
            $consulta->bindParam(":foto", $foto);
        } else {
            // Actualizar sin cambiar la foto
            $consulta = $conexion->prepare("
                UPDATE productos 
                SET nombre = :nombre, 
                    sku = :sku, 
                    descripcion = :descripcion, 
                    peso = :peso, 
                    estado = :estado,
                    stock = :stock,
                    cantidad_por_paleta = :cantidad_por_paleta
                WHERE id_producto = :id_producto
            ");
        }
        
        $consulta->bindParam(":nombre", $nombre);
        $consulta->bindParam(":sku", $sku);
        $consulta->bindParam(":descripcion", $descripcion);
        $consulta->bindParam(":peso", $peso);
        $consulta->bindParam(":estado", $estado);
        $consulta->bindParam(":stock", $stock);
        $consulta->bindParam(":cantidad_por_paleta", $cantidad_por_paleta);
        $consulta->bindParam(":id_producto", $id_producto);

        try {
            $consulta->execute();
            $mensaje = "$nombre actualizado exitosamente.";
            header("Location: vista_productos.php?mensaje=" . urlencode($mensaje));
            exit();
        } catch(Exception $e) {
            $mensaje = "Error al actualizar el producto: " . $e->getMessage();
            exit();
        }
    }
?>