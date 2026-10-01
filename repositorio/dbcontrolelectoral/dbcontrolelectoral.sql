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
-- Current Database: `dbcontrolelectoral`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dbcontrolelectoral` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `dbcontrolelectoral`;

--
-- Table structure for table `acta`
--

DROP TABLE IF EXISTS `acta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acta` (
  `idacta` int(11) NOT NULL AUTO_INCREMENT,
  `idmeza` int(11) NOT NULL,
  `totalpapeleta` int(11) NOT NULL DEFAULT 0,
  `totalblancos` int(11) NOT NULL DEFAULT 0,
  `totalnulos` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idacta`),
  KEY `idx_acta_meza` (`idmeza`),
  CONSTRAINT `fk_acta_meza` FOREIGN KEY (`idmeza`) REFERENCES `meza` (`idmeza`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acta`
--

LOCK TABLES `acta` WRITE;
/*!40000 ALTER TABLE `acta` DISABLE KEYS */;
INSERT INTO `acta` VALUES
(1,14,350,12,8),
(2,15,340,5,15);
/*!40000 ALTER TABLE `acta` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canton`
--

LOCK TABLES `canton` WRITE;
/*!40000 ALTER TABLE `canton` DISABLE KEYS */;
INSERT INTO `canton` VALUES
(8,'ESMERALDAS',5),
(9,'ATACAMES',5),
(11,'SAN LORENZO',5),
(12,'MUISNE',5),
(13,'ELOY ALFARO',5),
(14,'RIOVERDE',5),
(16,'QUININDE',5);
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
-- Table structure for table `dignidadacta`
--

DROP TABLE IF EXISTS `dignidadacta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dignidadacta` (
  `iddignidadacta` int(11) NOT NULL AUTO_INCREMENT,
  `idacta` int(11) NOT NULL,
  `iddignidad` int(11) NOT NULL,
  `votacion` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`iddignidadacta`),
  KEY `idx_dignidadacta_acta` (`idacta`),
  KEY `idx_dignidadacta_dignidad` (`iddignidad`),
  CONSTRAINT `fk_dignidadacta_acta` FOREIGN KEY (`idacta`) REFERENCES `acta` (`idacta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dignidadacta_dignidad` FOREIGN KEY (`iddignidad`) REFERENCES `dignidad` (`iddignidad`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dignidadacta`
--

LOCK TABLES `dignidadacta` WRITE;
/*!40000 ALTER TABLE `dignidadacta` DISABLE KEYS */;
INSERT INTO `dignidadacta` VALUES
(1,1,5,142),
(2,1,2,188),
(3,1,1,200);
/*!40000 ALTER TABLE `dignidadacta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distrito`
--

DROP TABLE IF EXISTS `distrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `distrito` (
  `iddistrito` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`iddistrito`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distrito`
--

LOCK TABLES `distrito` WRITE;
/*!40000 ALTER TABLE `distrito` DISABLE KEYS */;
INSERT INTO `distrito` VALUES
(1,'Norte'),
(2,'Sur');
/*!40000 ALTER TABLE `distrito` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=506 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meza`
--

LOCK TABLES `meza` WRITE;
/*!40000 ALTER TABLE `meza` DISABLE KEYS */;
INSERT INTO `meza` VALUES
(14,1,2,14),
(15,2,2,14),
(16,1,1,14),
(17,2,1,14),
(20,34,2,15),
(21,35,2,15),
(22,36,2,15),
(23,30,1,15),
(24,30,1,15),
(25,31,1,15),
(26,32,1,15),
(27,1,2,16),
(28,2,2,16),
(29,3,2,16),
(30,4,2,16),
(31,5,2,16),
(32,6,2,16),
(33,7,2,16),
(34,8,2,16),
(35,9,2,16),
(36,10,2,16),
(37,11,2,16),
(38,12,2,16),
(39,13,2,16),
(40,1,1,16),
(41,2,1,16),
(42,3,1,16),
(43,4,1,16),
(44,5,1,16),
(45,6,1,16),
(46,7,1,16),
(47,8,1,16),
(48,9,1,16),
(49,14,2,17),
(50,15,2,17),
(51,16,2,17),
(52,17,2,17),
(53,19,2,17),
(54,18,2,17),
(55,20,2,17),
(56,21,2,17),
(57,22,2,17),
(58,10,1,17),
(59,11,1,17),
(60,12,1,17),
(61,13,1,17),
(62,14,1,17),
(63,15,1,17),
(64,16,1,17),
(65,17,1,17),
(66,18,1,17),
(67,37,2,18),
(68,38,2,18),
(69,39,2,18),
(70,40,2,18),
(71,41,2,18),
(72,42,2,18),
(73,33,1,18),
(74,34,1,18),
(75,35,1,18),
(76,36,1,18),
(77,37,1,18),
(78,38,1,18),
(79,27,2,19),
(80,28,2,19),
(81,29,2,19),
(82,30,2,19),
(83,31,2,19),
(84,32,2,19),
(85,33,2,19),
(86,23,1,19),
(87,24,1,19),
(88,25,1,19),
(89,26,1,19),
(90,27,1,19),
(91,28,1,19),
(92,29,1,19),
(93,47,2,20),
(94,48,2,20),
(95,49,2,20),
(96,50,2,20),
(97,51,2,20),
(98,43,1,20),
(99,44,1,20),
(101,43,2,21),
(102,44,2,21),
(103,45,2,21),
(104,46,2,21),
(105,39,1,21),
(106,40,1,21),
(107,41,1,21),
(109,42,1,21),
(110,23,2,22),
(111,24,2,22),
(112,25,2,22),
(113,26,2,22),
(114,19,1,22),
(115,20,1,22),
(116,21,1,22),
(117,22,1,22),
(118,1,2,23),
(119,2,2,23),
(120,3,2,23),
(121,4,2,23),
(122,5,2,23),
(123,6,2,23),
(124,1,1,23),
(125,2,1,23),
(126,3,1,23),
(127,4,1,23),
(128,5,1,23),
(129,1,2,24),
(130,2,2,24),
(131,3,2,24),
(132,4,2,24),
(133,5,2,24),
(134,6,2,24),
(135,7,2,24),
(136,8,2,24),
(137,9,2,24),
(138,1,1,24),
(139,2,1,24),
(140,3,1,24),
(141,4,1,24),
(142,10,2,25),
(143,11,2,25),
(144,12,2,25),
(145,13,2,25),
(146,14,2,25),
(147,15,2,25),
(148,16,2,25),
(149,17,2,25),
(150,5,1,25),
(151,6,1,25),
(152,7,1,25),
(153,8,1,25),
(154,9,1,25),
(155,10,1,25),
(156,11,1,25),
(157,18,2,26),
(158,19,2,26),
(159,20,2,26),
(160,21,2,26),
(161,22,2,26),
(162,23,2,26),
(163,12,1,26),
(164,13,1,26),
(165,14,1,26),
(166,15,1,26),
(167,16,1,26),
(168,17,1,26),
(169,18,1,26),
(170,24,2,27),
(171,25,2,27),
(172,26,2,27),
(173,28,2,27),
(174,27,2,27),
(175,29,2,27),
(176,30,2,27),
(177,31,2,27),
(178,32,2,27),
(179,33,2,27),
(180,19,1,27),
(181,20,1,27),
(182,21,1,27),
(183,22,1,27),
(184,23,1,27),
(185,24,1,27),
(186,25,1,27),
(187,26,1,27),
(188,27,1,27),
(189,28,1,27),
(190,29,1,27),
(191,1,2,28),
(192,2,2,28),
(193,3,2,28),
(194,4,2,28),
(195,5,2,28),
(196,1,1,28),
(197,2,1,28),
(198,3,1,28),
(199,4,1,28),
(200,5,1,28),
(201,1,2,29),
(202,2,2,29),
(203,3,2,29),
(204,1,1,29),
(205,2,1,29),
(206,3,1,28),
(207,9,2,30),
(208,10,2,30),
(209,11,2,30),
(210,12,2,30),
(211,13,2,30),
(212,14,2,30),
(213,15,2,30),
(214,16,2,30),
(215,17,2,30),
(216,18,2,30),
(217,19,2,30),
(218,20,2,30),
(219,21,2,30),
(220,9,1,30),
(221,10,1,30),
(222,11,1,30),
(223,11,1,30),
(224,12,1,30),
(225,13,1,30),
(226,14,1,30),
(227,15,1,30),
(228,16,1,30),
(229,17,1,30),
(230,18,1,30),
(231,19,1,30),
(232,20,1,30),
(233,3,2,31),
(234,4,2,31),
(235,5,2,31),
(236,6,2,31),
(237,7,2,31),
(238,8,2,31),
(239,3,1,31),
(240,4,1,31),
(241,5,1,31),
(242,6,1,31),
(243,7,1,31),
(244,8,1,31),
(245,58,2,32),
(246,59,2,32),
(247,60,2,32),
(248,61,2,32),
(249,62,2,32),
(250,63,2,32),
(251,64,2,32),
(252,65,2,32),
(253,66,2,32),
(254,67,2,32),
(255,57,1,32),
(256,58,1,32),
(257,59,1,32),
(258,60,1,32),
(259,47,2,33),
(260,48,2,33),
(261,49,2,33),
(262,50,2,33),
(263,51,2,33),
(264,46,1,33),
(265,47,1,33),
(266,48,1,33),
(267,49,1,33),
(268,50,1,33),
(269,52,2,34),
(270,53,2,34),
(271,54,2,34),
(272,55,2,34),
(273,56,2,34),
(274,57,2,34),
(275,51,1,34),
(276,52,1,34),
(277,53,1,34),
(278,54,1,34),
(279,55,1,34),
(280,56,1,34),
(281,1,2,35),
(282,2,2,35),
(283,1,1,35),
(284,2,1,35),
(285,22,2,36),
(286,23,2,36),
(287,24,2,36),
(288,25,2,36),
(289,26,2,36),
(290,27,2,36),
(291,28,2,36),
(292,29,2,36),
(293,30,2,36),
(294,21,1,36),
(295,22,1,36),
(296,23,1,36),
(297,24,1,36),
(298,25,1,36),
(299,26,1,36),
(300,27,1,36),
(301,28,1,36),
(302,29,1,36),
(303,31,2,37),
(304,32,2,37),
(305,33,2,37),
(306,34,2,37),
(307,35,2,37),
(308,36,2,37),
(309,37,2,37),
(310,38,2,37),
(311,39,2,37),
(312,30,1,37),
(313,31,1,37),
(314,32,1,37),
(315,33,1,37),
(316,34,1,37),
(317,35,1,37),
(318,36,1,37),
(319,37,1,37),
(320,38,1,37),
(321,43,2,38),
(322,44,2,38),
(323,45,2,38),
(324,46,2,38),
(325,42,1,38),
(326,43,1,38),
(327,44,1,38),
(328,45,1,38),
(329,40,2,39),
(330,41,2,39),
(331,42,2,39),
(332,39,1,39),
(333,40,1,39),
(334,41,1,39),
(335,1,2,40),
(336,1,1,40),
(337,4,2,41),
(338,5,2,41),
(339,6,2,41),
(340,7,2,41),
(341,8,2,41),
(342,9,2,41),
(343,10,2,41),
(344,4,1,41),
(345,5,1,41),
(346,6,1,41),
(347,7,1,41),
(348,8,1,41),
(349,9,1,41),
(350,10,1,41),
(351,1,2,42),
(352,2,2,42),
(353,3,2,42),
(354,1,1,42),
(355,2,1,42),
(356,3,1,42),
(357,11,2,43),
(358,12,2,43),
(359,13,2,43),
(360,11,1,43),
(361,12,1,43),
(362,13,1,43),
(363,1,2,44),
(364,2,2,44),
(365,3,2,44),
(366,1,1,44),
(367,2,1,44),
(368,3,1,44),
(369,4,1,44),
(370,1,2,45),
(371,1,1,45),
(372,1,2,46),
(373,2,2,46),
(374,3,2,46),
(375,1,1,46),
(376,2,1,46),
(377,3,1,46),
(378,4,1,46),
(379,4,2,47),
(380,5,2,47),
(381,6,2,47),
(382,7,2,47),
(383,5,1,47),
(384,6,1,47),
(385,7,1,47),
(386,1,2,48),
(387,2,2,48),
(388,3,2,48),
(389,4,2,48),
(390,1,1,48),
(391,2,1,48),
(392,3,1,48),
(393,4,1,48),
(394,5,1,48),
(395,5,2,49),
(396,6,2,49),
(397,7,2,49),
(398,8,2,49),
(399,9,2,49),
(400,6,1,49),
(401,7,1,49),
(402,1,2,50),
(403,2,2,50),
(404,1,1,50),
(405,1,2,51),
(406,2,2,51),
(407,3,2,51),
(408,4,2,51),
(409,1,1,51),
(410,2,1,51),
(411,3,1,51),
(412,4,1,51),
(413,1,2,52),
(414,2,2,52),
(415,3,2,52),
(416,4,2,52),
(417,5,2,52),
(418,6,2,52),
(419,7,2,52),
(420,8,2,52),
(421,1,1,52),
(422,2,1,52),
(423,3,1,52),
(424,4,1,52),
(425,5,1,52),
(426,6,1,52),
(427,7,1,52),
(428,8,1,52),
(429,9,2,53),
(430,10,2,53),
(431,11,2,53),
(432,12,2,53),
(433,13,2,53),
(434,14,2,53),
(435,15,2,53),
(436,16,2,53),
(437,17,2,53),
(438,18,2,53),
(439,9,1,53),
(440,10,1,53),
(441,11,1,53),
(442,12,1,53),
(443,13,1,53),
(444,14,1,53),
(445,15,1,53),
(446,16,1,53),
(447,17,1,53),
(448,1,2,54),
(449,2,2,54),
(450,3,2,54),
(451,4,2,54),
(452,1,1,54),
(453,2,1,54),
(454,3,1,54),
(455,4,1,54),
(456,1,2,55),
(457,2,2,55),
(458,3,2,55),
(459,4,2,55),
(460,5,2,55),
(461,6,2,55),
(462,1,1,55),
(463,2,1,55),
(464,3,1,55),
(465,4,1,55),
(466,5,1,55),
(467,6,1,55),
(468,1,2,56),
(469,2,2,56),
(470,3,2,56),
(471,4,2,56),
(472,5,2,56),
(473,6,2,56),
(474,7,2,56),
(475,8,2,56),
(476,9,2,56),
(477,10,2,56),
(478,1,1,56),
(479,2,1,56),
(480,3,1,56),
(481,4,1,56),
(482,5,1,56),
(483,6,1,56),
(484,7,1,56),
(485,8,1,56),
(486,9,1,56),
(487,10,1,56),
(488,11,2,57),
(489,12,2,57),
(490,13,2,57),
(491,14,2,57),
(492,15,2,57),
(493,16,2,57),
(494,17,2,57),
(495,11,1,57),
(496,12,1,57),
(497,13,1,57),
(498,14,1,57),
(499,15,1,57),
(500,16,1,57),
(501,17,1,57),
(502,1,2,58),
(503,2,2,58),
(504,3,2,58),
(505,4,2,58);
/*!40000 ALTER TABLE `meza` ENABLE KEYS */;
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
  `idtipoparroquia` int(11) DEFAULT NULL,
  `iddistrito` int(11) DEFAULT NULL,
  PRIMARY KEY (`idparroquia`),
  KEY `fk_parroquia_canton_idx` (`idcanton`),
  KEY `fk_parroquia_tipoparroquia_idx` (`idtipoparroquia`),
  KEY `fk_parroquia_distrito_idx` (`iddistrito`),
  CONSTRAINT `fk_parroquia_canton` FOREIGN KEY (`idcanton`) REFERENCES `canton` (`idcanton`) ON UPDATE CASCADE,
  CONSTRAINT `fk_parroquia_distrito` FOREIGN KEY (`iddistrito`) REFERENCES `distrito` (`iddistrito`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_parroquia_tipoparroquia` FOREIGN KEY (`idtipoparroquia`) REFERENCES `tipoparroquia` (`idtipoparroquia`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parroquia`
--

LOCK TABLES `parroquia` WRITE;
/*!40000 ALTER TABLE `parroquia` DISABLE KEYS */;
INSERT INTO `parroquia` VALUES
(10,'ESMERALDAS',8,NULL,NULL),
(11,'5 DE AGOSTO',8,1,2),
(12,'BARTOLOME RUIZ',8,1,1),
(13,'LUIS TELLO',8,NULL,NULL),
(14,'SIMÓN PLATA TORRES',8,NULL,NULL),
(15,'VUELTA LARGA',8,NULL,NULL),
(16,'TACHINA',8,NULL,NULL),
(17,'TABIAZO',8,NULL,NULL),
(18,'SAN MATEO',8,NULL,NULL),
(19,'MAJUA',8,NULL,NULL),
(20,'CARLOS CONCHA',8,NULL,NULL),
(21,'CHINCA',8,NULL,NULL),
(22,'CAMARONES',8,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recintoelectoral`
--

LOCK TABLES `recintoelectoral` WRITE;
/*!40000 ALTER TABLE `recintoelectoral` DISABLE KEYS */;
INSERT INTO `recintoelectoral` VALUES
(14,'UNIDAD EDUCATIVA FISCAL \"5 DE AGOSTO\"',14,1258),
(15,'UNIDAD EDUCATIVA FISCAL MARGARITA CORTEZ NRO.2',14,2100),
(16,'UNIDAD EDUCATIVA PARTICULAR LA INMACULADA',14,7700),
(17,'UNIDAD EDUCATIVA FISCAL \"LUIS VARGAS TORRES\"',14,6300),
(18,'UNIDAD EDUCATIVA FISCAL NELSON ORTIZ ESTAFANUTO',14,4200),
(19,'UNIDAD EDUCATIVA FISCAL MARGARITA CORTEZ',14,4900),
(20,'ESCUELA FISCAL \"DR. JOSE MARÍA VELASCO IBARRA\"',14,2412),
(21,'ESCUELA FISCAL ESMERALDAS LIBRE',14,2800),
(22,'ESCUELA SAN JOSE OBRERO',14,2800),
(23,'UNIDAD EDUCATIVA FISCAL LUIS TELLO',13,3511),
(24,'UNIDAD EDUCATIVA FISCOMISIONAL \"CRISTO REY\"',15,4550),
(25,'UNIDAD EDUCATIVA \"DR. LUIS PRADO VITERI\"',15,5250),
(26,'UNIDAD EDUCATIVA MANUEL NIETO CADENA',15,4550),
(27,'UNIVERSIDAD TECNICA LUIS VARGAS TORRES',15,7000),
(28,'UNIDAD EDUCATIVA DEL MILENIUM CHINCA',16,3308),
(29,'UNIDAD EDUCATIVA FISCAL 10 DE SEPTIEMBRE',17,1763),
(30,'U.E. FISCOMISIONAL \"SAGRADO CORAZÓN\"',18,8750),
(31,'ESCUELA FISCAL\"HISPANO AMERICA NRO. 2\"',18,4200),
(32,'UNIDAD EDUCATIVA PARTICULAR LUZ Y LIBERTAD',18,4720),
(33,'UNIDAD EDUCATIVA FISCAL ELOY ALFARO',18,3500),
(34,'ESCUELA FISCAL \"JUAN MONTALVO\"',18,4200),
(35,'UNIDAD EDUCATIVA \"21 DE SEPTIEMBRE\"',18,1400),
(36,'U.E. FISCOMISIONAL DON BOSCO',18,6300),
(37,'ESCUELA FISCOMISIONAL DON BOSCO',18,6300),
(38,'UNIDAD EDUCATIVA FISCAL ELOY ALFARO NRO. 2',18,2800),
(39,'UNIDAD EDUCATIVA MONSEÑOR LEONIDAS PROAÑO',18,2100),
(40,'ESCUELA FISCAL LEONIDAS GRUEZO GEORGE',19,161),
(41,'UNIDAD EDUCATIVA FISCOMISIONAL \"NUEVO ECUADOR\"',20,4900),
(42,'ESCUELA FISCAL \"GUAYAQUIL\"',20,2100),
(43,'ESCUELA FISCAL MODESTO ELIAS MENDOZA MOREIRA',20,1651),
(44,'ESCUELA GENERAL BASICA \"GUAYAS\"',21,2190),
(45,'U.E. LIBERTAD DE TIMBRE',22,563),
(46,'UNIDAD EDUCATIVA SAN MATEO NRO. 2',24,2450),
(47,'UNIDAD EDUCATIVA SAN MATEO',24,2262),
(48,'ESCUELA  FISCAL  CONSUELO BENAVIDES CEVALLOS',25,3150),
(49,'ESCUELA FISCAL HOMERO LOPEZ SAUD',25,2135),
(50,'UNIDA EDUCATIVA FISCOMISIONAL \"SAN DANIEL COMBONI\"',26,946),
(51,'UNIDAD EDUCATIVA FISCOMISIONAL MARIA AUXILIADORA',27,2559),
(52,'ESCUELA FISCOMISIONAL \"NUESTRA SEÑORA DE LORETO\"',28,5600),
(53,'UNIDAD EDUCATIVA FISCAL ALFONSO QUIÑONEZ GEORGE',28,6226),
(54,'UNIDAD EDUCATIVA FISCAL TABIAZO',29,2717),
(55,'UNIDAD EDUCATIVA PEDRO CORNELIO DROUET',30,4096),
(56,'UNIDAD EDUCATIVA \"LEON FEBRES CORDERO\"',31,7000),
(57,'ESCUELA CESAR NAVIL ESTUPIÑAN BASS',31,4485),
(58,'UNIDAD EDUCATIVA \"DR. FRANKLIN TELLO\"',32,2695);
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
-- Table structure for table `tipoparroquia`
--

DROP TABLE IF EXISTS `tipoparroquia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipoparroquia` (
  `idtipoparroquia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idtipoparroquia`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoparroquia`
--

LOCK TABLES `tipoparroquia` WRITE;
/*!40000 ALTER TABLE `tipoparroquia` DISABLE KEYS */;
INSERT INTO `tipoparroquia` VALUES
(1,'Urbana'),
(2,'Rural');
/*!40000 ALTER TABLE `tipoparroquia` ENABLE KEYS */;
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
(1,4,'FRANCISQ','0802580613',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona`
--

LOCK TABLES `zona` WRITE;
/*!40000 ALTER TABLE `zona` DISABLE KEYS */;
INSERT INTO `zona` VALUES
(12,'PROPICIA 1',11),
(13,'PROPICIA 4',11),
(14,'5 DE AGOSTO',11),
(15,'BARTOLOME RUIZ',12),
(16,'CHINCA',21),
(17,'CARLOS CONCHA',20),
(18,'ESMERALDAS',10),
(19,'ISLA LUIS VARGAS TORRES',10),
(20,'LAS PALMAS',13),
(21,'MAJUA',19),
(22,'TIMBRE',18),
(24,'SAN MATEO',18),
(25,'VALLE DE SAN RAFAEL',14),
(26,'CASA BONITA',14),
(27,'LA TOLITA',14),
(28,'SIMON PLATA TORRES',14),
(29,'TABIAZO',17),
(30,'TACHINA',16),
(31,'VUELTA LARGA',15),
(32,'CAMARONES',22);
/*!40000 ALTER TABLE `zona` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-30 23:34:49
