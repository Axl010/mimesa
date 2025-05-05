<?php 
    include("../../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

     // Lista de Productos
     $productos = $conexion->prepare("SELECT id_producto, nombre, precio, stock, peso FROM productos WHERE estado = 'activo' AND stock > 0");
     $productos->execute();
     $lista_productos = $productos->fetchAll(PDO::FETCH_ASSOC);

     $vehiculos = $conexion->prepare("SELECT id_vehiculo, codigo, tipo, capacidad_carga_kg, capacidad_paletas FROM vehiculos");
     $vehiculos->execute();
     $lista_vehiculos = $vehiculos->fetchAll(PDO::FETCH_ASSOC);

     // Lista de clientes
     $clientes = $conexion->prepare("SELECT id_cliente, nombre, razon_social, documento_tipo, documento_numero FROM clientes WHERE estado = 'activo' ORDER BY nombre ASC");
     $clientes->execute();
     $lista_clientes = $clientes->fetchAll(PDO::FETCH_ASSOC);

     // Definir fecha actual
     $fecha_actual = date('Y-m-d');
?>