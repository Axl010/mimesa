<?php
    require_once("../../database/conexion.php");

    class CrudInicio {
        private $conectar;

        public function __construct() {
            $this->conectar = Conexion::Conectar();
        }

        public function getTotalProductos() {
            $sql = "SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'";
            $consulta = $this->conectar->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        }

        public function getTotalTransferencias() {
            $sql = "SELECT COUNT(*) as total FROM transferencias WHERE estado = 'completada'";
            $consulta = $this->conectar->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        }

        public function getTotalConductores() {
            $sql = "SELECT COUNT(*) as total FROM conductores WHERE estado = 'activo'";
            $consulta = $this->conectar->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        }

        public function getTotalClientes() {
            $sql = "SELECT COUNT(*) as total FROM clientes WHERE estado = 1";
            $consulta = $this->conectar->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        }

        public function getTotalUsuarios() {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'";
            $consulta = $this->conectar->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        }
    }

    // Instanciar la clase y obtener los totales
    $crud = new CrudInicio();
    $totalProductos = $crud->getTotalProductos();
    $totalTransferencias = $crud->getTotalTransferencias();
    $totalConductores = $crud->getTotalConductores();
    $totalClientes = $crud->getTotalClientes();
    $totalUsuarios = $crud->getTotalUsuarios();
?>