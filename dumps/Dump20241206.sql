CREATE DATABASE  IF NOT EXISTS `taller_zaracho` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `taller_zaracho`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: taller_zaracho
-- ------------------------------------------------------
-- Server version	9.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `cedula` varchar(50) NOT NULL,
  `nombre_apellido` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `estado` varchar(2) NOT NULL DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `cedula_UNIQUE` (`cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_clientes; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_clientes
BEFORE INSERT ON clientes
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_clientes; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_clientes
BEFORE UPDATE ON clientes
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;
--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'5959208','Luis Alberto Cabral Samudio','0971667440','Limpio salado','AC',NULL,NULL,NULL),(2,'59592087','David Rojas','0971667441','Fernando Mombyry','AC',NULL,NULL,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `estado` varchar(2) NOT NULL DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_rol; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_rol
BEFORE INSERT ON rol
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_rol; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_rol
BEFORE UPDATE ON rol
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`nombre`, `descripcion`) VALUES ('Administrador','Acceso total al sistema.');
INSERT INTO `rol` (`nombre`, `descripcion`) VALUES ('Mecanico','Acceso especifico para mecanicos.');
INSERT INTO `rol` (`nombre`, `descripcion`) VALUES ('Reporteria','Acceso para reporteria.');

--
-- Table structure for table `rol_ventanas`
--

DROP TABLE IF EXISTS `rol_ventanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `rol_ventanas` (
  `id_ventana` varchar(150) NOT NULL,
  `id_rol` int NOT NULL,
  `estado` varchar(2) NOT NULL DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_ventana`,`id_rol`),
  KEY `rol_ventana_fk_idx` (`id_rol`),
  CONSTRAINT `id_rol_fk` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  CONSTRAINT `id_ventana_fk` FOREIGN KEY (`id_ventana`) REFERENCES `ventanas` (`id_ventana`)
) ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_rol_ventanas; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_rol_ventanas
BEFORE INSERT ON rol_ventanas
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_rol_ventanas; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_rol_ventanas
BEFORE UPDATE ON rol_ventanas
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;

--
-- Dumping data for table `rol_ventanas`
--

LOCK TABLES `rol_ventanas` WRITE;
/*!40000 ALTER TABLE `rol_ventanas` DISABLE KEYS */;
INSERT INTO `rol_ventanas` VALUES ('gestion_clientes.php',1,'IN',NULL,NULL,NULL),('gestion_RolVentanas.php',1,'AC',NULL,NULL,NULL),('gestion_servicios.php',1,'AC',NULL,NULL,NULL),('gestion_usuarios.php',1,'AC',NULL,NULL,NULL),('gestion_vehiculos.php',1,'AC',NULL,NULL,NULL),('reportes.php',1,'AC',NULL,NULL,NULL);
/*!40000 ALTER TABLE `rol_ventanas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `servicios` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `id_vehiculo` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_usuario` int NOT NULL,
  `estado` varchar(15) DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_servicio`),
  KEY `id_vehiculo_fk_idx` (`id_vehiculo`),
  KEY `clie_serv_fk_idx` (`id_cliente`),
  KEY `usua_serv_fk_idx` (`id_usuario`),
  CONSTRAINT `clie_serv_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `usua_serv_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `vehi_serv_fk` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_servicios; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_servicios
BEFORE INSERT ON servicios
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_servicios; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_servicios
BEFORE UPDATE ON servicios
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (1,'Mantenimiento general',1,1,1,'AC',NULL,NULL,NULL);
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` int DEFAULT NULL,
  `estado` varchar(2) NOT NULL DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`cedula`),
  KEY `rol` (`rol`),
  CONSTRAINT `ro_usuariosl_fk` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_usuarios; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_usuarios
BEFORE INSERT ON usuarios
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_usuarios; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_usuarios
BEFORE UPDATE ON usuarios
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Taller','Zaracho','123456','$2y$10$axe2HoejlVj9Zd6YYOqXIOyEGsb1Wqa7VVe8emLy8DiM7cuJoWrvy',1,'AC',NULL,NULL,NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id_vehiculo` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` int NOT NULL,
  `placa` varchar(10) NOT NULL,
  `estado` varchar(2) NOT NULL DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_vehiculo`),
  UNIQUE KEY `placa` (`placa`),
  KEY `clie_vehi_fk_idx` (`id_cliente`),
  CONSTRAINT `clie_vehi_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_vehiculos; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_vehiculos
BEFORE INSERT ON vehiculos
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_vehiculos; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_vehiculos
BEFORE UPDATE ON vehiculos
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,1,'Fiat','Duna',1987,'ABC123','AC',NULL,NULL,NULL),(2,1,'Toyota','Corolla',1997,'AAB123','AC',NULL,NULL,NULL);
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventanas`
--

DROP TABLE IF EXISTS `ventanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `ventanas` (
  `id_ventana` varchar(250) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `grupo` varchar(45) NOT NULL DEFAULT 'Gestiones',
  `estado` varchar(2) NOT NULL DEFAULT 'AC',
  `fch_estado` datetime,
  `creado_en` datetime,
  `actualizado_en` datetime,
  PRIMARY KEY (`id_ventana`),
  KEY `grupo_ventana_idx` (`grupo`)
) ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER //

DROP TRIGGER IF EXISTS before_insert_ventanas; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_insert_ventanas
BEFORE INSERT ON ventanas
FOR EACH ROW
BEGIN
    SET NEW.fch_estado = NOW();
    SET NEW.creado_en = NOW();
END;
//
DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS before_update_ventanas; //  -- Elimina el trigger si ya existe

CREATE TRIGGER before_update_ventanas
BEFORE UPDATE ON ventanas
FOR EACH ROW
BEGIN
    SET NEW.actualizado_en = NOW();
END;
//
DELIMITER ;

--
-- Dumping data for table `ventanas`
--

LOCK TABLES `ventanas` WRITE;
/*!40000 ALTER TABLE `ventanas` DISABLE KEYS */;
INSERT INTO `ventanas` VALUES ('gestion_clientes.php','Clientes','Gestiones','AC',NULL,NULL,NULL),('gestion_RolVentanas.php','Rol de Ventanas','Gestiones','AC',NULL,NULL,NULL),('gestion_servicios.php','Servicios','Gestiones','AC',NULL,NULL,NULL),('gestion_usuarios.php','Usuarios','Gestiones','AC',NULL,NULL,NULL),('gestion_vehiculos.php','Vehiculos','Gestiones','AC',NULL,NULL,NULL),('reportes.php','Servicios','Reportes','AC',NULL,NULL,NULL);
/*!40000 ALTER TABLE `ventanas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'taller_zaracho'
--

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

-- Dump completed on 2024-12-06 17:23:03
