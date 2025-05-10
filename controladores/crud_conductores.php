<?php
    include(__DIR__ . "/../database/conexion.php");
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Obtener lista de conductores
    $consulta_conductores = $conexion->prepare("SELECT * FROM conductores ORDER BY fecha_creacion DESC");
    $consulta_conductores->execute();
    $lista_conductores = $consulta_conductores->fetchAll(PDO::FETCH_ASSOC);

    // Agregar conductor
    if(isset($_POST['agregar_conductor'])) {
        try {
            $nombre = trim($_POST['nombre']);
            $cedula = trim($_POST['cedula']);
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 'activo'; // Valor por defecto 'activo'
            
            $insertar_conductor = $conexion->prepare("INSERT INTO conductores (nombre, cedula, estado, fecha_creacion) VALUES (:nombre, :cedula, :estado, NOW())");
            $insertar_conductor->bindParam(':nombre', $nombre);
            $insertar_conductor->bindParam(':cedula', $cedula);
            $insertar_conductor->bindParam(':estado', $estado);
            
            if($insertar_conductor->execute()) {
                $mensaje = "Conductor agregado correctamente";
            } else {
                throw new Exception("Error al agregar conductor");
            }
            
            header("Location: ../../vistas/conductores/vista_conductores.php?mensaje=" . urlencode($mensaje));
            exit();
            
        } catch(Exception $e) {
            header("Location: ../../vistas/conductores/vista_conductores.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }

    // Editar conductor
    if(isset($_POST['editar_conductor'])) {
        try {
            $id_conductor = $_POST['id_conductor'];
            $nombre = trim($_POST['nombre']);
            $cedula = trim($_POST['cedula']);
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 'activo';

            $actualizar_conductor = $conexion->prepare("UPDATE conductores SET nombre = :nombre, cedula = :cedula, estado = :estado WHERE id_conductor = :id_conductor");
            $actualizar_conductor->bindParam(':nombre', $nombre);
            $actualizar_conductor->bindParam(':cedula', $cedula);
            $actualizar_conductor->bindParam(':estado', $estado);
            $actualizar_conductor->bindParam(':id_conductor', $id_conductor);
            
            if($actualizar_conductor->execute()) {
                $mensaje = "Conductor actualizado correctamente";
            } else {
                throw new Exception("Error al actualizar conductor");
            }
            
            header("Location: ../vistas/conductores/vista_conductores.php?mensaje=" . urlencode($mensaje));
            exit();
            
        } catch(Exception $e) {
            header("Location: ../vistas/conductores/vista_conductores.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
?>