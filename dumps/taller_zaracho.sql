-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 09-12-2024 a las 23:43:52
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taller_zaracho`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `cedula` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nombre_apellido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `direccion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `cedula`, `nombre_apellido`, `telefono`, `direccion`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
(1, '5959208', 'Luis Alberto Cabral Samudio', '0971667440', 'Limpio salado', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
(2, '59592087', 'David Rojas', '0971667441', 'Fernando Mombyry', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL);

--
-- Disparadores `clientes`
--
DELIMITER $$
CREATE TRIGGER `before_insert_clientes` BEFORE INSERT ON `clientes` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_clientes` BEFORE UPDATE ON `clientes` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `id_presupuesto` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `total_estimado` decimal(10,2) NOT NULL,
  `mano_obra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `repuestos` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('PENDIENTE','APROBADO','RECHAZADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'PENDIENTE',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `presupuestos`
--

INSERT INTO `presupuestos` (`id_presupuesto`, `id_servicio`, `descripcion`, `total_estimado`, `mano_obra`, `repuestos`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Mantenimiento Gral', '500000.00', '150000.00', '350000.00', 'APROBADO', '2024-12-09 20:34:45', '2024-12-09 20:33:53', '2024-12-09 20:34:45');

--
-- Disparadores `presupuestos`
--
DELIMITER $$
CREATE TRIGGER `before_insert_presupuestos` BEFORE INSERT ON `presupuestos` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_presupuestos` BEFORE UPDATE ON `presupuestos` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`, `descripcion`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
(1, 'Administrador', 'Acceso total al sistema.', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
(2, 'Mecanico', 'Acceso especifico para mecanicos.', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
(3, 'Reporteria', 'Acceso para reporteria.', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL);

--
-- Disparadores `rol`
--
DELIMITER $$
CREATE TRIGGER `before_insert_rol` BEFORE INSERT ON `rol` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_rol` BEFORE UPDATE ON `rol` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_ventanas`
--

CREATE TABLE `rol_ventanas` (
  `id_ventana` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol_ventanas`
--

INSERT INTO `rol_ventanas` (`id_ventana`, `id_rol`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
('gestion_clientes.php', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', '2024-12-07 11:57:48'),
('gestion_clientes.php', 2, 'AC', '2024-12-09 20:41:57', '2024-12-09 20:41:57', NULL),
('gestion_presupuestos.php', 1, 'AC', '2024-12-09 20:22:16', '2024-12-09 20:22:16', NULL),
('gestion_presupuestos.php', 2, 'AC', '2024-12-09 20:41:44', '2024-12-09 20:41:44', NULL),
('gestion_RolVentanas.php', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_servicios.php', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_servicios.php', 2, 'AC', '2024-12-09 20:41:39', '2024-12-09 20:41:39', NULL),
('gestion_usuarios.php', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_vehiculos.php', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_vehiculos.php', 2, 'AC', '2024-12-09 20:42:05', '2024-12-09 20:42:05', NULL),
('reportePresupuestos.php', 1, 'AC', '2024-12-09 20:18:19', '2024-12-09 20:18:19', NULL),
('reportePresupuestos.php', 3, 'AC', '2024-12-09 20:18:14', '2024-12-09 20:18:14', NULL),
('reportes.php', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('reportes.php', 3, 'AC', '2024-12-07 11:58:20', '2024-12-07 11:58:20', NULL);

--
-- Disparadores `rol_ventanas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_rol_ventanas` BEFORE INSERT ON `rol_ventanas` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_rol_ventanas` BEFORE UPDATE ON `rol_ventanas` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `descripcion`, `id_vehiculo`, `id_cliente`, `id_usuario`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
(1, 'Mantenimiento general', 1, 1, 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL);

--
-- Disparadores `servicios`
--
DELIMITER $$
CREATE TRIGGER `before_insert_servicios` BEFORE INSERT ON `servicios` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_servicios` BEFORE UPDATE ON `servicios` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cedula` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contraseña` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `cedula`, `contraseña`, `rol`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
(1, 'Taller', 'Zaracho', '123456', '$2y$10$axe2HoejlVj9Zd6YYOqXIOyEGsb1Wqa7VVe8emLy8DiM7cuJoWrvy', 1, 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
(2, 'Juan', 'Perez', '1234567', '$2y$10$Ae7tHP7LW6hHy2/4KASe1./3x6KxnQDe71JpiY4CWkwdzYUBCYpkS', 2, 'AC', '2024-12-09 20:40:50', '2024-12-09 20:40:50', NULL);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `before_insert_usuarios` BEFORE INSERT ON `usuarios` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_usuarios` BEFORE UPDATE ON `usuarios` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `marca` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `modelo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `anio` int(11) NOT NULL,
  `placa` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_vehiculo`, `id_cliente`, `marca`, `modelo`, `anio`, `placa`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Fiat', 'Duna', 1987, 'ABC123', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
(2, 1, 'Toyota', 'Corolla', 1997, 'AAB123', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL);

--
-- Disparadores `vehiculos`
--
DELIMITER $$
CREATE TRIGGER `before_insert_vehiculos` BEFORE INSERT ON `vehiculos` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_vehiculos` BEFORE UPDATE ON `vehiculos` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventanas`
--

CREATE TABLE `ventanas` (
  `id_ventana` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `grupo` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Gestiones',
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'AC',
  `fch_estado` datetime DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL,
  `actualizado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ventanas`
--

INSERT INTO `ventanas` (`id_ventana`, `descripcion`, `grupo`, `estado`, `fch_estado`, `creado_en`, `actualizado_en`) VALUES
('gestion_clientes.php', 'Clientes', 'Gestiones', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_presupuestos.php', 'Presupuestos', 'Gestiones', 'AC', '2024-12-09 20:21:56', '2024-12-09 20:21:56', NULL),
('gestion_RolVentanas.php', 'Rol de Ventanas', 'Gestiones', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_servicios.php', 'Servicios', 'Gestiones', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_usuarios.php', 'Usuarios', 'Gestiones', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('gestion_vehiculos.php', 'Vehiculos', 'Gestiones', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL),
('reportePresupuestos.php', 'Presupuestos x Servicio', 'Reportes', 'AC', '2024-12-09 20:17:34', '2024-12-09 20:17:34', NULL),
('reportes.php', 'Servicios', 'Reportes', 'AC', '2024-12-07 14:55:52', '2024-12-07 14:55:52', NULL);

--
-- Disparadores `ventanas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ventanas` BEFORE INSERT ON `ventanas` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_ventanas` BEFORE UPDATE ON `ventanas` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cedula_UNIQUE` (`cedula`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`id_presupuesto`),
  ADD KEY `serv_pres_fk_idx` (`id_servicio`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `rol_ventanas`
--
ALTER TABLE `rol_ventanas`
  ADD PRIMARY KEY (`id_ventana`,`id_rol`),
  ADD KEY `rol_ventana_fk_idx` (`id_rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `id_vehiculo_fk_idx` (`id_vehiculo`),
  ADD KEY `clie_serv_fk_idx` (`id_cliente`),
  ADD KEY `usua_serv_fk_idx` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`cedula`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `clie_vehi_fk_idx` (`id_cliente`);

--
-- Indices de la tabla `ventanas`
--
ALTER TABLE `ventanas`
  ADD PRIMARY KEY (`id_ventana`),
  ADD KEY `grupo_ventana_idx` (`grupo`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `servicio_presupuesto_fk` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`);

--
-- Filtros para la tabla `rol_ventanas`
--
ALTER TABLE `rol_ventanas`
  ADD CONSTRAINT `id_rol_fk` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `id_ventana_fk` FOREIGN KEY (`id_ventana`) REFERENCES `ventanas` (`id_ventana`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `clie_serv_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `usua_serv_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `vehi_serv_fk` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `ro_usuariosl_fk` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `clie_vehi_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
