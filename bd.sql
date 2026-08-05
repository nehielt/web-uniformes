-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-08-2026 a las 01:48:05
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
-- Base de datos: `uniformes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `nombre`) VALUES
(1, 'BOTAS DE SEGURIDAD'),
(2, 'CAMISA'),
(3, 'CHAQUETA PLASTICA'),
(4, 'CHAQUETA DE MOTORIZADO'),
(5, 'CHEMISE'),
(6, 'PANTALON'),
(7, 'FRANELA'),
(9, 'GORRA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `tabla` varchar(100) NOT NULL,
  `accion` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `sql_query` text DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario`, `tabla`, `accion`, `descripcion`, `sql_query`, `fecha`) VALUES
(1, 'admin', 'usuarios', 'UPDATE', 'Modificó usuario ID 1 (admin)', 'UPDATE usuarios SET username = \'admin\', nombre = \'Administrador\', role = \'admin\', permiso_archivo = 1, permiso_inventario = 1, permiso_ordenes = 1, permiso_reportes = 1, permiso_seguridad = 1, permiso_auditoria = 1 WHERE id = 1', '2026-06-27 21:13:36'),
(2, 'admin', 'ordenes', 'INSERT', 'Creó orden para empleado ID $idempleados por $nombreUsuarioEsc', '$ins', '2026-06-28 09:17:07'),
(3, 'admin', 'detalles', 'INSERT', 'Agregó detalle orden $ultimaorden, inventario ID $i, cantidad $cantidad[$i]', '$ins2', '2026-06-28 09:17:07'),
(4, 'admin', 'inventario', 'UPDATE', 'Reducido inventario ID $i a $resta tras orden $ultimaorden', '$upd', '2026-06-28 09:17:07'),
(5, 'admin', 'inventario', 'UPDATE', 'Actualizó existencias producto ID $id a $unidades', '$upd', '2026-07-12 12:19:19'),
(6, 'admin', 'inventario', 'UPDATE', 'Actualizó existencias producto ID $id a $unidades', '$upd', '2026-07-12 12:21:25'),
(7, 'admin', 'ordenes', 'INSERT', 'Creó orden para empleado ID $idempleados por $nombreUsuarioEsc', '$ins', '2026-07-14 18:58:11'),
(8, 'admin', 'detalles', 'INSERT', 'Agregó detalle orden $ultimaorden, inventario ID $i, cantidad $cantidad[$i]', '$ins2', '2026-07-14 18:58:11'),
(9, 'admin', 'inventario', 'UPDATE', 'Reducido inventario ID $i a $resta tras orden $ultimaorden', '$upd', '2026-07-14 18:58:11'),
(10, 'admin', 'detalles', 'INSERT', 'Agregó detalle orden $ultimaorden, inventario ID $i, cantidad $cantidad[$i]', '$ins2', '2026-07-14 18:58:11'),
(11, 'admin', 'inventario', 'UPDATE', 'Reducido inventario ID $i a $resta tras orden $ultimaorden', '$upd', '2026-07-14 18:58:11'),
(12, 'admin', 'inventario', 'UPDATE', 'Restauró inventario ID $idinventario a $nuevaExistencia por modificación de orden $idorden', '$updInventario', '2026-08-04 17:57:55'),
(13, 'admin', 'detalles', 'DELETE', 'Eliminó detalles previos de la orden $idorden', '$del', '2026-08-04 17:57:55'),
(14, 'admin', 'detalles', 'INSERT', 'Actualizó detalle orden $idorden, inventario ID $i, cantidad $cantidad[$i]', '$ins2', '2026-08-04 17:57:55'),
(15, 'admin', 'inventario', 'UPDATE', 'Reducido inventario ID $i a $resta tras modificación de orden $idorden', '$upd', '2026-08-04 17:57:55'),
(16, 'admin', 'ordenes', 'UPDATE', 'Modificó orden ID $idorden por $nombreUsuarioEsc', '$updOrden', '2026-08-04 17:57:55'),
(17, 'admin', 'ordenes', 'INSERT', 'Creó orden para empleado ID $idempleados por $nombreUsuarioEsc', '$ins', '2026-08-04 18:01:11'),
(18, 'admin', 'detalles', 'INSERT', 'Agregó detalle orden $ultimaorden, inventario ID $i, cantidad $cantidad[$i]', '$ins2', '2026-08-04 18:01:11'),
(19, 'admin', 'inventario', 'UPDATE', 'Reducido inventario ID $i a $resta tras orden $ultimaorden', '$upd', '2026-08-04 18:01:11'),
(20, 'admin', 'usuarios', 'INSERT', 'Creó usuario nvasquez con nombre completo NORVYS VAZQUEZ', 'INSERT INTO usuarios (username, password, nombre, role, permiso_archivo, permiso_inventario, permiso_ordenes, permiso_reportes, permiso_seguridad, permiso_auditoria) VALUES (\'nvasquez\', \'$2y$10$GpdQSfMN2zQyl.n.P7flp..ER4sfsNwCxCkqmo8x0zVL2xPLDboLC\', \'NORVYS VAZQUEZ\', \'Gerente\', 1, 1, 1, 1, 0, 1)', '2026-08-05 15:05:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `nombre`) VALUES
(1, 'ADMINISTRADORA'),
(2, 'ANALISTA DE FACTURACION'),
(3, 'ANALISTA DE RECURSOS HUMANOS'),
(4, 'ANALISTA DE SISTEMAS I'),
(5, 'ANALISTA I'),
(6, 'ANALISTA II'),
(7, 'ASISTENTE DE ARCHIVO'),
(8, 'ASISTENTE DE GERENCIA'),
(9, 'ASISTENTE DE SERV. GRALES'),
(10, 'AUDITOR DE PROCESOS'),
(11, 'AUDITOR SUPERVISOR'),
(12, 'AUX DE ARCHIVO Y OPER.'),
(13, 'AUXILIAR ADMINISTRATIVO Y'),
(14, 'AUXILIAR DE ARCHIVO'),
(15, 'AUXILIAR DE ARCHIVO II'),
(16, 'AUXILIAR DE ARCHIVO Y OPERACIONES'),
(17, 'CONTADOR'),
(18, 'COORD. DE OPER. II'),
(19, 'COORD. DE OPERACIONES'),
(20, 'COORD. DE SERV DE GD'),
(21, 'COORD. NOMINA Y G.H.'),
(22, 'COORD. S.GRLES'),
(23, 'COORDINADOR DE SISTEMAS'),
(24, 'COORDINADOR DE PROYECTO'),
(25, 'GERENT DE OPER.'),
(26, 'GERENTE DE RRHH'),
(27, 'GERENTE GENERAL'),
(28, 'GTE DE FINANZAS'),
(29, 'MANTENIMIENTO'),
(30, 'MENSAJERO'),
(31, 'OPERARIO'),
(32, 'OPERARIO II'),
(33, 'PRESIDENTE'),
(34, 'SUPERVISOR'),
(35, 'SUPERVISOR DE ALMACEN'),
(36, 'SUPERVISOR DE PROYECTOS'),
(37, 'VIGILANTE'),
(38, 'VIGILANTE ADMINISTRATIVO'),
(39, 'PASANTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `colores`
--

INSERT INTO `colores` (`id`, `nombre`) VALUES
(1, 'NEGRO'),
(2, 'GRIS'),
(3, 'ROJO'),
(4, 'AZUL'),
(5, 'NARANJA'),
(6, 'BLANCO'),
(7, 'MARRÓN'),
(8, 'ROSA'),
(9, 'AMARILLO'),
(10, 'VERDE'),
(11, 'PURPURA'),
(12, 'GRANATE'),
(13, 'TURQUESA'),
(14, 'AZUL MARINO'),
(15, 'DORADO'),
(16, 'AZUL CELESTE'),
(17, 'PLATA'),
(18, 'BRONCE'),
(19, 'LILA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles`
--

CREATE TABLE `detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `orden` bigint(20) UNSIGNED NOT NULL,
  `inventario` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles`
--

INSERT INTO `detalles` (`id`, `orden`, `inventario`, `cantidad`) VALUES
(1, 1, 6, 2),
(2, 2, 6, 1),
(3, 3, 2, 5),
(4, 3, 5, 5),
(5, 4, 4, 1),
(6, 5, 4, 1),
(8, 7, 1, 1),
(9, 7, 3, 1),
(10, 6, 5, 2),
(11, 8, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expediente` varchar(7) NOT NULL,
  `cedula` varchar(8) NOT NULL,
  `nombres` varchar(40) NOT NULL,
  `apellidos` varchar(40) NOT NULL,
  `cargo` bigint(20) UNSIGNED NOT NULL,
  `ingreso` date NOT NULL,
  `ubicacion` bigint(20) UNSIGNED NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `expediente`, `cedula`, `nombres`, `apellidos`, `cargo`, `ingreso`, `ubicacion`, `activo`) VALUES
(1, 'CO00013', '6901990', 'EDILIA MARIA', 'TORRES FERRER', 36, '2005-06-16', 8, 1),
(2, 'CO00015', '5122050', 'MANUEL GREGORIO', 'GOMEZ', 15, '2005-07-18', 4, 1),
(3, 'CO00019', '5874015', 'JACQUELINE YNOCENTE', 'ORDAZ URGUELLES', 36, '2005-10-03', 5, 1),
(4, 'CO00024', '12996542', 'DIANERLIS ATAMAICA', 'BOLIVAR HENRRY', 15, '2005-12-20', 2, 1),
(5, 'CO00034', '11918277', 'THANIA MERCEDES', 'CABRITA ESTRADA', 6, '2006-05-04', 27, 1),
(6, 'CO00076', '5407595', 'RODOLFO EUGENIO', 'FERNANDEZ LARA', 15, '2007-01-29', 4, 1),
(7, 'CO00077', '6234134', 'TERESA INES', 'CEBALLOS ARIZA', 24, '2007-01-29', 7, 1),
(8, 'CO00111', '12748317', 'LILIANA MARGARITA', 'SOTILLO GONZALEZ', 7, '2007-05-14', 14, 1),
(9, 'CO00402', '15582137', 'LOENDRYS JOSEFINA', 'RIVAS QUIJADA', 15, '2008-03-26', 21, 1),
(10, 'CO00471', '13162639', 'HAZEL YAILYN', 'QUINTERO MARVAL', 5, '2008-05-20', 29, 1),
(11, 'CO00553', '12981409', 'NORVIS DESIREE', 'VIZCAYA ROJAS', 36, '2008-07-08', 27, 1),
(12, 'CO00602', '6438495', 'ACARI ROSNELBE', 'MORALES PEREZ', 14, '2008-08-12', 27, 1),
(13, 'CO00674', '6517062', 'JOSE LUIS', 'ROJAS TERAN', 14, '2008-09-17', 8, 1),
(14, 'CO00711', '6866916', 'YAJAIRA ELIZABETH', 'SEVILLA CASTRO', 14, '2008-10-27', 4, 1),
(15, 'CO01028', '11150602', 'JOSE RAFAEL', 'GUEVARA', 14, '2009-02-10', 13, 1),
(16, 'CO01358', '11049946', 'EILING COROMOTO', 'BLANCO MOLINA', 34, '2010-11-03', 8, 1),
(17, 'CO01476', '16867492', 'LILIAN YARI', 'OLLARVES SIVIRA', 36, '2011-02-23', 6, 1),
(18, 'CO01755', '14559592', 'WENDY MARIA', 'DUGARTE PALACIOS', 14, '2012-01-30', 4, 1),
(19, 'CO02219', '20289330', 'LUIS ALFREDO', 'CAMPO ATENCIO', 14, '2013-10-24', 21, 1),
(20, 'CO02368', '16131715', 'EDGAR ALEXANDER', 'LICETT SANTANA', 14, '2014-05-26', 13, 1),
(21, 'CO02811', '17411619', 'MARGARET', 'CARRION ALVAREZ', 36, '2017-07-17', 11, 1),
(22, 'CO02812', '13879845', 'NEICAR PATRICIA', 'ALVIAREZ RIVAS', 14, '2017-07-25', 10, 1),
(23, 'CO02909', '25831389', 'ALONDRA MIHA', 'PEREZ ARAQUE', 14, '2018-06-20', 27, 1),
(24, 'CO02916', '25722489', 'ANGELICA ISABEL', 'COVA RODRIGUEZ', 14, '2018-07-09', 11, 1),
(25, 'CO02964', '19837466', 'KATHERINE LISSETH', 'CASTRO VILORIA', 14, '2018-12-26', 10, 1),
(26, 'CO02976', '12747098', 'LILIANA EMILIA', 'AVILA ZAMORA', 14, '2019-03-19', 7, 1),
(27, 'CO02981', '19932638', 'CESAR ARTURO', 'CELIS TORRES', 14, '2019-04-02', 7, 1),
(28, 'CO02990', '25839569', 'VICTOR ALFONSO', 'RIOS BRAVO', 12, '2019-05-27', 17, 1),
(29, 'CO03005', '27790085', 'WILDER JOSE', 'ESCORCIA ACUÑA', 14, '2019-08-19', 14, 1),
(30, 'CO03024', '6454920', 'YOSMAR ARMENIA', 'MOSQUEDA PINO', 14, '2020-01-13', 8, 1),
(31, 'CO03034', '27138874', 'ALEXANDER DANIEL', 'ANGELES GARCIA', 13, '2020-02-03', 5, 1),
(32, 'CO03047', '19335202', 'MARIELLYS CAROLINA', 'TORRES DIAZ', 36, '2021-03-24', 17, 1),
(33, 'CO03051', '30131803', 'LEONOR ELIZABETH DE LOS ANGELES', 'ESCOBAR MENOYO', 14, '2021-08-17', 11, 1),
(34, 'CO03052', '22028887', 'KARINA ALEXANDRA', 'SOTO URGILES', 12, '2021-08-26', 17, 1),
(35, 'CO03057', '29620439', 'JOSHEYLING DANIELA', 'HERRERA SALAS', 14, '2021-10-19', 9, 1),
(36, 'CO03058', '23776299', 'JENESIS AUXILIADORA', 'VILORIA QUINTERO', 14, '2021-10-25', 6, 1),
(37, 'CO03064', '13722463', 'ISLIAN MARIBEL', 'ABREU GUILARTE', 14, '2022-01-31', 20, 1),
(38, 'CO03066', '24217349', 'INGRIS MARBEYS', 'CAÑA CONTRERA', 14, '2022-03-21', 8, 1),
(39, 'CO03067', '26546122', 'NAICRIS MICHEL', 'TORRES', 14, '2022-03-21', 8, 1),
(40, 'CO03085', '26483900', 'YORBELIS COROMOTO', 'FLORES IRIARTE', 14, '2022-11-16', 8, 1),
(41, 'CO03089', '13600518', 'SULAY DEL CARMEN', 'INFANTE LINARES', 14, '2022-11-29', 12, 1),
(42, 'CO03093', '29571995', 'DARIANNA LESLIE', 'CARO GONZALEZ', 14, '2022-12-19', 8, 1),
(43, 'CO03103', '26946962', 'DAYANA PAOLA', 'AZACON MARTINEZ', 14, '2023-03-20', 16, 1),
(44, 'CO03105', '30098238', 'HELEN AYLIN', 'ESCALANTE MARCANO', 14, '2023-03-27', 8, 1),
(45, 'CO03111', '17784104', 'JOSE PASTOR', 'DUGARTE BRICEÑO', 14, '2023-05-02', 12, 1),
(46, 'CO03117', '28010115', 'YULIANA ALEJANDRA', 'VALOR MENDOZA', 14, '2023-09-06', 13, 1),
(47, 'CO03140', '18937932', 'HECTOR OSMAR', 'GONZALEZ MEDINA', 14, '2024-01-02', 17, 1),
(48, 'CO03143', '29875996', 'ANYELI NISLEN', 'RUIZ PACHECO', 14, '2024-01-09', 8, 1),
(49, 'CO03145', '16635129', 'MARIA EUGENIA', 'AGUILERA GUEDEZ', 14, '2019-09-30', 8, 1),
(50, 'CO03148', '11041198', 'LESVIA JOSEFINA', 'INFANTE LINARES', 14, '2024-05-20', 12, 1),
(51, 'CO03152', '26956265', 'ANDRES EMILIO', 'GUERRA RONDON', 16, '2025-01-24', 17, 1),
(52, 'EM00001', '3404849', 'CARLOS', 'ZULOAGA RODRIGUEZ', 33, '2000-07-01', 18, 1),
(53, 'EM00002', '6172977', 'BELEN IVELYSSE', 'CABALLERO DIAZ', 27, '1998-12-15', 18, 1),
(54, 'EM00003', '6960274', 'JOSE HERMENEGILDO', 'PEREZ GONZALEZ', 22, '1999-09-09', 30, 1),
(55, 'EM00006', '6793091', 'JOSE GREGORIO', 'PACHECO COVA', 30, '2001-05-07', 22, 1),
(56, 'EM00007', '9964756', 'JAVIER ALFREDO', 'BETANCOURT GIMENEZ', 19, '2001-10-21', 23, 1),
(57, 'EM00010', '5027047', 'JOSE RODOLFO', 'JAIMES', 37, '2003-09-16', 30, 1),
(58, 'EM00016', '12375535', 'EDWARD JESUS', 'CASTILLO LARA', 18, '2004-09-06', 32, 1),
(59, 'EM00047', '5311378', 'GLORIA INES', 'NIETO DE SATTA', 1, '2007-03-01', 1, 1),
(60, 'EM00065', '13612859', 'JOHNNY FRANCISCO', 'DORANTE BERRIOS', 32, '2007-10-10', 24, 1),
(61, 'EM00078', '14039240', 'ROMINA CAROLINA', 'HERNANDEZ NIEVES', 28, '2007-03-01', 1, 1),
(62, 'EM00110', '10805306', 'RONALD EDUARDO', 'SERRANO HERNANDEZ', 2, '2008-05-09', 15, 1),
(63, 'EM00132', '17058233', 'JOSE FRANCISCO', 'RODRIGUEZ VALLENILLA', 31, '2008-10-03', 24, 1),
(64, 'EM00135', '6196626', 'YSMELDA MARIA', 'TERAN ANGULO', 29, '2008-10-14', 30, 1),
(65, 'EM00179', '18404466', 'EGLYMAR DEL CARMEN', 'BRACAMONTE BOYER', 2, '2009-04-28', 15, 1),
(66, 'EM00181', '12617152', 'GILBERTO ENRIQUE', 'RODRIGUEZ PINO', 38, '2009-05-18', 30, 1),
(67, 'EM00215', '16154199', 'JOSE RAFAEL', 'CARDOZO REQUENA', 35, '2009-09-16', 26, 1),
(68, 'EM00275', '11314100', 'SUSANA', 'INCIARTE MORR', 20, '2010-09-09', 19, 1),
(69, 'EM00324', '11196456', 'RAFAEL ENRIQUE', 'RODRIGUEZ HERNANDEZ', 2, '2007-11-27', 15, 1),
(70, 'EM00387', '6292225', 'ALFREDO JOSE', 'PULIDO ESCALANTE', 30, '2012-05-21', 22, 1),
(71, 'EM00431', '11163389', 'NORVYS MARIA', 'VASQUEZ DE BAEZ', 26, '2013-02-25', 28, 1),
(72, 'EM00464', '6111017', 'CARLOS GABRIEL', 'MEDINA SERRANO', 31, '2013-10-21', 24, 1),
(73, 'EM00469', '14019981', 'ANDREINA DEL VALLE', 'MALAVE DIAZ', 25, '2013-11-25', 24, 1),
(74, 'EM00485', '17307596', 'KARINA ROSA', 'CANELON PEREZ', 11, '2010-10-19', 3, 1),
(75, 'EM00490', '17444268', 'ROLANDO JOSE', 'RODRIGUEZ RADA', 21, '2014-02-18', 28, 1),
(76, 'EM00586', '18753598', 'YOEDER DAVID', 'BESSON BIDES', 31, '2015-08-20', 23, 1),
(77, 'EM00628', '12455921', 'DAVID MANUEL', 'CUMANA', 9, '2016-03-10', 30, 1),
(78, 'EM00645', '16095006', 'MIGUEL ENRIQUE', 'JIMENEZ RODRIGUEZ', 31, '2016-05-09', 23, 1),
(79, 'EM00718', '6515168', 'LIDIAN COROMOTO', 'RODRIGUEZ SILVA', 1, '2018-12-03', 1, 1),
(80, 'EM00730', '11834434', 'FRANKLIN JOSE', 'TORREALBA ESPINOZA', 37, '2019-05-15', 30, 1),
(81, 'EM00742', '7923986', 'VICKY ROSA', 'MENDEZ GUTIERREZ', 20, '2019-08-12', 19, 1),
(82, 'EM00760', '20210958', 'WILMER RAFAEL', 'RIVAS FERNANDEZ', 31, '2020-02-05', 23, 1),
(83, 'EM00781', '18280648', 'BETSAULYS ELENA', 'TARAZONA SALAZAR', 29, '2021-07-20', 30, 1),
(84, 'EM00782', '27321246', 'PIERRE ANTHONY', 'RIVAS RIVERO', 31, '2021-08-30', 23, 1),
(85, 'EM00785', '15219553', 'GABRIEL ANTONIO', 'ACOSTA CENTENO', 31, '2021-11-08', 26, 1),
(86, 'EM00796', '14535588', 'JESUS ALBERTO', 'DUQUE SOLANO', 31, '2022-04-18', 23, 1),
(87, 'EM00811', '12697670', 'EDGAR ALEXANDER', 'MORALES', 34, '2022-08-01', 25, 1),
(88, 'EM00813', '19532206', 'JHON ANDERSON', 'MORAO MARTINEZ', 31, '2022-08-29', 23, 1),
(89, 'EM00823', '20676556', 'JACHSON RAMON', 'JIMENEZ SALAS', 31, '2022-11-01', 24, 1),
(90, 'EM00824', '12960221', 'EDWIN JOSE', 'SANCHEZ BARAJAS', 31, '2023-01-16', 24, 1),
(91, 'EM00830', '15837843', 'MARISELA', 'LAREZ LAREZ', 29, '2023-03-20', 30, 1),
(92, 'EM00834', '31187216', 'ENDYNAIKER KENWARD', 'HERNANDEZ NAVAS', 31, '2023-04-03', 23, 1),
(93, 'EM00836', '26724043', 'LEOMER JESUS', 'PADILLA SOJO', 31, '2023-04-10', 23, 1),
(94, 'EM00839', '16263931', 'DEYEN HERENIA', 'RADA APONTE', 3, '2023-05-02', 28, 1),
(95, 'EM00840', '15804361', 'MARIA EMILIA', 'CAMACARO MOGOLLON', 20, '2023-05-03', 19, 1),
(96, 'EM00841', '27773907', 'ANZONY MANUEL', 'MACHADO DIAZ', 31, '2023-05-08', 23, 1),
(97, 'EM00842', '26250681', 'CARLOS ALBERTO', 'LLANOS DIAZ', 31, '2023-05-08', 23, 1),
(98, 'EM00843', '13596726', 'ANELID AMERICA', 'MUJICA', 29, '2023-08-01', 30, 1),
(99, 'EM00845', '25760628', 'YEFERSON DAVID', 'MENDOZA CHACON', 31, '2023-08-22', 24, 1),
(100, 'EM00846', '14688324', 'MARBELIS YOHANA', 'URBINA ANGULO', 29, '2023-09-21', 30, 1),
(101, 'EM00847', '18529822', 'EILYN YUSMAR', 'VELARDE ARESTIGUIETA', 17, '2023-11-27', 1, 1),
(102, 'EM00848', '24074457', 'NINIVE', 'ORELLANO RODRIGUEZ', 29, '2023-12-04', 30, 1),
(103, 'EM00849', '13054994', 'DARIELIS CAROLINA', 'ARREAZA DE LINARES', 8, '2005-05-16', 18, 1),
(104, 'EM00866', '25019956', 'ALEJANDRO SATYA SAI BABA', 'ALVAREZ NUÑEZ', 4, '2024-04-01', 31, 1),
(105, 'EM00869', '18708319', 'MARIA JOSE', 'RIVERA FRONTADO', 10, '2024-06-03', 3, 1),
(106, 'EM00870', '12073855', 'NEHIEL JOSAPHA', 'TOVAR', 23, '2024-08-01', 31, 1),
(107, 'EM00874', '16203949', 'ANIBAL ANTONIO', 'VILLEGAS GODOY', 31, '2024-10-14', 24, 1),
(108, 'EM00875', '16432954', 'ERIK JAVIER', 'LUGO GIL', 35, '2024-11-21', 23, 1),
(109, 'EM00885', '22028568', 'DIMARLEN KATHERINE', 'VALECILLOS FUENTES', 3, '2025-07-22', 28, 1),
(111, 'DD00005', '13158512', 'DAVID ANTONIO', 'DAVILA MONTILLA', 4, '2026-03-13', 31, 0),
(112, 'll00001', '14291825', 'LUDYSMAR', 'LEON', 39, '2026-03-09', 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`) VALUES
(1, 'DAMAS'),
(2, 'CABALLEROS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idarticulos` bigint(20) UNSIGNED NOT NULL,
  `idcolores` bigint(20) UNSIGNED NOT NULL,
  `idtallas` bigint(20) UNSIGNED NOT NULL,
  `idgeneros` bigint(20) UNSIGNED NOT NULL,
  `minimo` int(11) NOT NULL,
  `existencia` int(11) NOT NULL,
  `precio` float(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `idarticulos`, `idcolores`, `idtallas`, `idgeneros`, `minimo`, `existencia`, `precio`) VALUES
(1, 5, 14, 1, 2, 5, 98, 4000.00),
(2, 5, 14, 1, 1, 5, 95, 4000.00),
(3, 1, 1, 16, 2, 5, 999, 30000.00),
(4, 4, 1, 4, 2, 2, 5, 35000.00),
(5, 6, 6, 7, 1, 5, 3, 4000.00),
(6, 1, 1, 18, 2, 5, 0, 40000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `empleado` bigint(20) UNSIGNED NOT NULL,
  `usuario_nombre` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `fecha`, `empleado`, `usuario_nombre`) VALUES
(1, '2026-04-23', 2, 'Norvys Vasquez'),
(2, '2026-04-23', 6, 'Rolando Rodriguez'),
(3, '2026-04-23', 1, 'Rolando Rodriguez'),
(4, '2026-04-25', 5, 'Norvys Vasquez'),
(5, '2026-06-28', 106, 'Norvys Vasquez'),
(6, '2026-06-28', 16, 'Administrador'),
(7, '2026-07-15', 104, 'Administrador'),
(8, '2026-08-05', 4, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id`, `nombre`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL'),
(6, 'XXXL'),
(7, '28'),
(8, '30'),
(9, '32'),
(10, '34'),
(11, '35'),
(12, '36'),
(13, '37'),
(14, '38'),
(15, '39'),
(16, '40'),
(17, '41'),
(18, '42'),
(19, '43'),
(20, '44'),
(21, '45'),
(22, '46'),
(23, '47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `nombre`) VALUES
(1, 'ADMINISTRACION'),
(2, 'ALIMENTOS HEINZ'),
(3, 'AUDITORIA'),
(4, 'BANPLUS'),
(5, 'BAYER'),
(6, 'DIGITEL BQTO'),
(7, 'DIGITEL CCS SEDE'),
(8, 'DIGITEL LOS CORTIJOS'),
(9, 'DIGITEL MARACAY'),
(10, 'DIGITEL MCBO'),
(11, 'DIGITEL PTO LA CRUZ'),
(12, 'DIGITEL TELETRABAJO'),
(13, 'DIGITEL VAL'),
(14, 'ENI'),
(15, 'FACTURACION Y COBRANZAS'),
(16, 'FARMATODO CCS'),
(17, 'FARMATODO CHARALLAVE'),
(18, 'GERENCIA GENERAL'),
(19, 'GESTION DOCUMENTAL LC'),
(20, 'JOHNSON & JOHNSON'),
(21, 'LAB. ELMOR'),
(22, 'MENSAJERIA'),
(23, 'OPERACIONES GUATIRE'),
(24, 'OPERACIONES LOS CORTIJOS'),
(25, 'OPERACIONES MCBO'),
(26, 'OPERACIONES VALENCIA'),
(27, 'PROYECTOS VARIOS'),
(28, 'RECURSOS HUMANOS'),
(29, 'SEGUROS MERCANTIL'),
(30, 'SERVICIOS GENERALES'),
(31, 'SISTEMAS'),
(32, 'TRANSPORTE'),
(33, 'CENTRO COMERCIAL CASABERA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `permiso_archivo` tinyint(1) NOT NULL DEFAULT 0,
  `permiso_inventario` tinyint(1) NOT NULL DEFAULT 0,
  `permiso_ordenes` tinyint(1) NOT NULL DEFAULT 0,
  `permiso_reportes` tinyint(1) NOT NULL DEFAULT 0,
  `permiso_seguridad` tinyint(1) NOT NULL DEFAULT 0,
  `permiso_auditoria` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `role`, `nombre`, `activo`, `permiso_archivo`, `permiso_inventario`, `permiso_ordenes`, `permiso_reportes`, `permiso_seguridad`, `permiso_auditoria`) VALUES
(1, 'admin', '$2y$10$YGz3B6glozGEaQAURSLD8e0KVP6nulukP/.zBd3x.5gl9qdWlblkm', 'admin', 'Administrador', 1, 1, 1, 1, 1, 1, 1),
(2, 'nvasquez', '$2y$10$GpdQSfMN2zQyl.n.P7flp..ER4sfsNwCxCkqmo8x0zVL2xPLDboLC', 'Gerente', 'NORVYS VAZQUEZ', 1, 1, 1, 1, 1, 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_auditoria_fecha` (`fecha`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `detalles`
--
ALTER TABLE `detalles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idx_detalles_orden` (`orden`),
  ADD KEY `idx_detalles_inventario` (`inventario`),
  ADD KEY `idx_detalles_orden_inventario` (`orden`,`inventario`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idx_empleados_cargo` (`cargo`),
  ADD KEY `idx_empleados_ubicacion` (`ubicacion`),
  ADD KEY `idx_empleados_activo_expediente` (`activo`,`expediente`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idx_inventario_articulo` (`idarticulos`),
  ADD KEY `idx_inventario_color` (`idcolores`),
  ADD KEY `idx_inventario_talla` (`idtallas`),
  ADD KEY `idx_inventario_genero` (`idgeneros`),
  ADD KEY `idx_inventario_catalogo` (`idarticulos`,`idcolores`,`idtallas`,`idgeneros`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idx_ordenes_empleado` (`empleado`),
  ADD KEY `idx_ordenes_empleado_fecha` (`empleado`,`fecha`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `detalles`
--
ALTER TABLE `detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles`
--
ALTER TABLE `detalles`
  ADD CONSTRAINT `fk_detalles_inventario` FOREIGN KEY (`inventario`) REFERENCES `inventario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalles_orden` FOREIGN KEY (`orden`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_empleados_cargo` FOREIGN KEY (`cargo`) REFERENCES `cargos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_empleados_ubicacion` FOREIGN KEY (`ubicacion`) REFERENCES `ubicaciones` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_inventario_articulo` FOREIGN KEY (`idarticulos`) REFERENCES `articulos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventario_color` FOREIGN KEY (`idcolores`) REFERENCES `colores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventario_genero` FOREIGN KEY (`idgeneros`) REFERENCES `generos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventario_talla` FOREIGN KEY (`idtallas`) REFERENCES `tallas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `fk_ordenes_empleado` FOREIGN KEY (`empleado`) REFERENCES `empleados` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
