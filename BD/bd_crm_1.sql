-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2024 a las 23:25:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_crm_1`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (IN `n_cantidad` INT, IN `n_precio` DECIMAL(10,2), IN `codigo` INT)   BEGIN
DECLARE nueva_existencia int;
DECLARE nuevo_total decimal(10,2);
DECLARE nuevo_precio decimal(10,2);

DECLARE cant_actual int;
DECLARE pre_actual decimal(10,2);

DECLARE actual_existencia int;
DECLARE actual_precio decimal(10,2);

SELECT precio, existencia INTO actual_precio, actual_existencia FROM producto WHERE codproducto = codigo;

SET nueva_existencia = actual_existencia + n_cantidad;
SET nuevo_total = n_precio;
SET nuevo_precio = nuevo_total;

UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE codproducto = codigo;

SELECT nueva_existencia, nuevo_precio;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))   BEGIN
DECLARE precio_actual decimal(10,2);
SELECT precio INTO precio_actual FROM producto WHERE codproducto = codigo;
INSERT INTO detalle_temp(token_user, codproducto, cantidad, precio_venta) VALUES (token_user, codigo, cantidad, precio_actual);
SELECT tmp.correlativo, tmp.codproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE tmp.token_user = token_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `data` ()   BEGIN
    DECLARE usuarios INT;
    DECLARE clientes INT;
    DECLARE proveedores INT;
    DECLARE productos INT;
    DECLARE ventas INT;

    SELECT COUNT(*) INTO usuarios FROM usuario;
    SELECT COUNT(*) INTO clientes FROM cliente;
    SELECT COUNT(*) INTO proveedores FROM proveedor;
    SELECT COUNT(*) INTO productos FROM producto;
    SELECT COUNT(*) INTO ventas FROM factura WHERE fecha > CURDATE();

    SELECT usuarios, clientes, proveedores, productos, ventas;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRol` (IN `idrol1` INT)   BEGIN
  DECLARE existUsuario INT;
  DECLARE existRolxPermiso INT;

 -- Verificar si el idRol existe en la tabla de usuarios
  IF EXISTS (SELECT * FROM usuario WHERE rol = idrol1) THEN
    SELECT 'El rol no puede ser eliminado. Existen usuarios asignados a este rol.' AS Mensaje;
  ELSE
        IF (SELECT count(*) FROM usuario WHERE rol = idrol1) > 0 THEN
    SELECT 'No se puede eliminar el rol. Hay usuarios asignados a este rol.' AS Mensaje;
  ELSE
    -- Eliminar el rolxpermiso correspondiente al idRol
    DELETE FROM rolxpermisos WHERE idRol = idrol1;
    
    -- Eliminar el rol
    DELETE FROM rol WHERE idRol = idrol1;
  END IF;  
  
    SELECT 'El rol fue eliminado correctamente.' AS Mensaje;
  END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token` VARCHAR(50))   BEGIN
DELETE FROM detalle_temp WHERE correlativo = id_detalle;
SELECT tmp.correlativo, tmp.codproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE tmp.token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_orden_trabajo` (IN `p_fecha_orden` DATE, IN `p_nombre_cliente` VARCHAR(100), IN `p_direccion_cliente` VARCHAR(255), IN `p_telefono_cliente` VARCHAR(50), IN `p_web_cliente` VARCHAR(100), IN `p_datos_facturacion` TEXT, IN `p_datos_empresa` TEXT)   BEGIN
    DECLARE v_id_orden INT;

    -- Inserta la orden de trabajo
    INSERT INTO orden_trabajo (fecha_orden, nombre_cliente, direccion_cliente, telefono_cliente, web_cliente, datos_facturacion, datos_empresa)
    VALUES (p_fecha_orden, p_nombre_cliente, p_direccion_cliente, p_telefono_cliente, p_web_cliente, p_datos_facturacion, p_datos_empresa);

    -- Obtén el ID de la orden recién insertada
    SET v_id_orden = LAST_INSERT_ID();

    -- Actualiza el número de orden con ceros a la izquierda
    UPDATE orden_trabajo
    SET numero_orden = LPAD(v_id_orden, 4, '0')
    WHERE id = v_id_orden;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(50))   BEGIN
DECLARE factura INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;

DECLARE tmp_cod_producto int;
DECLARE tmp_cant_producto int;
DECLARE a int;
SET a = 1;

CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int);
SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT codproducto, cantidad FROM detalle_temp WHERE token_user = token;
INSERT INTO factura (usuario,codcliente) VALUES (cod_usuario, cod_cliente);
SET factura = LAST_INSERT_ID();

INSERT INTO detallefactura(nofactura,codproducto,cantidad,precio_venta) SELECT (factura) AS nofactura, codproducto, cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
WHILE a <= registros DO
	SELECT cod_prod, cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
    SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
    SET nueva_existencia = existencia_actual - tmp_cant_producto;
    UPDATE producto SET existencia = nueva_existencia WHERE codproducto = tmp_cod_producto;
    SET a=a+1;
END WHILE;
SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
UPDATE factura SET totalfactura = total WHERE nofactura = factura;
DELETE FROM detalle_temp WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser;
SELECT * FROM factura WHERE nofactura = factura;
ELSE
SELECT 0;
END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account`
--

CREATE TABLE `account` (
  `idAccount` int(11) NOT NULL,
  `cuenta` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `contacto_principal` varchar(255) DEFAULT NULL,
  `secot` varchar(255) DEFAULT NULL,
  `propietario` varchar(255) DEFAULT NULL,
  `origen_cliente` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `account`
--

INSERT INTO `account` (`idAccount`, `cuenta`, `direccion`, `ciudad`, `contacto_principal`, `secot`, `propietario`, `origen_cliente`) VALUES
(1, 'A & G Seguridad Integral Sociedad Anonima Cerrada', 'LOS PRECURSORES NRO. 485 (ALT DE LA CDRA 3 AV FAUCETT) LIMA - LIMA - SAN MIGUEL', 'Lima', 'Joel Julca', 'Servicios', 'Mia Arecco', 'Generación'),
(2, 'Oficinas Integrales', 'Lima', '', '', '', '', ''),
(3, 'AB Technology SAC', 'Calle Marcela Castro 634 Urb. Tupac Amaru San Luis', 'Lima', 'Erick Vivas', 'Construcción e Inmobiliaria', 'Mia Arecco', 'Marca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activities`
--

