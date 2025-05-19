<?php
// Determinar la ruta correcta para la conexión
$ruta_conexion = file_exists("../../database/conexion.php") ? "../../database/conexion.php" : "../database/conexion.php";
include($ruta_conexion);

$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Obtener lista de vehículos
$consulta = $conexion->prepare("SELECT * FROM vehiculos ORDER BY fecha_creacion DESC");
$consulta->execute();
$lista_transporte = $consulta->fetchAll(PDO::FETCH_ASSOC);

// Agregar vehículo
if (isset($_POST['agregar_transporte'])) {
    $codigo = $_POST['codigo'];
    $placa = $_POST['placa'];
    $tipo = $_POST['tipo'];
    $capacidad_carga_kg = $_POST['capacidad_carga_kg'];
    $capacidad_paletas = $_POST['capacidad_paletas'];
    $estado = $_POST['estado'];

    $consulta = $conexion->prepare("INSERT INTO vehiculos (codigo, placa, tipo, capacidad_carga_kg, capacidad_paletas, estado) 
                                  VALUES (:codigo, :placa, :tipo, :capacidad_carga_kg, :capacidad_paletas, :estado)");
    
    $consulta->bindParam(':codigo', $codigo);
    $consulta->bindParam(':placa', $placa);
    $consulta->bindParam(':tipo', $tipo);
    $consulta->bindParam(':capacidad_carga_kg', $capacidad_carga_kg);
    $consulta->bindParam(':capacidad_paletas', $capacidad_paletas);
    $consulta->bindParam(':estado', $estado);

    if ($consulta->execute()) {
        header("Location: ../vistas/transporte/vista_transporte.php?mensaje=Transporte agregado correctamente");
    } else {
        header("Location: ../vistas/transporte/vista_transporte.php?mensaje=Error al agregar transporte");
    }
    exit();
}

// Editar vehículo
if (isset($_POST['editar_transporte'])) {
    $id_vehiculo = $_POST['id_vehiculo'];
    $codigo = $_POST['codigo'];
    $placa = $_POST['placa'];
    $tipo = $_POST['tipo'];
    $capacidad_carga_kg = $_POST['capacidad_carga_kg'];
    $capacidad_paletas = $_POST['capacidad_paletas'];
    $estado = $_POST['estado'];

    $consulta = $conexion->prepare("UPDATE vehiculos 
                                  SET codigo = :codigo, 
                                      placa = :placa, 
                                      tipo = :tipo, 
                                      capacidad_carga_kg = :capacidad_carga_kg, 
                                      capacidad_paletas = :capacidad_paletas, 
                                      estado = :estado 
                                  WHERE id_vehiculo = :id_vehiculo");
    
    $consulta->bindParam(':id_vehiculo', $id_vehiculo);
    $consulta->bindParam(':codigo', $codigo);
    $consulta->bindParam(':placa', $placa);
    $consulta->bindParam(':tipo', $tipo);
    $consulta->bindParam(':capacidad_carga_kg', $capacidad_carga_kg);
    $consulta->bindParam(':capacidad_paletas', $capacidad_paletas);
    $consulta->bindParam(':estado', $estado);

    if ($consulta->execute()) {
        header("Location: ../vistas/transporte/vista_transporte.php?mensaje=Transporte actualizado correctamente");
    } else {
        header("Location: ../vistas/transporte/vista_transporte.php?mensaje=Error al actualizar transporte");
    }
    exit();
}
?> 