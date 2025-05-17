<?php 
    include(__DIR__ . "/../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Lista de Productos
    $productos = $conexion->prepare("SELECT id_producto, nombre, stock, peso, cantidad_por_paleta FROM productos WHERE estado = 'activo' AND stock > 0");
    $productos->execute();
    $lista_productos = $productos->fetchAll(PDO::FETCH_ASSOC);

    // Lista de vehiculos activos
    $vehiculos = $conexion->prepare("SELECT id_vehiculo, codigo, tipo, capacidad_carga_kg, capacidad_paletas FROM vehiculos WHERE estado = 'activo'");
    $vehiculos->execute();
    $lista_vehiculos = $vehiculos->fetchAll(PDO::FETCH_ASSOC);

    // Lista de clientes
    $clientes = $conexion->prepare("SELECT id_cliente, nombre, razon_social, documento_tipo, documento_numero FROM clientes WHERE estado = 'activo' ORDER BY nombre ASC");
    $clientes->execute();
    $lista_clientes = $clientes->fetchAll(PDO::FETCH_ASSOC);

    // Lista de conductores activos
    $conductores = $conexion->prepare("SELECT id_conductor, nombre, cedula FROM conductores WHERE estado = 'activo' ORDER BY nombre ASC");
    $conductores->execute();
    $lista_conductores = $conductores->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener las transferencias con información relacionada
    $sql = "SELECT t.*, 
            c.nombre as nombre_cliente, 
            c.region as destino,
            v.tipo as tipo_vehiculo, 
            v.placa as placa_vehiculo,
            con.nombre as nombre_conductor, 
            con.cedula as cedula_conductor,
            COALESCE(SUM(dt.peso_kg), 0) as peso_total,
            COUNT(dt.id_detalle) as cantidad_productos,
            COALESCE(SUM(dt.cantidad / p.cantidad_por_paleta), 0) as total_paletas
            FROM transferencias t 
            LEFT JOIN clientes c ON t.id_cliente = c.id_cliente 
            LEFT JOIN vehiculos v ON t.id_vehiculo = v.id_vehiculo 
            LEFT JOIN conductores con ON t.id_conductor = con.id_conductor
            LEFT JOIN detalle_transferencia dt ON t.id_transferencia = dt.id_transferencia
            LEFT JOIN productos p ON dt.id_producto = p.id_producto
            WHERE t.estado = 'pendiente' OR t.estado = 'cancelada'
            GROUP BY t.id_transferencia, t.fecha_despacho, t.id_vehiculo, t.id_conductor, 
                     t.id_responsable, t.origen, t.id_cliente, t.direccion_destino, 
                     t.observacion, t.estado, t.fecha_creacion,
                     c.nombre, c.region, v.tipo, v.placa, con.nombre, con.cedula
            ORDER BY t.fecha_creacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $transferencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $consulta_trans_completadas = $conexion->prepare("SELECT t.*, 
            c.nombre as nombre_cliente, 
            c.region as destino,
            v.tipo as tipo_vehiculo, 
            v.placa as placa_vehiculo,
            con.nombre as nombre_conductor, 
            con.cedula as cedula_conductor,
            COALESCE(SUM(dt.peso_kg), 0) as peso_total,
            COUNT(dt.id_detalle) as cantidad_productos,
            COALESCE(SUM(dt.cantidad / p.cantidad_por_paleta), 0) as total_paletas
            FROM transferencias t 
            LEFT JOIN clientes c ON t.id_cliente = c.id_cliente 
            LEFT JOIN vehiculos v ON t.id_vehiculo = v.id_vehiculo 
            LEFT JOIN conductores con ON t.id_conductor = con.id_conductor
            LEFT JOIN detalle_transferencia dt ON t.id_transferencia = dt.id_transferencia
            LEFT JOIN productos p ON dt.id_producto = p.id_producto
            WHERE t.estado = 'completada'
            GROUP BY t.id_transferencia, t.fecha_despacho, t.id_vehiculo, t.id_conductor, 
                     t.id_responsable, t.origen, t.id_cliente, t.direccion_destino, 
                     t.observacion, t.estado, t.fecha_creacion,
                     c.nombre, c.region, v.tipo, v.placa, con.nombre, con.cedula");
    $consulta_trans_completadas->execute();
    $trans_completadas = $consulta_trans_completadas->fetchAll(PDO::FETCH_ASSOC);

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
                                id_conductor,
                                origen,
                                id_cliente,
                                estado,
                                observacion
                                ) VALUES (
                                NOW(),
                                :fecha_despacho,
                                :id_vehiculo,
                                :id_conductor,
                                :origen,
                                :id_cliente,
                                'pendiente',
                                :observacion
                                )";
            
            $stmt_transferencia = $conexion->prepare($sql_transferencia);
            $stmt_transferencia->bindParam(':fecha_despacho', $fecha_despacho, PDO::PARAM_STR);
            $stmt_transferencia->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmt_transferencia->bindParam(':id_conductor', $_POST['id_conductor'], PDO::PARAM_INT);
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
            $paletas_totales = 0;

            // Obtener la capacidad de paletas del vehículo
            $sql_vehiculo = "SELECT capacidad_paletas FROM vehiculos WHERE id_vehiculo = :id_vehiculo";
            $stmt_vehiculo = $conexion->prepare($sql_vehiculo);
            $stmt_vehiculo->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmt_vehiculo->execute();
            $vehiculo_info = $stmt_vehiculo->fetch(PDO::FETCH_ASSOC);
            
            if(!$vehiculo_info) {
                throw new Exception("Vehículo no encontrado");
            }
            
            $capacidad_paletas = $vehiculo_info['capacidad_paletas'];

            foreach($_POST['productos'] as $producto) {
                $id_producto = $producto['id'];
                $cantidad = $producto['cantidad'];
                $peso = $producto['peso'] * $cantidad;
                $peso_total += $peso;
                
                // Obtener información del producto
                $sql_get_producto = "SELECT stock, cantidad_por_paleta FROM productos WHERE id_producto = :id_producto";
                $stmt_get_producto = $conexion->prepare($sql_get_producto);
                $stmt_get_producto->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $stmt_get_producto->execute();
                $producto_info = $stmt_get_producto->fetch(PDO::FETCH_ASSOC);
                
                if(!$producto_info) {
                    throw new Exception("Producto no encontrado: ID ".$id_producto);
                }
                
                $stock_actual = $producto_info['stock'];
                $cantidad_por_paleta = $producto_info['cantidad_por_paleta'];
                
                // Calcular cantidad de paletas
                $paletas = ceil($cantidad / $cantidad_por_paleta);
                $paletas_totales += $paletas;
                
                // Verificar stock
                if($cantidad > $stock_actual) {
                    throw new Exception("Stock insuficiente para el producto ID ".$id_producto);
                }
            }

            // Verificar capacidad de paletas del vehículo después de procesar todos los productos
            if($paletas_totales > $capacidad_paletas) {
                throw new Exception("La cantidad de paletas (".$paletas_totales.") supera la capacidad del vehículo (".$capacidad_paletas.")");
            }

            // Si todo está bien, proceder a guardar los detalles
            foreach($_POST['productos'] as $producto) {
                $id_producto = $producto['id'];
                $cantidad = $producto['cantidad'];
                $peso = $producto['peso'] * $cantidad;
                
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
                $sql_update_stock = "UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto";
                $stmt_update_stock = $conexion->prepare($sql_update_stock);
                $stmt_update_stock->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
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
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['id_transferencia']) || empty($_POST['id_transferencia'])) {
                throw new Exception("Selecciona una transferencia");
            }

            $id_transferencia = $_POST['id_transferencia'];
            
            // Primero obtener la información de la transferencia
            $query_transferencia = "SELECT t.*, 
                                  c.nombre as nombre_cliente, 
                                  c.region as destino,
                                  v.tipo as tipo_vehiculo, 
                                  v.placa as placa_vehiculo,
                                  con.nombre as nombre_conductor, 
                                  con.cedula as cedula_conductor
                                  FROM transferencias t 
                                  LEFT JOIN clientes c ON t.id_cliente = c.id_cliente 
                                  LEFT JOIN vehiculos v ON t.id_vehiculo = v.id_vehiculo 
                                  LEFT JOIN conductores con ON t.id_conductor = con.id_conductor
                                  WHERE t.id_transferencia = :id_transferencia";
            
            $stmt_transferencia = $conexion->prepare($query_transferencia);
            $stmt_transferencia->bindParam(':id_transferencia', $id_transferencia, PDO::PARAM_INT);
            $stmt_transferencia->execute();
            $info_transferencia = $stmt_transferencia->fetch(PDO::FETCH_ASSOC);
            
            // Luego obtener los productos
            $query = "SELECT p.nombre, p.foto, p.sku, p.peso as peso_caja, p.cantidad_por_paleta, dt.cantidad, dt.peso_kg
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
            
            $resultado = array(
                'info_transferencia' => $info_transferencia,
                'productos' => array()
            );
            
            foreach ($productos as $producto) {
                $paletas = ceil($producto['cantidad'] / $producto['cantidad_por_paleta']);
                $resultado['productos'][] = array(
                    'nombre' => $producto['nombre'],
                    'foto' => $producto['foto'],
                    'cantidad' => $producto['cantidad'],
                    'peso_unitario' => number_format($producto['peso_caja'], 2),
                    'peso_total' => number_format($producto['peso_caja'] * $producto['cantidad'], 2),
                    'codigo_sku' => $producto['sku'],
                    'cantidad_por_paleta' => $producto['cantidad_por_paleta'],
                    'paletas' => $paletas
                );
            }
            
            echo json_encode($resultado);
            
        } catch (Exception $e) {
            echo json_encode(array(
                'error' => true,
                'mensaje' => $e->getMessage()
            ));
        }
        exit;
    }

    // Actualizar estado de transferencia
    if(isset($_POST['action']) && $_POST['action'] === 'actualizar_estado_transferencia') {
        $id_transferencia = $_POST['id_transferencia'];
        $nuevo_estado = $_POST['nuevo_estado'];
        
        try {
            $conexion->beginTransaction();

            // Obtener el estado actual y los productos de la transferencia
            $sql_estado = "SELECT estado FROM transferencias WHERE id_transferencia = :id_transferencia";
            $stmt_estado = $conexion->prepare($sql_estado);
            $stmt_estado->bindParam(':id_transferencia', $id_transferencia, PDO::PARAM_INT);
            $stmt_estado->execute();
            $estado_actual = $stmt_estado->fetchColumn();

            // Obtener los productos y cantidades de la transferencia
            $sql_productos = "SELECT dt.id_producto, dt.cantidad, p.stock 
                            FROM detalle_transferencia dt 
                            INNER JOIN productos p ON dt.id_producto = p.id_producto 
                            WHERE dt.id_transferencia = :id_transferencia";
            $stmt_productos = $conexion->prepare($sql_productos);
            $stmt_productos->bindParam(':id_transferencia', $id_transferencia, PDO::PARAM_INT);
            $stmt_productos->execute();
            $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

            // Manejar el stock según el cambio de estado
            if ($estado_actual === 'pendiente' && $nuevo_estado === 'cancelada') {
                // Devolver stock al cancelar
                foreach ($productos as $producto) {
                    $nuevo_stock = $producto['stock'] + $producto['cantidad'];
                    $sql_update = "UPDATE productos SET stock = :nuevo_stock WHERE id_producto = :id_producto";
                    $stmt_update = $conexion->prepare($sql_update);
                    $stmt_update->bindParam(':nuevo_stock', $nuevo_stock, PDO::PARAM_INT);
                    $stmt_update->bindParam(':id_producto', $producto['id_producto'], PDO::PARAM_INT);
                    $stmt_update->execute();
                }
            } 
            elseif ($estado_actual === 'cancelada' && $nuevo_estado === 'pendiente') {
                // Verificar y restar stock al restaurar
                foreach ($productos as $producto) {
                    if ($producto['stock'] < $producto['cantidad']) {
                        throw new Exception("Stock insuficiente para el producto ID " . $producto['id_producto']);
                    }
                }
                
                // Si hay stock suficiente, proceder con la actualización
                foreach ($productos as $producto) {
                    $nuevo_stock = $producto['stock'] - $producto['cantidad'];
                    $sql_update = "UPDATE productos SET stock = :nuevo_stock WHERE id_producto = :id_producto";
                    $stmt_update = $conexion->prepare($sql_update);
                    $stmt_update->bindParam(':nuevo_stock', $nuevo_stock, PDO::PARAM_INT);
                    $stmt_update->bindParam(':id_producto', $producto['id_producto'], PDO::PARAM_INT);
                    $stmt_update->execute();
                }
            }

            // Actualizar el estado de la transferencia
            $sql = "UPDATE transferencias SET estado = :nuevo_estado WHERE id_transferencia = :id_transferencia";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_STR);
            $stmt->bindParam(':id_transferencia', $id_transferencia, PDO::PARAM_INT);
            $stmt->execute();

            $conexion->commit();
            echo json_encode(['success' => true, 'mensaje' => 'Estado actualizado correctamente']);

        } catch(Exception $e) {
            $conexion->rollBack();
            echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
        }
        exit;
    }
?>