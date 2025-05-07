<?php 
    include(__DIR__ . "/../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Lista de Productos
    $productos = $conexion->prepare("SELECT id_producto, nombre, precio, stock, peso FROM productos WHERE estado = 'activo' AND stock > 0");
    $productos->execute();
    $lista_productos = $productos->fetchAll(PDO::FETCH_ASSOC);

    // Lista de vehiculos
    $vehiculos = $conexion->prepare("SELECT id_vehiculo, codigo, tipo, capacidad_carga_kg, capacidad_paletas FROM vehiculos");
    $vehiculos->execute();
    $lista_vehiculos = $vehiculos->fetchAll(PDO::FETCH_ASSOC);

    // Lista de clientes
    $clientes = $conexion->prepare("SELECT id_cliente, nombre, razon_social, documento_tipo, documento_numero FROM clientes WHERE estado = 'activo' ORDER BY nombre ASC");
    $clientes->execute();
    $lista_clientes = $clientes->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener las transferencias con información relacionada
    $sql = "SELECT t.*, c.nombre as nombre_cliente, c.region as destino, v.tipo as tipo_vehiculo,
    COALESCE(SUM(dt.peso_kg), 0) as peso_total,
    COUNT(dt.id_detalle) as cantidad_productos
    FROM transferencias t 
    LEFT JOIN clientes c ON t.id_cliente = c.id_cliente 
    LEFT JOIN vehiculos v ON t.id_vehiculo = v.id_vehiculo 
    LEFT JOIN detalle_transferencia dt ON t.id_transferencia = dt.id_transferencia
    GROUP BY t.id_transferencia
    ORDER BY t.fecha_creacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $transferencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Definir fecha actual
    $fecha_actual = date('Y-m-d');

    // Procesar el formulario de creación de transferencia
    if(isset($_POST['confirmar_transferencia'])) {
        try {
            // Iniciar transacción
            $conexion->beginTransaction();
            
            // Obtener datos del formulario
            $fecha_despacho = $_POST['fecha_despacho'];
            $id_vehiculo = $_POST['id_vehiculo'];
            $origen = $_POST['origen'];
            $id_cliente = $_POST['id_cliente'];
            $observacion = isset($_POST['observacion']) ? trim($_POST['observacion']) : '';
            
            // Verificar que existan productos
            if(!isset($_POST['productos']) || empty($_POST['productos'])) {
                throw new Exception("No se han agregado productos a la transferencia");
            }
            
            // Crear la transferencia
            $sql_transferencia = "INSERT INTO transferencias (
                                fecha_creacion,
                                fecha_despacho,
                                id_vehiculo,
                                origen,
                                id_cliente,
                                estado,
                                observacion
                                ) VALUES (
                                NOW(),
                                :fecha_despacho,
                                :id_vehiculo,
                                :origen,
                                :id_cliente,
                                'pendiente',
                                :observacion
                                )";
            
            $stmt_transferencia = $conexion->prepare($sql_transferencia);
            $stmt_transferencia->bindParam(':fecha_despacho', $fecha_despacho, PDO::PARAM_STR);
            $stmt_transferencia->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmt_transferencia->bindParam(':origen', $origen, PDO::PARAM_STR);
            $stmt_transferencia->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt_transferencia->bindParam(':observacion', $observacion, PDO::PARAM_STR);
            
            $stmt_transferencia->execute();
            $id_transferencia = $conexion->lastInsertId();
            
            // Verificar que se haya creado la transferencia
            if(!$id_transferencia) {
                throw new Exception("Error al crear la transferencia");
            }
            
            // Recorrer y guardar cada producto
            $peso_total = 0;
            foreach($_POST['productos'] as $producto) {
                $id_producto = $producto['id'];
                $cantidad = $producto['cantidad'];
                $peso = $producto['peso'] * $cantidad;
                $peso_total += $peso;
                
                // Obtener precio y stock del producto
                $sql_get_producto = "SELECT precio, stock FROM productos WHERE id_producto = :id_producto";
                $stmt_get_producto = $conexion->prepare($sql_get_producto);
                $stmt_get_producto->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $stmt_get_producto->execute();
                $producto_info = $stmt_get_producto->fetch(PDO::FETCH_ASSOC);
                
                if(!$producto_info) {
                    throw new Exception("Producto no encontrado: ID ".$id_producto);
                }
                
                $precio = $producto_info['precio'];
                $stock_actual = $producto_info['stock'];
                
                // Verificar stock
                if($cantidad > $stock_actual) {
                    throw new Exception("Stock insuficiente para el producto ID ".$id_producto);
                }
                
                // Guardar detalle de la transferencia
                $sql_detalle = "INSERT INTO detalle_transferencia (
                            id_transferencia, 
                            id_producto, 
                            cantidad,
                            peso_kg
                            ) VALUES (
                            :id_transferencia,
                            :id_producto,
                            :cantidad,
                            :peso_kg
                            )";
                
                $stmt_detalle = $conexion->prepare($sql_detalle);
                $stmt_detalle->bindParam(':id_transferencia', $id_transferencia, PDO::PARAM_INT);
                $stmt_detalle->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $stmt_detalle->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt_detalle->bindParam(':peso_kg', $peso, PDO::PARAM_STR);
                $stmt_detalle->execute();
                
                // Actualizar stock
                $nuevo_stock = $stock_actual - $cantidad;
                $sql_update_stock = "UPDATE productos SET stock = :nuevo_stock WHERE id_producto = :id_producto";
                $stmt_update_stock = $conexion->prepare($sql_update_stock);
                $stmt_update_stock->bindParam(':nuevo_stock', $nuevo_stock, PDO::PARAM_INT);
                $stmt_update_stock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $stmt_update_stock->execute();
            }
            
            // Confirmar transacción
            $conexion->commit();
            
            // Redirigir con mensaje de éxito
            header("Location: vista_transferencias.php?mensaje=Transferencia creada con éxito");
            exit();
            
        } catch(Exception $e) {
            // Revertir cambios en caso de error
            $conexion->rollBack();
            
            // Redirigir con mensaje de error
            header("Location: vista_transferencias.php?error=".urlencode($e->getMessage()));
            exit();
        }
    }

    // Obtener productos de una transferencia
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_productos_transferencia') {
        try {
            $id_transferencia = $_POST['id_transferencia'];
            
            $query = "SELECT p.nombre, p.foto, p.precio, dt.cantidad, dt.peso_kg, (dt.peso_kg * dt.cantidad) as peso_total
                      FROM detalle_transferencia dt
                      INNER JOIN productos p ON dt.id_producto = p.id_producto
                      WHERE dt.id_transferencia = :id_transferencia";
                      
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':id_transferencia', $id_transferencia, PDO::PARAM_INT);
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($productos)) {
                throw new Exception("No se encontraron productos para esta transferencia");
            }
            
            $resultado = array();
            foreach ($productos as $producto) {
                $resultado[] = array(
                    'nombre' => $producto['nombre'],
                    'foto' => $producto['foto'],
                    'precio' => number_format($producto['precio'], 2),
                    'cantidad' => $producto['cantidad'],
                    'peso_unitario' => number_format($producto['peso_kg'], 3),
                    'peso_total' => number_format($producto['peso_total'], 3)
                );
            }
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'error' => true,
                'mensaje' => $e->getMessage()
            ));
        }
        exit;
    }
?>