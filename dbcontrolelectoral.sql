-- ==========================================================
-- Base de Datos: dbcontrolelectoral
-- Sistema de Control Electoral
-- Arquitectura MVC - CodeIgniter 4
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `dbcontrolelectoral` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dbcontrolelectoral`;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `sexo`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sexo` (
  `idsexo` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idsexo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `provincia`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `provincia` (
  `idprovincia` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idprovincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `canton`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `canton` (
  `idcanton` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `idprovincia` INT NOT NULL,
  PRIMARY KEY (`idcanton`),
  KEY `fk_canton_provincia_idx` (`idprovincia`),
  CONSTRAINT `fk_canton_provincia` FOREIGN KEY (`idprovincia`) REFERENCES `provincia` (`idprovincia`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `parroquia`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `parroquia` (
  `idparroquia` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `idcanton` INT NOT NULL,
  PRIMARY KEY (`idparroquia`),
  KEY `fk_parroquia_canton_idx` (`idcanton`),
  CONSTRAINT `fk_parroquia_canton` FOREIGN KEY (`idcanton`) REFERENCES `canton` (`idcanton`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `zona`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `zona` (
  `idzona` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `idparroquia` INT NOT NULL,
  PRIMARY KEY (`idzona`),
  KEY `fk_zona_parroquia_idx` (`idparroquia`),
  CONSTRAINT `fk_zona_parroquia` FOREIGN KEY (`idparroquia`) REFERENCES `parroquia` (`idparroquia`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `recintoelectoral`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `recintoelectoral` (
  `idrecintoelectoral` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `idzona` INT NOT NULL,
  `numeroelectores` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idrecintoelectoral`),
  KEY `fk_recinto_zona_idx` (`idzona`),
  CONSTRAINT `fk_recinto_zona` FOREIGN KEY (`idzona`) REFERENCES `zona` (`idzona`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `meza`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `meza` (
  `idmeza` INT AUTO_INCREMENT NOT NULL,
  `numero` INT NOT NULL,
  `idsexo` INT NOT NULL,
  `idrecintoelectoral` INT NOT NULL,
  PRIMARY KEY (`idmeza`),
  KEY `fk_meza_sexo_idx` (`idsexo`),
  KEY `fk_meza_recinto_idx` (`idrecintoelectoral`),
  CONSTRAINT `fk_meza_sexo` FOREIGN KEY (`idsexo`) REFERENCES `sexo` (`idsexo`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_meza_recinto` FOREIGN KEY (`idrecintoelectoral`) REFERENCES `recintoelectoral` (`idrecintoelectoral`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `tipodignidad`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tipodignidad` (
  `idtipodignidad` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idtipodignidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `dignidad`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dignidad` (
  `iddignidad` INT AUTO_INCREMENT NOT NULL,
  `idpersona` INT NOT NULL,
  `idtipodignidad` INT NOT NULL,
  PRIMARY KEY (`iddignidad`),
  KEY `fk_dignidad_persona_idx` (`idpersona`),
  KEY `fk_dignidad_tipodignidad_idx` (`idtipodignidad`),
  CONSTRAINT `fk_dignidad_persona` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_dignidad_tipodignidad` FOREIGN KEY (`idtipodignidad`) REFERENCES `tipodignidad` (`idtipodignidad`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `mezadignidad`
-- (Dignidades a ser elegidas en cada mesa electoral y cantidad de papeletas contadas)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mezadignidad` (
  `idmezadignidad` INT AUTO_INCREMENT NOT NULL,
  `idmeza` INT NOT NULL,
  `iddignidad` INT NOT NULL,
  `numeropapeleta` INT NOT NULL COMMENT 'Cantidad de papeletas que fueron contadas para esta dignidad en la mesa',
  PRIMARY KEY (`idmezadignidad`),
  KEY `idx_mezadignidad_meza` (`idmeza`),
  KEY `idx_mezadignidad_dignidad` (`iddignidad`),
  CONSTRAINT `fk_mezadignidad_meza` FOREIGN KEY (`idmeza`) REFERENCES `meza` (`idmeza`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_mezadignidad_dignidad` FOREIGN KEY (`iddignidad`) REFERENCES `dignidad` (`iddignidad`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Estructura de tabla para la tabla `persona`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `persona` (
  `idpersona` INT AUTO_INCREMENT NOT NULL,
  `cedula` VARCHAR(15) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellidos` VARCHAR(50) NOT NULL,
  `fechanacimiento` DATE NOT NULL,
  `idsexo` INT NOT NULL,
  PRIMARY KEY (`idpersona`),
  UNIQUE KEY `idx_persona_cedula` (`cedula`),
  KEY `fk_persona_sexo_idx` (`idsexo`),
  CONSTRAINT `fk_persona_sexo` FOREIGN KEY (`idsexo`) REFERENCES `sexo` (`idsexo`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `rolusuario`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rolusuario` (
  `idrolusuario` INT AUTO_INCREMENT NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idrolusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `usuario`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` INT AUTO_INCREMENT NOT NULL,
  `idpersona` INT NOT NULL,
  `usuario` VARCHAR(20) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  `idrolusuario` INT NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `idx_usuario_username` (`usuario`),
  KEY `fk_usuario_persona_idx` (`idpersona`),
  KEY `fk_usuario_rolusuario_idx` (`idrolusuario`),
  CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_usuario_rolusuario` FOREIGN KEY (`idrolusuario`) REFERENCES `rolusuario` (`idrolusuario`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos iniciales para `sexo`
-- --------------------------------------------------------
INSERT INTO `sexo` (`idsexo`, `nombre`) VALUES
(1, 'Masculino'),
(2, 'Femenino')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `provincia`
-- --------------------------------------------------------
INSERT INTO `provincia` (`idprovincia`, `nombre`) VALUES
(1, 'Pichincha'),
(2, 'Guayas'),
(3, 'Manabí'),
(4, 'Azuay'),
(5, 'Esmeraldas')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `canton`
-- --------------------------------------------------------
INSERT INTO `canton` (`idcanton`, `nombre`, `idprovincia`) VALUES
(1, 'Quito', 1),
(2, 'Rumiñahui', 1),
(3, 'Guayaquil', 2),
(4, 'Samborondón', 2),
(5, 'Portoviejo', 3),
(6, 'Manta', 3),
(7, 'Cuenca', 4),
(8, 'Esmeraldas', 5),
(9, 'Atacames', 5)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `parroquia`
-- --------------------------------------------------------
INSERT INTO `parroquia` (`idparroquia`, `nombre`, `idcanton`) VALUES
(1, 'Iñaquito', 1),
(2, 'Mariscal Sucre', 1),
(3, 'Cumbayá', 1),
(4, 'Tarqui', 3),
(5, 'Ximena', 3),
(6, 'Manta', 6),
(7, 'El Sagrario', 7),
(8, 'Atacames', 9)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `zona`
-- --------------------------------------------------------
INSERT INTO `zona` (`idzona`, `nombre`, `idparroquia`) VALUES
(1, 'Zona 1 - Centro', 1),
(2, 'Zona 2 - Bellavista', 1),
(3, 'Zona 1 - La Mariscal', 2),
(4, 'Zona 1 - Central', 3),
(5, 'Zona 1 - Kennedy', 4),
(6, 'Zona 2 - Urdesa', 4),
(7, 'Zona 1 - Puerto Hondo', 5),
(8, 'Zona 1 - Centro Puerto', 6),
(9, 'Zona 1 - Centro Histórico', 7),
(10, 'Zona 1 - Playa Central', 8)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `recintoelectoral`
-- --------------------------------------------------------
INSERT INTO `recintoelectoral` (`idrecintoelectoral`, `nombre`, `idzona`, `numeroelectores`) VALUES
(1, 'Colegio Benalcázar', 1, 3500),
(2, 'Unidad Educativa Municipal Eugenio Espejo', 1, 2800),
(3, 'Colegio San Gabriel', 2, 4100),
(4, 'Colegio Manuela Cañizares', 3, 3250),
(5, 'Unidad Educativa Cumbayá', 4, 1950),
(6, 'Universidad Católica Santiago de Guayaquil', 5, 5400),
(7, 'Facultad de Jurisprudencia Universidad de Guayaquil', 6, 4800),
(8, 'Colegio Técnico Febres Cordero', 7, 2600),
(9, 'Unidad Educativa Fiscal Manta', 8, 3100),
(10, 'Unidad Educativa Benigno Malo', 9, 3900),
(11, 'Colegio Nacional Atacames', 10, 2150)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `numeroelectores` = VALUES(`numeroelectores`);

-- --------------------------------------------------------
-- Datos iniciales para `meza`
-- --------------------------------------------------------
INSERT INTO `meza` (`idmeza`, `numero`, `idsexo`, `idrecintoelectoral`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 2, 1, 1),
(4, 2, 2, 1),
(5, 1, 1, 2),
(6, 1, 2, 2),
(7, 1, 1, 3),
(8, 1, 2, 3),
(9, 1, 1, 4),
(10, 1, 2, 4),
(11, 1, 1, 5),
(12, 1, 2, 6)
ON DUPLICATE KEY UPDATE `numero` = VALUES(`numero`), `idsexo` = VALUES(`idsexo`), `idrecintoelectoral` = VALUES(`idrecintoelectoral`);





-- --------------------------------------------------------
-- Datos de prueba para `persona`
-- --------------------------------------------------------
INSERT INTO `persona` (`idpersona`, `cedula`, `nombre`, `apellidos`, `fechanacimiento`, `idsexo`) VALUES
(1, '0801234567', 'Carlos Alberto', 'Mendoza Vera', '1995-04-12', 1),
(2, '0809876543', 'María Elena', 'Zambrano Quiñónez', '1998-10-25', 2)
ON DUPLICATE KEY UPDATE `cedula` = VALUES(`cedula`);

-- --------------------------------------------------------
-- Datos iniciales para `rolusuario`
-- --------------------------------------------------------
INSERT INTO `rolusuario` (`idrolusuario`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Operador Electoral'),
(3, 'Supervisor'),
(4, 'Auditor')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `usuario`
-- --------------------------------------------------------
INSERT INTO `usuario` (`idusuario`, `idpersona`, `usuario`, `password`, `idrolusuario`) VALUES
(1, 1, 'admin', 'admin123', 1),
(2, 2, 'mzambrano', 'voto2026', 2)
ON DUPLICATE KEY UPDATE `usuario` = VALUES(`usuario`);

-- --------------------------------------------------------
-- Datos iniciales para `tipodignidad`
-- --------------------------------------------------------
INSERT INTO `tipodignidad` (`idtipodignidad`, `nombre`) VALUES
(1, 'Presidente y Vicepresidente de la República'),
(2, 'Asambleísta Nacional'),
(3, 'Asambleísta Provincial'),
(4, 'Prefecto y Viceprefecto Provincial'),
(5, 'Alcalde Municipal / Distrital'),
(6, 'Concejal Urbano'),
(7, 'Concejal Rural'),
(8, 'Vocal de Junta Parroquial')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- --------------------------------------------------------
-- Datos iniciales para `dignidad`
-- --------------------------------------------------------
INSERT INTO `dignidad` (`iddignidad`, `idpersona`, `idtipodignidad`) VALUES
(1, 1, 1),
(2, 2, 2)
ON DUPLICATE KEY UPDATE `idpersona` = VALUES(`idpersona`), `idtipodignidad` = VALUES(`idtipodignidad`);

-- --------------------------------------------------------
-- Datos iniciales para `mezadignidad`
-- --------------------------------------------------------
INSERT INTO `mezadignidad` (`idmezadignidad`, `idmeza`, `iddignidad`, `numeropapeleta`) VALUES
(1, 1, 1, 101),
(2, 1, 2, 201),
(3, 2, 1, 101),
(4, 2, 2, 201),
(5, 3, 1, 101),
(6, 4, 1, 104)
ON DUPLICATE KEY UPDATE `idmeza` = VALUES(`idmeza`), `iddignidad` = VALUES(`iddignidad`), `numeropapeleta` = VALUES(`numeropapeleta`);


