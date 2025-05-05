<?php
    if (!defined('servidor')) define('servidor', 'localhost');
    if (!defined('nombre_bd')) define('nombre_bd', 'mimesa');
    if (!defined('usuario')) define('usuario', 'root');
    if (!defined('password')) define('password', base64_decode('QXhsMjg0MzM='));

    class Conexion {
        public static function Conectar() {
            $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

            try{
                $conexion = new PDO("mysql:host=".servidor.";dbname=".nombre_bd, usuario, password, $opciones);
                return $conexion;
            }catch(Exception $e){
                die("El error de conexion es: ".$e->getMessage());
            }
        }
    }
?>