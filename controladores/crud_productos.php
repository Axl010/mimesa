<?php 
    include_once("../../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Lista de Productos
    $consulta = $conexion->prepare("    
        SELECT p.id_producto, p.producto, p.descripcion, p.precio, p.foto, p.estado, p.fecha_creacion, p.stock
        FROM productos p
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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $producto = $_POST['producto'];
        $descripcion = $_POST['descripcion'];
        $precio = (int)$_POST['precio'];
        $id_categoria = $_POST['id_categoria'];
        $estado = $_POST['estado'];
        
        // Manejo de la foto
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
            $nombre_foto = $_FILES['foto']['name'];
            $temp_foto = $_FILES['foto']['tmp_name'];

            $ruta_destino = '../../photos/' . time() . "_" . $nombre_foto; // Evita nombres duplicados
            move_uploaded_file($temp_foto, $ruta_destino);
        }else {
            $ruta_destino = '../../photos/default_producto.jpg'; // Si no se subio imagen
        }

        try {
            $conexion->beginTransaction();

            $create = $conexion->prepare("  INSERT INTO productos (producto, descripcion, precio, foto, estado)
                                            VALUES (:producto, :descripcion, :precio, :foto, :estado)");
            $create->bindParam(':producto', $producto);
            $create->bindParam(':descripcion', $descripcion);
            $create->bindParam(':precio', $precio);
            $create->bindParam(':foto', $ruta_destino);
            $create->bindParam(':estado', $estado);
            $create->execute();
    
            $id_producto = $conexion->lastInsertId();

            // Insertar relación con la categoría si se proporcionó
            if (!is_null($id_categoria)) {
                $relacion = $conexion->prepare("    INSERT INTO categoria_producto (id_categoria, id_producto)
                                                    VALUES (:id_categoria, :id_producto)");
                $relacion->bindParam(":id_categoria", $id_categoria);
                $relacion->bindParam(":id_producto", $id_producto);
                $relacion->execute();
            }

            $conexion->commit();
            $mensaje = "Producto agregado exitosamente";
        }catch(Exception $e) {
            $conexion->rollBack();
            $mensaje = "Error" . $e->getMessage();
        }
        header("Location: view_productos.php?mensaje=" . urlencode($mensaje));
        exit();
    }
?>