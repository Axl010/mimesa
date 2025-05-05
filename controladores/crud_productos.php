<?php 
    include_once("../../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Lista de Productos
    $consulta = $conexion->prepare("    
        SELECT id_producto, sku, nombre, descripcion, precio, foto, peso, estado, stock, fecha_creacion
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
        $nombre = $_POST['producto'];
        $descripcion = $_POST['descripcion'];
        $precio = (int)$_POST['precio'];
        $peso = $_POST['peso'];
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
            $create = $conexion->prepare("  INSERT INTO productos (sku, nombre, descripcion, precio, foto, peso, estado)
                                            VALUES (:sku, :nombre, :descripcion, :precio, :foto, :peso, :estado)");
            $create->bindParam(':sku', $sku);
            $create->bindParam(':nombre', $nombre);
            $create->bindParam(':descripcion', $descripcion);
            $create->bindParam(':precio', $precio);
            $create->bindParam(':foto', $ruta_destino);
            $create->bindParam(':peso', $peso);
            $create->bindParam(':estado', $estado);
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
            SELECT id_producto, sku, nombre, descripcion, precio, foto, peso, estado, stock
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
        $nombre = $_POST['producto'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $peso = $_POST['peso'];
        $estado = $_POST['estado'];
        $foto = $_FILES['foto'];

        $ruta_destino = $producto['foto'];
        if(!empty($foto['name'])) {
            $nombre_foto = $_FILES['foto']['name'];
            $temp_foto = $_FILES['foto']['tmp_name'];

            // Generar una nueva ruta para la nueva foto
            $ruta_destino = '../../photos/' . time() . "_" . $nombre_foto;
            move_uploaded_file($temp_foto, $ruta_destino);
            
            // Eliminar la foto anterior si no es la predeterminada
            if (!empty($producto['foto']) && file_exists($producto['foto']) && strpos($producto['foto'], 'default') === false) {
                unlink($producto['foto']);
            }
        }

        // Actualizar los Datos 
        try {
            $consulta = $conexion->prepare("UPDATE productos SET nombre = :nombre, sku = :sku, descripcion = :descripcion, precio = :precio, foto = :foto, peso = :peso, estado = :estado
                                            WHERE id_producto = :id_producto");
            $consulta->bindParam(":sku", $sku);
            $consulta->bindParam(":nombre", $nombre);
            $consulta->bindParam(":descripcion", $descripcion);
            $consulta->bindParam(":precio", $precio);
            $consulta->bindParam(":foto", $ruta_destino);
            $consulta->bindParam(":peso", $peso);
            $consulta->bindParam(":estado", $estado);
            $consulta->bindParam(":id_producto", $id_producto);
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