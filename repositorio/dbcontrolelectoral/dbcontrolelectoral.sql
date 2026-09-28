/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.18-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: dbcontrolelectoral
-- ------------------------------------------------------
-- Server version	10.11.18-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `canton`
--

DROP TABLE IF EXISTS `canton`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `canton` (
  `idcanton` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `idprovincia` int(11) NOT NULL,
  PRIMARY KEY (`idcanton`),
  KEY `fk_canton_provincia` (`idprovincia`),
  CONSTRAINT `fk_canton_provincia` FOREIGN KEY (`idprovincia`) REFERENCES `provincia` (`idprovincia`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canton`
--

LOCK TABLES `canton` WRITE;
/*!40000 ALTER TABLE `canton` DISABLE KEYS */;
INSERT INTO `canton` VALUES
(1,'Quito',1),
(2,'Rumiñahui',1),
(3,'Guayaquil',2),
(4,'Samborondón',2),
(5,'Portoviejo',3),
(6,'Manta',3),
(7,'Cuenca',4),
(8,'Esmeraldas',5),
(9,'Atacames',5);
/*!40000 ALTER TABLE `canton` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dignidad`
--

DROP TABLE IF EXISTS `dignidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dignidad` (
  `iddignidad` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) NOT NULL,
  `idtipodignidad` int(11) NOT NULL,
  PRIMARY KEY (`iddignidad`),
  KEY `idx_dignidad_persona` (`idpersona`),
  KEY `idx_dignidad_tipo` (`idtipodignidad`),
  CONSTRAINT `fk_dignidad_persona` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON UPDATE CASCADE,
  CONSTRAINT `fk_dignidad_tipodignidad` FOREIGN KEY (`idtipodignidad`) REFERENCES `tipodignidad` (`idtipodignidad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dignidad`
--

LOCK TABLES `dignidad` WRITE;
/*!40000 ALTER TABLE `dignidad` DISABLE KEYS */;
INSERT INTO `dignidad` VALUES
(1,5,5),
(2,2,5),
(3,6,5),
(5,1,5);
/*!40000 ALTER TABLE `dignidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meza`
--

DROP TABLE IF EXISTS `meza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `meza` (
  `idmeza` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `idsexo` int(11) NOT NULL,
  `idrecintoelectoral` int(11) NOT NULL,
  PRIMARY KEY (`idmeza`),
  KEY `idx_meza_sexo` (`idsexo`),
  KEY `idx_meza_recinto` (`idrecintoelectoral`),
  CONSTRAINT `fk_meza_recinto` FOREIGN KEY (`idrecintoelectoral`) REFERENCES `recintoelectoral` (`idrecintoelectoral`) ON UPDATE CASCADE,
  CONSTRAINT `fk_meza_sexo` FOREIGN KEY (`idsexo`) REFERENCES `sexo` (`idsexo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meza`
--

LOCK TABLES `meza` WRITE;
/*!40000 ALTER TABLE `meza` DISABLE KEYS */;
INSERT INTO `meza` VALUES
(1,1,1,1),
(2,1,2,1),
(3,3,1,1),
(4,2,2,1),
(5,1,1,2),
(6,1,2,2),
(7,1,1,3),
(8,1,2,3),
(9,1,1,4),
(10,1,2,4),
(11,1,1,5),
(12,1,2,6);
/*!40000 ALTER TABLE `meza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mezadignidad`
--

DROP TABLE IF EXISTS `mezadignidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mezadignidad` (
  `idmezadignidad` int(11) NOT NULL AUTO_INCREMENT,
  `idmeza` int(11) NOT NULL,
  `iddignidad` int(11) NOT NULL,
  `numeropapeleta` int(11) NOT NULL COMMENT 'Cantidad de papeletas que fueron contadas para esta dignidad en la mesa',
  PRIMARY KEY (`idmezadignidad`),
  KEY `idx_mezadignidad_meza` (`idmeza`),
  KEY `idx_mezadignidad_dignidad` (`iddignidad`),
  CONSTRAINT `fk_mezadignidad_dignidad` FOREIGN KEY (`iddignidad`) REFERENCES `dignidad` (`iddignidad`) ON UPDATE CASCADE,
  CONSTRAINT `fk_mezadignidad_meza` FOREIGN KEY (`idmeza`) REFERENCES `meza` (`idmeza`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mezadignidad`
--

LOCK TABLES `mezadignidad` WRITE;
/*!40000 ALTER TABLE `mezadignidad` DISABLE KEYS */;
INSERT INTO `mezadignidad` VALUES
(1,1,1,101),
(2,1,2,201),
(3,2,1,101),
(4,2,2,201),
(5,3,1,101),
(6,3,3,202),
(8,11,3,0);
/*!40000 ALTER TABLE `mezadignidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parroquia`
--

DROP TABLE IF EXISTS `parroquia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parroquia` (
  `idparroquia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `idcanton` int(11) NOT NULL,
  PRIMARY KEY (`idparroquia`),
  KEY `fk_parroquia_canton_idx` (`idcanton`),
  CONSTRAINT `fk_parroquia_canton` FOREIGN KEY (`idcanton`) REFERENCES `canton` (`idcanton`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parroquia`
--

LOCK TABLES `parroquia` WRITE;
/*!40000 ALTER TABLE `parroquia` DISABLE KEYS */;
INSERT INTO `parroquia` VALUES
(1,'Iñaquito',1),
(2,'Mariscal Sucre',1),
(3,'Cumbayá',1),
(4,'Tarqui',3),
(5,'Ximena',3),
(6,'Manta',6),
(7,'El Sagrario',7),
(8,'Atacames',9);
/*!40000 ALTER TABLE `parroquia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `fechanacimiento` date NOT NULL,
  `idsexo` int(11) NOT NULL,
  PRIMARY KEY (`idpersona`),
  KEY `fk_persona_sexo` (`idsexo`),
  CONSTRAINT `fk_persona_sexo` FOREIGN KEY (`idsexo`) REFERENCES `sexo` (`idsexo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES
(1,'0801305731','Kleber Fernando','Arroyo Quiñónez','1995-04-12',1),
(2,'0802887364','Raúl Clemente','Ulloa de Souza','1998-10-25',1),
(4,'0802580613','Franklin Xavier','Francis Quinde','1982-03-27',1),
(5,'0801786310','Maria Roberta','Ortiz Zambrano','2000-01-01',2),
(6,'0802022004','Rina Asunción','Campain Brambilla','2000-01-01',2);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincia`
--

DROP TABLE IF EXISTS `provincia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `provincia` (
  `idprovincia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idprovincia`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincia`
--

LOCK TABLES `provincia` WRITE;
/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
INSERT INTO `provincia` VALUES
(1,'Pichincha'),
(2,'Guayas'),
(3,'Manabí'),
(4,'Azuay'),
(5,'Esmeraldas');
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recintoelectoral`
--

DROP TABLE IF EXISTS `recintoelectoral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recintoelectoral` (
  `idrecintoelectoral` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `idzona` int(11) NOT NULL,
  `numeroelectores` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idrecintoelectoral`),
  KEY `idx_recinto_zona` (`idzona`),
  CONSTRAINT `fk_recinto_zona` FOREIGN KEY (`idzona`) REFERENCES `zona` (`idzona`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recintoelectoral`
--

LOCK TABLES `recintoelectoral` WRITE;
/*!40000 ALTER TABLE `recintoelectoral` DISABLE KEYS */;
INSERT INTO `recintoelectoral` VALUES
(1,'Colegio Benalcázar',1,3500),
(2,'Unidad Educativa Municipal Eugenio Espejo',1,2800),
(3,'Colegio San Gabriel',2,4100),
(4,'Colegio Manuela Cañizares',3,3250),
(5,'Unidad Educativa Cumbayá',4,1950),
(6,'Universidad Católica Santiago de Guayaquil',5,5400),
(7,'Facultad de Jurisprudencia Universidad de Guayaquil',6,4800),
(8,'Colegio Técnico Febres Cordero',7,2600),
(9,'Unidad Educativa Fiscal Manta',8,3100),
(10,'Unidad Educativa Benigno Malo',9,3900),
(11,'Colegio Nacional Atacames',10,2150);
/*!40000 ALTER TABLE `recintoelectoral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rolusuario`
--

DROP TABLE IF EXISTS `rolusuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rolusuario` (
  `idrolusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idrolusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rolusuario`
--

LOCK TABLES `rolusuario` WRITE;
/*!40000 ALTER TABLE `rolusuario` DISABLE KEYS */;
INSERT INTO `rolusuario` VALUES
(1,'Administrador'),
(2,'Digitador'),
(3,'Supervisor'),
(4,'Auditor'),
(6,'Cordinador'),
(7,'Veedor');
/*!40000 ALTER TABLE `rolusuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sexo`
--

DROP TABLE IF EXISTS `sexo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sexo` (
  `idsexo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`idsexo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sexo`
--

LOCK TABLES `sexo` WRITE;
/*!40000 ALTER TABLE `sexo` DISABLE KEYS */;
INSERT INTO `sexo` VALUES
(1,'Masculino'),
(2,'Femenino');
/*!40000 ALTER TABLE `sexo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipodignidad`
--

DROP TABLE IF EXISTS `tipodignidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipodignidad` (
  `idtipodignidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`idtipodignidad`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipodignidad`
--

LOCK TABLES `tipodignidad` WRITE;
/*!40000 ALTER TABLE `tipodignidad` DISABLE KEYS */;
INSERT INTO `tipodignidad` VALUES
(1,'Presidente y Vicepresidente de la República'),
(2,'Asambleísta Nacional'),
(3,'Asambleísta Provincial'),
(4,'Prefecto y Viceprefecto Provincial'),
(5,'Alcalde Municipal / Distrital'),
(6,'Concejal Urbano'),
(7,'Concejal Rural'),
(8,'Vocal de Junta Parroquial');
/*!40000 ALTER TABLE `tipodignidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `idrolusuario` int(11) NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `idx_usuario_unique` (`usuario`),
  KEY `fk_usuario_persona_idx` (`idpersona`),
  KEY `fk_usuario_rolusuario_idx` (`idrolusuario`),
  CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_rolusuario` FOREIGN KEY (`idrolusuario`) REFERENCES `rolusuario` (`idrolusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES
(1,1,'admin','admin123',1),
(2,2,'mzambrano','voto2026',2);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona`
--

DROP TABLE IF EXISTS `zona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona` (
  `idzona` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `idparroquia` int(11) NOT NULL,
  PRIMARY KEY (`idzona`),
  KEY `idx_zona_parroquia` (`idparroquia`),
  CONSTRAINT `fk_zona_parroquia` FOREIGN KEY (`idparroquia`) REFERENCES `parroquia` (`idparroquia`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona`
--

LOCK TABLES `zona` WRITE;
/*!40000 ALTER TABLE `zona` DISABLE KEYS */;
INSERT INTO `zona` VALUES
(1,'Zona 1 - Centro',1),
(2,'Zona 2 - Bellavista',1),
(3,'Zona 1 - La Mariscal',2),
(4,'Zona 1 - Central',3),
(5,'Zona 1 - Kennedy',4),
(6,'Zona 2 - Urdesa',4),
(7,'Zona 1 - Puerto Hondo',5),
(8,'Zona 1 - Centro Puerto',6),
(9,'Zona 1 - Centro Histórico',7),
(10,'Zona 1 - Playa Central',8);
/*!40000 ALTER TABLE `zona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dbcontrolelectoral'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-28  6:57:23