CREATE TABLE `activities` (
  `idActivities` int(11) NOT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaInicio` datetime DEFAULT NULL,
  `fechaFin` datetime DEFAULT NULL,
  `COD_idContact` int(11) DEFAULT NULL,
  `COD_idAccount` int(11) DEFAULT NULL,
  `COD_idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `allproduct`
--

CREATE TABLE `allproduct` (
  `idAllproduct` int(11) NOT NULL,
  `Producto` varchar(255) DEFAULT NULL,
  `Fecha de modificación` datetime DEFAULT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Fabricante` varchar(255) DEFAULT NULL,
  `Id. de producto` varchar(255) DEFAULT NULL,
  `Precio listado` varchar(255) DEFAULT NULL,
  `Segmento` varchar(255) DEFAULT NULL,
  `Lista de precios predeterminada` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `allproduct`
--

INSERT INTO `allproduct` (`idAllproduct`, `Producto`, `Fecha de modificación`, `Nombre`, `Fabricante`, `Id. de producto`, `Precio listado`, `Segmento`, `Lista de precios predeterminada`) VALUES
(735, 'cama', '2024-06-05 16:49:39', 'joel', 'COMEDIC', '1200', '20000 US$', 'Privado', 'na');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `api`
--

CREATE TABLE `api` (
  `idapi` int(11) NOT NULL,
  `api` varchar(255) DEFAULT NULL,
  `fecha_actualizada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `api`
--

INSERT INTO `api` (`idapi`, `api`, `fecha_actualizada`) VALUES
(1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFsdC54ay0zb3FjdW9hbUB5b3BtYWlsLmNvbSJ9.KKpvjSbWiLBE-xTNpcocsPYAccjpOyHECsp1NkVfPqc', '2024-04-16 23:05:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `dni` int(8) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` int(15) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `dni`, `nombre`, `telefono`, `direccion`, `usuario_id`) VALUES
(1, 123545, 'Pubico en general', 925491523, 'Lima', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `collections`
--

CREATE TABLE `collections` (
  `idCollections` int(11) NOT NULL,
  `NumeroFactura` varchar(50) DEFAULT NULL,
  `fechaEmision` date DEFAULT NULL,
  `fechaVencimiento` date DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `documento` varchar(100) DEFAULT NULL,
  `observaciones` varchar(200) DEFAULT NULL,
  `recurrente` varchar(200) DEFAULT NULL,
  `CODidcustomer` int(11) DEFAULT NULL,
  `numeroDocumento` varchar(255) DEFAULT NULL,
  `tipoDocumento` varchar(255) DEFAULT NULL,
  `ruc` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `collections`
--

INSERT INTO `collections` (`idCollections`, `NumeroFactura`, `fechaEmision`, `fechaVencimiento`, `monto`, `moneda`, `estado`, `documento`, `observaciones`, `recurrente`, `CODidcustomer`, `numeroDocumento`, `tipoDocumento`, `ruc`) VALUES
(85, 'E001-92', '2024-02-22', '2024-02-29', 20000.00, 'Dolares', 'Pendiente', 'Cuentas VB.pdf', 'seguimiento de venta', '2000', 84, NULL, NULL, ''),
(86, '777', '2024-06-01', '2024-06-28', 30000.00, 'Dolares', 'Pendiente', 'none', 'hacer pago', 'si', 210, NULL, NULL, ''),
(87, NULL, '2024-06-05', '2024-06-22', 2000.00, 'Dolares', 'Pendiente', 'none', 'xd', '200', 296, '767', 'Factura', '2020202043'),
(88, NULL, '2024-06-05', '2024-06-22', 2000.00, 'Dolares', 'Pendiente', 'none', 'xd', '200', 296, '767', 'Factura', '2020202043'),
(89, NULL, '2024-06-01', '2024-06-22', 10000.00, 'Soles', 'Pendiente', 'none', 'pago despues de mes', '100', 100, '200', 'Boleta', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `dni` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text NOT NULL,
  `igv` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `dni`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `igv`) VALUES
(1, 10731458907, 'Joel', 'IndustriasHernandez', 930921531, 'industriasjhernandez22@gmail.com', 'Lima - Perú', 1.18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

CREATE TABLE `contacts` (
  `idContacts` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `segundo_nombre` varchar(255) DEFAULT NULL,
  `apellido_paterno` varchar(255) DEFAULT NULL,
  `apellido_materno` varchar(255) DEFAULT NULL,
  `Nivel de interés` varchar(255) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono_fijo` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `COD_idusuario` int(11) NOT NULL,
  `AddedContacts` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`idContacts`, `nombre`, `segundo_nombre`, `apellido_paterno`, `apellido_materno`, `Nivel de interés`, `observaciones`, `email`, `telefono_fijo`, `celular`, `COD_idusuario`, `AddedContacts`) VALUES
(265, 'Ministerio de salud', 'MINSA', 'MINSA', 'MINSA', 'Alto', '30 camas uci', 'MINSA@gmail.com', '2743081', '987654321', 58, '2024-05-08 13:39:42'),
(266, 'Clinica Javier Prado', 'Clinica Javier Prado', 'Clinica Javier Prado', 'Clinica Javier Prado', 'Medio', '20 camillas de enefermeria', 'ClinicaJavierPrado@hotmail.com', '2736091', '931287654', 58, '2024-05-08 13:40:52'),
(267, 'Hospital del niño', 'Hospital del niño', 'Hospital del niño', 'Hospital del niño', 'Bajo', '10 veladores metalicos', 'Hospitaldelninno@outlook.com', '2809090', '9384751234', 58, '2024-05-08 13:42:12'),
(268, 'carlo', 'delgado', 'morales', 'calderon', 'Medio', 'flaco', 'holaq@gmail.com', '2764536', '983456723', 57, '2024-06-05 20:10:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `idCliente` int(11) NOT NULL,
  `Company` varchar(255) NOT NULL,
  `RUC` bigint(20) DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Distrito` varchar(50) DEFAULT NULL,
  `Provincia` varchar(50) DEFAULT NULL,
  `Departamento` varchar(50) DEFAULT NULL,
  `Pais` varchar(50) DEFAULT NULL,
  `Cargo` varchar(100) DEFAULT NULL,
  `Cantidad_Empleados` int(11) DEFAULT NULL,
  `OrigenCliente` varchar(255) DEFAULT NULL,
  `COD_idusuario` int(11) NOT NULL,
  `COD_idcontacto` int(11) NOT NULL,
  `AddedCustomers` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`idCliente`, `Company`, `RUC`, `URL`, `Direccion`, `Distrito`, `Provincia`, `Departamento`, `Pais`, `Cargo`, `Cantidad_Empleados`, `OrigenCliente`, `COD_idusuario`, `COD_idcontacto`, `AddedCustomers`) VALUES
(70, 'VIRTUAL BUSINESS S.A.C', 20601622921, 'www.virtualbusiness.pe', 'MLC. 28 DE JULIO NRO 211 DEP. 407 URB. PETIT JEAN OCHARAN ', 'MIRAFLORES', 'LIMA', 'LIMA', 'Perú', 'Gerente de Ventas', 3, 'Generación Propia', 23, 33, '2023-07-27 16:15:30'),
(72, 'AEROPUERTOS DEL PERU S.A.', 20514513172, '', 'JR. DOMENICO MORELLI NRO 110 INT. 501 ', 'SAN BORJA', 'LIMA', 'LIMA', 'Perú', 'Sistemas', 1, 'Generación Propia', 23, 35, '2023-07-27 16:15:30'),
(74, 'COMPAÑIA PROCESADORA MOLLEHUACA S.A.C.', 20535879762, '', 'AV. MANUEL OLGUIN NRO 373 INT. 501 URB. LOS GRANADOS ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'TI', 20, 'Generación Propia', 24, 37, '2023-07-31 17:31:59'),
(76, 'DS PROYECTOS S.A.C.', 20392474618, '', 'AV. MANUEL OLGUIN NRO 335 INT. 1301 ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'TI', 20, 'Generación Propia', 24, 38, '2023-08-07 16:16:31'),
(77, 'BOOSTER GROUP PERU SAC', 20519118221, '', 'CAL. SANTO TOMAS MZA. M1 LOTE 18 URB. VILLA MARINA ', 'CHORRILLOS', 'LIMA', 'LIMA', 'Perú', 'TI', 20, 'Generación Propia', 24, 38, '2023-08-07 22:44:40'),
(78, 'NEXTPLACEMENT EIRL', 20601193192, '', 'CAL. PONTEVEDRA NRO 185 DEP. 301 ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Gerente', 10, 'Generación Propia', 24, 43, '2023-08-07 23:54:03'),
(79, 'CCE CONSULTING S.A.C.', 20550870593, '', 'CAL. MARTIN DE MURUA NRO 150 INT. 403 URB. MARANGA ET. SIETE ', 'SAN MIGUEL', 'LIMA', 'LIMA', 'Perú', 'TI', 20, 'Generación Propia', 24, 37, '2023-08-14 20:27:34'),
(81, 'QUADRAT S.A.C.', 20508941341, '', 'AV. JAVIER PRADO ESTE NRO 5268 INT. 45 URB. CAMACHO ', 'LA MOLINA', 'LIMA', 'LIMA', 'Perú', 'TI', 10, 'Generación Propia', 24, 38, '2023-08-21 22:43:54'),
(82, 'RENZO COSTA S.A.C.', 20138998780, '', 'AV. DOS DE MAYO NRO 674 URB. ORRANTIA ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'TI', 500, 'Generación Propia', 24, 45, '2023-09-01 14:55:16'),
(84, 'SAMANCO MINING S.A.C.', 20611424541, '', 'AV. LA REPUBLICA NRO 818 URB. SANTA CRUZ ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Gerente', 5, 'Generación Propia', 24, 38, '2023-09-18 14:08:31'),
(85, 'INCA ONE METALS PERU S.A.', 20544188926, '', 'CAL. AMADOR MERINO REYNA NRO 465 INT. 402 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'TI', 50, 'Generación Propia', 24, 48, '2023-09-18 14:20:44'),
(86, 'UNIVERSIDAD CATOLICA LOS ANGELES DE CHIMBOTE', 20319956043, '', 'JR. TUMBES NRO 247 CENTRO COMERCIAL Y FINANC ', 'CHIMBOTE', 'SANTA', 'ANCASH', 'Perú', 'TI', 50, 'Generación Propia', 24, 49, '2023-09-18 15:39:08'),
(87, 'GENRENT DEL PERU S.A.C.', 20567129331, '', 'CAL. MOORE NRO 682 INT. A ', 'IQUITOS', 'MAYNAS', 'LORETO', 'Perú', 'TI', 50, 'Referido de marca', 24, 50, '2023-09-18 15:49:37'),
(88, 'LATERCER S.A.C.', 20514134155, '', 'AV. LAS TORRES MZA. S-N LOTE 27 ', 'LURIGANCHO', 'LIMA', 'LIMA', 'Perú', 'TI', 50, 'Generación Propia', 24, 51, '2023-09-18 15:57:13'),
(89, 'SERVICIOS DE SALUD LOS FRESNOS S.A.C.', 20453503047, '', 'CAL. LOS NOGALES NRO 179 URB. EL INGENIO ', 'CAJAMARCA', 'CAJAMARCA', 'CAJAMARCA', 'Perú', 'TI', 50, 'Referido de marca', 24, 52, '2023-09-18 16:02:16'),
(90, 'COFAMGA S.A.C.', 20606635606, 'www.claro.com.pe', 'CAL. LAUTARO NRO 234 DEP. 201 URB. GERMAN ASTETE ', 'SAN MIGUEL', 'LIMA', 'LIMA', 'Perú', 'Gerente de Ventas', 5, 'Generación Propia', 1, 52, '2023-09-21 15:32:23'),
(91, 'DATA SYSTEM SOLUTION PERU S.A.C.', 20553812837, '', 'AV. VILLARAN NRO 1117 URB. LOS SAUCES ', 'SURQUILLO', 'LIMA', 'LIMA', 'Perú', 'TI', 200, 'Generación Propia', 24, 53, '2023-09-26 16:37:22'),
(92, 'PRODUCTOS INDUSTRIALES ARTI S A', 20100310288, '', 'CAL. LOS TALLADORES NRO 353 INT. A URB. EL ARTESANO ', 'ATE', 'LIMA', 'LIMA', 'Perú', 'Gerente General', 200, 'Generación Propia', 24, 54, '2023-09-26 16:48:28'),
(93, 'DATA SYSTEM & GLOBAL SERVICES S.A.C.', 20600633601, '', 'CAL. MONTE ROSA NRO 256 INT. 1001 URB. CHACARILLA DEL ESTANQUE ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'TI', 200, 'Generación Propia', 24, 53, '2023-09-26 17:08:26'),
(94, 'PACHA EL MIRADOR S.A.C.', 20608439103, '', 'CAL. MARTIN DE MURUA NRO 150 INT. 403 URB. MARANGA ET. SIETE ', 'SAN MIGUEL', 'LIMA', 'LIMA', 'Perú', 'TI', 15, 'Generación Propia', 24, 37, '2023-09-26 21:19:19'),
(96, 'CORPORACION ACEROS AREQUIPA S.A.', 20370146994, '', 'CAR. PANAMERICANA SUR NRO 241 PANAMERICANA SUR ', 'PARACAS', 'PISCO', 'ICA', 'Perú', 'Gerente General ', 500, 'Generación Propia', 32, 55, '2023-09-29 15:53:04'),
(97, 'COMPAñIA DE MINAS BUENAVENTURA S.A.A.', 20100079501, 'https://www.buenaventura.com/es/', 'CAL. LAS BEGONIAS NRO 415 INT. P-19 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Ingeniero de TI', 1000, 'Campaña', 32, 56, '2023-09-29 16:20:26'),
(98, 'BI GRAND CONFECCIONES S.A.C.', 20553856451, 'ES UNA PRUEBA', 'JR. SAN GABRIEL NRO 284 URB. SAN CARLOS COMAS ', 'COMAS', 'LIMA', 'LIMA', 'Perú', 'ESTO ES UNA PRUEBA', 1, 'Campaña', 33, 35, '2023-09-29 21:18:51'),
(99, 'E.J.M. CONTADORES & ASESORES E.I.R.L.', 20601558026, 'https://compuempresa.com/info/bi-grand-confecciones-sac-20553856451', 'MZA. G LOTE 20 URB. VILLA HERMOSA ', 'SAN VICENTE DE CAÑETE', 'CAÑETE', 'LIMA', 'Perú', '1', 12, 'Referido de marca', 33, 35, '2023-09-29 21:23:40'),
(100, 'EJM CARDENAS S.A.C.', 20523860632, 'https://compuempresa.com/info/bi-grand-confecciones-sac-20553856451', 'AV. NICOLAS AYLLON MZA. A LOTE 23 URB. PARQ. IND. DE LA MADERA KM. 15.5 ', 'ATE', 'LIMA', 'LIMA', 'Perú', 'ESTO ES UNA PRUEBA', 32, 'Campaña', 33, 35, '2023-09-29 21:25:35'),
(101, 'ESCUELA DE DETECTIVES PRIVADOS DEL PERU E.I.R.L. - ESDEPRIP', 20603498799, 'https://compuempresa.com/info/bi-grand-confecciones-sac-20553856451', 'AV. SAN MARTIN NRO 335 URB. STA ISABEL ', 'CARABAYLLO', 'LIMA', 'LIMA', 'Perú', 'ESTO ES UNA PRUEBA', 2, 'Campaña', 33, 57, '2023-10-02 14:55:26'),
(102, 'FINE ART SOLUTIONS SOCIEDAD ANONIMA CERRADA', 20606106883, '', 'AV. GEMINIS NRO 395 ', 'SAN BORJA', 'LIMA', 'LIMA', 'Perú', 'Sistemas', 32, 'Referido de marca', 33, 35, '2023-10-02 15:00:33'),
(103, 'INFORMES TECH', 20606106883, 'https://compuempresa.com/info/bi-grand-confecciones-sac-20553856451', '', '', '', '', 'Perú', 'ESTO ES UNA PRUEBA', 1, 'Referido de marca', 33, 35, '2023-10-02 17:20:09'),
(104, 'CALZADOS AZALEIA PERU S.A', 20374412524, 'https://www.azaleia.pe/', 'AV. PROLONGACION ARICA NRO 2248 ', 'LIMA', 'LIMA', 'LIMA', 'Perú', 'Ingeniero de TI', 50, 'Generación Propia', 32, 59, '2023-10-05 21:15:35'),
(105, 'PESQUERA DIAMANTE S.A.', 20159473148, 'https://www.diamante.com.pe/', 'CAL. AMADOR MERINO REYNA NRO 307 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Ingeniero de TI', 100, 'Generación Propia', 32, 60, '2023-10-05 22:10:05'),
(106, 'SAMILLAN ALACHE MARIA MELANIA', 10164090588, '', '-', '', '', '', 'Perú', 'Ing', 1, 'Campaña', 33, 57, '2023-10-16 16:46:31'),
(107, 'DISTRIBUIDORA AMERICANA S.R.LTDA', 20352534677, 'https://compuempresa.com/info/bi-grand-confecciones-sac-20553856451', 'CAL. LAS ACACIAS NRO 210 URB. SANTA VICTORIA ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'TI', 1, 'Campaña', 33, 61, '2023-10-16 21:19:11'),
(108, 'PINEDA FLORES BRUNO ALONSO', 10764556009, 'https://compuempresa.com/info/bi-grand-confecciones-sac-20553856451', '-', '', '', '', 'Perú', 'Sistemas', 50, 'Referido de marca', 36, 63, '2023-10-18 20:16:39'),
(109, 'MUNICIPALIDAD DISTRITAL DE LA PUNTA', 20131379600, '', 'AV. GRAU Y SAENZ PENA NRO 298 ', 'LA PUNTA', 'PROV. CONST. DEL CALLAO', 'CALLAO', 'Perú', 'Administrador de Red', 30, 'Generación Propia', 24, 64, '2023-10-18 21:42:29'),
(110, 'QUIROZ CRUZ ZOILA AUREA', 10165036854, '', '-', '', '', '', 'Perú', '1', 1, 'Campaña', 37, 65, '2023-10-19 14:18:21'),
(111, 'SOLUCIONES INTEGRALES DE ALTA TECNOLOGIA S.A.C.', 20508195584, 'http://www.abzingenieros.com', 'AV. REPUBLICA DE PANAMA NRO 3418 URB. LIMATAMBO ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Jefe de Logistica', 50, 'Generación Propia', 32, 66, '2023-10-20 17:59:12'),
(112, 'ASTAH S.A.C.', 20603214499, '', 'MZA. H16 LOTE 5F URB. LAS DELICIAS DE VILLA ', 'CHORRILLOS', 'LIMA', 'LIMA', 'Perú', 'Gerente', 6, 'Generación Propia', 24, 67, '2023-10-27 16:44:49'),
(113, 'EDM TEXTIL SOCIEDAD ANONIMA CERRADA EN LIQUIDACION - EDM TEXTIL S.A.C. EN LIQUIDACION', 20520564200, 'https://sfibras-frontend.azurewebsites.net/login', 'JR. RIO DE JANEIRO NRO 124 ', 'JESUS MARIA', 'LIMA', 'LIMA', 'Perú', 'Jefe', 20, 'Campaña', 35, 68, '2023-11-30 23:21:32'),
(114, 'INMOBILIARIA LOS ALERCES S.A.C.', 20600517571, '', 'CAL. GERMAN SCHEREIBER NRO 276 URB. SANTA ANA ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'TI', 20, 'Generación Propia', 24, 69, '2023-12-19 22:33:40'),
(115, 'NETWORKING & CLOUD PERU S.A.C.', 20602552501, 'https://intermetalindustrias.com/', 'CAL. JOSE TORIBIO POLO NRO 327 URB. SANTA CRUZ ', 'MIRAFLORES', 'LIMA', 'LIMA', 'Perú', 'SEO SOFTWARE', 25, 'Generación Propia', 35, 70, '2024-01-30 00:41:27'),
(116, 'ALFARO & CONTADORES SOCIEDAD ANONIMA CERRADA', 20570892291, '', 'CAL. MARISCAL SUCRE NRO 1513 SEC. PUEBLO NUEVO ', 'JAEN', 'JAEN', 'CAJAMARCA', 'Perú', 'TI', 500, 'Campaña', 35, 71, '2024-01-30 16:51:54'),
(117, 'SUITES EL GOLF LOS INCAS S.A.', 20348851251, '', 'AV. CERROS DE CAMACHO NRO 500 URB. CERROS DE CAMACHO ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'TI', 100, 'Campaña', 41, 72, '2024-02-08 18:34:19'),
(118, 'COMPOSTELLA PERU E.I.R.L.', 20602638244, '', 'CAL. JULIAN ARCE NRO 229 URB. SANTA CATALINA ', 'LA VICTORIA', 'LIMA', 'LIMA', 'Perú', 'TI', 30, 'Generación Propia', 24, 73, '2024-02-22 20:13:49'),
(119, 'LADRILLOS DEL PERU S.A.C.', 20555893486, '', 'PJ. MARTIR OLAYA NRO 136 DEP. 303 ', 'MIRAFLORES', 'LIMA', 'LIMA', 'Perú', 'TI', 32, 'Referido de marca', 45, 74, '2024-02-22 20:32:21'),
(120, 'HALEMA S.A.C.', 20123316658, '', 'AV. VIRREY CONDE DE LEMOS NRO 231 URB. LA COLONIAL ', 'CALLAO', 'PROV. CONST. DEL CALLAO', 'CALLAO', 'Perú', 'ADMNISTRADOR', 293, 'Generación Propia', 50, 75, '2024-03-01 22:29:20'),
(121, 'CENTRO MEDICO SERVIMEDICS S.A.C.', 20610430717, '', 'CAL. AYACUCHO NRO 254 CERCADO DE ICA ', 'ICA', 'ICA', 'ICA', 'Perú', 'Gerente', 8, 'Generación Propia', 44, 76, '2024-03-01 22:51:44'),
(122, 'BUS CRUCERO STAR S.A.C.', 20568254792, '', 'AV. FERROCARRIL NRO 151 INT. 29 ', 'HUANCAYO', 'HUANCAYO', 'JUNIN', 'Perú', 'Gerente General', 2, 'Referido de marca', 47, 78, '2024-03-04 14:47:00'),
(123, 'CORPORACIÓN EDUCATIVA F.R. S.A.C.', 20568305532, '', 'JR. HUANCAS NRO 749 ', 'HUANCAYO', 'HUANCAYO', 'JUNIN', 'Perú', 'TI', 66, 'Referido de marca', 47, 80, '2024-03-04 14:55:53'),
(124, 'INVERSIONES TOMAS VALLE S.A.C', 20476633932, '', 'JR. DOMINGO CUETO NRO 444 ', 'JESUS MARIA', 'LIMA', 'LIMA', 'Perú', 'Compras - TI', 547, 'Generación Propia', 47, 81, '2024-03-04 15:09:19'),
(126, 'GTD PERÚ S.A', 20421780472, '', 'AV. LA ENCALADA NRO 1257 URB. LIMA POLO AND HUNT CLUB ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Jefe de Preventa', 202, 'Generación Propia', 45, 82, '2024-03-04 15:51:36'),
(128, 'JIMSA ENTERTAINMENT GROUP PERU S.A.C.', 20393636239, '', 'JR. MANCO CAPAC NRO 371 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 84, '2024-03-04 23:58:29'),
(129, 'SEBASTIAN GARCIA AGAPITO ALBERTO', 10089719645, '', '-', '', '', '', 'Perú', 'ADMINISTRADOR', 1, 'Generación Propia', 50, 85, '2024-03-05 00:09:07'),
(130, 'SOLUCIONES E INNOVACIONES E&T EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 20602814051, '', 'MZA. U LOTE 10 P.J. VILLA LOURDES - JOSE GALV ', 'MOLLENDO', 'ISLAY', 'AREQUIPA', 'Perú', 'Gerente', 10, 'Generación Propia', 49, 86, '2024-03-05 04:07:55'),
(131, 'SERVICIOS MEDICOS SAGRADO CORAZON E.I.R.L.', 20533066594, '', 'JR. BATALLA DE ARICA MZA. L LOTE 2 URB. ADUANEROS ', 'ILO', 'ILO', 'MOQUEGUA', 'Perú', 'Administradora', 18, 'Generación Propia', 49, 87, '2024-03-05 04:19:18'),
(132, 'POLICLINICO MIRMAR S.A.C.', 20601698987, '', 'CAL. H. UNANUE / CALLE SAUCINI MZA. B LOTE 15 ', 'TACNA', 'TACNA', 'TACNA', 'Perú', 'Administradora', 20, 'Generación Propia', 49, 88, '2024-03-05 04:24:54'),
(133, 'M. & P. REPRESENTACIONES GENERALES S.A.C', 20498192930, '', 'NRO F INT. 14 URB. LARA ', 'SOCABAYA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Gerente', 79, 'Generación Propia', 49, 89, '2024-03-05 04:28:37'),
(134, 'VULK ENGINEERING EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 20455789509, '', 'JR. MADRE DE DIOS ZONA G MZA. 4 LOTE 3 URB. SEMIRURAL PACHACUTEC ', 'CERRO COLORADO', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Encargada Logística', 50, 'Referido de marca', 49, 90, '2024-03-05 04:31:58'),
(135, 'CORPORACION PERUANA DE AEROPUERTOS Y AVIACION COMERCIAL SOCIEDAD ANONIMA - CORPAC S.A.', 20100004675, '', 'AV. ELMER FAUCETT NRO 3400 ARPTO INTER J CHAVE ', 'CALLAO', 'PROV. CONST. DEL CALLAO', 'CALLAO', 'Perú', 'GERENTE', 1449, 'Generación Propia', 50, 91, '2024-03-05 12:36:53'),
(136, 'CORPORACION DE SERVICIOS GR S.A.', 20100039037, '', 'AV. CIRCUNVALACIÓN DEL CLUB G NRO 134 INT. 1101 URB. CLUB GOLF LOS INCAS ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'GERENTE TI', 162, 'Generación Propia', 50, 92, '2024-03-05 12:42:17'),
(137, 'CAJA MUNICIPAL DE CREDITO POPULAR DE LIM A', 20100269466, '', 'AV. NICOLAS DE PIEROLA NRO 534 CERCADO DE LIMA ', 'LIMA', 'LIMA', 'LIMA', 'Perú', 'GERENTE TI', 503, 'Generación Propia', 50, 93, '2024-03-05 12:51:46'),
(138, 'CONAFOVICER', 20100816611, '', 'PROL CANGALLO NRO 670 INT. PS3 ', 'LA VICTORIA', 'LIMA', 'LIMA', 'Perú', 'GERENTE TI', 297, 'Generación Propia', 50, 94, '2024-03-05 12:55:38'),
(139, 'EXIMPORT DISTRIBUIDORES DEL PERU S A', 20100041520, '', 'AV. ARGENTINA NRO 1710 ', 'LIMA', 'LIMA', 'LIMA', 'Perú', 'GERENTE TI', 275, 'Generación Propia', 50, 95, '2024-03-05 12:58:49'),
(140, 'SCOTIABANK PERU SAA', 20100043140, '', 'AV. CANAVAL Y MOREYRA NRO 522 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'GERENTE TI', 5577, 'Generación Propia', 50, 96, '2024-03-05 13:02:06'),
(141, 'CEMENTOS PACASMAYO S.A.A.', 20419387658, '', 'CAL. LA COLONIA NRO 150 URB. EL VIVERO ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Jefe de Infraestruct', 720, 'Generación Propia', 45, 97, '2024-03-05 13:55:01'),
(142, 'YOFC PERU S.A.C.', 20604175756, '', 'AV. ENRIQUE CANAVAL Y MOREIRA NRO 480 INT. 1500 URB. LIMATAMBO ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Gerente de Proyecto', 349, 'Generación Propia', 45, 98, '2024-03-05 14:19:22'),
(143, 'HYDROGAS S.A.C.', 20607544663, '', 'AV. SAN BORJA NORTE NRO 1222 INT. 402 URB. SAN BORJA SUR ', 'SAN BORJA', 'LIMA', 'LIMA', 'Perú', 'Administración', 37, 'Generación Propia', 45, 99, '2024-03-05 14:38:15'),
(144, 'FRECUENCIA ORIENTAL PARA RADIO Y TELEVISION J.J. MUÑOZ E HIJOS S.A.', 20206557045, '', 'JR. VARGAS GUERRA NRO 344 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 15, 'Generación Propia', 50, 101, '2024-03-05 15:38:23'),
(145, 'COMUNICADORA AMAZONICA S.A.C.', 20129010895, '', 'JR. LETICIA NRO 127 INT. B ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 2, 'Generación Propia', 50, 102, '2024-03-05 15:48:01'),
(146, 'A MIL POR HORA S.A.C.', 20604093024, '', 'JR. URUBAMBA MZA. 244 LOTE 02 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 9, 'Generación Propia', 50, 103, '2024-03-05 15:50:27'),
(147, 'COMUNICADORA INDOPERUANA  E.I.R.L.', 20393305631, '', 'JR. 7 DE JUNIO NRO 500 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 104, '2024-03-05 15:56:41'),
(148, 'CORPORACION LIDER S.R.L.', 20393331390, '', 'JR. GRAU NRO 144 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 6, 'Generación Propia', 50, 105, '2024-03-05 16:03:25'),
(149, 'EMPRESA RADIO DIFUSORA DEL PROGRESO EIRL', 20309676158, '', 'JR. PROGRESO NRO 625 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 9, 'Generación Propia', 50, 106, '2024-03-05 16:06:23'),
(150, 'PRODUCCIONES PERIODISTICAS RADIO TELEVISIVA  FFOV. E.I.R.L.', 20393818850, '', 'JR. OSCAR R. BENAVIDES NRO 433 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 107, '2024-03-05 16:09:22'),
(151, 'RADIO PUCALLPA SCR LTDA', 20128869264, '', 'JR. INMACULADA NRO 667 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 108, '2024-03-05 16:11:35'),
(152, 'RADIODIFUSORA DEL UCAYALI S.A.C', 20128932721, '', 'JR. CORONEL PEDRO PORTILLO NRO 448A URB. CERCADO DE PUCALLPA ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 19, 'Generación Propia', 50, 109, '2024-03-05 16:13:55'),
(153, 'IN GAMING S.A.C.', 20601192170, '', 'LOS TALLANES NRO 204 DEP. 401 URB. SANTA CONSTANZA ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Administración', 17, 'Referido de marca', 45, 110, '2024-03-05 16:42:54'),
(154, 'METAL SUPPLIES COORP-E.I.R.L.', 20144246871, '', 'CAL. LAGO JUNIN NRO 270 URB. LA RINCONADA DEL LAGO ', 'LA MOLINA', 'LIMA', 'LIMA', 'Perú', 'Gerente General', 3, 'Generación Propia', 47, 111, '2024-03-05 21:55:21'),
(155, 'SABORES MILENARIOS DEL PERU SAC', 20517900738, '', 'MZA. H1 LOTE 28 URB. EL CARMEN ', 'PUNTA HERMOSA', 'LIMA', 'LIMA', 'Perú', 'Administrador', 4, 'Generación Propia', 47, 112, '2024-03-05 21:58:09'),
(156, 'FLESAN DEL PERU SOCIEDAD ANONIMA CERRADA', 20516368994, '', 'AV. JAVIER PRADO OESTE NRO 757 URB. SAN FELIPE ', 'MAGDALENA DEL MAR', 'LIMA', 'LIMA', 'Perú', 'Gerente General', 153, 'Generación Propia', 47, 113, '2024-03-05 22:04:27'),
(157, 'PLUMAS S.A.C.', 20102026986, '', 'PJ. SAMUEL LUIS VILLARAN NRO 228 ', 'CHORRILLOS', 'LIMA', 'LIMA', 'Perú', 'gerente general', 64, 'Referido de marca', 47, 115, '2024-03-05 22:18:22'),
(158, 'NEXT GENERATION SERVICES S.A.C.', 20502653076, '', 'AV. SALAVERRY NRO 2430 ', 'JESUS MARIA', 'LIMA', 'LIMA', 'Perú', 'gerente general', 4, 'Generación Propia', 47, 116, '2024-03-05 22:26:14'),
(159, 'A & C POLO CONSULTORES E.I.R.L.', 20608699059, '', 'AV. PRIMAVERA MZA. 33 LOTE 3 A.H. SAN ISIDRO ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 118, '2024-03-06 02:20:37'),
(160, 'A & G INVERSIONES SELVA E.I.R.L.', 20601776201, '', 'JR. 7 DE JUNIO NRO 820 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 4, 'Generación Propia', 50, 119, '2024-03-06 02:30:46'),
(161, 'FREE GAMES S.A.C.', 20563314924, '', 'AV. MANUEL OLGUIN NRO 211 INT. 1101 URB. LOS GRANADOS ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Administradora', 1296, 'Generación Propia', 45, 120, '2024-03-06 14:39:10'),
(163, 'LO JUSTO S.A.C.', 20413815071, '', 'JR. HUANUCO NRO 204 SEMI RURAL PACHACUTEC ', 'CERRO COLORADO', 'AREQUIPA', 'AREQUIPA', 'Perú', '1300', 60, 'Generación Propia', 49, 122, '2024-03-07 03:38:47'),
(164, 'SOCIEDAD DE BENEFICENCIA AREQUIPA', 20120958136, '', 'AV. GOYENECHE NRO 341 URB. CERCADO ', 'AREQUIPA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'TI', 250, 'Generación Propia', 49, 123, '2024-03-07 03:44:08'),
(165, 'A & F SERVICIOS GENERALES Y CONSTRUCCIONES S.A.C.', 20393997177, '', 'JR. URUBAMBA NRO 573 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Campaña', 50, 124, '2024-03-07 12:06:56'),
(166, 'UNIVERSIDAD TECNOLOGICA DEL PERU S.A.C. O UTP S.A.C.', 20462509236, '', 'CAL. NATALIO SANCHEZ NRO 125 URB. SANTA BEATRIZ ', 'LIMA', 'LIMA', 'LIMA', 'Perú', 'TI', 30, 'Referido de marca', 24, 125, '2024-03-07 14:35:45'),
(167, 'J. G Y R S.A.C.', 20454742321, '', 'AV. RAMON CASTILLA NRO 201A P.J. FRANCISCO BOLOGNESI ', 'CAYMA', 'AREQUIPA', 'AREQUIPA', 'Perú', '1300', 46, 'Generación Propia', 49, 127, '2024-03-07 15:23:26'),
(169, 'UNIDAD EJECUTORA 406 RED DE SALUD HUANCAVELICA', 20600985206, '', 'AV. ERNESTO MORALES NRO 929 A.H. ASCENSION ', 'ASCENSION', 'HUANCAVELICA', 'HUANCAVELICA', 'Perú', 'Coordinadora', 984, 'Campaña', 47, 129, '2024-03-08 15:46:24'),
(171, 'ARCA CONTINENTAL LINDLEY S.A.', 20101024645, '', 'JR. CAJAMARQUILLA NRO 1241 URB. ZARATE ', 'SAN JUAN DE LURIGANCHO', 'LIMA', 'LIMA', 'Perú', 'Coordinador Infra', 2492, 'Generación Propia', 47, 131, '2024-03-11 14:39:31'),
(172, 'REPRESENT Y DISTRIBUCIONES DEL NORTE SAC', 20103668876, '', 'AV. PAKAMUROS NRO 1108 INT. 1110 SEC. PUEBLO LIBRE ', 'JAEN', 'JAEN', 'CAJAMARCA', 'Perú', 'JEFE DE SISTEMAS', 98, 'Generación Propia', 50, 132, '2024-03-11 15:35:29'),
(173, 'A & K SERVICIOS MULTIPLES INGENIERIA S.R.L.', 20393230364, '', 'JR. LAGO TITICACA NRO 143 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 5, 'Generación Propia', 50, 133, '2024-03-11 16:03:41'),
(174, 'A C S CONSTRUCCIONES & SERVICIOS GENERALES E.I.R.L.', 20604170801, '', 'JR. AYACUCHO MZA. 274A LOTE 10 ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 134, '2024-03-11 16:08:54'),
(175, 'A&J CONSULTING AND CONSTRUCTING E.I.R.L.', 20606808926, '', 'JR. RICARDO FLORES MZA. J LOTE 33 A.H. CORONEL PEDRO PORTILLO ', 'CALLERIA', 'CORONEL PORTILLO', 'UCAYALI', 'Perú', 'GERENTE', 1, 'Generación Propia', 50, 135, '2024-03-11 16:14:40'),
(176, 'DISTRIBUCIONES OLANO S.A.C.', 20103365628, '', 'CAL. LEONCIO PRADO NRO 549 URB. EL PORVENIR ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'JEFE DE SISTEMAS', 398, 'Generación Propia', 50, 136, '2024-03-11 21:40:36'),
(177, 'HOSPITAL METROPOLITANO SOCIEDAD ANONIMA', 20394723259, '', 'CAL. MANUEL MARIA IZAGA NRO 154 CENTRO ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'JEFE DE TI', 106, 'Campaña', 46, 100, '2024-03-12 12:55:30'),
(178, 'GOBIERNO REGIONAL LAMBAYEQUE', 20479569780, '', 'AV. JUAN TOMIS STACK KM. 4.5 ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'GERENTE EJECUTIVO EM', 132, 'Campaña', 46, 137, '2024-03-12 13:33:37'),
(179, 'MUNICIPALIDAD PROVINCIAL DE LAMBAYEQUE', 20175975234, '', 'CAL. BOLIVAR NRO 400 CERCADO ', 'LAMBAYEQUE', 'LAMBAYEQUE', 'LAMBAYEQUE', 'Perú', 'JEFE DE LOGISTICA', 611, 'Campaña', 46, 138, '2024-03-12 14:06:36'),
(180, 'CAJA MUNICIPAL DE AHORRO Y CREDITO DE MAYNAS S.A.', 20103845328, '', 'JR. PROSPERO NRO 791 ', 'IQUITOS', 'MAYNAS', 'LORETO', 'Perú', 'Jefe de Sistemas', 648, 'Generación Propia', 45, 139, '2024-03-12 14:26:53'),
(181, 'SOA PROFESSIONALS PERU S.A.C.', 20518805526, '', 'AV. JORGE BASADRE NRO 607 INT. 323 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Gerente General', 28, 'Referido de marca', 45, 141, '2024-03-12 14:32:25'),
(182, 'LOGISTICA DEL SUR S.R.L.', 20324731564, '', 'AV. J.J. ELIAS NRO 451 ', 'ICA', 'ICA', 'ICA', 'Perú', 'JEFE TI', 8, 'Campaña', 44, 142, '2024-03-12 15:33:06'),
(183, 'EMP. DE TRANS. FLORES HNOS. SRL.', 20119407738, '', 'AV. PASEO DE LA REPUBLICA 619 NRO 627 CERCADO ', 'LA VICTORIA', 'LIMA', 'LIMA', 'Perú', 'administradora', 55, 'Campaña', 44, 143, '2024-03-12 15:45:43'),
(184, 'KING KONG LLAMPAYEC E.I.R.L.', 20480606842, '', 'AV. RAMON CASTILLA NRO 443 P.J. CPM RAMON CASTILLA ', 'LAMBAYEQUE', 'LAMBAYEQUE', 'LAMBAYEQUE', 'Perú', 'CONTADOR', 19, 'Campaña', 46, 144, '2024-03-12 15:56:38'),
(185, 'INSTITUTO DE EDUCACION SUPERIOR TECNOLOGICO PUBLICO HUANCAVELICA', 20199466730, '', 'AV. SANTOS VILLA NRO 1850 ', 'ASCENSION', 'HUANCAVELICA', 'HUANCAVELICA', 'Perú', 'RESPONSABLE DE TI', 5, 'Campaña', 46, 145, '2024-03-12 16:00:57'),
(186, 'CORTE SUPERIOR DE JUSTICIA DE LIMA SUR', 20602779875, '', 'MZA. A5 LOTE 1 P.J. J.C. MARIATEGUI ', 'VILLA MARIA DEL TRIUNFO', 'LIMA', 'LIMA', 'Perú', 'Compras', 923, 'Campaña', 45, 146, '2024-03-12 17:41:08'),
(187, 'MUNICIPALIDAD PROVINCIAL DE HUAMANGA', 20143137296, '', 'PORTAL MUNICIPAL NRO 44 ', 'AYACUCHO', 'HUAMANGA', 'AYACUCHO', 'Perú', 'Compras', 926, 'Referido de marca', 45, 147, '2024-03-12 19:16:16'),
(188, 'MINERA SHOUXIN PERU S.A.', 20392776975, '', 'AV. REPUBLICA  DE CHILE NRO 262 ', 'JESUS MARIA', 'LIMA', 'LIMA', 'Perú', 'Infraestructura', 484, 'Campaña', 45, 148, '2024-03-13 15:59:54'),
(189, 'UNIDAD DE GESTION EDUCATIVA LOCAL N° 8 CANETE', 20191551371, '', 'AV. 28 DE JULIO NRO 424 ', 'SAN VICENTE DE CAÑETE', 'CAÑETE', 'LIMA', 'Perú', 'JEFE DEL AREA PEDAGO', 100, 'Generación Propia', 50, 149, '2024-03-13 17:03:56'),
(190, 'VISTONY COMPAÑIA INDUSTRIAL DEL PERU SOCIEDAD ANONIMA CERRADA', 20102306598, '', 'MZA. B1 LOTE 01 PQUE.IND.DE ANCON-ACOMPIA ', 'ANCON', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 100, 'Generación Propia', 50, 150, '2024-03-13 20:06:01'),
(191, 'CAJA MUNICIPAL DE AHORRO Y CREDITO DE PAITA S.A.', 20102361939, '', 'JR. PLAZA DE ARMAS NRO 176 INT. 178 RES. CENTRO DE LA CIUDAD ', 'PAITA', 'PAITA', 'PIURA', 'Perú', 'JEFE DE SISTEMAS', 305, 'Generación Propia', 50, 151, '2024-03-13 20:08:45'),
(192, 'MANUFACTURAS SAN ISIDRO S.A.C.', 20101298851, '', 'JR. AZANGARO NRO 246 ', 'LIMA', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 1785, 'Generación Propia', 50, 152, '2024-03-13 20:11:58'),
(193, 'LA MOLINA CHRISTIAN SCHOOLS', 20346818990, '', 'AV. LA MOLINA NRO 3880 URB. SOL DE LA MOLINA ', 'LA MOLINA', 'LIMA', 'LIMA', 'Perú', 'PRESIDENTE', 44, 'Generación Propia', 50, 153, '2024-03-13 22:06:54'),
(194, 'SAFETY TRANSPORT PERU S.R.L.', 20513610913, '', 'AV. PERU MZA. C LOTE 10 ASC. APTASA ', 'CERRO COLORADO', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Gerente', 20, 'Generación Propia', 49, 154, '2024-03-13 22:14:23'),
(195, 'VALLEJO YUCA MAYKE RIQUELMER', 10725092640, '', '-', '', '', '', 'Perú', 'TI', 15, 'Generación Propia', 49, 155, '2024-03-13 22:18:21'),
(196, 'HOSPITAL REGIONAL MANUEL NUÑEZ BUTRON', 20448446485, '', 'JR. RICARDO PALMA NRO 120 BARRIO VICTORIA ', 'PUNO', '', 'PUNO', 'Perú', 'TI', 729, 'Generación Propia', 49, 156, '2024-03-13 22:24:26'),
(197, 'MIGO SOCIEDAD ANONIMA CERRADA - MIGO S.A.C.', 20603274742, '', 'AV. JORGE CHAVEZ NRO 204 URB. JORGE CHAVEZ ', 'PAUCARPATA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Gerente', 6, 'Generación Propia', 49, 157, '2024-03-13 22:30:19'),
(199, 'MINISTERIO DE DESARROLLO E INCLUSION SOCIAL', 20545565359, '', 'AV. PASEO DE LA REPÚBLICA NRO 3101 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'CORDINADORA', 799, 'Referido de marca', 46, 159, '2024-03-13 23:21:40'),
(200, 'RAPID FOOD SERVICE E.I.R.L.', 20532174946, '', 'CAL. LOS ARCES LOTE 13 DEP. 301 RES. SANTA ELISA ', 'CAYMA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Administradora', 12, 'Generación Propia', 49, 160, '2024-03-14 00:27:11'),
(201, 'EMPRESA DE TRANSPORTES EL CUMBE S.A.C.', 20105752149, '', 'AV. JOSE QUINONES NRO 425 ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'CONTADOR', 71, 'Generación Propia', 46, 161, '2024-03-14 00:36:02'),
(202, 'HIKVISION PERU S.A.C.', 20603856351, '', 'AV. ENRIQUE CANAVAL Y MOREYRA NRO 480 DEP. 1001 URB. LIMATAMBO ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'asistente', 37, 'Campaña', 47, 162, '2024-03-14 17:01:35'),
(203, 'SERVICIOS DE SALUD MONTEFIORI SAC', 20461665820, '', 'AV. SEPARADORA INDUSTRIAL NRO 1820 URB. LOS CACTUS ', 'LA MOLINA', 'LIMA', 'LIMA', 'Perú', 'TI', 361, 'Campaña', 47, 163, '2024-03-14 19:18:27'),
(204, 'SURTIFOODS PERU S.A.C.', 20549049240, '', 'AV. CHORRILLOS NRO 396 URB. COSTA SUR ', 'CHORRILLOS', 'LIMA', 'LIMA', 'Perú', 'CONTABILIDAD ', 28, 'Referido de marca', 46, 164, '2024-03-19 15:59:33'),
(205, 'UNIVERSIDAD NACIONAL DE HUANCAVELICA', 20168014962, '', 'JR. VICTORIA GARMA NRO 275 BARR.CENTRO ', 'HUANCAVELICA', 'HUANCAVELICA', 'HUANCAVELICA', 'Perú', 'Jefe de Infraestruct', 730, 'Generación Propia', 45, 165, '2024-03-19 18:44:21'),
(206, 'GERENCIA REGIONAL DE AGRICULTURA', 20396088127, '', 'CAL. AREQUIPA NRO 138 URB. LOS LIBERTADORES ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'ABASTECIMIENTO', 161, 'Generación Propia', 46, 166, '2024-03-19 23:55:51'),
(207, 'UNIDAD EJECUTORA RED DE SALUD HUAMANGA', 20495122361, '', 'MZA. D LOTE 16 URB. BANCO DE LA NACION ', 'AYACUCHO', 'HUAMANGA', 'AYACUCHO', 'Perú', 'ABASTECIMIENTO', 100, 'Generación Propia', 50, 167, '2024-03-20 22:46:21'),
(208, 'ZAMINE SERVICE PERU SAC', 20392995006, '', 'AV. LA ENCALADA NRO 1420 INT. 801 URB. POLO HUNT ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'TI', 240, 'Referido de marca', 49, 168, '2024-03-21 00:33:45'),
(209, 'SERVICIOS MEDICOS FIKA E.I.R.L.', 20602804365, '', 'CAL. CLORINDA MATTO DE TURNER MZA. B LOTE 29 URB. PABLO VI - PRIMERA ETAPA ', 'AREQUIPA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Administrador', 160, 'Generación Propia', 49, 169, '2024-03-21 01:04:06'),
(210, 'COMPUMEX E.I.R.L.', 20600548663, '', 'AV. PRINCIPAL MZA. B LOTE 1 A.H. SAN GENARO ', 'CHORRILLOS', 'LIMA', 'LIMA', 'Perú', 'RESPONSABLE SUCURSAL', 3, 'Referido de marca', 46, 170, '2024-03-21 04:34:37'),
(211, 'ZONAPC E.I.R.L.', 20539216989, '', 'BL. 15 NRO 1501 ', 'TUMAN', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'GERENTE', 2, 'Generación Propia', 46, 171, '2024-03-21 05:37:36'),
(212, 'AVIATION SECURITY GROUP SAC', 20462792396, '', 'AV. DEL PINAR NRO 180 INT. 403 URB. CHACARILLA DEL ESTANQUE ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 362, 'Generación Propia', 50, 172, '2024-03-21 11:15:52'),
(213, 'C 2 G SECURITY S.A.C.', 20554965289, '', 'AV. ALFREDO MENDIOLA NRO 5159 ', 'LOS OLIVOS', 'LIMA', 'LIMA', 'Perú', 'GERENTE', 50, 'Generación Propia', 50, 173, '2024-03-21 11:19:27'),
(214, 'CORPORACION EMPRESARIAL C&Z S.A.C.', 20481661481, '', 'CAL. BOLOGNESI NRO 180 INT. 706 ', 'MIRAFLORES', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 285, 'Generación Propia', 50, 174, '2024-03-21 11:21:31'),
(215, 'G4S PERU S.A.C. .', 20422293699, '', 'JR. JOSE GALVEZ 1766-1780 NRO 0 ', 'LINCE', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 9400, 'Generación Propia', 50, 175, '2024-03-21 11:26:55'),
(216, 'KANGUROS 3V S.A.C', 20525147976, '', 'CAL. NN2 NRO 120 INT. 213 ', 'PUEBLO LIBRE', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 254, 'Generación Propia', 50, 176, '2024-03-21 11:29:14'),
(217, 'LINEA 24 PERU SERVICIOS GENERALES SOCIEDAD ANONIMA CERRADA', 20548503796, '', 'AV. PAZ SOLDAN NRO 170 INT. 304 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 40, 'Generación Propia', 50, 177, '2024-03-21 11:31:59'),
(218, 'PLANINVEST S A', 20101008283, '', 'AV. DOS DE MAYO NRO 1225 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 556, 'Generación Propia', 50, 178, '2024-03-21 11:36:51'),
(219, 'SECURITY S.A.C.', 20516764831, '', 'CAL. 6 MZA. C URB. INDUSTRIAL AEROPUERTO ', 'CALLAO', 'PROV. CONST. DEL CALLAO', 'CALLAO', 'Perú', 'ASISTENTE TI', 153, 'Generación Propia', 50, 179, '2024-03-21 11:45:16'),
(220, 'SEGURIDAD Y VIGILANCIA MINOTAURO S.A.C.', 20565744764, '', 'MZA. A LOTE 11 A.H. LOS JAZMINES ', 'ANCON', 'LIMA', 'LIMA', 'Perú', 'ASISTENTE GERENCIA', 58, 'Generación Propia', 50, 180, '2024-03-21 11:48:00'),
(221, 'SERVICIO INTEGRAL INTERAMERICANO S.R.L.', 20331826279, '', 'CAL. LOS HALCONES NRO 210 URB. LIMATAMBO ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE LOGISTICA', 338, 'Generación Propia', 50, 181, '2024-03-21 11:54:23'),
(222, 'ARTHUR J. GALLAGHER PERU CORREDORES DE SEGUROS SOCIEDAD ANONIMA', 20601594464, '', 'AV. RICARDO RIVERA NAVARRETE NRO 475 INT. 1801 URB. JARDIN ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 50, 'Generación Propia', 50, 183, '2024-03-21 12:01:27'),
(223, 'ADMINISTRACION INMOBILIARIA SOCIEDAD ANONIMA CERRADA', 20506628963, '', 'CAL. LAS ORQUIDEAS NRO 585 INT. 301A URB. JARDIN ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 177, 'Generación Propia', 50, 184, '2024-03-21 12:03:42'),
(224, 'CUSHMAN & WAKEFIELD PERU S.A.', 20513809914, '', 'CAL. GERMAN SCHREIBER NRO 210 INT. 701 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 86, 'Generación Propia', 50, 185, '2024-03-21 13:49:42'),
(225, 'URBANOVA INMOBILIARIA S.A.C', 20551182992, '', 'CAL. LAS BEGONIAS NRO 415 URB. LA VICTORIA ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 137, 'Generación Propia', 50, 186, '2024-03-21 13:51:25'),
(226, 'A & Q INGENIEROS Y CONSULTORES S.A.C.', 20516986655, '', 'CAL. VIRREY TOLEDO NRO 420 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 129, 'Generación Propia', 50, 187, '2024-03-21 13:57:01'),
(227, 'APTIM PERU S.A.C.', 20601961009, '', 'CAL. CALLE 2 - ZONA B-1 MZA. A1 LOTE 2 URB. LAS VERTIENTES DE LURIN ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 800, 'Generación Propia', 50, 188, '2024-03-21 14:01:52'),
(228, 'CHINA INTERNATIONAL WATER & ELECTRIC CORP (PERU)', 20347029697, '', 'AV. VICTOR ANDRES BELAUNDE NRO 147 INT. 401 URB. EL ROSARIO ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 500, 'Generación Propia', 50, 189, '2024-03-21 14:03:42'),
(229, 'DOS COLUMNAS S.A.C.', 20513757931, '', 'AV. LA MAR NRO 1263 INT. 702 URB. SANTA CRUZ ', 'MIRAFLORES', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 96, 'Generación Propia', 50, 190, '2024-03-21 14:05:41'),
(230, 'CONSTRUCTORA Y SERVICIOS  RODEMA E.I.R.L', 20154727826, '', 'CAL. JOSÉ GRANDA NRO 459 URB. COUNTRY CLUB ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 81, 'Generación Propia', 50, 191, '2024-03-21 14:08:15'),
(231, 'MUNICIPALIDAD DISTRITAL DE SAN JOSE', 20163164401, '', 'CAL. BOLOGNESI NRO S/N ', 'SAN JOSE', 'LAMBAYEQUE', 'LAMBAYEQUE', 'Perú', 'ADMINISTRADOR', 40, 'Generación Propia', 46, 192, '2024-03-21 21:08:42'),
(234, 'SCAN PERU S.A.', 20420605006, '', 'AV. AVIACION NRO 3367 INT. 105 ', 'SAN BORJA', 'LIMA', 'LIMA', 'Perú', 'Gerente General', 5, 'Generación Propia', 45, 195, '2024-03-22 17:06:10'),
(236, 'INST.SUP.TECNOLOGICO PUBLICO SAN AGUSTIN', 20479359033, '', 'CAL. ALFONSO ARANA VIDAL NRO SN MORRO SOLAR BAJO ', 'JAEN', 'JAEN', 'CAJAMARCA', 'Perú', 'ADMINISTRATIVO', 1, 'Generación Propia', 46, 197, '2024-03-25 17:48:36'),
(237, 'ORGANISMO DE SUPERVISION DE LOS RECURSOS FORESTALES Y DE FAUNA SILVESTRE - OSINFOR', 20522224783, '', 'AV. ANTONIO MIROQUESADA NRO 420 URB. SAN FELIPE ', 'MAGDALENA DEL MAR', 'LIMA', 'LIMA', 'Perú', 'ANALISTA LOGISTICO ', 199, 'Referido de marca', 46, 198, '2024-03-25 23:56:05'),
(238, 'CABLE OPERADOR YVAENZ SOCIEDAD ANONIMA CERRADA', 20603094281, '', 'PRO. TUPAC AMARU NRO 637 CENTRO ', 'MONSEFU', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', '500', 6, 'Generación Propia', 46, 199, '2024-03-26 03:30:43'),
(239, 'EMP NACIONAL DE PUERTOS S A', 20100003199, '', 'JR. MANCO CAPAC NRO 255 ', 'CALLAO', 'PROV. CONST. DEL CALLAO', 'CALLAO', 'Perú', 'SUPERVISIÓN DE LOGÍS', 282, 'Generación Propia', 46, 200, '2024-03-26 04:28:06'),
(240, 'CORTE SUPERIOR DE JUSTICIA DE AREQUIPA', 20456310959, '', 'AV. SIGLO XX NRO S/N ', 'AREQUIPA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Gerente ', 1799, 'Referido de marca', 45, 201, '2024-03-26 14:37:56'),
(241, 'MICROSTRONIC EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA - MICROSTRONIC E.I.R.L.', 20606187069, '', 'PJ. 05  DE MAYO NRO S/N ', 'ASCENSION', 'HUANCAVELICA', 'HUANCAVELICA', 'Perú', 'JEFE DE SISTEMAS', 10, 'Generación Propia', 50, 202, '2024-03-26 16:52:02'),
(242, 'COMITE DE ADMINISTRACION DEL FONDO DE ASISTENCIA Y ESTIMULO (SUB CAFAE) DEL HOSPITAL STA MARIA DEL S', 20452445809, '', 'CAL. CASTROVIRREYNA NRO S/N ', 'ICA', 'ICA', 'ICA', 'Perú', 'Administración', 20, 'Referido de marca', 45, 203, '2024-03-26 19:39:52'),
(243, 'SUPERINTENDENCIA NAC.SERV.DE SANEAMIENTO', 20158219655, '', 'AV. BERNARDO MONTEAGUDO NRO 210 ', 'MAGDALENA DEL MAR', 'LIMA', 'LIMA', 'Perú', 'Gerente de Compras', 734, 'Campaña', 45, 204, '2024-03-26 19:57:00'),
(244, 'EMPRESA DE SEGURIDAD PRIVADA LEONES DE ORO SOCIEDAD COMERCIAL DE RESPONSABILIDAD LIMITADA', 20489468795, '', 'JR. ABTAO NRO 218 ', 'HUANUCO', 'HUANUCO', 'HUANUCO', 'Perú', 'GERENTE GENERAL', 65, 'Generación Propia', 50, 205, '2024-03-27 21:12:49'),
(245, 'TIENDAS TAMBO S.A.C.', 20563529378, '', 'AV. JAVIER PRADO ESTE NRO 6210 INT. 1201 URB. RIVERA DE MONTERRICO ', 'LA MOLINA', 'LIMA', 'LIMA', 'Perú', 'JEFE DE SISTEMAS', 1000, 'Generación Propia', 50, 206, '2024-03-27 21:43:30'),
(246, 'CONTUGAS S.A.C.', 20519485487, '', 'CAL. LAS ORQUIDEAS NRO 585 INT. 402 URB. JARDIN ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'Controller', 122, 'Generación Propia', 45, 207, '2024-04-03 15:05:50'),
(247, 'NATIONAL AIR & MOTOR CO. S.R.L.', 20603177194, '', 'MZA. D LOTE 6 ASC. ARTEMPA ', 'CERRO COLORADO', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Administrador', 17, 'Generación Propia', 49, 208, '2024-04-04 01:22:22'),
(248, 'PROCESADORA LESLIE SAMANCO SOCIEDAD ANONIMA CERRADA - PROCELSA S.A.C.', 20541726137, '', 'CAL. OCEANO PACIFICO NRO 265 URB. NEPTUNO ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Administradora', 35, 'Generación Propia', 49, 209, '2024-04-04 01:27:05'),
(249, 'JUNTA DE PROPIETARIOS DEL CENTRO COMERCIAL PASEO CENTRAL', 20609806410, '', 'NRO S/N PAGO CERRO JULI PASEO IND ', 'JACOBO HUNTER', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Administradora', 10, 'Generación Propia', 49, 210, '2024-04-04 01:31:40'),
(250, 'MUNICIPALIDAD DISTRITAL PUNTA DE BOMBON', 20181009161, '', 'PZA. 28 DE JULIO NRO S/N ', 'PUNTA DE BOMBON', 'ISLAY', 'AREQUIPA', 'Perú', 'Encargada Logística', 109, 'Generación Propia', 49, 211, '2024-04-04 01:37:59'),
(251, 'FAST & QUALITY SOCIEDAD COMERCIAL DE RESPONSABILIDAD LIMITADA', 20600150937, '', 'AV. PERU MZA. 18 LOTE 1 COLUMNA PASCO ', 'YANACANCHA', 'PASCO', 'PASCO', 'Perú', 'GERENTE', 20, 'Generación Propia', 44, 212, '2024-04-05 13:03:58'),
(252, 'SOLUM LOGISTICS S.A.C - SOLUM S.A.C.', 20600293568, '', 'AV. MANUEL OLGUIN NRO 335 INT. 1301 URB. LOS GRANADOS ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Administrador de Red', 269, 'Generación Propia', 45, 213, '2024-04-08 15:11:37'),
(253, 'GOBIERNO REGIONAL DE TACNA', 20519752515, '', 'AV. MANUEL A. ODRIA NRO 1245 ', 'TACNA', 'TACNA', 'TACNA', 'Perú', 'Operaciones', 1408, 'Generación Propia', 45, 214, '2024-04-08 15:45:39'),
(254, 'AUNA S.A.A.', 20477840427, '', 'AV. REPUBLICA DE PANAMA NRO 3461 ', 'SAN ISIDRO', 'LIMA', 'LIMA', 'Perú', 'TI', 34, 'Generación Propia', 52, 215, '2024-04-08 16:00:19'),
(255, 'YOBEL SUPPLY CHAIN MANAGEMENT S.A.', 20100074029, 'https://www.yobelscm.biz/', 'AV. SAN GENARO NRO 150 URB. MOLITALIA ', 'LOS OLIVOS', 'LIMA', 'LIMA', 'Perú', 'Administrador', 1045, 'Generación Propia', 45, 216, '2024-04-09 16:56:02'),
(256, 'EJERCITO PERUANO', 20131369124, '', 'AV. BOULEVARD NRO SN ', 'SAN BORJA', 'LIMA', 'LIMA', 'Perú', 'JEFE ZONAL', 100, 'Campaña', 50, 217, '2024-04-09 16:58:44'),
(257, 'A.V.I SEGURIDAD PRIVADA S.A.C.', 20535783650, '', 'MZA. H LOTE 17 SECTOR 01, GRUPO 05 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 206, 'Campaña', 50, 218, '2024-04-09 19:52:40'),
(258, 'SOLUCIONES ELECTROMECANICAS Y ESTRUCTURALES PERU E.I.R.L.', 20602718647, '', 'SECTOR 1 MZA. L LOTE 23 GRU. 22A ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 16, 'Campaña', 50, 219, '2024-04-10 01:03:40'),
(259, 'SOLUCIONES INTEGRALES Y SERVICIOS DE AIRE S.A.C. - SERVICIOS DE AIRE S.A.C.', 20535958395, '', 'MZA. G LOTE 15 SECTOR 3 GRUPO 10 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 12, 'Generación Propia', 50, 220, '2024-04-10 01:07:19'),
(260, 'RCD ASOCIADOS S.A.C.', 20536934660, '', 'GRUPO 14 MZA. N LOTE 11 SECTOR 1 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 12, 'Generación Propia', 50, 222, '2024-04-10 01:14:06'),
(261, 'SEGURIDAD EN INSTALACIONES GENERALES S.A.C.', 20608325299, '', 'MZA. A LOTE 9 OTR. SECTOR 1 GRUPO 25 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 19, 'Generación Propia', 50, 223, '2024-04-10 01:17:29'),
(262, 'STARSEC PERU S.A.C.', 20609346079, '', 'MZA. L LOTE 16 DEP. 3 SEC. 1 GRUPO 25A ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 17, 'Generación Propia', 50, 224, '2024-04-10 01:19:51'),
(263, 'INVERSIONES Y PROYECTOS INTEGRALES BARPA S.A.C.', 20603305923, '', 'MZA. L LOTE 8 SEC. 3 GRUPO 20 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 20, 'Generación Propia', 50, 225, '2024-04-10 01:21:44'),
(264, 'RUMI LABORATORIO GEOTÉCNICO S.A.C.', 20546473440, '', 'OTR. A GRU.16 MZA. B LOTE 1-A SEC. 2 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 68, 'Generación Propia', 50, 226, '2024-04-10 01:25:29'),
(265, 'PEOPLE DEVELOPMENT & INNOVATION CONSULTING S.A.C.', 20600228782, '', 'AV. JOSE CARLOS MARIATEGUI MZA. A LOTE 13 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 14, 'Generación Propia', 50, 228, '2024-04-10 01:30:03'),
(266, 'EURO MOTORS S.A.', 20168544252, 'http://www.euromotors.com.pe', 'AV. DOMINGO ORUE NRO 973 ', 'SURQUILLO', 'LIMA', 'LIMA', 'Perú', 'Coordinador TI', 235, 'Generación Propia', 54, 230, '2024-04-11 18:13:49'),
(267, 'ICATOM S.A.', 20310422755, 'http://www.icatom.com', 'AV. MANUEL SANTANA CHIRI NRO 1155 ', 'ICA', 'ICA', 'ICA', 'Perú', 'Responsable TI', 557, 'Generación Propia', 54, 229, '2024-04-11 18:14:53'),
(268, 'CADENA DE COMERCIO PERU S.A.C.', 20602743960, 'https://oxxo.pe/', 'JR. EL POLO NRO 401 URB. EL DERBY ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Administrador de red', 1286, 'Generación Propia', 45, 231, '2024-04-11 20:43:25'),
(269, 'SUPERINTENDENCIA NACIONAL DE FISCALIZACION LABORAL - SUNAFIL', 20555195444, '', 'AV. SALAVERRY NRO 655 ', 'JESUS MARIA', 'LIMA', 'LIMA', 'Perú', 'Oficina de Abastecim', 1619, 'Generación Propia', 46, 232, '2024-04-12 00:18:17'),
(270, 'CLUB CENTRO DEPORTIVO MUNICIPAL', 20206882469, '', 'OTR. SECTOR TERCERO MZA. M LOTE 13 RES. 11 VILLA EL SALVADOR ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 54, 'Generación Propia', 50, 235, '2024-04-15 17:31:31'),
(271, 'EMP.DE SERVICIOS EDUCATIVOS PRIVADOS SRL', 20390193883, '', 'GRUPO 26 MZA. J LOTE 22 SEC. 1 ', 'VILLA EL SALVADOR', 'LIMA', 'LIMA', 'Perú', 'GERENTE GENERAL', 264, 'Generación Propia', 50, 236, '2024-04-15 17:39:52'),
(272, 'BUGANVILLA TOURS S.A.C.', 20452235073, 'https://buganvillatours.com/', 'CAL. CAMINO REAL NRO D INT. 15 RES. LA ANGOSTURA ', 'ICA', 'ICA', 'ICA', 'Perú', 'Marketing', 34, 'Generación Propia', 54, 237, '2024-04-15 21:17:09'),
(273, 'PAPERCLIP E.I.R.L', 20452370108, 'https://paperclip.com.pe/', 'MZA. A LOTE 10 URB. SAN LUIS ', 'ICA', 'ICA', 'ICA', 'Perú', 'Gerente General', 1, 'Generación Propia', 54, 238, '2024-04-15 21:31:22'),
(274, 'ESCUELA DE CONDUCTORES INTEGRAL BREVE - T DEL SUR SOCIEDAD COMERCIAL DE RESPONSABILIDAD LIMITADA', 20448596967, '', 'JR. RAMON CASTILLA NRO 628 ', 'JULIACA', 'SAN ROMAN', 'PUNO', 'Perú', 'TI', 18, 'Generación Propia', 49, 239, '2024-04-15 22:18:26'),
(275, 'CAJA MUNICIPAL DE AHORRO Y CREDITO DE ICA SA', 20104888934, 'https://cajaica.pe/', 'AV. CONDE DE NIEVA NRO 498 ', 'ICA', 'ICA', 'ICA', 'Perú', 'TI Seguridad', 1610, 'Generación Propia', 54, 240, '2024-04-15 22:27:50'),
(276, 'CONSULTORIA CONSTRUCCION Y TELECOMUNICACIONES FORTED EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 20603701942, '', 'CAL. PUNO NRO 712 ', 'MIRAFLORES', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Gerente', 24, 'Generación Propia', 49, 241, '2024-04-16 00:06:18'),
(277, 'SERVICIOS MEDICOS SAN MARTIN DE PORRES EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 20600900651, '', 'JR. SAN MARTIN NRO 833 ', 'JULIACA', 'SAN ROMAN', 'PUNO', 'Perú', 'Gerente', 15, 'Generación Propia', 49, 242, '2024-04-16 00:11:17'),
(278, 'POWER S.A.C.', 20120858425, '', 'PJ. MARTINETTY NRO 129 ', 'AREQUIPA', 'AREQUIPA', 'AREQUIPA', 'Perú', 'TI', 85, 'Generación Propia', 49, 243, '2024-04-16 18:03:38'),
(279, 'INRETAIL PHARMA S.A.', 20331066703, '', 'AV. DEFENSORES DEL MORRO NRO 1277 ', 'CHORRILLOS', 'LIMA', 'LIMA', 'Perú', 'Gerente General', 22, 'Generación Propia', 46, 246, '2024-04-17 17:22:21'),
(280, 'CIRION TECHNOLOGIES PERU S.A.', 20252575457, 'https://www.ciriontechnologies.com/es-pe/', 'AV. MANUEL OLGUIN NRO 395 URB. LOS GRANADOS ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Senior Account Manag', 132, 'Generación Propia', 45, 247, '2024-04-17 23:24:05'),
(281, 'PROGRAMA INTEGRAL NACIONAL PARA EL BIENESTAR FAMILIAR - INABIF', 20507920722, 'https://www.gob.pe/inabif', 'AV. SAN MARTIN NRO 685 ', 'PUEBLO LIBRE', 'LIMA', 'LIMA', 'Perú', 'Compras', 2312, 'Campaña', 45, 250, '2024-04-19 20:22:50'),
(282, 'SOLGAS S.A.', 20100176450, '', 'CAL. CARPACCIO NRO 250 INT. 701 URB. SAN BORJA ', 'SAN BORJA', 'LIMA', 'LIMA', 'Perú', 'prueba', 21, 'Referido de marca', 24, 51, '2024-04-19 21:44:47'),
(283, 'PROYECTO ESPECIAL OLMOS TINAJONES', 20148346055, '', 'CAL. LAS VIOLETAS NRO 148 URB. LOS LIBERTADORES ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'PATRIMONIO', 303, 'Generación Propia', 46, 251, '2024-04-19 21:51:06'),
(284, 'PROGRAMA DE EMPLEO TEMPORAL LLAMKASUN PERÚ', 20504007945, 'https://www.gob.pe/llamkasunperu', 'AV. SALAVERRY NRO 655 INT. PS.7 ', 'JESUS MARIA', 'LIMA', 'LIMA', 'Perú', 'Compras', 186, 'Generación Propia', 45, 252, '2024-04-22 22:21:28'),
(285, 'CENTRO MEDICO SANTO TOMAS S.A.C.', 20608776100, '', 'AV. PANAMERICANA NRO 515 C.P.M. RICARDO PALMA ', 'CHICLAYO', 'CHICLAYO', 'LAMBAYEQUE', 'Perú', 'GERENTE GENERAL', 1, 'Generación Propia', 46, 254, '2024-04-23 01:15:08'),
(286, 'MARKETING POWER SOCIEDAD ANONIMA CERRADA - MARKETING POWER S.A.C.', 20543921255, '', 'AV. EL DERBY NRO 254 INT. 1705 URB. EL DERBY ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Administrador', 4909, 'Generación Propia', 44, 253, '2024-04-23 01:20:33'),
(287, 'INKA SELECT FRUIT S.A.C.', 20605493603, 'https://inkaselectfruit.com/index.php/nosotros/', 'CAL. EL BOULEVARD NRO 141 INT. 703 ', 'SANTIAGO DE SURCO', 'LIMA', 'LIMA', 'Perú', 'Gerente de Logistica', 2, 'Referido de marca', 45, 257, '2024-04-24 17:30:21'),
(288, 'CERTIFICADORA SAN MARTIN EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 20606581018, '', 'JR. AYACUCHO NRO 748 BAR. SAN ANTONIO ', 'PUNO', '', 'PUNO', 'Perú', 'TI', 16, 'Generación Propia', 49, 239, '2024-04-25 01:24:25'),
(289, 'CERTIFICADORA S & M PUNO E.I.R.L.', 20610022643, '', 'JR. AYACUCHO NRO 748 BAR. SAN ANTONIO ', 'PUNO', '', 'PUNO', 'Perú', 'TI', 15, 'Generación Propia', 49, 239, '2024-04-25 01:26:28'),
(290, 'HOSPITAL REGIONAL HERMILIO VALDIZAN', 20146038329, '', 'JR. HERMILIO VALDIZAN NRO 950 ', 'HUANUCO', 'HUANUCO', 'HUANUCO', 'Perú', 'Compras', 1245, 'Campaña', 54, 259, '2024-04-25 13:40:23'),
(291, 'GRUPO ISAMPAL S.A.C.', 20609149451, '', 'AV. 28 DE ABRIL NRO 340 A.H. SAN CRISTOBAL ', 'HUANCAVELICA', 'HUANCAVELICA', 'HUANCAVELICA', 'Perú', 'GERENTE GENERAL', 1, 'Generación Propia', 50, 261, '2024-04-26 15:57:13'),
(292, 'ELECTROSAN SERV.Y SUMINISTROS ELECTRICOS IND.SOC.COM.DE RESP.LIMITADA -ELECTROSAN INDUSTRIAL S.R.L.', 20455384193, '', 'CAL. ANCASH NRO 417 URB. EL  PORVENIR ', 'MIRAFLORES', 'AREQUIPA', 'AREQUIPA', 'Perú', 'Administrador', 26, 'Generación Propia', 49, 262, '2024-04-30 16:04:45'),
(293, 'CORE NETWORK SOLUTIONS S.A.C.', 20548470928, 'https://www.linkedin.com/company/core-network-solutions-inc./?originalSubdomain=ca', 'MZA. 90 LOTE 21 SECTOR EL BRILLANTE ', 'SAN JUAN DE MIRAFLORES', 'LIMA', 'LIMA', 'Perú', 'Gerente ', 5, 'Generación Propia', 45, 263, '2024-04-30 16:46:17'),
(294, 'PORCELANATO LATINO SOCIEDAD ANONIMA CERRADA - PORCELATINO S.A.C.', 20609175789, 'http://www.porcelatino.net/', 'AV. GUARDIA CIVIL NRO 1321 INT. 401 URB. VILLA VICTORIA ', 'SURQUILLO', 'LIMA', 'LIMA', 'Perú', 'Administración', 350, 'Campaña', 45, 264, '2024-05-03 22:36:45'),
(295, 'PROVEEDORES METALICOS DEL ACERO S.A.C.', 20601975280, '', 'CAL. 15 MZA. L LOTE 15 URB. SAN CARLOS ', 'SANTA ANITA', 'LIMA', 'LIMA', 'Perú', '20 planchas de acero', 20, 'Referido de marca', 58, 266, '2024-05-08 13:45:54'),
(296, 'J & C FRIO AIRE MOREY S.A.C. - J & C FAM S.A.C', 20546362613, 'http://www.jycfamsac.com', 'CAL. SALVADOR ALLENDE NRO 325 URB. JOSE BERNARDO ALCEDO ', 'VILLA MARIA DEL TRIUNFO', 'LIMA', 'LIMA', 'Perú', '20 planchas de acero', 20, 'Referido de marca', 57, 268, '2024-06-05 20:13:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(20) NOT NULL,
  `nofactura` bigint(20) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_orden`
--

CREATE TABLE `detalle_orden` (
  `id` int(11) NOT NULL,
  `id_orden` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `impuestos` decimal(10,2) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_orden`
--

INSERT INTO `detalle_orden` (`id`, `id_orden`, `cantidad`, `descripcion`, `impuestos`, `precio_unitario`, `total`) VALUES
(1, 3, 2, 'Producto A', 18.00, 50.00, 100.00),
(2, 3, 1, 'Producto B', 18.00, 100.00, 100.00),
(3, 4, 6, 'Amet maxime sit om', 40.00, 80.00, 89.00),
(4, 5, 28, 'Suscipit Nam tempori', 98.00, 30.00, 98.00),
(5, 6, 28, 'Suscipit Nam tempori', 98.00, 30.00, 98.00),
(6, 7, 2, 'CONDONDES TAMAñO CUY', 15.00, 100.00, 115.00),
(7, 7, 1, 'condones tamaño burro', 5.00, 85.00, 90.00),
(8, 8, 21, 'Porro ut perspiciati', 90.00, 97.00, 71.00),
(9, 9, 94, 'Magna ut veritatis t', 37.00, 18.00, 21.00),
(10, 10, 30, 'camas uci', 18.00, 30000.00, 30000000.00),
(11, 10, 20, 'sillas de ruedas', 18.00, 1000.00, 100000.00),
(12, 11, 56, 'Dolore in officia ad', 34.00, 43.00, 16.00),
(13, 12, 2, 'Veniam eaque nihil ', 58.00, 64.00, 46.00),
(14, 12, 63, 'Minus eiusmod animi', 92.00, 15.00, 78.00),
(15, 12, 100, 'Maiores suscipit id', 82.00, 31.00, 20.00),
(16, 12, 100, 'Qui doloremque ut na', 92.00, 51.00, 49.00),
(17, 12, 17, 'Nihil soluta volupta', 92.00, 63.00, 34.00),
(18, 12, 26, 'Dolor sed cupiditate', 70.00, 3.00, 48.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `asignado` varchar(255) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `mostrar_hora` varchar(250) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `COD_idrol` int(11) DEFAULT NULL,
  `color_evento` varchar(255) DEFAULT NULL,
  `COD_idusuario` int(11) NOT NULL,
  `COD_idsales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `asignado`, `asunto`, `fecha_inicio`, `fecha_fin`, `ubicacion`, `mostrar_hora`, `descripcion`, `COD_idrol`, `color_evento`, `COD_idusuario`, `COD_idsales`) VALUES
(11, 'Admin - vida@gmail.com', 'De prueba', '2023-07-27 10:36:00', '2023-07-27 10:36:00', 'San isidro', 'Ocupada', 'a', 1, '#FF0000', 1, 1),
(14, 'Delia Flores - deli489@gmail.com', 'De prueba', '2023-07-27 10:45:00', '2023-07-27 10:45:00', 'San isidro', 'Fuera de la oficina', 'a', 20, '#FFFF00', 23, 4),
(15, 'Delia Flores - deli489@gmail.com', 'De prueba', '2023-07-27 10:45:00', '2023-07-28 10:45:00', 'San isidro', 'Fuera de la oficina', 'a', 20, '#FFFF00', 23, 5),
(16, 'test12 - asasasa@gmail.com', 'Conferencia de google', '2023-09-15 12:16:00', '2023-09-15 17:16:00', 'San isidro', 'Ocupada', 'Google cloud', 1, '#FF0000', 26, 21),
(17, 'Mia Arecco - marecco@virtualbusiness.pe', 'Reunión ', '2023-09-21 10:13:00', '2023-09-21 10:15:00', 'san isidro', '--Ninguno--', 'xxxx', 1, '#00FF00', 24, 1),
(18, 'Mia Arecco - marecco@virtualbusiness.pe', '', '2023-09-21 10:13:00', '2023-09-21 10:14:00', '', '--Ninguno--', '', 1, '#00FF00', 24, 1),
(19, 'Mia Arecco - marecco@virtualbusiness.pe', '', '2023-09-26 12:15:00', '2023-09-26 12:15:00', '', '--Ninguno--', 'GYGY', 1, '#00FF00', 24, 1),
(20, 'Angela Israel - angela.israel@virtualbusiness.pe', 'CAPACITACION INGRAM 4 -5PM   ', '2023-09-28 17:10:00', '2023-09-28 17:10:00', '', 'Ocupada', 'CAPACITACION - CON EL ACCESO DE MIA ', 20, '#FF0000', 32, 0),
(21, 'Bruno - fafafg@gmail.com', 'De prueba', '2023-10-02 10:04:00', '2023-10-02 10:04:00', 'San isidro', 'Ocupada', 'si', 20, '#FF0000', 33, 64);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos1`
--

CREATE TABLE `eventos1` (
  `id` int(11) NOT NULL,
  `asignado` varchar(255) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `h_inicio` time DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `h_fin` time DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `mostrar_hora` varchar(250) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `COD_idrol` int(11) DEFAULT NULL,
  `color_evento` varchar(255) DEFAULT NULL,
  `COD_idusuario` int(11) NOT NULL,
  `COD_idsales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `nofactura` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `codcliente` int(11) NOT NULL,
  `totalfactura` decimal(10,2) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`nofactura`, `fecha`, `usuario`, `codcliente`, `totalfactura`, `estado`) VALUES
(1, '2024-06-28 15:35:48', 1, 1, 5000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `family&products`
--

CREATE TABLE `family&products` (
  `id` int(11) NOT NULL,
  `Familia` varchar(255) DEFAULT NULL,
  `Marca` varchar(255) DEFAULT NULL,
  `Producto/Servicio` varchar(255) DEFAULT NULL,
  `Descripción` varchar(255) DEFAULT NULL,
  `Proveedor` varchar(255) DEFAULT NULL,
  `Contacto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `family&products`
--

INSERT INTO `family&products` (`id`, `Familia`, `Marca`, `Producto/Servicio`, `Descripción`, `Proveedor`, `Contacto`) VALUES
(1, 'Seguridad', 'A10', 'A10 Thunder SSLi', 'Decodificación SSL/TLS, protección de datos y comunicaciones seguras', 'Deltron', ''),
(2, 'Seguridad', 'AlienVault', 'AlienVault USM', 'Gestión de seguridad unificada, detección de amenazas y cumplimiento', '', ''),
(3, 'Seguridad', 'Allied Telesis', 'Allied Telesis xSeries', 'Switches de red seguros, protección y control de accesos a la red', '', ''),
(4, 'Seguridad', 'Barracuda', 'Barracuda Web Filter', 'Filtro de contenido web, seguridad web y protección contra amenazas', '', ''),
(5, 'Seguridad', 'BeyondTrust', 'BeyondTrust PAM', 'Gestión de accesos privilegiados, control y monitoreo de accesos privilegiados', '', ''),
(6, 'Seguridad', 'CheckPoint', 'CheckPoint Next Generation Firewall', 'Firewall de próxima generación, protección de redes y detección de amenazas', '', ''),
(7, 'Seguridad', 'CyberArk', 'CyberArk PAM', 'Gestión de accesos privilegiados, protección y control de accesos privilegiados', '', ''),
(8, 'Seguridad', 'Cisco', 'Cisco Firepower', 'Firewalls de próxima generación, seguridad de red y protección contra amenazas', '', ''),
(9, 'Seguridad', 'F5', 'F5 Advanced WAF', 'Firewall de aplicaciones web, protección de aplicaciones web y datos', '', ''),
(10, 'Seguridad', 'FireMon', 'FireMon Security Manager', 'Gestión de políticas de seguridad, monitoreo y optimización de políticas de seguridad', '', ''),
(11, 'Seguridad', 'ForeScout', 'ForeScout CounterACT', 'Control de acceso a la red, detección y respuesta a amenazas en tiempo real', '', ''),
(12, 'Seguridad', 'Fortinet', 'Fortinet FortiGate', 'Firewalls de próxima generación, protección de redes y detección de amenazas', '', ''),
(13, 'Seguridad', 'Gigamon', 'Gigamon GigaVUE', 'Visibilidad de tráfico de red, monitoreo y análisis del tráfico de red', '', ''),
(14, 'Seguridad', 'Imperva', 'Imperva Web Application Firewall', 'Firewall de aplicaciones web, protección de aplicaciones web y datos', '', ''),
(15, 'Seguridad', 'Infoblox', 'Infoblox BloxOne Threat Defense', 'Protección contra amenazas en DNS, seguridad y prevención de amenazas en DNS', '', ''),
(16, 'Seguridad', 'Ivanti', 'Ivanti Security Controls', 'Gestión de parches y seguridad de endpoints, protección y cumplimiento de endpoints', '', ''),
(17, 'Seguridad', 'Juniper', 'Juniper SRX', 'Firewalls de próxima generación, seguridad de red y protección contra amenazas', '', ''),
(18, 'Seguridad', 'LogRhythm', 'LogRhythm SIEM', 'Gestión de eventos e información de seguridad, monitoreo y análisis de eventos de seguridad', '', ''),
(19, 'Seguridad', 'Microsoft', 'Microsoft Defender ATP', 'Protección avanzada de endpoints, prevención de amenazas y protección de endpoints', '', ''),
(20, 'Seguridad', 'Netscout', 'Netscout Arbor', 'Protección DDoS, mitigación y prevención de ataques DDoS', '', ''),
(21, 'Seguridad', 'Nozomi Networks', 'Nozomi Networks Guardian', 'Seguridad en infraestructura crítica, protección y monitoreo de infraestructuras críticas', '', ''),
(22, 'Seguridad', 'Palo Alto', 'Palo Alto Networks Next-Generation Firewall', 'Firewall de próxima generación, protección de redes y detección de amenazas', '', ''),
(23, 'Seguridad', 'Proofpoint', 'Proofpoint Email Protection', 'Protección del correo electrónico, prevención de amenazas y filtrado de spam', '', ''),
(24, 'Seguridad', 'Radwin', 'Radwin Wireless Broadband', 'Conectividad inalámbrica segura, comunicaciones encriptadas', '', ''),
(25, 'Seguridad', 'SonicWall', 'SonicWall TZ Series', 'Firewalls de próxima generación, seguridad de red y protección contra amenazas', '', ''),
(26, 'Seguridad', 'Splunk', 'Splunk Enterprise Security', 'SIEM, análisis y correlación de eventos de seguridad', '', ''),
(27, 'Seguridad', 'SkyBox', 'SkyBox Security Suite', 'Gestión de vulnerabilidades y políticas de seguridad, análisis y priorización', '', ''),
(28, 'Seguridad', 'Symantec', 'Symantec Endpoint Security', 'Protección de endpoints, detección y respuesta a amenazas', '', ''),
(29, 'Seguridad', 'Thales', 'Thales SafeNet Data Protection', 'Protección de datos y cifrado, seguridad de la información', '', ''),
(30, 'Seguridad', 'Oracle', 'Oracle Advanced Security', 'Seguridad de bases de datos, cifrado y prevención de intrusiones', '', ''),
(31, 'Seguridad', 'RSA', 'RSA NetWitness Platform', 'Detección y respuesta a amenazas, análisis de seguridad', '', ''),
(32, 'Seguridad', 'Tenable', 'Tenable.io', 'Gestión de vulnerabilidades, monitoreo y priorización de riesgos', '', ''),
(33, 'Seguridad', 'Trend Micro', 'Trend Micro Apex One', 'Protección de endpoints, seguridad y cumplimiento', '', ''),
(34, 'Seguridad', 'Tripwire', 'Tripwire Enterprise', 'Monitoreo de integridad y cumplimiento, seguridad de la información', '', ''),
(35, 'Seguridad', 'Sophos', 'Sophos XG Firewall', 'Firewalls de próxima generación, seguridad de red y protección contra amenazas', '', ''),
(36, 'Seguridad', 'Pulse Secure', 'Pulse Secure Access Suite', 'Acceso remoto seguro, VPN', '', ''),
(37, 'Cloud', 'AWS', 'Amazon Web Services (AWS)', 'Plataforma de servicios en la nube, IaaS, PaaS, y SaaS, almacenamiento, bases de datos, análisis y más', '', ''),
(38, 'Cloud', 'Microsoft', 'Microsoft Azure', 'Plataforma de servicios en la nube, IaaS, PaaS, y SaaS, computación, bases de datos, inteligencia artificial y más', '', ''),
(39, 'Cloud', 'Google', 'Google Cloud Platform (GCP)', 'Plataforma de servicios en la nube, IaaS, PaaS, y SaaS, almacenamiento, análisis, machine learning y más', '', ''),
(40, 'Cloud', 'Oracle', 'Oracle Cloud Infrastructure (OCI)', 'Plataforma de servicios en la nube, IaaS, PaaS, y SaaS, bases de datos, aplicaciones empresariales, analítica y más', '', ''),
(41, 'Infraestructura', 'Allied Telesis', 'Allied Telesis Switches', 'Switches de red, enrutamiento y gestión de tráfico', '', ''),
(42, 'Infraestructura', 'Juniper', 'Juniper Networks Routers', 'Routers empresariales, enrutamiento y optimización de red', '', ''),
(43, 'Infraestructura', 'NetApp', 'NetApp Storage Systems', 'Sistemas de almacenamiento, NAS y SAN, gestión de datos', '', ''),
(44, 'Infraestructura', 'Netscout', 'Netscout nGeniusONE', 'Monitoreo y análisis del rendimiento de la red, optimización y solución de problemas', '', ''),
(45, 'Infraestructura', 'CommScope Ruckus', 'Ruckus Wireless Access Points', 'Puntos de acceso inalámbricos, redes Wi-Fi empresariales y administración de red', '', ''),
(46, 'Infraestructura', 'Ciena', 'Ciena Optical Networking', 'Redes ópticas, transporte y conmutación de alta capacidad', '', ''),
(47, 'Infraestructura', 'Nokia', 'Nokia IP/MPLS Networking', 'Redes IP/MPLS, enrutamiento y conmutación de paquetes', '', ''),
(48, 'Infraestructura', 'Cisco', 'Cisco Networking Solutions', 'Soluciones de redes, switches, routers y puntos de acceso', '', ''),
(49, 'Infraestructura', 'Nutanix', 'Nutanix HCI', 'Infraestructura hiperconvergente, virtualización y gestión de centros de datos', '', ''),
(50, 'Infraestructura', 'Ekahau', 'Ekahau Wi-Fi Design', 'Diseño y planificación de redes Wi-Fi, análisis y optimización', '', ''),
(51, 'Infraestructura', 'Opengear', 'Opengear Out-of-Band Management', 'Gestión fuera de banda, acceso remoto seguro a dispositivos de red', '', ''),
(52, 'Infraestructura', 'Extreme', 'Extreme Networks', 'Soluciones de redes, switches, routers, y puntos de acceso', '', ''),
(53, 'Infraestructura', 'Oracle', 'Oracle Servers and Storage', 'Servidores y almacenamiento, optimización de centros de datos y rendimiento', '', ''),
(54, 'Infraestructura', 'Exagrid', 'Exagrid Disk-based Backup', 'Almacenamiento de respaldo basado en disco, deduplicación y recuperación', '', ''),
(55, 'Infraestructura', 'Fortinet', 'Fortinet Secure SD-WAN', 'Redes de área amplia definidas por software, optimización y seguridad', '', ''),
(56, 'Infraestructura', 'Pure Storage', 'Pure Storage FlashArray', 'Almacenamiento flash, rendimiento y escalabilidad', '', ''),
(57, 'Infraestructura', 'Gigamon', 'Gigamon Visibility and Analytics Fabric', 'Visibilidad de la red y análisis, optimización del tráfico y monitoreo', '', ''),
(58, 'Infraestructura', 'Rubrik', 'Rubrik Cloud Data Management', 'Gestión de datos en la nube, protección y recuperación de datos', '', ''),
(59, 'Infraestructura', 'Hitachi', 'Hitachi Vantara Storage Solutions', 'Soluciones de almacenamiento, virtualización y gestión de datos', '', ''),
(60, 'Infraestructura', 'Silver Peak', 'Silver Peak SD-WAN', 'Redes de área amplia definidas por software, optimización y rendimiento de red', '', ''),
(61, 'Business Intelligence y Process', 'AWS', 'AWS QuickSight', 'Servicio de análisis empresarial y visualización de datos', '', ''),
(62, 'Business Intelligence y Process', 'AppViewX', 'AppViewX Platform', 'Automatización y orquestación de infraestructura de red', '', ''),
(63, 'Business Intelligence y Process', 'Exabeam', 'Exabeam Security Management Platform', 'Plataforma de gestión de seguridad y análisis de datos', '', ''),
(64, 'Business Intelligence y Process', 'Extreme', 'ExtremeAnalytics', 'Análisis de red y visibilidad del tráfico', '', ''),
(65, 'Business Intelligence y Process', 'Erwin', 'Erwin Data Modeler', 'Modelado de datos, diseño de bases de datos y gestión', '', ''),
(66, 'Business Intelligence y Process', 'LogRhythm', 'LogRhythm NextGen SIEM', 'SIEM de próxima generación, análisis de seguridad y respuesta a incidentes', '', ''),
(67, 'Business Intelligence y Process', 'Fortinet', 'Fortinet FortiAnalyzer', 'Análisis de seguridad y eventos, correlación y alertas', '', ''),
(68, 'Business Intelligence y Process', 'Ivanti', 'Ivanti IT Asset Management', 'Gestión de activos de TI y optimización de procesos', '', ''),
(69, 'Business Intelligence y Process', 'Splunk', 'Splunk Enterprise', 'Análisis de datos y plataforma de inteligencia operativa', '', ''),
(70, 'Business Intelligence y Process', 'RSA', 'RSA Archer GRC', 'Gestión de riesgos, cumplimiento y gobernanza de seguridad', '', ''),
(71, 'Business Intelligence y Process', 'Oracle', 'Oracle Business Intelligence', 'Análisis de datos empresariales y visualización', '', ''),
(72, 'Business Intelligence y Process', 'Microsoft', 'Microsoft Power BI', 'Servicio de análisis empresarial y visualización de datos', '', ''),
(73, 'Business Intelligence y Process', 'Microsoft', 'Microsoft', '', 'Deltron', 'Amparo/ 977869484'),
(74, 'Business Intelligence y Process', 'Microsoft', 'Microsoft', '', 'Multimport', 'Miguel Angel Huaman/ 981028909'),
(75, 'Business Continuity', 'Arcserve', 'Arcserve Unified Data Protection', 'Protección de datos unificada, respaldo y recuperación', '', ''),
(76, 'Business Continuity', 'Cisco', 'Cisco Disaster Recovery', 'Soluciones de recuperación ante desastres y continuidad del negocio', '', ''),
(77, 'Business Continuity', 'Commvault', 'Commvault Data Protection', 'Protección de datos, respaldo y recuperación, gestión de la información', '', ''),
(78, 'Business Continuity', 'Extreme', 'Extreme Fabric Connect', 'Conectividad de red resiliente y recuperación rápida', '', ''),
(79, 'Business Continuity', 'F5', 'F5 BIG-IP Local Traffic Manager', 'Gestión de tráfico local y balanceo de carga para alta disponibilidad', '', ''),
(80, 'Business Continuity', 'NetApp', 'NetApp SnapMirror', 'Replicación de datos y recuperación ante desastres', '', ''),
(81, 'Business Continuity', 'Oracle', 'Oracle Data Guard', 'Protección de datos y recuperación ante desastres para bases de datos Oracle', '', ''),
(82, 'Business Continuity', 'Pure Storage', 'Pure Storage ActiveCluster', 'Almacenamiento activo-activo y recuperación ante desastres', '', ''),
(83, 'Business Continuity', 'Radware', 'Radware Alteon', 'Balanceo de carga y continuidad del negocio', '', ''),
(84, 'Business Continuity', 'Rubrik', 'Rubrik Cloud Data Management', 'Gestión de datos en la nube, protección y recuperación de datos', '', ''),
(85, 'Modern App', 'AWS', 'AWS Lambda', 'Servicio de cómputo sin servidor para aplicaciones modernas', '', ''),
(86, 'Modern App', 'AppViewX', 'AppViewX Platform', 'Automatización y orquestación de infraestructura de red', '', ''),
(87, 'Modern App', 'Cisco', 'Cisco AppDynamics', 'Monitoreo de rendimiento de aplicaciones y análisis', '', ''),
(88, 'Modern App', 'F5', 'F5 BIG-IP Application Security Manager', 'Seguridad y protección de aplicaciones web', '', ''),
(89, 'Modern App', 'Ivanti', 'Ivanti Application Control', 'Control y seguridad de aplicaciones empresariales', '', ''),
(90, 'Modern App', 'Juniper', 'Juniper AppFormix', 'Monitoreo y optimización de aplicaciones en la nube', '', ''),
(91, 'Modern App', 'Microsoft', 'Microsoft Azure Functions', 'Servicio de cómputo sin servidor para aplicaciones modernas', '', ''),
(92, 'Modern App', 'Fox IT', 'Fox IT Managed Services', 'Servicios administrados de seguridad y soporte para aplicaciones modernas', '', ''),
(93, 'Modern App', 'Netscout', 'Netscout nGeniusPULSE', 'Monitoreo del rendimiento y disponibilidad de aplicaciones', '', ''),
(94, 'Modern App', 'Splunk', 'Splunk App for Infrastructure', 'Monitoreo y análisis de la infraestructura de aplicaciones', '', ''),
(95, 'Modern App', 'Radware', 'Radware AppWall', 'Protección y seguridad de aplicaciones web', '', ''),
(96, 'Modern App', 'RSA', 'RSA Archer GRC', 'Gestión de riesgos, cumplimiento y gobernanza de seguridad', '', ''),
(97, 'Colaboración', 'AudioCodes', 'AudioCodes One Voice', 'Soluciones de voz y colaboración para empresas', '', ''),
(98, 'Colaboración', 'Microsoft', 'Microsoft Teams', 'Plataforma de colaboración y comunicación empresarial', '', ''),
(99, 'Colaboración', 'Avaya', 'Avaya Spaces', 'Plataforma de colaboración y comunicación en equipo', '', ''),
(100, 'Colaboración', 'Cisco', 'Cisco Webex', 'Plataforma de videoconferencia y colaboración en línea', '', ''),
(101, 'Colaboración', 'Ribbon', 'Ribbon Unified Communications', 'Soluciones de comunicación unificada y colaboración', '', ''),
(102, 'Colaboración', 'Zoom', 'Zoom Video Communications', 'Plataforma de videoconferencia y colaboración en línea', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `COD_idCollections` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `files`
--

INSERT INTO `files` (`id`, `url`, `type`, `COD_idCollections`) VALUES
(12, 'files/20230615211458-recibo (2).pdf', 'pdf', 72),
(13, 'files/20230615192104-recibo (1).pdf', 'pdf', 73),
(15, 'files/20230618230122-REPORTE PDF.pdf', 'pdf', 75),
(16, 'files/20230621114541-CertificadoDigital.pdf', 'pdf', 76),
(17, 'files/20230703121838-Ejemplo.pdf', 'pdf', 77),
(20, 'files/20230703212015-recibo (2).pdf', 'pdf', 80),
(21, 'none', 'none', 81),
(22, 'files/20230709225347-Ejemplo.pdf', 'pdf', 82),
(24, 'files/20240222152004-Cuentas VB.pdf', 'pdf', 85),
(25, 'none', 'none', 86),
(26, 'none', 'none', 88),
(27, 'none', 'none', 89);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files_sales`
--

CREATE TABLE `files_sales` (
  `id` int(11) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `COD_idSales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file_sales`
--

CREATE TABLE `file_sales` (
  `id` int(11) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `COD_idSales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `file_sales`
--

INSERT INTO `file_sales` (`id`, `url`, `type`, `COD_idSales`) VALUES
(12, 'none', 'none', 0),
(13, 'none', 'none', 4),
(14, 'none', 'none', 5),
(15, 'none', 'none', 0),
(16, 'none', 'none', 6),
(17, 'none', 'none', 7),
(18, 'none', 'none', 8),
(19, 'none', 'none', 9),
(20, 'none', 'none', 10),
(21, 'none', 'none', 11),
(22, 'none', 'none', 12),
(23, 'none', 'none', 13),
(24, 'none', 'none', 14),
(25, 'none', 'none', 15),
(26, 'none', 'none', 0),
(27, 'none', 'none', 16),
(28, 'files_sales/20230807112459-Propuesta Técnico EconómicaV2.pdf', 'pdf', 17),
(29, 'files_sales/20230807174150-Propuesta Técnico EconómicaV2.pdf', 'pdf', 18),
(30, 'none', 'none', 0),
(31, 'files_sales/20230811121955-Mollehuaca.pdf', 'pdf', 19),
(32, 'none', 'none', 20),
(33, 'files_sales/20230814122931-mollehuaca.pdf', 'pdf', 21),
(34, 'files_sales/20230814122933-mollehuaca.pdf', 'pdf', 22),
(35, 'files_sales/20230814123146-mollehuaca plan 3.pdf', 'pdf', 23),
(36, 'files_sales/20230814154112-Consult.pdf', 'pdf', 24),
(37, 'none', 'none', 25),
(38, 'files_sales/20230815120407-Samanco coming.pdf', 'pdf', 26),
(39, 'files_sales/20230821175929-licencia business estandar.pdf', 'pdf', 0),
(40, 'files_sales/20230901095414-01082023.pdf', 'pdf', 0),
(41, 'none', 'none', 0),
(42, 'files_sales/20230901101554-Propuesta Técnico Económica.pdf', 'pdf', 27),
(43, 'files_sales/20230904144921-Mollehuaca 04092023.pdf', 'pdf', 0),
(44, 'files_sales/20230904145722-04092023.pdf', 'pdf', 28),
(45, 'none', 'none', 29),
(46, 'none', 'none', 0),
(47, 'none', 'none', 0),
(48, 'files_sales/20230913131117-12092023.pdf', 'pdf', 0),
(49, 'none', 'none', 30),
(50, 'files_sales/20230915104637-TARIFARIO LIMA 23.pdf', 'pdf', 31),
(51, 'files_sales/20230915122143-cobit.pdf', 'pdf', 32),
(52, 'none', 'none', 0),
(53, 'files_sales/20230921102527-1865987-1 (1).pdf', 'pdf', 33),
(54, 'files_sales/20230918102659-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 0),
(55, 'files_sales/20230918103101-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 0),
(56, 'files_sales/20230918104106-Propuesta Técnico Económica 2806231032v1.pdf', 'pdf', 34),
(57, 'none', 'none', 35),
(58, 'none', 'none', 0),
(59, 'none', 'none', 0),
(60, 'none', 'none', 0),
(61, 'none', 'none', 0),
(62, 'none', 'none', 0),
(63, 'none', 'none', 36),
(64, 'none', 'none', 37),
(65, 'none', 'none', 0),
(66, 'none', 'none', 0),
(67, 'none', 'none', 0),
(68, 'none', 'none', 38),
(69, 'files_sales/20230920142948-cobit.pdf', 'pdf', 39),
(70, 'none', 'none', 40),
(71, 'none', 'none', 0),
(72, 'none', 'none', 41),
(73, 'none', 'none', 42),
(74, 'none', 'none', 43),
(75, 'none', 'none', 0),
(76, 'files_sales/20230921152009-21092023.pdf', 'pdf', 0),
(77, 'files_sales/20230921152229-Propuesta de Proyecto de Migración de Google Workspace a Microsoft Office 365 v2.pdf', 'pdf', 0),
(78, 'files_sales/20230921161313-21092023.pdf', 'pdf', 0),
(79, 'files_sales/20230922090433-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 0),
(80, 'none', 'none', 0),
(81, 'files_sales/20230922091157-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 0),
(82, 'files_sales/20230922103745-cobit.pdf', 'pdf', 44),
(83, 'files_sales/20230922104231-cobit.pdf', 'pdf', 45),
(84, 'files_sales/20230922114234-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 0),
(85, 'none', 'none', 0),
(86, 'files_sales/20230922115518-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 46),
(87, 'files_sales/20230922115813-Propuesta Servicios Azure para INCA ONE v2.pdf', 'pdf', 47),
(88, 'none', 'none', 48),
(89, 'none', 'none', 49),
(90, 'none', 'none', 0),
(91, 'none', 'none', 0),
(92, 'none', 'none', 50),
(93, 'none', 'none', 0),
(94, 'none', 'none', 0),
(95, 'none', 'none', 0),
(96, 'none', 'none', 51),
(97, 'none', 'none', 0),
(98, 'none', 'none', 0),
(99, 'none', 'none', 0),
(100, 'none', 'none', 0),
(101, 'none', 'none', 0),
(102, 'none', 'none', 0),
(103, 'none', 'none', 52),
(104, 'none', 'none', 53),
(105, 'none', 'none', 54),
(106, 'files_sales/20230925092547-01082023.pdf', 'pdf', 55),
(107, 'none', 'none', 56),
(108, 'files_sales/20230926115408-Propuesta Técnico Económica v2.pdf', 'pdf', 57),
(109, 'files_sales/20230926115926-Propuesta Técnico Económica v2.pdf', 'pdf', 58),
(110, 'none', 'none', 59),
(111, 'files_sales/20230926162114-26092023.pdf', 'pdf', 60),
(112, 'none', 'none', 61),
(113, 'none', 'none', 62),
(114, 'none', 'none', 63),
(115, 'none', 'none', 0),
(116, 'none', 'none', 64),
(117, 'none', 'none', 65),
(118, 'files_sales/20231005160045-041023.pdf', 'pdf', 66),
(119, 'files_sales/20231013154056-Propuesta Técnico Económica VM UCLACHv2.pdf', 'pdf', 67),
(120, 'files_sales/20231018151856-ROL_PARCIAL_2023-2 Ercilia_21-09-2023_ (2).pdf', 'pdf', 68),
(121, 'files_sales/20231018164438-muni la punta.pdf', 'pdf', 69),
(122, 'none', 'none', 70),
(123, 'files_sales/20231020130732-Propuesta Técnico Económica- 1222201023.pdf', 'pdf', 0),
(124, 'files_sales/20231020131302-Propuesta Técnico Económica- 1222201023.pdf', 'pdf', 71),
(125, 'files_sales/20231027113753-sankaku.pdf', 'pdf', 72),
(126, 'files_sales/20231027114020-mollehuaca.pdf', 'pdf', 73),
(127, 'files_sales/20231027114755-Oferta Técnico Económica - 20603214499 - ASTAH S.A.C v3.pdf', 'pdf', 0),
(128, 'none', 'none', 74),
(129, 'none', 'none', 75),
(130, 'files_sales/20240222151658-brochure.pdf', 'pdf', 76),
(131, 'files_sales/20240222153400-brochure.pdf', 'pdf', 77),
(132, 'files_sales/20240301173720-CTO REGULAR 2024 VIRTUAL.pdf', 'pdf', 78),
(133, 'files_sales/20240306183458-Contrato Wow Ica.pdf', 'pdf', 79),
(134, 'files_sales/20240306143348-OTE_CRUCERO STAR.pdf', 'pdf', 80),
(135, 'none', 'none', 81),
(136, 'none', 'none', 82),
(137, 'none', 'none', 83),
(138, 'none', 'none', 84),
(139, 'none', 'none', 0),
(140, 'none', 'none', 85),
(141, 'none', 'none', 86),
(142, 'none', 'none', 87),
(143, 'files_sales/20240304230937-OTE - SOLUCIONES E INNOVACIONES E&T.pdf', 'pdf', 88),
(144, 'files_sales/20240304232116-OTE - SERVICIOS  MEDICOS SAGRADO CORAZON DE JESUS EIRL.pdf', 'pdf', 89),
(145, 'files_sales/20240304232552-OTE - POLICLINICO MIRMAR SAC.pdf', 'pdf', 90),
(146, 'files_sales/20240304232930-OTE - M. & P. REPRESENTACIONES GENERALES SAC.pdf', 'pdf', 91),
(147, 'none', 'none', 92),
(148, 'files_sales/20240305101844-OTE_ENTEL (Cotización 1).pdf', 'pdf', 93),
(149, 'files_sales/20240305102248-OTE_ENTEL (Cotización 2).pdf', 'pdf', 94),
(150, 'files_sales/20240306105708-COBERTURA APUESTA TOTAL 2024.pdf', 'pdf', 95),
(151, 'none', 'none', 96),
(152, 'none', 'none', 97),
(153, 'none', 'none', 98),
(154, 'none', 'none', 99),
(155, 'none', 'none', 100),
(156, 'none', 'none', 101),
(157, 'none', 'none', 102),
(158, 'none', 'none', 103),
(159, 'none', 'none', 104),
(160, 'none', 'none', 105),
(161, 'none', 'none', 106),
(162, 'none', 'none', 107),
(163, 'files_sales/20240312101046-Oferta Técnico comercial- Cotización de Internet dedicado- SOA PROFESSIONAL_2024.pdf', 'pdf', 108),
(164, 'files_sales/20240312101239-Oferta Técnico comercial- Cotización de Internet dedicado-Cementos Pacasmayo_WOW PERU_V2.pdf', 'pdf', 109),
(165, 'files_sales/20240312101440-OTE_HYDROGAS_INTERNET_DEDICADO_10Mbps_EMPRESAS_WOW_2024.pdf', 'pdf', 110),
(166, 'none', 'none', 111),
(167, 'none', 'none', 112),
(168, 'none', 'none', 113),
(169, 'none', 'none', 114),
(170, 'none', 'none', 115),
(171, 'none', 'none', 116),
(172, 'none', 'none', 117),
(173, 'none', 'none', 118),
(174, 'none', 'none', 119),
(175, 'none', 'none', 120),
(176, 'none', 'none', 121),
(177, 'files_sales/20240408121654-WOW PERU 2024_PROPUESTA TECNICO ECONÓMICA MINERA SHOUXIN.pdf', 'pdf', 122),
(178, 'none', 'none', 123),
(179, 'none', 'none', 124),
(180, 'none', 'none', 125),
(181, 'none', 'none', 126),
(182, 'none', 'none', 127),
(183, 'none', 'none', 128),
(184, 'files_sales/20240313172154-WOW_INTERNET.pdf', 'pdf', 129),
(185, 'none', 'none', 130),
(186, 'none', 'none', 131),
(187, 'none', 'none', 132),
(188, 'none', 'none', 133),
(189, 'none', 'none', 134),
(190, 'none', 'none', 135),
(191, 'none', 'none', 136),
(192, 'none', 'none', 137),
(193, 'none', 'none', 138),
(194, 'none', 'none', 139),
(195, 'none', 'none', 140),
(196, 'none', 'none', 141),
(197, 'none', 'none', 142),
(198, 'none', 'none', 143),
(199, 'none', 'none', 144),
(200, 'none', 'none', 145),
(201, 'none', 'none', 146),
(202, 'none', 'none', 147),
(203, 'files_sales/20240319155819-CONTRATO DE.pdf', 'pdf', 148),
(204, 'none', 'none', 149),
(205, 'none', 'none', 150),
(206, 'none', 'none', 151),
(207, 'none', 'none', 152),
(208, 'none', 'none', 153),
(209, 'none', 'none', 154),
(210, 'none', 'none', 155),
(211, 'none', 'none', 156),
(212, 'none', 'none', 157),
(213, 'none', 'none', 158),
(214, 'none', 'none', 159),
(215, 'none', 'none', 160),
(216, 'none', 'none', 161),
(217, 'none', 'none', 162),
(218, 'none', 'none', 163),
(219, 'files_sales/20240321163606-ORDEN DE SERVICIO 226-2024.pdf', 'pdf', 164),
(220, 'none', 'none', 165),
(221, 'files_sales/20240322120745-DIA WOW - ANEXO 1 .pdf', 'pdf', 166),
(222, 'none', 'none', 167),
(223, 'none', 'none', 168),
(224, 'none', 'none', 169),
(225, 'none', 'none', 170),
(226, 'none', 'none', 171),
(227, 'none', 'none', 172),
(228, 'none', 'none', 173),
(229, 'files_sales/20240326144207-CONTRATO Y dni FIRMADO.pdf', 'pdf', 174),
(230, 'files_sales/20240326145852-OS N_390-2024.pdf', 'pdf', 175),
(231, 'files_sales/20240326145952-OS N_391-2024.pdf', 'pdf', 176),
(232, 'none', 'none', 177),
(233, 'none', 'none', 178),
(234, 'none', 'none', 179),
(235, 'none', 'none', 180),
(236, 'none', 'none', 181),
(237, 'none', 'none', 182),
(238, 'none', 'none', 183),
(239, 'none', 'none', 184),
(240, 'files_sales/20240408121813-Oferta Técnico comercial WOW TEL SAC- Cotización de Internet dedicado-Gobierno Regional de Tacna Internet Dedicado.pdf', 'pdf', 185),
(241, 'files_sales/20240408110322-Cuenta VB- Interbank (1).pdf', 'pdf', 0),
(242, 'files_sales/20240408110454-Cuenta VB- Interbank (1).pdf', 'pdf', 186),
(243, 'files_sales/20240408121414-OTE-GTD-V1-12-24-36_PASCO_TACNA_CHIMBOTE.pdf', 'pdf', 187),
(244, 'files_sales/20240408123355-Oferta Técnico comercial- Cotización de Internet dedicado_CAJA MAYNAS.pdf', 'pdf', 188),
(245, 'none', 'none', 189),
(246, 'none', 'none', 190),
(247, 'none', 'none', 191),
(248, 'none', 'none', 192),
(249, 'none', 'none', 193),
(250, 'files_sales/20240411192151-Cotización B2B_MASIVO_SUNAFIL_SEDE_TALARA.pdf', 'pdf', 194),
(251, 'none', 'none', 195),
(252, 'none', 'none', 196),
(253, 'none', 'none', 197),
(254, 'none', 'none', 198),
(255, 'none', 'none', 199),
(256, 'none', 'none', 200),
(257, 'none', 'none', 201),
(258, 'none', 'none', 202),
(259, 'none', 'none', 203),
(260, 'none', 'none', 204),
(261, 'none', 'none', 205),
(262, 'none', 'none', 206),
(263, 'none', 'none', 207),
(264, 'none', 'none', 208),
(265, 'none', 'none', 209),
(266, 'none', 'none', 210),
(267, 'none', 'none', 211),
(268, 'none', 'none', 212),
(269, 'none', 'none', 213),
(270, 'none', 'none', 214),
(271, 'none', 'none', 215),
(272, 'files_sales/20240425084642-1.00 FORMATOS PARA COTIZACION - HOSPITAL 2024.pdf', 'pdf', 216),
(273, 'none', 'none', 217),
(274, 'none', 'none', 218),
(275, 'files_sales/20240429191624-OTE-GTD-V7-12_Abancay_Chachapoyas.pdf', 'pdf', 219),
(276, 'none', 'none', 220),
(277, 'none', 'none', 221),
(278, 'none', 'none', 222),
(279, 'none', 'none', 223),
(280, 'none', 'none', 224);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maker`
--

CREATE TABLE `maker` (
  `idFabricante` int(11) NOT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `fecha_De_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maker`
--

INSERT INTO `maker` (`idFabricante`, `marca`, `fecha_De_registro`) VALUES
(9, 'Symantec', '2023-06-07 16:51:42'),
(11, 'Otro', '2023-07-08 23:12:53'),
(13, 'COMEDIC', '2024-06-05 21:48:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newcustomers`
--

CREATE TABLE `newcustomers` (
  `idnewCliente` int(11) NOT NULL,
  `Company` varchar(255) NOT NULL,
  `RUC` int(11) DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `Tipo_Contacto` varchar(50) DEFAULT NULL,
  `Contact_Name` varchar(255) DEFAULT NULL,
  `Apellido_Paterno` varchar(255) DEFAULT NULL,
  `Apellido_Materno` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` int(11) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Distrito` varchar(50) DEFAULT NULL,
  `Provincia` varchar(50) DEFAULT NULL,
  `Departamento` varchar(50) DEFAULT NULL,
  `Pais` varchar(50) DEFAULT NULL,
  `Cargo` varchar(100) DEFAULT NULL,
  `Cantidad_Empleados` int(11) DEFAULT NULL,
  `OrigenCliente` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_trabajo`
--

CREATE TABLE `orden_trabajo` (
  `id` int(11) NOT NULL,
  `numero_orden` varchar(10) DEFAULT NULL,
  `fecha_orden` date NOT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `direccion_cliente` varchar(255) DEFAULT NULL,
  `telefono_cliente` varchar(50) DEFAULT NULL,
  `web_cliente` varchar(100) DEFAULT NULL,
  `datos_facturacion` text DEFAULT NULL,
  `datos_empresa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id`, `numero_orden`, `fecha_orden`, `nombre_cliente`, `direccion_cliente`, `telefono_cliente`, `web_cliente`, `datos_facturacion`, `datos_empresa`) VALUES
(3, '0003', '2024-07-12', 'Juan Pérez', 'Calle Falsa 123', '555-1234', 'www.juanperez.com', 'Facturación a nombre de Juan Pérez', 'Empresa XYZ'),
(4, '0004', '1980-05-04', 'Corporis consectetur', 'Non culpa ut quibus', 'Impedit enim sed ve', 'Et iste magni cupidi', 'Facturación a nombre de Corporis consectetur', 'Empresa XYZ'),
(5, '0005', '1987-06-04', 'Eiusmod libero neque', 'Eveniet sint molest', 'Qui in voluptas face', 'Omnis sed iure ea co', 'Facturación a nombre de Eiusmod libero neque', 'Empresa XYZ'),
(6, '0006', '1987-06-04', 'Eiusmod libero neque', 'Eveniet sint molest', 'Qui in voluptas face', 'Omnis sed iure ea co', 'Facturación a nombre de Eiusmod libero neque', 'Empresa XYZ'),
(7, '0007', '2024-08-01', 'Roberto Sánchez seitan', 'Francisco Bejarano 145 zona B SJM', '9301902902', 'www.joelmitsuoserbia.com', 'Facturación a nombre de Roberto Sánchez seitan', 'Empresa XYZ'),
(8, '0008', '2015-02-28', 'Et non illum ipsam ', 'Nisi dolores mollit ', 'Pariatur Et culpa ', 'Voluptas architecto ', 'Facturación a nombre de Et non illum ipsam ', 'Empresa XYZ'),
(9, '0009', '2024-01-24', 'Quod dolor alias ips', 'Dolorum deserunt acc', 'Sunt consequatur do', 'Quidem quas quas qua', 'Facturación a nombre de Quod dolor alias ips', 'Empresa XYZ'),
(10, '0010', '2024-07-27', 'Roberto Sánchez seitan', 'Francisco Bejarano 145 zona B SJM', '9301902902', 'www.joelmitsuoserbia.com', 'Facturación a nombre de Roberto Sánchez seitan', 'Empresa XYZ'),
(11, '0011', '1999-11-26', 'Provident aliquip c', 'Blanditiis voluptas ', 'Rerum illum cillum ', 'Voluptatum ut nostru', 'Facturación a nombre de Provident aliquip c', 'Empresa XYZ'),
(12, '0012', '1970-07-28', 'Id sit quas nemo la', 'Id est nulla iusto ', 'Quia mollit sunt no', 'Est nemo maxime quam', 'Facturación a nombre de Id sit quas nemo la', 'Empresa XYZ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `permiso` varchar(100) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `permiso`, `fecha_creacion`) VALUES
(1, 'Account', '2023-07-08'),
(2, 'Collections', '2023-07-08'),
(3, 'Contacts', '2023-07-08'),
(4, 'ALL Products', '2023-07-08'),
(5, 'ALL Account', '2023-07-08'),
(6, 'Activities', '2023-07-08'),
(7, 'Sales', '2023-07-08'),
(8, 'Family and Products', '2023-07-08'),
(9, 'Usuarios', '2023-07-08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `idProduct` int(11) NOT NULL,
  `Producto` char(36) NOT NULL,
  `FechaModificacion` datetime DEFAULT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Fabricante` varchar(255) DEFAULT NULL,
  `IdProducto` varchar(255) DEFAULT NULL,
  `PrecioListado` decimal(10,2) DEFAULT NULL,
  `Segmento` varchar(255) DEFAULT NULL,
  `ListaPreciosPredeterminada` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`idProduct`, `Producto`, `FechaModificacion`, `Nombre`, `Fabricante`, `IdProducto`, `PrecioListado`, `Segmento`, `ListaPreciosPredeterminada`) VALUES
(1, '9a52656f-f216-ec11-b6e7-000d3a88538e', '2022-01-12 15:27:00', 'Project Server 2019', 'Microsoft', 'DG7GMGF0F4MH', 7613.88, 'Comercial', 'Microsoft'),
(2, 'd5f6bad5-3bbc-ec11-983f-000d3a8888a9', '2022-04-14 21:43:00', 'Cisco Umbrella Insights (1000-2499)', 'Otro', 'CISCOSINID', 42960.00, 'Comercial', 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `proveedor` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `descripcion`, `proveedor`, `precio`, `existencia`, `usuario_id`) VALUES
(1, 'Laptop lenovo', 1, 1560.00, 49, 2),
(2, 'Televisor', 1, 2500.00, 79, 1),
(6, 'Impresora', 1, 800.00, 0, 1),
(7, 'Gaseosa', 3, 1500.00, 5, 1),
(0, 'ASDASDSA', 0, 0.00, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `preciounidad` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `descripcion`, `preciounidad`) VALUES
(1, 'MED001', 'Cama hospitalaria estándar', 2500.00),
(2, 'MED002', 'Silla de ruedas plegable', 1200.00),
(3, 'MED003', 'Mesa de examen ajustable', 1800.00),
(4, 'MED004', 'Monitor de signos vitales', 8500.00),
(5, 'MED005', 'Lámpara quirúrgica', 4500.00),
(6, 'MED006', 'Carro de emergencia', 3000.00),
(7, 'MED007', 'Colchón antiescaras', 1500.00),
(8, 'MED008', 'Desfibrilador automático', 12000.00),
(9, 'MED009', 'Mesa de mayo', 800.00),
(10, 'MED010', 'Paro cardíaco', 8500.00),
(11, 'MED011', 'Bomba de infusión', 6500.00),
(12, 'MED012', 'Ventilador mecánico', 15000.00),
(13, 'MED013', 'Escalera de dos peldaños', 300.00),
(14, 'MED014', 'Lámpara de pie LED', 700.00),
(15, 'MED015', 'Equipo de succión', 2000.00),
(16, 'MED016', 'Báscula digital', 1200.00),
(17, 'MED017', 'Mobiliario para instrumental', 2300.00),
(18, 'MED018', 'Camilla de transporte', 3500.00),
(19, 'MED019', 'Estetoscopio electrónico', 1500.00),
(20, 'MED020', 'Oxímetro de pulso', 800.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `contacto` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `usuario_id`) VALUES
(1, 'Open Services', '965432143', 9645132, 'Lima', 2),
(3, 'Lineo', '25804', 9865412, 'Lima', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(20, 'Vendedor'),
(21, 'soporte'),
(23, 'Desarrollador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolxpermisos`
--

CREATE TABLE `rolxpermisos` (
  `id` int(11) NOT NULL,
  `idRol` int(11) DEFAULT NULL,
  `idPermisoArreglo` varchar(250) DEFAULT NULL,
  `crud` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rolxpermisos`
--

INSERT INTO `rolxpermisos` (`id`, `idRol`, `idPermisoArreglo`, `crud`) VALUES
(1, 1, '[1,2,3,4,5,6,7,8,9]', '[[1,2,3,4],[1,2,3,4],[1,2,3,4],[1,2,3,4],[1,2,3,4],[1,2,3,4],[1,2,3,4],[1,2,3,4],[1,2,3,4]]'),
(17, 20, '[1,3,6,7]', '[[1,2,3],[],[1,2,3],[],[],[1,2,3,4],[1,2,3],[],[]]'),
(18, 21, '[1,2,3]', '[[1,2,3,4],[1,2,3,4],[1,2,3,4],[],[],[],[],[],[]]'),
(20, 23, '[1,2,3,4,5,6,7]', '[[1,2,3,4],[2],[1,3,4],[2],[2],[1,2,3,4],[2],[],[]]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale`
--

CREATE TABLE `sale` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Company` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `Priority` varchar(255) DEFAULT NULL,
  `MRC` decimal(10,2) DEFAULT NULL,
  `Account Owner` varchar(255) DEFAULT NULL,
  `Detalle` text DEFAULT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `Expected Close` date DEFAULT NULL,
  `Added` datetime DEFAULT NULL,
  `Contacto Cliente` varchar(255) DEFAULT NULL,
  `FCV` date DEFAULT NULL,
  `One Shot` varchar(255) DEFAULT NULL,
  `Producto` varchar(255) DEFAULT NULL,
  `Propuesta` varchar(255) DEFAULT NULL,
  `Typesale` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sale`
--

INSERT INTO `sale` (`id`, `Name`, `Company`, `Status`, `Priority`, `MRC`, `Account Owner`, `Detalle`, `Phone`, `Expected Close`, `Added`, `Contacto Cliente`, `FCV`, `One Shot`, `Producto`, `Propuesta`, `Typesale`, `idUsuario`) VALUES
(28, 'Chamo', 'DSDS', 'Qualified', 'High', 121.00, 'User', 'sdsd', '1212121', '0000-00-00', '2023-07-11 17:41:50', 'asass', '0000-00-00', '10250 PEN', 'ddd', 'none', 0, 0),
(29, 'dsffdf', 'asdsd', 'Lead', 'High', 23131.00, 'sadfg', 'AS', '121', '2023-07-12', '2023-07-11 17:42:16', 'XS', '0000-00-00', '123 USD', 'ASA', 'reading-quiz-1-virtual-202211-avanzado-12-16-00-17-30_compress.pdf', 0, 0),
(31, 'DATA', 'ASASASA', 'Lead', 'High', 212121.00, 'ASASASA', 'SASASASA', '121212121', '2023-07-18', '2023-07-14 11:12:11', 'ASASASASASA', '2012-12-01', '12121 PEN', 'ASASASA', 'none', 0, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `Oportunidad` varchar(255) NOT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Priorit` varchar(50) DEFAULT NULL,
  `MRC` decimal(10,2) DEFAULT NULL,
  `Detalle` text DEFAULT NULL,
  `Added` datetime NOT NULL DEFAULT current_timestamp(),
  `Expected Close` date DEFAULT NULL,
  `FCV` float DEFAULT 0,
  `One Shot` decimal(10,2) DEFAULT NULL,
  `tipo` varchar(30) NOT NULL,
  `Producto` varchar(255) DEFAULT NULL,
  `Propuesta` varchar(255) DEFAULT NULL,
  `Typesale` varchar(50) DEFAULT NULL,
  `current_step` int(11) NOT NULL,
  `progress_width` int(11) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `idContact` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`id`, `Oportunidad`, `Status`, `Priorit`, `MRC`, `Detalle`, `Added`, `Expected Close`, `FCV`, `One Shot`, `tipo`, `Producto`, `Propuesta`, `Typesale`, `current_step`, `progress_width`, `idUsuario`, `idCustomer`, `idContact`) VALUES
(1, 'Office', 'Ganada', 'High', 12.00, 'Que tal', '2023-07-27 15:59:14', '2023-07-19', 12, 2500.00, 'PEN', 'Gama alta', 'arw3-midterm-exam-virtual-202109-avanzado-12-16-00-17-30_compress.pdf', '0', 4, 100, 1, 56, 20),
(2, 'ddddd', 'Negotiation', 'High', 122121.00, 'sss', '2023-07-27 15:59:14', '2023-08-04', 3213220, 12321.00, 'PEN', 'dasd', 'none', '0', 3, 75, 1, 58, 11),
(3, 'BORRAR', 'Lost', 'Medium', 120.00, 'esto es una prueba', '2023-07-27 15:59:14', '2023-07-19', 123, 205.00, 'PEN', 'nose', 'none', '0', 5, 125, 1, 56, 20),
(4, 'Licencias', 'Lead', 'High', 123.00, 'Gama alta', '2023-07-27 15:59:14', '2023-07-19', 1500, 2500.00, 'PEN', 'Licencias', 'none', '0', 0, 0, 23, 61, 23),
(5, 'PC', 'Qualified', 'Medium', 123.00, 'detalle', '2023-07-27 15:59:14', '2023-07-28', 5800, 5950.00, 'PEN', 'pc gamer', 'none', '0', 1, 25, 23, 62, 24),
(6, 'Tinta', 'Proposal', 'High', 1500.00, 'Canon', '2023-07-27 15:59:14', '2023-07-28', 50, 50.00, 'PEN', 'Tinta canon', 'none', '0', 2, 50, 23, 64, 26),
(7, 'Usb Kingston', 'Negotiation', 'Low', 15.00, 'USB de 128gb', '2023-07-27 15:59:14', '2023-07-28', 50, 80.00, 'PEN', 'Usb kingston', 'none', '0', 3, 75, 23, 65, 27),
(8, 'Impresoras', 'Ganada', 'High', 1500.00, '5 laptops edson', '2023-07-27 15:59:14', '2023-07-27', 150, 2500.00, 'PEN', 'Laptop EDSON X5', 'none', '0', 4, 100, 23, 66, 28),
(9, '500 celulares Samsung', 'Lost', 'Medium', 0.00, 'Gama alta S24', '2023-07-27 15:59:14', '2023-08-05', 5800, 5654.00, 'PEN', 'Samsung', 'none', '0', 5, 125, 23, 67, 29),
(10, 'Azure', 'Negotiation', 'Low', 1000.00, '2 VM para app de control de horario', '2023-07-27 15:59:14', '2023-07-03', 36000, 500.00, 'PEN', 'Azure a medida', 'none', '0', 3, 75, 23, 70, 33),
(11, 'CRM', 'Lost', 'High', 1025.00, 'ES UNA PRUEBA', '2023-07-27 15:59:14', '2023-08-05', 125, 125.00, 'PEN', 'CRM', 'none', '0', 5, 125, 23, 71, 35),
(12, 'PRUEBA', 'Lead', 'Medium', 123.00, 'aa', '2023-07-27 15:59:57', '2023-07-28', 12, 1.00, 'PEN', 'a', 'none', '0', 0, 0, 1, 63, 25),
(13, 'Licencia Business Standar', 'Lead', 'Medium', 0.00, '1 Licencia Business Standar', '2023-07-31 17:10:37', '2023-08-01', 150, 150.00, 'PEN', 'Licencia Business Standar', 'none', '0', 0, 0, 1, 73, 37),
(17, 'Licencias Office 365', 'Lost', 'High', 545.00, 'M365 Business Standard (36)\r\nM365 Business Basic (34)\r\nMicrosoft Cloud App Security', '2023-08-07 16:24:59', '2023-08-07', 7832.8, 1300.00, 'PEN', 'M365 Business Standard, M365 Business Basic y Microsoft Cloud App Security', 'Propuesta Técnico EconómicaV2.pdf', '0', 5, 125, 24, 76, 38),
(18, 'Licencia', 'Ganada', 'High', 200.00, '', '2023-08-07 22:41:50', '2023-08-07', 200, 200.00, 'PEN', 'lll', 'Propuesta Técnico EconómicaV2.pdf', '0', 4, 100, 24, 76, 38),
(19, 'Licencia Business Standard', 'Ganada', 'High', 0.00, 'Licencia Business Standard (1)', '2023-08-11 17:19:55', '2023-08-11', 150, 150.00, 'PEN', 'Licencia Business Standard', 'Mollehuaca.pdf', '0', 4, 100, 24, 74, 37),
(21, 'Licencia M365', 'Ganada', 'High', 0.00, '2 Lincencias Business Standard', '2023-08-14 17:29:31', '2023-08-14', 300, 300.00, 'USD', 'Lincencia Business Standard', 'mollehuaca.pdf', '0', 4, 100, 24, 74, 37),
(22, 'Licencia M365', 'Ganada', 'High', 0.00, '2 Lincencias Business Standard', '2023-08-14 17:29:33', '2023-08-14', 300, 300.00, 'USD', 'Lincencia Business Standard', 'mollehuaca.pdf', '0', 4, 100, 24, 74, 37),
(23, 'Licencia Project Plan 3', 'Ganada', 'High', 0.00, 'Licencia Project plan 3', '2023-08-14 17:31:46', '2023-08-14', 360, 360.00, 'USD', 'Licencia project plan 3 ', 'mollehuaca plan 3.pdf', '0', 4, 100, 24, 74, 37),
(24, 'Licencia Business estándar', 'Ganada', 'High', 0.00, 'Licencia business estandar (2)', '2023-08-14 20:41:12', '2023-08-14', 300, 300.00, 'USD', 'Licencia Business estándar ', 'Consult.pdf', '0', 4, 100, 24, 79, 37),
(25, 'Licencia Business Standard', 'Lost', 'High', 0.00, 'Licencia Business Standard (2)', '2023-08-14 20:52:04', '2023-08-14', 300, 300.00, 'USD', 'Licencia Business Standard', 'none', '0', 5, 125, 24, 79, 37),
(27, 'Office 365', 'Lost', 'High', 4510.00, 'M365 Business Basic (132)\r\nMicrosoft 365 E3 (4)\r\nVisio Plan 2 (1)\r\nExchange Online (Plan 1) (3)\r\nExchange Online (Plan 2) (4)\r\nPower Automate per user with attended RPA plan NCE MCY (1)\r\nProject Plan 3 (1)\r\nM365 Business Standard (20)\r\nPower BI Pro (39)', '2023-09-01 15:15:54', '2023-09-08', 54120, 0.00, 'PEN', 'Office 365', 'Propuesta Técnico Económica.pdf', '0', 5, 125, 24, 82, 45),
(28, 'Licencia Office 365', 'Ganada', 'High', 195.00, 'Licencia Business Basic (3)\r\nLicencia Business Standard (3)', '2023-09-04 19:57:22', '2023-09-04', 2333.52, 0.00, 'PEN', 'Licencia Business Standard, Licencia Business Basic', '04092023.pdf', '0', 4, 100, 24, 81, 38),
(29, 'Licencia Business Standard', 'Ganada', 'High', 0.00, 'Licencia Business Standard (4)', '2023-09-07 21:00:26', '2023-09-07', 600, 600.00, 'USD', 'Licencia Business Standard (4)', 'none', '0', 4, 100, 24, 74, 37),
(33, 'Licencias Business Basic', 'Ganada', 'Medium', 60.00, 'Licencia Business Basic', '2023-09-18 14:12:59', '2023-09-18', 720, 0.00, 'USD', '', '1865987-1 (1).pdf', '0', 4, 100, 24, 84, 38),
(34, 'Implementación AD', 'Proposal', 'High', 0.00, 'Implementación AD', '2023-09-18 15:41:06', '2023-09-18', 1300, 0.00, 'USD', '', 'Propuesta Técnico Económica 2806231032v1.pdf', '0', 2, 50, 24, 86, 49),
(35, 'Licencia Business Standard', 'qualified', 'High', 115.00, 'Microsoft 365 Empresa Estándar (10)- WOW / INGRAM MICRO', '2023-09-18 15:53:37', '2024-03-16', 1380, 0.00, 'USD', 'Business Standard', 'none', '0', 1, 25, 24, 87, 50),
(42, 'Plan Azure', 'Qualified', 'High', 370.00, 'Plan azure - WOW - Ingram', '2023-09-20 19:40:46', '2023-11-18', 4440, 0.00, 'USD', 'Plan Azure', 'none', '0', 1, 25, 24, 88, 51),
(43, 'Exchange online (Plan 1)', 'Lost', 'High', 200.00, 'Exchange online (Plan 1) 40 licencia - WOW - Ingram', '2023-09-20 19:45:37', '2023-10-01', 2400, 0.00, 'USD', 'Exchange online Plan 1', 'none', '0', 5, 125, 24, 89, 52),
(45, 'azure', 'ganada', 'High', 0.00, 'asas', '2023-09-22 15:42:31', '2023-09-27', 12, 21.00, 'PEN', '21', 'cobit.pdf', '0', 4, 100, 24, 85, 48),
(46, 'Despliegue VM', 'proposal', 'High', 0.00, 'Despliegue VM', '2023-09-22 16:55:18', '2023-10-02', 0, 100.00, 'USD', 'Azure', 'Propuesta Servicios Azure para INCA ONE v2.pdf', '0', 2, 50, 24, 85, 48),
(47, 'VM plataforma Azure', 'proposal', 'High', 367.00, 'VM plataforma Azure', '2023-09-22 16:58:13', '2023-10-02', 0, 367.00, 'USD', 'Azure', 'Propuesta Servicios Azure para INCA ONE v2.pdf', '0', 2, 50, 24, 85, 48),
(57, 'Licencia Business Standard ', 'Negotiation', 'High', 1816.00, 'Licencia Business Standard (44) ', '2023-09-26 16:54:08', '2023-09-28', 21791, 0.00, 'PEN', 'Licencia Business Standard ', 'Propuesta Técnico Económica v2.pdf', '0', 3, 75, 24, 92, 54),
(58, 'Licencia Business Basic', 'Negotiation', 'High', 872.00, 'Licencia Business Basic (44)', '2023-09-26 16:59:26', '2023-09-28', 10464, 0.00, 'PEN', 'Licencia Business Basic', 'Propuesta Técnico Económica v2.pdf', '0', 3, 75, 24, 92, 54),
(59, 'CERTIFICACION DE PUNTOS DE RED CATEGORIA A6', 'Lost', 'High', 0.00, 'CERTIFICACION DE PUNTOS DE RED CATEGORIA A6 ( 853)', '2023-09-26 17:14:20', '2023-09-29', 0, 8530.00, 'PEN', 'CERTIFICACION DE PUNTOS DE RED CATEGORIA A6', 'none', '0', 5, 125, 24, 91, 53),
(60, 'Licencia Business Standard', 'Negotiation', 'High', 0.00, 'Licencia Business Standard (1)', '2023-09-26 21:21:14', '2023-09-29', 0, 150.00, 'PEN', 'Licencia Business Standard', '26092023.pdf', '0', 3, 75, 24, 94, 37),
(61, 'LICENCIA POWER BI PRO ', 'Lead', 'High', 500.00, 'LICENCIA POWER BI PRO ( 100) ', '2023-09-29 16:01:06', '2023-10-15', 6000, 0.00, 'USD', 'LICENCIA POWER BI PRO (100) ', 'none', '0', 0, 0, 32, 96, 55),
(62, 'LICENCIA BUSINESS BASIC ', 'Lead', 'High', 0.00, 'LICENCIAS BUSINESS BASIC (100) ', '2023-09-29 16:34:27', '2023-09-30', 545, 1.00, 'USD', 'LICENCIA BUSINESS BASIC (100) ', 'none', '0', 0, 0, 32, 97, 56),
(64, 'OFFICE', 'Lead', 'High', 0.00, 'asas', '2023-10-02 15:04:17', '2023-10-02', 0, 0.00, 'PEN', 'office', 'none', '0', 0, 0, 33, 101, 57),
(65, 'LICENCIA BUSINESS BASIC ', 'Lead', 'High', 18.00, '', '2023-10-05 20:53:20', '2023-10-05', 216, 0.00, 'PEN', 'LICENCIA BUSINESS BASIC', 'none', '0', 0, 0, 32, 84, 38),
(66, 'LICENCIA BUSINESS BASIC Y LICENCIA BUSINESS STANDARD', 'Ganada', 'High', 31.00, 'LICENCIA BUSINESS BASIC (3) Y LICENCIA BUSINESS STANDARD(1)', '2023-10-05 21:00:45', '2023-10-05', 366, 0.00, 'USD', 'LICENCIA BUSINESS BASIC (3) Y LICENCIA BUSINESS STANDARD(1)', '041023.pdf', '0', 4, 100, 32, 84, 38),
(67, 'Windows Server', 'Negotiation', 'High', 0.00, 'Licencias Windows Server 2022 Standard -16 Core Licence Pack\r\nWindows Server 2022 Standard - 2 Core License Pack\r\nImplementación, Roles, SetUp, GPO, y Batch\r\nCapacitación\r\n', '2023-10-13 20:40:56', '2023-10-17', 6000, 0.00, 'USD', 'Windows Server', 'Propuesta Técnico Económica VM UCLACHv2.pdf', '0', 3, 75, 24, 86, 49),
(68, 'Licencias Office 365', 'qualified', 'High', 25.00, '', '2023-10-18 20:18:56', '0000-00-00', 25, 50.00, 'USD', 'Licencias Office 365 x5', 'ROL_PARCIAL_2023-2 Ercilia_21-09-2023_ (2).pdf', '0', 1, 25, 36, 108, 63),
(69, 'Discos solidos', 'Lead', 'High', 0.00, 'Discos solidos (10-15 unidades)', '2023-10-18 21:44:38', '2023-10-30', 0, 1000.00, 'PEN', 'Discos solidos', 'muni la punta.pdf', '0', 0, 0, 24, 109, 64),
(70, 'Demo', 'Lost', 'High', 500.00, 'a', '2023-10-19 14:18:55', '2023-10-19', 3, 3.00, 'PEN', 'prueba', 'none', '0', 5, 125, 37, 110, 65),
(71, 'Licencias Microsoft 365 ', 'Ganada', 'High', 0.00, 'Licencia Project plan 5 y Licencia Visio plan 2 ', '2023-10-20 18:13:02', '2023-10-23', 0, 6804.00, 'USD', 'Licencia Project plan 5 y Licencia Visio plan 2 ', 'Propuesta Técnico Económica- 1222201023.pdf', '0', 4, 100, 32, 111, 66),
(72, 'Licencias Business Standard', 'Ganada', 'High', 0.00, 'Licencia Business Standard (2)', '2023-10-27 16:37:53', '2023-10-27', 0, 250.00, 'USD', 'Microsoft 365', 'sankaku.pdf', '0', 4, 100, 24, 74, 37),
(73, 'Licencia Business Standard', 'Ganada', 'High', 0.00, 'Licencia Business  Standard (1)', '2023-10-27 16:40:20', '2023-10-27', 0, 88.00, 'USD', 'Licencia Business Standard', 'mollehuaca.pdf', '0', 4, 100, 24, 74, 37),
(74, 'VOIP', 'lost', 'High', 2000.00, 'demo', '2023-11-30 23:23:09', '2023-12-09', 50000, 1999.00, 'PEN', 'Telefonia VOIP', 'none', '0', 5, 125, 35, 113, 68),
(75, 'Licencia 365', 'Lost', 'Medium', 100.00, 'Este contacto es un conocido', '2024-01-30 17:00:45', '2024-02-01', 4000, 4000.00, 'PEN', 'Licencias de office', 'none', '0', 5, 125, 35, 116, 71),
(76, 'Office 365', 'Lost', 'Medium', 1200.00, '50 Licencias Office 365 business standard', '2024-02-22 20:16:58', '2024-04-18', 24000, 0.00, 'USD', 'Office 365', 'brochure.pdf', '0', 5, 125, 24, 118, 73),
(77, 'azure', 'Proposal', 'High', 0.00, 'Azure ', '2024-02-22 20:34:00', '2024-02-29', 20000, 20000.00, 'PEN', 'azure', 'brochure.pdf', '0', 2, 50, 45, 119, 74),
(78, 'Office 365', 'Lost', 'Medium', 0.00, 'Office 365 - Business Standard (100 licencias)', '2024-03-01 22:37:20', '2024-03-22', 240, 240.00, 'USD', 'Office 365', 'CTO REGULAR 2024 VIRTUAL.pdf', '0', 5, 125, 50, 120, 75),
(79, 'INTERNET DEDICADO', 'Lost', 'Medium', 550.00, 'INTERNET DEDICADO 10 MBPS A 12 MESES RENTA MENSUAL 2500 SOLES', '2024-03-01 22:59:37', '2024-03-05', 6600, 0.00, 'PEN', 'INTERNET DEDICADO 10 MBPS A 12 MESES ', 'Contrato Wow Ica.pdf', '0', 5, 125, 44, 121, 76),
(85, 'INTERNET DEDICADO', 'Lost', 'High', 300.00, 'CLIENTE EVALUA PROPUESTA', '2024-03-05 00:04:29', '2024-03-08', 0, 0.00, 'PEN', 'INTERNET DEDICADO 20 MBPS', 'none', '0', 5, 125, 50, 128, 84),
(86, 'INTERNET DEDICADO', 'Lost', 'High', 300.00, 'CLIENTE EVALUA PROPUESTA', '2024-03-05 00:10:43', '2024-03-08', 0, 0.00, 'PEN', 'INTERNET DEDICADO 20 MBPS', 'none', '0', 5, 125, 50, 129, 85),
(87, 'INTERNET DEDICADO', 'Negotiation', 'High', 400.00, 'CLIENTE EVALUA PROPUESTA', '2024-03-05 00:15:24', '2024-05-06', 0, 0.00, 'PEN', 'INTERNET DEDICADO 50 MBPS', 'none', '0', 3, 75, 50, 120, 75),
(88, 'DEDICADO_250M_COCACHACRA', 'Ganada', 'High', 2600.00, 'INTERNET DEDICADO 250M CON CF 2600 A 18 MESES', '2024-03-05 04:09:37', '2024-03-05', 46800, 0.00, 'PEN', 'INTERNET DEDICADO', 'OTE - SOLUCIONES E INNOVACIONES E&T.pdf', '0', 4, 100, 49, 130, 86),
(89, 'DEDICADO_20M_ILO', 'Lost', 'High', 690.00, 'DEDICADO DE 20M CON CF 690 A 12 MESES', '2024-03-05 04:21:16', '2024-03-07', 8280, 0.00, 'PEN', 'INTERNET DEDICADO', 'OTE - SERVICIOS  MEDICOS SAGRADO CORAZON DE JESUS EIRL.pdf', '0', 5, 125, 49, 131, 87),
(90, 'DEDICADO_40M_TACNA', 'Lost', 'High', 990.00, 'DEDICADO 40M A 12 MESES CON CF 990', '2024-03-05 04:25:52', '2024-05-15', 11880, 0.00, 'PEN', 'INTERNET DEDICADO', 'OTE - POLICLINICO MIRMAR SAC.pdf', '0', 5, 125, 49, 132, 88),
(91, 'DEDICADO_40M_AQP', 'Ganada', 'High', 990.00, 'DEDICADO 40M A 12 MESES CON CF 990', '2024-03-05 04:29:30', '2024-03-19', 11880, 0.00, 'PEN', 'INTERNET DEDICADO', 'OTE - M. & P. REPRESENTACIONES GENERALES SAC.pdf', '0', 4, 100, 49, 133, 89),
(92, 'SOHO_1000M_AQP', 'Ganada', 'Medium', 159.00, 'SOHO 1000M', '2024-03-05 04:32:55', '2024-03-22', 954, 0.00, 'PEN', 'SOHO', 'none', '0', 4, 100, 49, 134, 90),
(95, '11 Sohos Provincia', 'Ganada', 'High', 700.00, 'Internet Soho en 11 sedes en provincia de 1000 Mbps', '2024-03-06 15:57:08', '2024-03-31', 20988, 0.00, 'PEN', 'Internet Soho', 'COBERTURA APUESTA TOTAL 2024.pdf', '0', 4, 100, 45, 161, 120),
(96, 'DEDICADO_50M_AQP', 'Qualified', 'Medium', 0.00, 'DEDICADO 50M', '2024-03-07 03:39:49', '2024-05-15', 23400, 0.00, 'PEN', 'INTERNET DEDICADO + SIP', 'none', '0', 1, 25, 49, 163, 122),
(97, 'RPV_BENEFICENCIA_AQP', 'Lost', 'Medium', 3600.00, 'PROYECTO RPV + SEGURIDAD PERIMETRAL', '2024-03-07 03:45:20', '2024-04-30', 43200, 0.00, 'PEN', 'RPV + SA', 'none', '0', 5, 125, 49, 164, 123),
(98, 'DEDICADO_50M_AQP_JGyR', 'Lost', 'Low', 1300.00, 'INTERNET DEDICADO 50M + LINEA ANALOGICACF ESTIMADO 1300', '2024-03-07 15:24:57', '2024-06-03', 31200, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 5, 125, 49, 167, 127),
(102, 'INTERNET DEDICADO', 'Qualified', 'High', 1000.00, 'GERENTE DE TI', '2024-03-11 15:03:54', '2024-03-31', 0, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 1, 25, 50, 137, 93),
(103, 'INTERNET DEDICADO', 'Lead', 'Low', 1000.00, 'JEFE DE SISTEMAS', '2024-03-11 15:37:49', '2024-03-31', 0, 0.00, 'PEN', 'IP VPN', 'none', '0', 0, 0, 50, 172, 132),
(104, 'INTERNET DEDICADO', 'Lead', 'Low', 1000.00, 'JEFE DE SISTEMAS', '2024-03-11 21:41:56', '2024-03-31', 0, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 0, 0, 50, 176, 136),
(105, 'DEDICADO_SEGURIDAD_36M_', 'Lost', 'High', 1700.00, 'SOLUCION ACTUAL DEL CLIENTE CON WIN 40MB + SEGURIDAD EN LA NUBE CON 10 USUARIOS REMOTOS ', '2024-03-12 12:59:47', '2024-03-31', 61200, 0.00, 'PEN', 'INTERNET DEDICADO + SEGURIDAD PERIMETRAL', 'none', '0', 5, 125, 46, 177, 100),
(106, 'DUO_1000_GORE_GEM', 'Ganada', 'High', 169.00, 'SERVICIO SOHO_DUO 1000MB', '2024-03-12 13:40:41', '2024-03-21', 2028, 0.00, 'PEN', 'WOW DUO 1000 MBPS', 'none', '0', 4, 100, 46, 178, 137),
(107, 'INTERNET_DEDICADO + DUO', 'Lost', 'High', 1000.00, 'ENVIO TDR INTERNET DEDICADO + DUO', '2024-03-12 14:10:29', '2024-03-13', 12000, 0.00, 'PEN', 'INTERNET DEDICADO 50MB + DUO ', 'none', '0', 5, 125, 46, 179, 138),
(108, 'Enlace Internet y Datos', 'Negotiation', 'High', 6600.00, '2 enlaces de internet 500 Mbps + 2 enlaces de datos 500 Mbps', '2024-03-12 15:10:46', '2024-03-31', 237600, 0.00, 'PEN', 'Internet + Datos', 'Oferta Técnico comercial- Cotización de Internet dedicado- SOA PROFESSIONAL_2024.pdf', '0', 3, 75, 45, 181, 141),
(109, 'Internet Surco', 'Negotiation', 'High', 700.00, 'Internet Backup', '2024-03-12 15:12:39', '2024-03-31', 0, 0.00, 'PEN', 'Internet', 'Oferta Técnico comercial- Cotización de Internet dedicado-Cementos Pacasmayo_WOW PERU_V2.pdf', '0', 3, 75, 45, 141, 97),
(110, 'Internet Tumbes', 'Lost', 'High', 400.00, 'Servicio oficina', '2024-03-12 15:14:40', '2024-03-31', 4800, 0.00, 'PEN', 'Internet Dedicado', 'OTE_HYDROGAS_INTERNET_DEDICADO_10Mbps_EMPRESAS_WOW_2024.pdf', '0', 5, 125, 45, 143, 99),
(111, 'INTERNET DEDIADO', 'Lost', 'High', 550.00, 'INTERNET DEDICADO 10 MBPS A 12 MESES RENTA MENSUAL', '2024-03-12 15:27:34', '2024-03-20', 6600, 0.00, 'PEN', 'INTERNET DEDICADO 10 MBPS A 12 MESES', 'none', '0', 5, 125, 44, 121, 76),
(112, ': Internet Dedicado', 'Ganada', 'High', 650.00, 'Velocidad: 40 MBPS / Plazo de contrato: 36 / Moneda: Soles / Tarifa unitaria inc. IGV: S/.650.00', '2024-03-12 15:35:02', '2024-03-30', 23400, 0.00, 'PEN', 'INTERNET DEDICADO 40MBPS', 'none', '0', 4, 100, 44, 182, 142),
(113, 'INTERNET 300', 'Ganada', 'High', 79.00, 'INTERNET SOLO 300MB', '2024-03-12 15:47:10', '2024-03-30', 474, 0.00, 'PEN', 'INTERNET SOLO 300MB', 'none', '0', 4, 100, 44, 183, 143),
(114, 'INTERNET DEDICADO 20MB_36MESES', 'Ganada', 'High', 500.00, 'INTERNET DEDICADO 20MB 36 MESES 500 SOLES MENSUALES', '2024-03-12 15:57:58', '2024-04-30', 18000, 0.00, 'PEN', 'INTERNET DEDICADO 20MB', 'none', '0', 4, 100, 46, 184, 144),
(115, 'INERNET DEDICADO', 'Lead', 'High', 1800.00, 'INTERNET DEDICADO 100MB _ 12 MESES', '2024-03-12 16:02:09', '2024-04-30', 0, 0.00, 'PEN', '20400', 'none', '0', 0, 0, 46, 185, 145),
(121, 'SOHO_WOW_INTERNET_1000MB_SOLO', 'Ganada', 'High', 159.00, 'SOHO WOW_INTERNET_1000MB 6 MESES', '2024-03-13 00:12:26', '2024-03-30', 954, 0.00, 'PEN', 'SOHO_WOW_INTERNET_1000MB_SOLO', 'none', '0', 4, 100, 46, 185, 145),
(122, 'Enlace RPVL', 'Negotiation', 'High', 3000.00, 'Enlace de datos privados desde Marcona hasta Oficinas en Lima', '2024-03-13 16:01:16', '2024-05-15', 108000, 0.00, 'PEN', 'Red Privada Virtual', 'WOW PERU 2024_PROPUESTA TECNICO ECONÓMICA MINERA SHOUXIN.pdf', '0', 3, 75, 45, 188, 148),
(124, 'INTERNET DEDICADO', 'Qualified', 'High', 600.00, 'INTERNET PARA COLEGIOS DE CAÑETE VARIAS SEDES', '2024-03-13 17:05:30', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET SOHO', 'none', '0', 1, 25, 50, 189, 149),
(126, 'INTERNET DEDICADO', 'Proposal', 'High', 900.00, 'INTERNET  DEDICADO DE 100 MBPS', '2024-03-13 22:09:33', '2024-03-25', 0, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 2, 50, 50, 193, 153),
(127, 'DEDICADO_50M_AQP', 'Proposal', 'Medium', 990.00, 'DEDICADO', '2024-03-13 22:15:37', '2024-05-15', 11880, 0.00, 'PEN', 'DEDICADO', 'none', '0', 2, 50, 49, 194, 154),
(128, 'DEDICADO_150M_MOLLENDO', 'Negotiation', 'Medium', 1090.00, 'DEDICADO 150M', '2024-03-13 22:20:00', '2024-04-25', 19620, 0.00, 'PEN', 'DEDICADO', 'none', '0', 3, 75, 49, 195, 155),
(130, 'DEDICADO_HOSPITAL_PUNO', 'Lead', 'Low', 3000.00, 'DEDICADO', '2024-03-13 22:25:43', '2024-06-10', 36000, 0.00, 'PEN', 'DEDICADO', 'none', '0', 0, 0, 49, 196, 156),
(131, 'SOHO_500M_MIGO', 'Ganada', 'High', 89.00, 'INTERNET SOHO 500M', '2024-03-13 22:31:20', '2024-03-14', 534, 0.00, 'PEN', 'INTERNET SOHO', 'none', '0', 4, 100, 49, 197, 157),
(133, 'SOHO_200M_RAPIDFOOD', 'Ganada', 'High', 69.00, 'INTERNET SOHO 200M', '2024-03-14 00:28:00', '2024-03-15', 414, 0.00, 'PEN', 'SOHO', 'none', '0', 4, 100, 49, 200, 160),
(134, 'TRANSMISION DE DATOS', 'Qualified', 'High', 1000.00, 'CONTRATO A 12 MESES', '2024-03-14 13:31:28', '2024-03-31', 0, 0.00, 'PEN', 'TRANSMISION DE DATOS', 'none', '0', 1, 25, 50, 137, 93),
(135, 'CIBERSEGURIDAD', 'Qualified', 'High', 1000.00, 'CONTRATO A 12 MESES', '2024-03-14 13:33:32', '2024-03-31', 0, 0.00, 'PEN', 'CIBERSEGURIDAD', 'none', '0', 1, 25, 50, 137, 93),
(136, 'MICROSOFT', 'Qualified', 'High', 1000.00, 'SOFTWARE Y LICENCIAS', '2024-03-14 13:36:22', '0000-00-00', 0, 0.00, 'PEN', 'MICROSOFT', 'none', '0', 1, 25, 50, 137, 93),
(137, 'TELEFONIA FIJA', 'Qualified', 'High', 1000.00, 'CONTRATO X 12 MESES', '2024-03-14 14:31:59', '2024-03-31', 0, 0.00, 'PEN', 'TELEFONIA IP', 'none', '0', 1, 25, 50, 137, 93),
(138, 'TELEFONIA FIJA', 'Lead', 'High', 0.00, 'TELEFONIA Y CENTRAL', '2024-03-14 16:41:59', '2024-04-22', 0, 0.00, 'PEN', 'TELEFONIA', 'none', '0', 2, 50, 50, 120, 75),
(139, 'TELEFONIA FIJA', 'Lost', 'High', 500.00, 'TELEFONIA', '2024-03-14 16:42:55', '2024-03-29', 0, 0.00, 'PEN', 'TELEFONIA', 'none', '0', 5, 125, 50, 128, 84),
(144, 'Sede Cañete', 'Negotiation', 'High', 950.00, 'Ultima milla', '2024-03-19 14:37:13', '2024-03-31', 11400, 1000.00, 'PEN', 'Transporte de Red', 'none', '0', 3, 75, 45, 126, 82),
(145, 'SOHO_WOW INTERNET 500 MBPS SOLO', 'Ganada', 'High', 89.00, 'WOW INTERNET 500 MBPS SOLO', '2024-03-19 15:49:46', '2024-03-30', 534, 0.00, 'PEN', 'WOW INTERNET 500 MBPS SOLO', 'none', '0', 4, 100, 46, 201, 161),
(146, 'INTERNET DEDICADO_100MB', 'Lead', 'High', 2000.00, 'INTERNET DEDICADO 100MB', '2024-03-19 16:01:13', '2024-04-30', 72000, 0.00, 'PEN', 'INTERNET DEDICADO 100MB', 'none', '0', 0, 0, 46, 204, 164),
(147, 'Internet Campus Principal', 'Lead', 'High', 1660.00, 'Cliente quiere contratar para el Campus Principal', '2024-03-19 18:45:46', '2024-04-30', 40000, 0.00, 'PEN', 'Internet Dedicado', 'none', '0', 0, 0, 45, 205, 165),
(148, 'SOHO_WOW INTERNET 300 MBPS SOLO', 'Ganada', 'High', 79.00, 'SOHO_WOW INTERNET 300 MBPS SOLO', '2024-03-19 20:58:19', '0000-00-00', 474, 0.00, 'PEN', 'WOW INTERNET 300 MBPS SOLO', 'CONTRATO DE.pdf', '0', 4, 100, 46, 184, 144),
(149, 'SOHO_MOCHUMI_WOW INTERNET 300 MBPS SOLO', 'Ganada', 'High', 79.00, 'MOCHUMI_WOW INTERNET 300 MBPS SOLO', '2024-03-20 00:50:35', '2024-03-30', 790, 0.00, 'PEN', 'WOW INTERNET 300 MBPS SOLO', 'none', '0', 4, 100, 46, 206, 166),
(150, 'SOHO_LAMBAYEQUE_WOW INTERNET 300 MBPS SOLO', 'Ganada', 'High', 79.00, 'SEDE LAMBAYEQUE WOW INTERNET 300 MBPS SOLO', '2024-03-20 01:19:55', '2024-03-30', 790, 0.00, 'PEN', 'WOW INTERNET 300 MBPS SOLO', 'none', '0', 4, 100, 46, 206, 166),
(151, 'SOHO_MOTUPE_WOW INTERNET 300 MBPS SOLO', 'Negotiation', 'High', 79.00, 'SEDE MOTUPE WOW INTERNET 300 MBPS SOLO', '2024-03-20 01:20:43', '2024-03-30', 790, 0.00, 'PEN', 'WOW INTERNET 300 MBPS SOLO', 'none', '0', 3, 75, 46, 206, 166),
(152, 'INTERNET SOHO', 'Ganada', 'High', 477.00, '03 PLANES DE 1000 MBPS', '2024-03-20 22:48:01', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET SOHO', 'none', '0', 4, 100, 50, 207, 167),
(153, 'SOHO_200M_ZAMINE', 'Lost', 'High', 69.00, 'SOHO 200M RENTA 69', '2024-03-21 00:36:04', '2024-03-20', 414, 0.00, 'PEN', 'INTERNET SOHO', 'none', '0', 5, 125, 49, 208, 168),
(154, 'DEDICADO_80M_POLICLINICO_SAN_ANTONIO', 'Qualified', 'Medium', 690.00, 'DEDICADO 80M', '2024-03-21 01:05:29', '2024-05-15', 12420, 0.00, 'PEN', 'DEDICADO', 'none', '0', 1, 25, 49, 209, 169),
(155, 'INTERNET_DEDICADO_60MB', 'Lead', 'High', 950.00, 'REQUIERE INTERNET DEDICADO CON IP PARA 30 CAMARAS', '2024-03-21 04:39:59', '2024-04-30', 0, 0.00, 'PEN', '34200', 'none', '0', 0, 0, 46, 210, 170),
(156, 'DEDICADO_250MB', 'Qualified', 'High', 1800.00, 'INTERNET DEDICADO 250mb', '2024-03-21 05:39:46', '2024-03-30', 64800, 0.00, 'PEN', 'INTERNET_DEDICADO_500MB', 'none', '0', 3, 75, 46, 211, 171),
(162, 'INTERNET DEDICADO_3_SEDES_', 'Lead', 'High', 2000.00, 'INTERCONEXION DE 3 LOCALES INTERNET DEDICADO 50MB / 30MB / 30MB', '2024-03-21 21:18:58', '2024-03-30', 24000, 0.00, 'PEN', 'INTERNET_DEDICADO_50MB_30MB_30MB', 'none', '0', 0, 0, 46, 231, 192),
(164, 'WOW INTERNET 300 MBPS SOLO_SEDE_FERREÑAFE', 'Ganada', 'High', 79.00, 'WOW INTERNET 300 MBPS SOLO_SEDE_FERREÑAFE', '2024-03-21 21:36:06', '2024-03-31', 790, 0.00, 'PEN', 'WOW INTERNET 300 MBPS SOLO', 'ORDEN DE SERVICIO 226-2024.pdf', '0', 4, 100, 46, 206, 166),
(166, 'Internet 10 Mbps Cañete', 'Ganada', 'High', 300.00, 'Internet dedicado en Cañete con 1 IP pública', '2024-03-22 17:07:45', '2024-03-22', 7200, 0.00, 'PEN', 'Internet Dedicado', 'DIA WOW - ANEXO 1 .pdf', '0', 4, 100, 45, 234, 195),
(168, 'WOW 500 MBPS / 1000 Min DUO', 'Ganada', 'High', 99.00, 'WOW 500 MBPS / 1000 Min DUO', '2024-03-25 17:50:35', '2024-03-30', 1188, 0.00, 'PEN', 'WOW 500 MBPS / 1000 Min DUO', 'none', '0', 4, 100, 46, 236, 197),
(169, 'RENO_MIGRA_WOW 200 MBPS / 1000 Min DUO', 'Ganada', 'High', 79.00, 'WOW 200 MBPS / 1000 Min DUO', '2024-03-26 02:59:30', '2024-04-30', 948, 0.00, 'PEN', 'WOW 200 MBPS / 1000 Min DUO', 'none', '0', 4, 100, 46, 237, 198),
(170, 'SOHO_WOW 500 MBPS / 1000 Min DUO', 'Ganada', 'High', 99.00, 'ANALISTA LOGISTICO - UNIDAD DE ABASTECIMIENTO', '2024-03-26 03:09:59', '2024-04-30', 1188, 0.00, 'PEN', 'WOW 500 MBPS / 1000 Min DUO', 'none', '0', 4, 100, 46, 237, 198),
(171, 'UPGRADE_INTERNET_DEDICADO_250MB', 'Ganada', 'High', 500.00, 'ACTUALMENTE TIENE 80MB POR S/. 2000 SE PROPONE UPGRADE A S/ 2500 POR 250MB', '2024-03-26 03:33:40', '2024-04-30', 18000, 0.00, 'PEN', 'UPGRADE_INTERNET DEDICADO_250MB', 'none', '0', 4, 100, 46, 238, 199),
(172, 'INTERNET_DEDICADO_40MB_ILO', 'Ganada', 'High', 1100.00, 'INTERNET DEDICADO 40MB ENAPU SEDE ILO', '2024-03-26 04:30:37', '2024-04-15', 13200, 0.00, 'PEN', 'INTERNET DEDICADO 40MB', 'none', '0', 4, 100, 46, 239, 200),
(173, 'INTERNET DEDICADO', 'Negotiation', 'High', 1300.00, 'INTERNET DE 100 MBPS REQUIERE 01 IP PUBLICA', '2024-03-26 16:54:15', '2024-04-22', 0, 0.00, 'PEN', 'INTENET DEDICADO 100 MBPS', 'none', '0', 3, 75, 50, 241, 202),
(174, 'Soho Hospital Ica', 'Ganada', 'High', 69.00, 'servicio dentro del Hospital Sta. Maria del ', '2024-03-26 19:42:07', '2024-03-26', 828, 0.00, 'PEN', 'Internet Soho', 'CONTRATO Y dni FIRMADO.pdf', '0', 4, 100, 45, 242, 203),
(175, 'Internet Soho Cañete', 'Ganada', 'High', 169.00, 'servicio soho Cañete', '2024-03-26 19:58:52', '2024-03-26', 2028, 0.00, 'PEN', 'Internet Soho', 'OS N_390-2024.pdf', '0', 4, 100, 45, 243, 204),
(176, 'Internet Soho VES', 'Ganada', 'High', 169.00, 'Internet en Villa El Salvador', '2024-03-26 19:59:52', '2024-03-26', 0, 0.00, 'PEN', 'Internet Soho', 'OS N_391-2024.pdf', '0', 4, 100, 45, 243, 204),
(177, 'INTERNET DEDICADO', 'Proposal', 'High', 600.00, 'INTERNET DEDICADO 50 MBPS CON IP PUBLICA', '2024-03-27 21:14:28', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 2, 50, 50, 244, 205),
(178, 'Carrier Ethernet Chincha Pisco', 'Lead', 'Medium', 1000.00, 'Enlace que permite integral las redes de sus sedes en Pisco y Chincha', '2024-04-03 15:08:40', '2024-07-03', 12000, 0.00, 'PEN', 'Enlace de Datos', 'none', '0', 0, 0, 45, 246, 207),
(179, 'DEDICADO_50M_NAM', 'Lost', 'High', 550.00, 'INTERNET DEDICADO 50M', '2024-04-04 01:23:44', '2024-04-26', 19800, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 5, 125, 49, 247, 208),
(180, 'DEDICADO_100M_PROCELSA', 'qualified', 'Medium', 990.00, 'INTERNET DEDICADO 100M', '2024-04-04 01:28:40', '2024-05-15', 17820, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 1, 25, 49, 248, 209),
(181, 'DEDICADO_100M_MALL_PASEO_CENTRAL', 'Proposal', 'Medium', 990.00, 'INTERNET DEDICADO 100M', '2024-04-04 01:32:31', '2024-04-25', 0, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 2, 50, 49, 249, 210),
(182, 'DEDICADO_100M_MUNI_PUNTA_BOMBON', 'Lead', 'Medium', 1800.00, 'INTERNET DEDICADO + SEGURIDAD PERIMETRAL', '2024-04-04 01:39:16', '2024-05-15', 21600, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 0, 0, 49, 250, 211),
(183, 'INTERNET DEDICADO 100MB', 'Lead', 'High', 2000.00, 'SE ENVIO COTIZACION A CLIENTE', '2024-04-05 13:06:29', '2024-04-30', 72, 0.00, 'PEN', 'INTERNET DEDICADO 100MB', 'none', '0', 0, 0, 44, 251, 212),
(184, 'INTERNET 200 MBPS', 'Proposal', 'High', 2000.00, 'La sede Lurín presenta problemas con su servicio de internet con el actual proveedor. ', '2024-04-08 15:14:07', '2024-05-31', 24000, 0.00, 'PEN', 'Internet Dedicado + RPV( Red Privada Virtual)', 'none', '0', 3, 75, 45, 252, 213),
(185, 'Internet Tacna Sede Principal', 'Proposal', 'High', 1900.00, 'COntratar servicio de internet dedicado.', '2024-04-08 15:47:41', '2024-05-11', 22800, 0.00, 'PEN', 'Internet Dedicado 1.1', 'Oferta Técnico comercial WOW TEL SAC- Cotización de Internet dedicado-Gobierno Regional de Tacna Internet Dedicado.pdf', '0', 2, 50, 45, 253, 214),
(186, 'Office 365', 'Lost', 'High', 200.00, 'office 365 business standard (5 licencias) por 10 meses', '2024-04-08 16:04:54', '2024-04-19', 2000, 0.00, 'USD', 'office 365', 'Cuenta VB- Interbank (1).pdf', '0', 5, 125, 52, 254, 215),
(187, '3 Sedes Contraloria', 'Ganada', 'High', 2800.00, 'Proyecto de GTD con la Contraloría ', '2024-04-08 17:14:14', '2024-04-15', 103700, 0.00, 'PEN', 'Transporte Nacional y última milla', 'OTE-GTD-V1-12-24-36_PASCO_TACNA_CHIMBOTE.pdf', '0', 4, 100, 45, 126, 82),
(188, 'Internet Dedicado Jaen', 'Proposal', 'Medium', 700.00, 'Servicio sede jaen', '2024-04-08 17:33:55', '2024-04-30', 8400, 0.00, 'PEN', 'Internet Dedicado 50 Mbps', 'Oferta Técnico comercial- Cotización de Internet dedicado_CAJA MAYNAS.pdf', '0', 2, 50, 45, 180, 139),
(189, 'GPON_12 Sedes', 'Proposal', 'High', 8400.00, '12 enlaces de 15 Mbps a 36 meses. Se envió cotización en enlace dedicado 1.1, pero se solicita cotizar en GPON. ', '2024-04-08 17:41:40', '2024-04-30', 302400, 0.00, 'PEN', 'Transporte Nacional y última milla', 'none', '0', 2, 50, 45, 126, 82),
(190, 'Internet 50  Mbps Surco', 'Ganada', 'High', 350.00, 'Instalar Internet Dedicado en Oficinas recién alquiladas. ', '2024-04-09 16:57:36', '2024-04-30', 8400, 0.00, 'PEN', 'Internet Dedicado', 'none', '0', 4, 100, 45, 255, 216),
(191, 'INTERNET SOHO', 'Ganada', 'High', 79.00, 'FIRMO CONTRATO ADJUNTO DOCUMENTOS SOLICITADOS', '2024-04-09 17:01:10', '2024-04-09', 0, 0.00, 'PEN', 'INTERNET SOHO 300 MBPS', 'none', '0', 4, 100, 50, 256, 217),
(192, 'INTERNET DEDICADO', 'Proposal', 'High', 900.00, 'ACTUAL OPERADOR MOVISTAR', '2024-04-09 20:06:56', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET DEDICADO  100 MBPS', 'none', '0', 2, 50, 50, 257, 218),
(193, 'Internet 50 Mbps Piso 8 Edificio el Polo', 'Negotiation', 'High', 400.00, 'Cotización Internet Dedicado Edificio El Polo piso 8', '2024-04-11 20:46:20', '2024-05-31', 14400, 0.00, 'PEN', 'Internet Dedicado', 'none', '0', 3, 75, 45, 268, 231),
(194, 'TALARA_INTERNET 200 MBPS SOLO', 'Ganada', 'High', 69.00, 'WOW INTERNET 200 MBPS SOLO', '2024-04-12 00:21:51', '2024-04-15', 828, 0.00, 'PEN', 'WOW INTERNET 200 MBPS SOLO', 'Cotización B2B_MASIVO_SUNAFIL_SEDE_TALARA.pdf', '0', 4, 100, 46, 269, 232),
(195, 'ALTA_SOHO_JAEN_INTERNET 200 MBPS SOLO', 'Ganada', 'High', 69.00, ' INTERNET SOLO 200MB ALTA SOHO SEDE JAEN', '2024-04-12 02:19:10', '2024-04-30', 828, 0.00, 'PEN', 'WOW INTERNET 200 MBPS SOLO', 'none', '0', 4, 100, 46, 269, 232),
(196, 'INTERNET DEDICADO', 'Proposal', 'High', 700.00, 'INTERNET DEDICADO 50 MBPS 03 IPS PUBLICAS', '2024-04-15 17:33:20', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET DEDICADO 50 MBPS', 'none', '0', 2, 50, 50, 270, 235),
(197, 'INTERNET DEDICADO', 'Proposal', 'Medium', 800.00, 'IEP NUESTRA SEÑORA DE LA MERCED ', '2024-04-15 17:41:15', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET DEDICADO  100 MBPS', 'none', '0', 2, 50, 50, 271, 236),
(198, 'DEDICADO_SEGURIDAD_48', 'Lead', 'High', 1700.00, 'SOLUCION ACTUAL DEL CLIENTE CON WIN 40MB + SEGURIDAD EN LA NUBE CON 10 USUARIOS REMOTOS -prueba', '2024-04-15 21:30:55', '2024-04-30', 61200, 0.00, 'PEN', 'INTERNET DEDICADO + SEGURIDAD PERIMETRAL', 'none', '0', 0, 0, 46, 177, 100),
(199, 'Internet Sede Norte', 'Lead', 'Low', 69.00, 'Cotización servicio SOHO', '2024-04-15 22:14:15', '0000-00-00', 828, 0.00, 'PEN', 'SOHO 200mbps', 'none', '0', 0, 0, 54, 266, 230),
(200, 'Conexión Contingencia', 'Lead', 'Low', 69.00, 'Conexión Backup', '2024-04-15 22:17:04', '0000-00-00', 828, 0.00, 'PEN', 'SOHO 200mbps', 'none', '0', 0, 0, 54, 272, 237),
(201, 'Migración Internet', 'Lead', 'Medium', 400.00, 'Migración proveedor principal internet', '2024-04-15 22:19:30', '0000-00-00', 48000, 0.00, 'PEN', 'Internet Dedicado 20mbps', 'none', '0', 0, 0, 54, 273, 238),
(202, 'DEDICADO_10M_BREVE - T DEL SUR', 'Qualified', 'Medium', 399.00, 'DEDICADO 10M CON IP FIJA', '2024-04-15 22:19:40', '2024-04-29', 4788, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 1, 25, 49, 274, 239),
(203, 'Licencias Microsoft', 'Lead', 'Low', 500.00, 'Servicios Microsoft 365', '2024-04-15 22:20:38', '0000-00-00', 6000, 0.00, 'PEN', 'Microsoft 365', 'none', '0', 0, 0, 54, 267, 229),
(204, 'CyberSoc', 'Lead', 'High', 1500.00, 'Servicio Gestionado CyberSOC 24x7 por 3 años', '2024-04-15 22:29:44', '0000-00-00', 54000, 0.00, 'PEN', 'Servicio Gestionado CyberSOC ', 'none', '0', 0, 0, 54, 275, 240),
(205, 'SOHO_1000M_CONTELFORTED', 'Ganada', 'High', 159.00, 'INTERNET SOHO 1000M', '2024-04-16 00:07:29', '2024-04-22', 954, 0.00, 'PEN', 'INTERNET SOHO', 'none', '0', 4, 100, 49, 276, 241),
(206, 'DEDICADO_20M_SAN_MARTIN', 'Qualified', 'High', 490.00, 'INTERNET DEDICADO 20M', '2024-04-16 00:11:57', '2024-04-30', 5900, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 1, 25, 49, 277, 242),
(207, 'INTERNET DEDICADO _30MB', 'Lead', 'High', 600.00, 'INTERNET DEDICADO ', '2024-04-16 02:16:04', '2024-04-30', 21600, 0.00, 'PEN', 'INTERNET DEDICADO 30MB', 'none', '0', 0, 0, 46, 236, 197),
(208, 'DEDICADO_30M_NASCA', 'Negotiation', 'High', 490.00, 'DEDICADO 30M', '2024-04-16 18:04:37', '2024-04-30', 8820, 0.00, 'PEN', 'INTERNET DEDICADO', 'none', '0', 3, 75, 49, 278, 243),
(209, 'ALTA SOHO_1000MBPS', 'Ganada', 'High', 159.00, 'ALTA SOHO 1000MBPS SOLO ', '2024-04-18 05:39:36', '2024-04-30', 1908, 0.00, 'PEN', 'WOW INTERNET 1000MBPS SOLO', 'none', '0', 4, 100, 46, 236, 197),
(210, 'INTERNET DEDICADO - OF.PRINCIPAL', 'Lead', 'High', 600.00, 'Dedicado 200 mbps,  8 IPS', '2024-04-18 20:20:24', '2024-04-30', 72000, 0.00, 'PEN', 'INTERNET DEDICADO 200mbps', 'none', '0', 0, 0, 54, 267, 229),
(211, 'Parque Lurín Solum', 'Qualified', 'High', 2000.00, 'Internet 100 Mbps en Sede planta Lurín', '2024-04-18 21:35:04', '2024-05-01', 24000, 0.00, 'PEN', 'Internet', 'none', '0', 1, 25, 45, 252, 213),
(212, 'Renovación Internet Soho LLankasun', 'Ganada', 'High', 159.00, 'Migración de plan de Internet Soho 500 a Internet Soho 1000', '2024-04-22 22:23:35', '2024-04-22', 1908, 0.00, 'PEN', 'Internet Soho', 'none', '0', 4, 100, 45, 284, 252),
(213, 'Internet Soho 500 Mbps', 'Lead', 'High', 89.00, 'Oficina Edificio El Polo', '2024-04-24 17:31:30', '2024-04-30', 534, 0.00, 'PEN', 'Internet Soho 500 Mbps', 'none', '0', 0, 0, 45, 287, 257),
(214, 'SOHO_1000M_CERTIFICADORA_SAN MARTIN', 'Ganada', 'High', 159.00, 'SOHO 1000M', '2024-04-25 01:25:50', '2024-04-25', 954, 0.00, 'PEN', 'INTERNET SOHO', 'none', '0', 4, 100, 49, 288, 239),
(215, 'SOHO_1000M_CERTIFICADORA_S&M_PUNO', 'Ganada', 'High', 159.00, 'INTERNET SOHO 1000M', '2024-04-25 01:27:23', '2024-04-25', 954, 0.00, 'PEN', 'INTERNET SOHO 1000M', 'none', '0', 4, 100, 49, 289, 239),
(216, 'Internet Dedicado', 'Negotiation', 'Medium', 3000.00, 'Internet Dedicado 450 mbps', '2024-04-25 13:46:42', '2024-04-30', 36000, 0.00, 'PEN', 'Internet Dedicado', '1.00 FORMATOS PARA COTIZACION - HOSPITAL 2024.pdf', '0', 3, 75, 54, 290, 259),
(217, 'ALTA_SOHO_1000_MBPS', 'Lead', 'High', 159.00, 'INTERNET SOHO 1000 MBPS WOW', '2024-04-25 22:31:24', '2024-04-30', 954, 0.00, 'PEN', 'WOW INTERNET 1000 MBPS SOLO', 'none', '0', 0, 0, 46, 204, 164),
(218, 'INTERNET DEDICADO ', 'Negotiation', 'High', 1000.00, 'INTERNET DEDICADO 1OO MBPS', '2024-04-26 15:58:54', '2024-04-30', 0, 0.00, 'PEN', 'INTERNET DEDICADO 100 MBPS', 'none', '0', 3, 75, 50, 291, 261),
(219, 'Transporte Abancay 80 Mbps', 'Ganada', 'High', 1000.00, 'Transporte Abancay', '2024-04-30 00:16:24', '2024-04-29', 12990, 0.00, 'PEN', 'Transport ID Abancay', 'OTE-GTD-V7-12_Abancay_Chachapoyas.pdf', '0', 4, 100, 45, 126, 82),
(220, 'Transporte ID Chachapoyas', 'Ganada', 'High', 950.00, '', '2024-04-30 00:19:12', '2024-04-29', 35100, 900.00, 'PEN', 'Transporte Provincia Chachapoyas', 'none', '0', 4, 100, 45, 126, 82),
(221, 'DEDICADO_10M_ELECTROSAN', 'Negotiation', 'Medium', 390.00, 'INTERNET DEDICADO 10M', '2024-04-30 16:05:57', '2024-05-02', 9360, 0.00, 'PEN', 'INTERNET DEDICADO 10M', 'none', '0', 3, 75, 49, 292, 262),
(222, 'Internet 40 Mbps Juliaca Real Plaza', 'Lead', 'High', 500.00, 'Enlace dentro del centro comercial real plaza juliaca', '2024-04-30 16:49:15', '2024-05-03', 4800, 0.00, 'PEN', 'Internet Dedicado', 'none', '0', 0, 0, 45, 293, 263),
(223, 'Balanceador de Enlaces', 'Lead', 'High', 1500.00, 'Equipo de balanceo de enlaces como servicio para la HA de los enlaces de internet del clientes. ', '2024-05-02 22:42:50', '2024-05-31', 54000, 0.00, 'PEN', 'LAN GESTIONADA', 'none', '0', 0, 0, 45, 252, 213),
(224, 'INTERNET 300 MBPS PORCELATINO', 'Negotiation', 'High', 2500.00, 'Proyecto de construcción de fibra óptica ', '2024-05-03 23:34:00', '2024-06-30', 160000, 10000.00, 'PEN', 'Internet Dedicado 300 Mbps', 'none', '0', 3, 75, 45, 294, 264);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `segment`
--

CREATE TABLE `segment` (
  `idSegment` int(11) NOT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `fecha_De_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `segment`
--

INSERT INTO `segment` (`idSegment`, `marca`, `fecha_De_registro`) VALUES
(2, 'Empresa', '2023-06-07 16:55:48'),
(3, 'Gobierno', '2023-06-07 16:55:48'),
(4, 'Regional', '2024-01-30 11:53:33'),
(5, 'Privado', '2024-06-05 21:49:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `typecustomer`
--

CREATE TABLE `typecustomer` (
  `idtypecustomer` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `COD_idCliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `typecustomer`
--

INSERT INTO `typecustomer` (`idtypecustomer`, `type`, `COD_idCliente`) VALUES
(16, 2, 70),
(18, 2, 72),
(20, 1, 74),
(22, 1, 76),
(23, 1, 77),
(24, 1, 78),
(25, 1, 79),
(27, 1, 81),
(28, 1, 82),
(30, 1, 84),
(31, 1, 85),
(32, 1, 86),
(33, 1, 87),
(34, 1, 88),
(35, 1, 89),
(36, 1, 90),
(37, 1, 91),
(38, 1, 92),
(39, 1, 93),
(40, 1, 94),
(42, 1, 96),
(43, 1, 97),
(44, 1, 98),
(45, 1, 99),
(46, 1, 100),
(47, 1, 101),
(48, 1, 102),
(49, 1, 103),
(50, 1, 104),
(51, 1, 105),
(52, 2, 106),
(53, 1, 107),
(54, 2, 108),
(55, 1, 109),
(56, 1, 110),
(57, 1, 111),
(58, 1, 112),
(59, 1, 113),
(60, 1, 114),
(61, 1, 115),
(62, 1, 116),
(63, 1, 117),
(64, 1, 118),
(65, 1, 119),
(66, 1, 120),
(67, 2, 121),
(68, 1, 122),
(69, 1, 123),
(70, 1, 124),
(72, 2, 126),
(74, 1, 128),
(75, 1, 129),
(76, 2, 130),
(77, 2, 131),
(78, 2, 132),
(79, 2, 133),
(80, 2, 134),
(81, 1, 135),
(82, 1, 136),
(83, 1, 137),
(84, 1, 138),
(85, 1, 139),
(86, 1, 140),
(87, 2, 141),
(88, 1, 142),
(89, 1, 143),
(90, 1, 144),
(91, 1, 145),
(92, 1, 146),
(93, 1, 147),
(94, 1, 148),
(95, 1, 149),
(96, 1, 150),
(97, 1, 151),
(98, 1, 152),
(99, 2, 153),
(100, 1, 154),
(101, 2, 155),
(102, 2, 156),
(103, 1, 157),
(104, 1, 158),
(105, 1, 159),
(106, 1, 160),
(107, 1, 161),
(109, 2, 163),
(110, 2, 164),
(111, 1, 165),
(112, 1, 166),
(113, 1, 167),
(115, 1, 169),
(117, 1, 171),
(118, 1, 172),
(119, 1, 173),
(120, 1, 174),
(121, 1, 175),
(122, 1, 176),
(123, 1, 177),
(124, 1, 178),
(125, 1, 179),
(126, 1, 180),
(127, 1, 181),
(128, 1, 182),
(129, 1, 183),
(130, 1, 184),
(131, 1, 185),
(132, 1, 186),
(133, 1, 187),
(134, 1, 188),
(135, 1, 189),
(136, 1, 190),
(137, 1, 191),
(138, 1, 192),
(139, 1, 193),
(140, 1, 194),
(141, 1, 195),
(142, 1, 196),
(143, 1, 197),
(145, 2, 199),
(146, 1, 200),
(147, 1, 201),
(148, 1, 202),
(149, 1, 203),
(150, 2, 204),
(151, 2, 205),
(152, 1, 206),
(153, 1, 207),
(154, 1, 208),
(155, 1, 209),
(156, 1, 210),
(157, 1, 211),
(158, 1, 212),
(159, 1, 213),
(160, 1, 214),
(161, 1, 215),
(162, 1, 216),
(163, 1, 217),
(164, 1, 218),
(165, 1, 219),
(166, 1, 220),
(167, 1, 221),
(168, 1, 222),
(169, 1, 223),
(170, 1, 224),
(171, 1, 225),
(172, 1, 226),
(173, 1, 227),
(174, 1, 228),
(175, 1, 229),
(176, 1, 230),
(177, 1, 231),
(180, 1, 234),
(182, 1, 236),
(183, 1, 237),
(184, 1, 238),
(185, 1, 239),
(186, 1, 240),
(187, 1, 241),
(188, 1, 242),
(189, 1, 243),
(190, 1, 244),
(191, 1, 245),
(192, 1, 246),
(193, 1, 247),
(194, 1, 248),
(195, 1, 249),
(196, 1, 250),
(197, 1, 251),
(198, 2, 252),
(199, 1, 253),
(200, 1, 254),
(201, 1, 255),
(202, 1, 256),
(203, 1, 257),
(204, 1, 258),
(205, 1, 259),
(206, 1, 260),
(207, 1, 261),
(208, 1, 262),
(209, 1, 263),
(210, 1, 264),
(211, 1, 265),
(212, 1, 266),
(213, 1, 267),
(214, 1, 268),
(215, 1, 269),
(216, 1, 270),
(217, 1, 271),
(218, 1, 272),
(219, 1, 273),
(220, 1, 274),
(221, 1, 275),
(222, 1, 276),
(223, 1, 277),
(224, 1, 278),
(225, 1, 279),
(226, 1, 280),
(227, 1, 281),
(228, 1, 282),
(229, 1, 283),
(230, 1, 284),
(231, 1, 285),
(232, 1, 286),
(233, 1, 287),
(234, 1, 288),
(235, 1, 289),
(236, 1, 290),
(237, 1, 291),
(238, 1, 292),
(239, 1, 293),
(240, 1, 294),
(241, 1, 295),
(242, 1, 296);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`) VALUES
(34, 'Admin4', 'Admin4@gmail.com', 'Admin4', '263bce650e68ab4e23f28263760b9fa5', 20),
(57, 'Joel', 'elprocrakxd123@gmail.com', 'joel', 'c000ccf225950aac2a082a59ac5e57ff', 1),
(58, 'Edgar', 'Edgarhumani02@gmail.com', 'edgar', '6b1d24ff83a319070db95c6c84b9be31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work`
--

CREATE TABLE `work` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `billing_data` text DEFAULT NULL,
  `company_data` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `taxes` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `payment_notes` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `total_tax` decimal(10,2) DEFAULT NULL,
  `total_with_tax` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`idAccount`);

--
-- Indices de la tabla `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`idActivities`),
  ADD KEY `COD_idContact` (`COD_idContact`),
  ADD KEY `COD_idAccount` (`COD_idAccount`);

--
-- Indices de la tabla `allproduct`
--
ALTER TABLE `allproduct`
  ADD PRIMARY KEY (`idAllproduct`);

--
-- Indices de la tabla `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`idapi`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`idCollections`),
  ADD KEY `CODidcustomer` (`CODidcustomer`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`idContacts`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_orden` (`id_orden`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `COD_idrol` (`COD_idrol`);

--
-- Indices de la tabla `eventos1`
--
ALTER TABLE `eventos1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `COD_idrol` (`COD_idrol`);

--
-- Indices de la tabla `family&products`
--
ALTER TABLE `family&products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `COD_idCollections` (`COD_idCollections`);

--
-- Indices de la tabla `files_sales`
--
ALTER TABLE `files_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `COD_idSales` (`COD_idSales`);

--
-- Indices de la tabla `file_sales`
--
ALTER TABLE `file_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maker`
--
ALTER TABLE `maker`
  ADD PRIMARY KEY (`idFabricante`);

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idProduct`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `rolxpermisos`
--
ALTER TABLE `rolxpermisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idRol` (`idRol`);

--
-- Indices de la tabla `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `segment`
--
ALTER TABLE `segment`
  ADD PRIMARY KEY (`idSegment`);

--
-- Indices de la tabla `typecustomer`
--
ALTER TABLE `typecustomer`
  ADD PRIMARY KEY (`idtypecustomer`),
  ADD KEY `COD_idCliente` (`COD_idCliente`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indices de la tabla `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `account`
--
ALTER TABLE `account`
  MODIFY `idAccount` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT de la tabla `activities`
--
ALTER TABLE `activities`
  MODIFY `idActivities` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `allproduct`
--
ALTER TABLE `allproduct`
  MODIFY `idAllproduct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=736;

--
-- AUTO_INCREMENT de la tabla `collections`
--
ALTER TABLE `collections`
  MODIFY `idCollections` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `contacts`
--
ALTER TABLE `contacts`
  MODIFY `idContacts` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT de la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `eventos1`
--
ALTER TABLE `eventos1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `family&products`
--
ALTER TABLE `family&products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `files_sales`
--
ALTER TABLE `files_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `file_sales`
--
ALTER TABLE `file_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT de la tabla `maker`
--
ALTER TABLE `maker`
  MODIFY `idFabricante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `idProduct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `rolxpermisos`
--
ALTER TABLE `rolxpermisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT de la tabla `segment`
--
ALTER TABLE `segment`
  MODIFY `idSegment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `typecustomer`
--
ALTER TABLE `typecustomer`
  MODIFY `idtypecustomer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `work`
--
ALTER TABLE `work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`COD_idContact`) REFERENCES `contacts` (`idContacts`),
  ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`COD_idAccount`) REFERENCES `account` (`idAccount`);

--
-- Filtros para la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  ADD CONSTRAINT `detalle_orden_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `orden_trabajo` (`id`);

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`COD_idrol`) REFERENCES `rol` (`idrol`);

--
-- Filtros para la tabla `eventos1`
--
ALTER TABLE `eventos1`
  ADD CONSTRAINT `eventos1_ibfk_1` FOREIGN KEY (`COD_idrol`) REFERENCES `rol` (`idrol`);

--
-- Filtros para la tabla `files_sales`
--
ALTER TABLE `files_sales`
  ADD CONSTRAINT `files_sales_ibfk_1` FOREIGN KEY (`COD_idSales`) REFERENCES `sale` (`id`);

--
-- Filtros para la tabla `rolxpermisos`
--
ALTER TABLE `rolxpermisos`
  ADD CONSTRAINT `rolxpermisos_ibfk_1` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idrol`);

--
-- Filtros para la tabla `typecustomer`
--
ALTER TABLE `typecustomer`
  ADD CONSTRAINT `typecustomer_ibfk_1` FOREIGN KEY (`COD_idCliente`) REFERENCES `customers` (`idCliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
