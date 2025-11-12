-- Reiniciar y crear la base de datos
DROP DATABASE IF EXISTS senaparking_db;
CREATE DATABASE senaparking_db;
USE senaparking_db;

-- Configuración inicial del entorno
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;
SET time_zone = '+00:00';
START TRANSACTION;

-- --------------------------------------

-- Estructura de tabla para tb_usersys
-- --------------------------------------
CREATE TABLE tb_usersys (
  id_userSys INT NOT NULL AUTO_INCREMENT,
  id_rol ENUM('1', '2', '3') NOT NULL, 
  tipo_documento ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
  numero_documento VARCHAR(20) NOT NULL,
  nombres_sys VARCHAR(50) NOT NULL,
  apellidos_sys VARCHAR(50) NOT NULL,
  numero_contacto VARCHAR(14) NOT NULL,
  username VARCHAR(16) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo',
  PRIMARY KEY (id_userSys),
  UNIQUE KEY uq_documento_sys (tipo_documento, numero_documento),
  UNIQUE KEY uq_correo (correo),
  KEY idx_id_rol (id_rol),
  KEY idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para tb_usersys
INSERT INTO tb_usersys (id_userSys, id_rol, tipo_documento, numero_documento, nombres_sys, apellidos_sys, numero_contacto, username, correo, password, estado) VALUES
(1, '1', 'cedula_ciudadania', '10983838', 'admin', 'sys', '320555555', 'admin', 'admin@gmail.com', '$2y$10$rvRb2KCR3osn.uONFaaqDutLcnrpRyq44k9RkKXLAtywXs6ZymQ4W', 'activo'),
(2, '2', 'cedula_ciudadania', '10980998', 'supervisor', 'sys', '322097888', 'supervisor', 'supervisor@gmail.com', '$2y$10$XnF1OjPiQlMiKHN8QFcZ3OVdPQgHBNmf8gT3aEIrtCjBfL0vRWG5S', 'activo'),
(3, '3', 'cedula_ciudadania', '5987555', 'guardia', 'sys', '350977889', 'guardia', 'guardia@gmail.com', '$2y$10$LnFrsmLwm0DukhLSZMrefeYqy2UzPRdDZ2TNMetHGMw.3GNl1y346', 'activo');

-- --------------------------------------
-- Estructura de tabla para password_resets
-- --------------------------------------
CREATE TABLE password_resets (
  id_PassRest INT NOT NULL AUTO_INCREMENT,
  correo VARCHAR(100) NOT NULL,
  token VARCHAR(255) NOT NULL,
  hora_fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_PassRest),
  KEY idx_correo (correo),
  CONSTRAINT fk_password_resets_usersys FOREIGN KEY (correo) REFERENCES tb_usersys (correo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para tb_userpark
-- --------------------------------------
CREATE TABLE tb_userpark (
  id_userPark INT NOT NULL AUTO_INCREMENT,
  tipo_user ENUM('servidor_público', 'contratista', 'trabajador_oficial', 'visitante_autorizado', 'aprendiz', 'instructor') NOT NULL,
  tipo_documento ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
  numero_documento VARCHAR(20) NOT NULL,
  nombres_park VARCHAR(50) NOT NULL,
  apellidos_park VARCHAR(50) NOT NULL,
  edificio ENUM('CMD', 'CGI', 'CENIGRAF') NOT NULL,
  numero_contacto VARCHAR(14) DEFAULT NULL,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo',
  PRIMARY KEY (id_userPark),
  UNIQUE KEY uq_documento (tipo_documento, numero_documento),
  KEY idx_edificio (edificio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para tb_vehiculos
-- --------------------------------------
CREATE TABLE tb_vehiculos (
  id_vehiculo INT NOT NULL AUTO_INCREMENT,
  id_userPark INT NOT NULL,
  placa VARCHAR(10) NOT NULL,
  tarjeta_propiedad VARCHAR(100) DEFAULT NULL,
  tipo ENUM('Oficial', 'Automóvil', 'Motocicleta', 'Moto', 'Otro') NOT NULL,
  modelo VARCHAR(50) DEFAULT NULL,
  color VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_vehiculo),
  UNIQUE KEY uq_placa (placa),
  KEY idx_id_userPark (id_userPark),
  CONSTRAINT fk_vehiculos_userPark FOREIGN KEY (id_userPark) REFERENCES tb_userpark (id_userPark) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para tb_accesos
-- --------------------------------------
CREATE TABLE tb_accesos (
  id_acceso INT NOT NULL AUTO_INCREMENT,
  id_vehiculo INT NOT NULL,
  id_userSys INT NOT NULL,
  tipo_accion ENUM('ingreso', 'salida') NOT NULL,
  fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  espacio_asignado INT DEFAULT NULL,
  PRIMARY KEY (id_acceso),
  KEY idx_id_vehiculo (id_vehiculo),
  KEY idx_id_userSys (id_userSys),
  KEY idx_fecha_hora (fecha_hora),
  CONSTRAINT fk_accesos_usersys FOREIGN KEY (id_userSys) REFERENCES tb_usersys (id_userSys) ON DELETE RESTRICT,
  CONSTRAINT fk_accesos_vehiculos FOREIGN KEY (id_vehiculo) REFERENCES tb_vehiculos (id_vehiculo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para tb_actividades
-- --------------------------------------
CREATE TABLE tb_actividades (
  id_activi INT NOT NULL AUTO_INCREMENT,
  id_userSys INT NOT NULL,
  accion VARCHAR(255) NOT NULL,
  fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_activi),
  KEY idx_id_userSys (id_userSys),
  KEY idx_fecha_hora (fecha_hora),
  CONSTRAINT fk_actividades_usersys FOREIGN KEY (id_userSys) REFERENCES tb_usersys (id_userSys) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

=======
-- Estructura de tabla para `tb_roles`
-- --------------------------------------
CREATE TABLE `tb_roles` (
  `id_rol` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `uq_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para `tb_roles`
INSERT INTO `tb_roles` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'supervisor'),
(3, 'guardia');

-- --------------------------------------
-- Estructura de tabla para `tb_usersys`
-- --------------------------------------
CREATE TABLE `tb_usersys` (
  `id_userSys` INT NOT NULL AUTO_INCREMENT,
  `id_rol` INT NOT NULL,
  `tipo_documento` ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
  `numero_documento` VARCHAR(20) NOT NULL,
  `nombres_sys` VARCHAR(50) NOT NULL,
  `apellidos_sys` VARCHAR(50) NOT NULL,
  `numero_contacto` VARCHAR(14) NOT NULL,
  `username` VARCHAR(16) NOT NULL,
  `correo` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `estado` ENUM('activo', 'inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id_userSys`),
  UNIQUE KEY `uq_documento_sys` (`tipo_documento`, `numero_documento`),
  UNIQUE KEY `uq_correo` (`correo`),
  KEY `idx_id_rol` (`id_rol`),
  KEY `idx_username` (`username`),
  CONSTRAINT `fk_usersys_rol` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para `tb_usersys`
INSERT INTO `tb_usersys` (`id_userSys`, `id_rol`, `tipo_documento`, `numero_documento`, `nombres_sys`, `apellidos_sys`, `numero_contacto`, `username`, `correo`, `password`, `estado`) VALUES
(1, 1, 'cedula_ciudadania', '10983838', 'admin', 'sys', '320555555', 'admin', 'admin@gmail.com', '$2y$10$rvRb2KCR3osn.uONFaaqDutLcnrpRyq44k9RkKXLAtywXs6ZymQ4W', 'activo'),
(2, 2, 'cedula_ciudadania', '10980998', 'supervisor', 'sys', '322097888', 'supervisor', 'supervisor@gmail.com', '$2y$10$XnF1OjPiQlMiKHN8QFcZ3OVdPQgHBNmf8gT3aEIrtCjBfL0vRWG5S', 'activo'),
(3, 3, 'cedula_ciudadania', '5987555', 'guardia', 'sys', '350977889', 'guardia', 'guardia@gmail.com', '$2y$10$LnFrsmLwm0DukhLSZMrefeYqy2UzPRdDZ2TNMetHGMw.3GNl1y346', 'activo');

-- --------------------------------------
-- Estructura de tabla para `password_resets`
-- --------------------------------------
CREATE TABLE `password_resets` (
  `id_PassRest` INT NOT NULL AUTO_INCREMENT,
  `correo` VARCHAR(100) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `hora_fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_PassRest`),
  KEY `idx_correo` (`correo`),
  CONSTRAINT `fk_password_resets_usersys` FOREIGN KEY (`correo`) REFERENCES `tb_usersys` (`correo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para `tb_userpark`
-- --------------------------------------
CREATE TABLE `tb_userpark` (
  `id_userPark` INT NOT NULL AUTO_INCREMENT,
  `tipo_user` ENUM('servidor_público', 'contratista', 'trabajador_oficial', 'visitante_autorizado', 'aprendiz', 'instructor') NOT NULL,
  `tipo_documento` ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
  `numero_documento` VARCHAR(20) NOT NULL,
  `nombres_park` VARCHAR(50) NOT NULL,
  `apellidos_park` VARCHAR(50) NOT NULL,
  `edificio` ENUM('CMD', 'CGI', 'CENIGRAF') NOT NULL,
  `numero_contacto` VARCHAR(14) DEFAULT NULL,
  `estado` ENUM('activo', 'inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id_userPark`),
  UNIQUE KEY `uq_documento` (`tipo_documento`, `numero_documento`),
  KEY `idx_edificio` (`edificio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para `tb_vehiculos`
-- --------------------------------------
CREATE TABLE `tb_vehiculos` (
  `id_vehiculo` INT NOT NULL AUTO_INCREMENT,
  `id_userPark` INT NOT NULL,
  `placa` VARCHAR(10) NOT NULL,
  `tarjeta_propiedad` VARCHAR(100) DEFAULT NULL,
  `tipo` ENUM('Oficial', 'Automóvil', 'Motocicleta', 'Moto', 'Otro') NOT NULL,
  `modelo` VARCHAR(50) DEFAULT NULL,
  `color` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id_vehiculo`),
  UNIQUE KEY `uq_placa` (`placa`),
  KEY `idx_id_userPark` (`id_userPark`),
  CONSTRAINT `fk_vehiculos_userPark` FOREIGN KEY (`id_userPark`) REFERENCES `tb_userpark` (`id_userPark`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para `tb_accesos`
-- --------------------------------------
CREATE TABLE `tb_accesos` (
  `id_acceso` INT NOT NULL AUTO_INCREMENT,
  `id_vehiculo` INT NOT NULL,
  `id_userSys` INT NOT NULL,
  `tipo_accion` ENUM('ingreso', 'salida') NOT NULL,
  `fecha_hora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `espacio_asignado` INT DEFAULT NULL,
  PRIMARY KEY (`id_acceso`),
  KEY `idx_id_vehiculo` (`id_vehiculo`),
  KEY `idx_id_userSys` (`id_userSys`),
  KEY `idx_fecha_hora` (`fecha_hora`),
  CONSTRAINT `fk_accesos_usersys` FOREIGN KEY (`id_userSys`) REFERENCES `tb_usersys` (`id_userSys`) ON DELETE RESTRICT,
  CONSTRAINT `fk_accesos_vehiculos` FOREIGN KEY (`id_vehiculo`) REFERENCES `tb_vehiculos` (`id_vehiculo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------
-- Estructura de tabla para `tb_actividades`
-- --------------------------------------
CREATE TABLE `tb_actividades` (
  `id_activi` INT NOT NULL AUTO_INCREMENT,
  `id_userSys` INT NOT NULL,
  `accion` VARCHAR(255) NOT NULL,
  `fecha_hora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_activi`),
  KEY `idx_id_userSys` (`id_userSys`),
  KEY `idx_fecha_hora` (`fecha_hora`),
  CONSTRAINT `fk_actividades_usersys` FOREIGN KEY (`id_userSys`) REFERENCES `tb_usersys` (`id_userSys`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Finalizar la transacción
COMMIT;