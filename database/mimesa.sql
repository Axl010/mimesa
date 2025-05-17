-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2025 a las 17:19:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mimesa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `tipo_cliente` enum('regular','preferencial','mayorista') NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `razon_social` varchar(100) DEFAULT NULL,
  `documento_tipo` enum('RIF','CI','Pasaporte') NOT NULL,
  `documento_numero` varchar(20) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `notas` text DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `tipo_cliente`, `nombre`, `razon_social`, `documento_tipo`, `documento_numero`, `direccion`, `telefono`, `email`, `estado`, `notas`, `region`, `fecha_registro`) VALUES
(1, 'regular', 'Maria', 'Razon', 'RIF', '28456789', 'Valencia', '04124567892', 'maria@gmail.com', 'activo', 'asdasd', 'Carabobo', '2025-05-05 21:39:26'),
(2, 'mayorista', 'Juan', 'Razon', 'CI', '123123123', 'Valencia', '04141486860', 'juan@gmail.com', 'activo', 'asdas', 'Carabobo', '2025-05-06 21:42:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

CREATE TABLE `conductores` (
  `id_conductor` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`id_conductor`, `nombre`, `cedula`, `estado`, `fecha_creacion`) VALUES
(2, 'Pedro', '12456897', 'activo', '2025-05-09 20:07:06'),
(3, 'Carlos', '28563985', 'activo', '2025-05-09 20:38:38'),
(4, 'Luis', '26523698', 'activo', '2025-05-09 20:39:07'),
(5, 'Josue', '30563888', 'activo', '2025-05-09 20:50:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_transferencia`
--

CREATE TABLE `detalle_transferencia` (
  `id_detalle` int(11) NOT NULL,
  `id_transferencia` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `peso_kg` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_transferencia`
--

INSERT INTO `detalle_transferencia` (`id_detalle`, `id_transferencia`, `id_producto`, `cantidad`, `peso_kg`) VALUES
(1, 1, 62, 1.00, 0.28),
(2, 1, 1, 1.00, 11.04),
(3, 4, 1, 100.00, 1104.00),
(4, 5, 6, 100.00, 1656.00),
(5, 5, 19, 100.00, 96.00),
(6, 6, 1, 90.00, 993.60),
(7, 6, 3, 1.00, 16.56),
(8, 7, 1, 90.00, 993.60),
(9, 7, 3, 5.00, 82.80),
(10, 8, 1, 1.00, 11.04),
(11, 8, 2, 1.00, 11.04),
(12, 8, 3, 1.00, 16.56),
(13, 8, 4, 1.00, 16.56),
(14, 8, 5, 2.00, 30.00),
(15, 8, 6, 1.00, 16.56),
(16, 8, 7, 1.00, 15.00),
(17, 8, 8, 1.00, 15.00),
(18, 8, 9, 1.00, 15.00),
(19, 8, 10, 1.00, 15.00),
(20, 8, 11, 2.00, 9.60),
(21, 8, 12, 1.00, 14.50),
(22, 8, 13, 1.00, 16.56),
(23, 8, 14, 1.00, 18.00),
(24, 8, 15, 2.00, 33.12),
(25, 8, 17, 2.00, 33.12),
(26, 8, 18, 1.00, 3.36),
(27, 8, 20, 1.00, 0.80),
(28, 8, 48, 1.00, 12.00),
(29, 8, 49, 1.00, 12.00),
(30, 8, 50, 1.00, 12.00),
(31, 8, 51, 1.00, 12.00),
(32, 8, 52, 1.00, 12.00),
(33, 8, 53, 1.00, 10.00),
(34, 8, 54, 1.00, 12.00),
(35, 8, 59, 1.00, 12.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `peso` decimal(10,3) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `stock` int(11) DEFAULT 0,
  `cantidad_por_paleta` int(11) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `sku`, `nombre`, `descripcion`, `foto`, `peso`, `estado`, `stock`, `cantidad_por_paleta`, `fecha_creacion`) VALUES
(1, '19113', 'Vatel Soya', 'Vatel Soya 12X1Lt.', NULL, 11.040, 'activo', 719, 90, '2025-05-10 21:54:15'),
(2, '19313', 'Vatel Vegetal', 'Vatel Vegetal 12X1Lt.', NULL, 11.040, 'activo', 999, 90, '2025-05-10 21:54:15'),
(3, '19406', 'Aceite Vegetal Branca', 'ACEITE VEGETAL BRANCA 18 LT', NULL, 16.560, 'activo', 993, 36, '2025-05-10 21:54:15'),
(4, '20036', 'Manteca Líquida Paila', 'MANTECA LIQUIDA PAILA KG', NULL, 16.560, 'activo', 999, 36, '2025-05-10 21:54:15'),
(5, '21104', 'Manteca Vegetal Tresco', 'MANTECA VEGETAL COMP TRESCO 42LT', NULL, 15.000, 'activo', 998, 50, '2025-05-10 21:54:15'),
(6, '21118', 'Grasa Veg Comp 37P', 'GRASA VEG COMP 37P (PAILA 18L)', NULL, 16.560, 'activo', 899, 36, '2025-05-10 21:54:15'),
(7, '21203', 'Manteca Tresco LTE', 'MANTECA TRESCO LTE', NULL, 15.000, 'activo', 999, 50, '2025-05-10 21:54:15'),
(8, '21204', 'Manteca Tresco 42 VLT', 'MANTECA TRESCO 42 VLT CJ 15 KG', NULL, 15.000, 'activo', 999, 50, '2025-05-10 21:54:15'),
(9, '21207', 'Manteca Tresco 42VP', 'MANTECA TRESCO 42VP CJ 15 KG', NULL, 15.000, 'activo', 999, 50, '2025-05-10 21:54:15'),
(10, '21309', 'Manteca Vegetal Tresco', 'MANTECA VEGETAL TRESCO 48LT', NULL, 15.000, 'activo', 999, 45, '2025-05-10 21:54:15'),
(11, '21005', 'Manteca Vegetal Compuesta', 'MANTECA VEGETAL COMPUESTA LOS', NULL, 4.800, 'activo', 998, 60, '2025-05-10 21:54:15'),
(12, 'AAL01', 'Liza Aceite Algodón', 'LIZA ACEITE DE ALGODÓN BUCKET', NULL, 14.500, 'activo', 999, 48, '2025-05-10 21:54:15'),
(13, 'AGL02', 'Liza Aceite Girasol', 'LIZA ACEITE GIRASOL', NULL, 16.560, 'activo', 999, 60, '2025-05-10 21:54:15'),
(14, 'ASL01', 'Liza Aceite Soya', 'LIZA ACEITE SOYA 20X900 ML', NULL, 18.000, 'activo', 999, 60, '2025-05-10 21:54:15'),
(15, 'ASL02', 'Liza Aceite Soya', 'LIZA ACEITE SOYA 6LT', NULL, 16.560, 'activo', 998, 40, '2025-05-10 21:54:15'),
(16, 'ASL03', 'Liza Aceite Soya', 'LIZA ACEITE SOYA 6LT', NULL, 11.040, 'activo', 1000, 40, '2025-05-10 21:54:15'),
(17, 'ASV01', 'Vatel Aceite Soya', 'VATEL 5VF ACEITE SOYA20X900ML', NULL, 16.560, 'activo', 998, 60, '2025-05-10 21:54:15'),
(18, 'ED201', 'Edulcorante Truvia', 'EDULCORANTE TRUVIA 12X280GR', NULL, 3.360, 'activo', 999, 100, '2025-05-10 21:54:15'),
(19, 'ED401', 'Truvia Natural Sweetener', 'TRUVIA NATURAL SWEETENER 12X40', NULL, 0.960, 'activo', 900, 80, '2025-05-10 21:54:15'),
(20, 'ED402', 'Truvia Natural Sweetener', 'TRUVIA NATURAL SWEETENER 1X400', NULL, 0.800, 'activo', 999, 216, '2025-05-10 21:54:15'),
(21, 'ED406', 'Truvia Refill Bag', 'TRUVIA REFILL BAG 8x482gr', NULL, 3.840, 'activo', 1000, 112, '2025-05-10 21:54:15'),
(22, 'ED405', 'Fracción Edulcorante Truvia', 'FRACCION EDULC TRUVIA', NULL, 0.080, 'activo', 1000, 12, '2025-05-10 21:54:15'),
(23, 'ED408', 'Truvia Brown Sugar', 'TRUVIA BROWN SUGAR BLEND 510 GR', NULL, 3.840, 'activo', 1000, 50, '2025-05-10 21:54:15'),
(24, 'ED409', 'Truvia Sweet Complete', 'TRUVIA SWEET COMPLETE 340GR', NULL, 3.840, 'activo', 1000, 50, '2025-05-10 21:54:15'),
(25, 'ED410', 'Truvia Granulated', 'TRUVIA GRANULATED 454GR', NULL, 3.840, 'activo', 1000, 50, '2025-05-10 21:54:15'),
(26, 'G119', 'Pasticho Normal Ronco', 'PASTICHO NORMAL RONCO 12 X 1/4', NULL, 3.000, 'activo', 1000, 150, '2025-05-10 21:54:15'),
(27, 'G155', 'Harina Flor Leudante', 'HARINA BLANCA FLOR LEUDANTE 20', NULL, 20.000, 'activo', 1000, 55, '2025-05-10 21:54:15'),
(28, 'G156', 'Harina Flor Todo Uso', 'HARINA BLANCA FLOR TODO USO 20', NULL, 20.000, 'activo', 1000, 55, '2025-05-10 21:54:15'),
(29, 'G164', 'Aceite El Rey', 'ACEITE EL REY 18LTS', NULL, 16.560, 'activo', 1000, 36, '2025-05-10 21:54:15'),
(30, 'G201', 'Ronco Vermicelli Jet', 'RONCO VERMICELLI JET 24x1/2 KG', NULL, 12.000, 'activo', 1000, 84, '2025-05-10 21:54:15'),
(31, 'G35', 'Fiorentina Tornillo', 'FIORENTINA TORNILLO1KGX10', NULL, 10.000, 'activo', 1000, 33, '2025-05-10 21:54:15'),
(32, 'G38', 'Vermicelli Fiorentina', 'VERMICELLI FIORENTINA 12X1KG', NULL, 12.000, 'activo', 1000, 84, '2025-05-10 21:54:15'),
(33, 'H6088', 'Harina Panadera RDN', 'HARINA PANADERA RDN 45 KG', NULL, 45.000, 'activo', 1000, 15, '2025-05-10 21:54:15'),
(34, 'OPV02', 'Vatel Oleina Palma', 'VATEL PRO OLEINA DE PALMA', NULL, 16.560, 'activo', 1000, 36, '2025-05-10 21:54:15'),
(35, 'PTADS01', 'Aceite Soya Doril', 'ACEITE SOYA COMESTIBLE DORIL 1', NULL, 11.040, 'activo', 1000, 90, '2025-05-10 21:54:15'),
(36, 'PTAPR01', 'Purilev Aceite Canola', 'PURILEV ACEITE CANOLA 12X1L', NULL, 11.040, 'activo', 1000, 90, '2025-05-10 21:54:15'),
(37, 'PTAPR04', 'Purilev Aceite Canola', 'PURILEV ACEITE CANOLA 24X1/2 LT', NULL, 11.040, 'activo', 1000, 84, '2025-05-10 21:54:15'),
(38, 'PTAPR05', 'Purilev Aceite Girasol', 'PURILEV ACEITE GIRASOL 1LT', NULL, 11.040, 'activo', 1000, 90, '2025-05-10 21:54:15'),
(39, 'PTAVE01', 'Venezuela Aceite Soya', 'VENEZUELA ACEITE SOYA 12X1L', NULL, 11.040, 'activo', 1000, 90, '2025-05-10 21:54:15'),
(40, 'PTAVS01', 'Vatel Aceite Soya', 'VATEL ACEITE SOYA 24X1/2 LT', NULL, 11.040, 'activo', 1000, 84, '2025-05-10 21:54:15'),
(41, 'PTAVS02', 'Vatel Aceite Soya', 'VATEL ACEITE SOYA 24X1/4LT', NULL, 5.520, 'activo', 1000, 152, '2025-05-10 21:54:15'),
(42, 'PTHRO07', 'Harina Ronco Leudante', 'HARINA RONCO LEUDANTE 20X1KG', NULL, 20.000, 'activo', 1000, 63, '2025-05-10 21:54:15'),
(43, 'PTHRO08', 'Harina Ronco Todo Uso', 'HARINA RONCO TODO USO 20X1KG', NULL, 20.000, 'activo', 1000, 63, '2025-05-10 21:54:15'),
(44, 'PTMTC01', 'Manteca Tresco LTE', 'MANTECA TRESCO LTE 10K', NULL, 10.000, 'activo', 1000, 70, '2025-05-10 21:54:15'),
(45, 'PTMTC04', 'Manteca Tresco LT', 'MANTECA TRESCO LT 10K', NULL, 10.000, 'activo', 1000, 70, '2025-05-10 21:54:15'),
(46, 'PTPFI01', 'Fiorentina Pluma', 'FIORENTINA PLUMA 1KGX10', NULL, 10.000, 'activo', 1000, 39, '2025-05-10 21:54:15'),
(47, 'PTPFI04', 'Fiorentina Dedal', 'FIORENTINA DEDAL 1KGX10', NULL, 10.000, 'activo', 1000, 33, '2025-05-10 21:54:15'),
(48, 'PTPRO07', 'Ronco Rigaton Jet', 'RONCO E RIGATON JET 24X500GR', NULL, 12.000, 'activo', 999, 30, '2025-05-10 21:54:15'),
(49, 'PTPRO10', 'Ronco Codo Jet', 'RONCO E CODO JET 24X500GR', NULL, 12.000, 'activo', 999, 30, '2025-05-10 21:54:15'),
(50, 'PTPRO18', 'Ronco Vermicelli', 'RONCO L VERMICELLI 24X500GR', NULL, 12.000, 'activo', 999, 84, '2025-05-10 21:54:15'),
(51, 'PTPRO21', 'Ronco Jet Pluma', 'RONCO C JET PLUMA 500G', NULL, 12.000, 'activo', 999, 30, '2025-05-10 21:54:15'),
(52, 'PTPRO25', 'Ronco Premium Vermicelli', 'RONCO PREMIUM VERMICELLI 1KG12', NULL, 12.000, 'activo', 999, 84, '2025-05-10 21:54:15'),
(53, 'PTPRO26', 'Ronco Premium Pluma', 'RONCO PREMIUM PLUMA 1 KG B10KG', NULL, 10.000, 'activo', 999, 39, '2025-05-10 21:54:15'),
(54, 'PTPRO33', 'Ronco Premium Linguini', 'RONCO PREMIUM LINGUINI 12KG', NULL, 12.000, 'activo', 999, 84, '2025-05-10 21:54:15'),
(55, 'PTPRO34', 'Ronco Premium Dedal', 'RONCO PREMIUM DEDAL 10KG', NULL, 10.000, 'activo', 1000, 39, '2025-05-10 21:54:15'),
(56, 'PTPRO36', 'Ronco Premium Tornillo', 'RONCO PREMIUM TORNILLO 10X1KG', NULL, 10.000, 'activo', 1000, 33, '2025-05-10 21:54:15'),
(57, 'PTPRO49', 'Ronco Vit Dedal', 'RONCO VIT DEDAL 24X500G', NULL, 12.000, 'activo', 1000, 30, '2025-05-10 21:54:15'),
(58, 'PTPRO47', 'Ronco Vit Vermicelli', 'RONCO VIT VERMICELLI 24X500G', NULL, 12.000, 'activo', 1000, 84, '2025-05-10 21:54:15'),
(59, 'PTPRO48', 'Ronco Vit Tornillo', 'RONCO VIT TORNILLO 24X500G', NULL, 12.000, 'activo', 999, 30, '2025-05-10 21:54:15'),
(60, 'PTPRO50', 'Ronco Vit Plumas', 'RONCO VIT PLUMAS 24X500G', NULL, 12.000, 'activo', 1000, 30, '2025-05-10 21:54:15'),
(61, 'PTPRO52', 'Fetuchini', 'FETUCHINI', NULL, 8.000, 'activo', 1000, 28, '2025-05-10 21:54:15'),
(62, 'FED003', 'Fracción Edulcorante Truvia', 'FRACCION EDULC TRUVIA 280GR', NULL, 0.280, 'activo', 1000, 12, '2025-05-10 21:54:15'),
(63, '21122', 'Vatel Girasol', 'VATEL GIRASOL', NULL, 16.560, 'activo', 1000, 36, '2025-05-10 21:54:15'),
(64, '20037', 'Aceite Maravilla', 'ACEITE MARAVILLA', NULL, 16.560, 'activo', 1000, 36, '2025-05-10 21:54:15'),
(65, '21121', 'Vatel Grasa Veg Comp', 'VATEL PRO GRASA VEG COMP 37P', NULL, 16.560, 'activo', 1000, 36, '2025-05-10 21:54:15'),
(66, 'ED407', 'Truvia Refill Bag', 'TRUVIA REFILL BAG 1x482gr', NULL, 0.480, 'activo', 1000, 8, '2025-05-10 21:54:15'),
(67, 'G130', 'Pasticho', 'PASTICHO', NULL, 3.000, 'activo', 1000, 168, '2025-05-10 21:54:15'),
(68, '19318', 'Aceite Vegetal Garrafa', 'ACEITE VEGETAL CAJA GARRAFA', NULL, 13.920, 'activo', 1000, 48, '2025-05-10 21:54:15'),
(69, 'P', 'Prueba', '', '../../photos/productos/6822259bb2ef4.png', 10.000, 'activo', 100, 1, '2025-05-12 16:45:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencias`
--

CREATE TABLE `transferencias` (
  `id_transferencia` int(11) NOT NULL,
  `fecha_despacho` datetime NOT NULL,
  `id_vehiculo` int(11) DEFAULT NULL,
  `id_conductor` int(11) DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  `origen` varchar(100) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL,
  `direccion_destino` varchar(100) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `estado` enum('pendiente','completada','cancelada') DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transferencias`
--

INSERT INTO `transferencias` (`id_transferencia`, `fecha_despacho`, `id_vehiculo`, `id_conductor`, `id_responsable`, `origen`, `id_cliente`, `direccion_destino`, `observacion`, `estado`, `fecha_creacion`) VALUES
(1, '2025-05-12 00:00:00', 16, 3, NULL, 'Planta Valencia', 1, NULL, '', 'cancelada', '2025-05-12 14:23:47'),
(4, '2025-05-12 00:00:00', 3, 3, NULL, 'Planta Valencia', 2, NULL, '', 'completada', '2025-05-12 18:58:36'),
(5, '2025-05-12 00:00:00', 4, 5, NULL, 'Planta Valencia', 1, NULL, '', 'pendiente', '2025-05-12 14:25:08'),
(6, '2025-05-12 00:00:00', 16, 3, NULL, 'Planta Valencia', 2, NULL, '', 'pendiente', '2025-05-12 19:27:20'),
(7, '2025-05-12 00:00:00', 13, 5, NULL, 'Planta Valencia', 2, NULL, '', 'pendiente', '2025-05-12 19:28:29'),
(8, '2025-05-12 00:00:00', 16, 3, NULL, 'Planta Valencia', 2, NULL, '', 'pendiente', '2025-05-12 20:38:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `foto_usuario` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `ultima_conexion` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `usuario`, `password`, `foto_usuario`, `telefono`, `estado`, `ultima_conexion`, `fecha_creacion`) VALUES
(7, 'admin', 'admin', '$2y$10$oVngnao6a5u.ke3HomwjXOVMCYBRTzGgFMcPe6xPQIYz4t2mFGIOK', '', '0424', 'activo', '2025-05-13 08:47:30', '2025-05-02 16:31:11'),
(8, 'Mariana De Almeida', 'Mariana De Almeida', '$2y$10$CAafXTYjyjggiQAXpSBhNO2cx1XoU2a01lowcuTQXIH6FIGlAuC0a', '../../photos/usuarios/1746802235_Logo.png', '', 'activo', '2025-05-09 11:14:38', '2025-05-09 14:50:36'),
(9, 'Alexis Bolivar', 'Alexis Bolivar', '$2y$10$9pVZOGjL1FzpzHf9yrg8ae.FczFr60KAPximhmLTk6mr4mCYq1EIW', '../../photos/usuarios/default_user.png', '', 'activo', '2025-05-10 10:02:08', '2025-05-10 13:56:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `placa` varchar(15) DEFAULT NULL,
  `tipo` varchar(125) NOT NULL,
  `capacidad_carga_kg` decimal(10,2) NOT NULL,
  `capacidad_paletas` decimal(10,2) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_vehiculo`, `codigo`, `placa`, `tipo`, `capacidad_carga_kg`, `capacidad_paletas`, `estado`, `fecha_creacion`) VALUES
(1, '428', 'NV-562', 'CISTERNA', 0.00, 0.00, 'activo', '2025-05-09 16:04:24'),
(2, '098', 'DD-368', 'PANEL', 750.00, 2.00, 'activo', '2025-05-12 14:02:59'),
(3, '102', 'BL-117', 'C350', 2800.00, 4.00, 'activo', '2025-05-12 14:03:05'),
(4, '110', 'TT-527', 'NPR', 5100.00, 6.00, 'activo', '2025-05-12 14:04:22'),
(5, '107', 'DE-570', 'FSR', 7300.00, 10.00, 'activo', '2025-05-12 14:05:00'),
(6, '108', 'CW-107', 'FK', 7700.00, 10.00, 'activo', '2025-05-12 14:05:05'),
(7, '105', 'MK-577', 'C750 10 PAL', 10000.00, 10.00, 'activo', '2025-05-12 14:05:21'),
(8, '105', 'LS-273', 'FM', 10000.00, 10.00, 'activo', '2025-05-12 14:05:24'),
(9, '112', 'VNC9258', 'C750 12 PAL', 12000.00, 12.00, 'activo', '2025-05-12 14:05:28'),
(10, '112', 'IWJ2306', 'FVR', 12000.00, 12.00, 'activo', '2025-05-12 14:05:34'),
(11, '117', 'RZX5143', 'TORONTO', 16000.00, 16.00, 'activo', '2025-05-12 14:05:40'),
(12, '124', 'QWI2328', 'GANDOLA 20 PAL', 18000.00, 20.00, 'activo', '2025-05-12 14:05:44'),
(13, '124', 'SCI4066', 'PATIN', 18000.00, 20.00, 'activo', '2025-05-12 14:05:48'),
(14, '124', 'TRE9150', 'GANDOLA 22 PAL', 22000.00, 22.00, 'activo', '2025-05-12 14:05:52'),
(15, '128', 'YBL1401', 'GANDOLA 24 PAL', 24000.00, 24.00, 'activo', '2025-05-12 14:06:02'),
(16, '129', 'WHT9538', 'GANDOLA 26 PAL', 26000.00, 26.00, 'activo', '2025-05-12 14:06:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `documento_numero` (`documento_numero`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD PRIMARY KEY (`id_conductor`);

--
-- Indices de la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_transferencia` (`id_transferencia`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indices de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id_transferencia`),
  ADD KEY `id_responsable` (`id_responsable`),
  ADD KEY `id_vehiculo` (`id_vehiculo`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_conductor` (`id_conductor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `conductores`
--
ALTER TABLE `conductores`
  MODIFY `id_conductor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id_transferencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  ADD CONSTRAINT `detalle_transferencia_ibfk_1` FOREIGN KEY (`id_transferencia`) REFERENCES `transferencias` (`id_transferencia`),
  ADD CONSTRAINT `detalle_transferencia_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `transferencias`
--
ALTER TABLE `transferencias`
  ADD CONSTRAINT `fk_transferencia_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `id_conductor` FOREIGN KEY (`id_conductor`) REFERENCES `conductores` (`id_conductor`),
  ADD CONSTRAINT `transferencias_ibfk_1` FOREIGN KEY (`id_responsable`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `transferencias_ibfk_2` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
