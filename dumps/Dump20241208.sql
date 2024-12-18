CREATE DATABASE  IF NOT EXISTS `taller_zaracho` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `taller_zaracho`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: taller_zaracho
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Not dumping tablespaces as no INFORMATION_SCHEMA.FILES table on this server
--

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `nombre_apellido` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(150) default NULL,
  `estado` varchar(2) NOT NULL default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_cliente`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--
-- ORDER BY:  `id_cliente`

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'5959208','Luis Alberto Cabral Samudio','0971667440','Limpio salado','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),(2,'59592087','David Rojas','0971667441','Fernando Mombyry','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_clientes` BEFORE INSERT ON `clientes` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_clientes` BEFORE UPDATE ON `clientes` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `presupuestos`
--

DROP TABLE IF EXISTS `presupuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuestos` (
  `id_presupuesto` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `descripcion` varchar(255) default NULL,
  `total_estimado` decimal(10,2) NOT NULL,
  `mano_obra` decimal(10,2) NOT NULL default '0.00',
  `repuestos` decimal(10,2) NOT NULL default '0.00',
  `estado` enum('PENDIENTE','APROBADO','RECHAZADO') default 'PENDIENTE',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_presupuesto`),
  KEY `serv_pres_fk_idx` (`id_servicio`),
  CONSTRAINT `servicio_presupuesto_fk` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuestos`
--
-- ORDER BY:  `id_presupuesto`

LOCK TABLES `presupuestos` WRITE;
/*!40000 ALTER TABLE `presupuestos` DISABLE KEYS */;
/*!40000 ALTER TABLE `presupuestos` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_presupuestos` BEFORE INSERT ON `presupuestos` FOR EACH ROW BEGIN
    -- Inicializa fch_estado en la fecha actual si no se proporciona un valor
    IF NEW.fch_estado IS NULL THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_presupuestos` BEFORE UPDATE ON `presupuestos` FOR EACH ROW BEGIN
    -- Si el estado cambia, actualiza la fecha de estado
    IF NEW.estado <> OLD.estado THEN
        SET NEW.fch_estado = NOW();
    END IF;
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `estado` varchar(2) NOT NULL default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_rol`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--
-- ORDER BY:  `id_rol`

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador','Acceso total al sistema.','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),(2,'Mecanico','Acceso especifico para mecanicos.','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),(3,'Reporteria','Acceso para reporteria.','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_rol` BEFORE INSERT ON `rol` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_rol` BEFORE UPDATE ON `rol` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `rol_ventanas`
--

DROP TABLE IF EXISTS `rol_ventanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol_ventanas` (
  `id_ventana` varchar(150) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` varchar(2) NOT NULL default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_ventana`,`id_rol`),
  KEY `rol_ventana_fk_idx` (`id_rol`),
  CONSTRAINT `id_rol_fk` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  CONSTRAINT `id_ventana_fk` FOREIGN KEY (`id_ventana`) REFERENCES `ventanas` (`id_ventana`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol_ventanas`
--
-- ORDER BY:  `id_ventana`,`id_rol`

LOCK TABLES `rol_ventanas` WRITE;
/*!40000 ALTER TABLE `rol_ventanas` DISABLE KEYS */;
INSERT INTO `rol_ventanas` VALUES ('gestion_clientes.php',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52','2024-12-07 11:57:48'),('gestion_RolVentanas.php',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_servicios.php',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_usuarios.php',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_vehiculos.php',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('reportes.php',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('reportes.php',3,'AC','2024-12-07 11:58:20','2024-12-07 11:58:20',NULL);
/*!40000 ALTER TABLE `rol_ventanas` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_rol_ventanas` BEFORE INSERT ON `rol_ventanas` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_rol_ventanas` BEFORE UPDATE ON `rol_ventanas` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` varchar(15) default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_servicio`),
  KEY `id_vehiculo_fk_idx` (`id_vehiculo`),
  KEY `clie_serv_fk_idx` (`id_cliente`),
  KEY `usua_serv_fk_idx` (`id_usuario`),
  CONSTRAINT `clie_serv_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `usua_serv_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `vehi_serv_fk` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--
-- ORDER BY:  `id_servicio`

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (1,'Mantenimiento general',1,1,1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL);
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_servicios` BEFORE INSERT ON `servicios` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_servicios` BEFORE UPDATE ON `servicios` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` int(11) default NULL,
  `estado` varchar(2) NOT NULL default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_usuario`),
  UNIQUE KEY `email` (`cedula`),
  KEY `rol` (`rol`),
  CONSTRAINT `ro_usuariosl_fk` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--
-- ORDER BY:  `id_usuario`

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Taller','Zaracho','123456','$2y$10$axe2HoejlVj9Zd6YYOqXIOyEGsb1Wqa7VVe8emLy8DiM7cuJoWrvy',1,'AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_usuarios` BEFORE INSERT ON `usuarios` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_usuarios` BEFORE UPDATE ON `usuarios` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `estado` varchar(2) NOT NULL default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_vehiculo`),
  UNIQUE KEY `placa` (`placa`),
  KEY `clie_vehi_fk_idx` (`id_cliente`),
  CONSTRAINT `clie_vehi_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--
-- ORDER BY:  `id_vehiculo`

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,1,'Fiat','Duna',1987,'ABC123','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),(2,1,'Toyota','Corolla',1997,'AAB123','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL);
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_vehiculos` BEFORE INSERT ON `vehiculos` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_vehiculos` BEFORE UPDATE ON `vehiculos` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Table structure for table `ventanas`
--

DROP TABLE IF EXISTS `ventanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventanas` (
  `id_ventana` varchar(250) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `grupo` varchar(45) NOT NULL default 'Gestiones',
  `estado` varchar(2) NOT NULL default 'AC',
  `fch_estado` datetime default NULL,
  `creado_en` datetime default NULL,
  `actualizado_en` datetime default NULL,
  PRIMARY KEY  (`id_ventana`),
  KEY `grupo_ventana_idx` (`grupo`)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventanas`
--
-- ORDER BY:  `id_ventana`

LOCK TABLES `ventanas` WRITE;
/*!40000 ALTER TABLE `ventanas` DISABLE KEYS */;
INSERT INTO `ventanas` VALUES ('gestion_clientes.php','Clientes','Gestiones','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_RolVentanas.php','Rol de Ventanas','Gestiones','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_servicios.php','Servicios','Gestiones','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_usuarios.php','Usuarios','Gestiones','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('gestion_vehiculos.php','Vehiculos','Gestiones','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL),('reportes.php','Servicios','Reportes','AC','2024-12-07 14:55:52','2024-12-07 14:55:52',NULL);
/*!40000 ALTER TABLE `ventanas` ENABLE KEYS */;
UNLOCK TABLES;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_insert_ventanas` BEFORE INSERT ON `ventanas` FOR EACH ROW BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END */;;
DELIMITER ;
--
-- WARNING: old server version. The following dump may be incomplete.
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `before_update_ventanas` BEFORE UPDATE ON `ventanas` FOR EACH ROW BEGIN
    SET NEW.actualizado_en = NOW();
END */;;
DELIMITER ;

--
-- Dumping routines for database 'taller_zaracho'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-08 16:14:36
