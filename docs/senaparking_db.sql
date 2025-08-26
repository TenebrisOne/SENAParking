-- CREACIÓN DE LA BASE DE DATOS
DROP DATABASE IF EXISTS senaparking_db;
CREATE DATABASE IF NOT EXISTS senaparking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senaparking_db;


-- CONFIGURACIONES INICIALES
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

START TRANSACTION;

-- -----------------------------
-- TABLA: tb_roles
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_roles (
  id_rol INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tb_roles (id_rol, nombre) VALUES
  (1, 'admin'),
  (2, 'supervisor'),
  (3, 'guardia');

-- -----------------------------
-- TABLA: tb_permisos
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_permisos (
  id_permiso INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (id_permiso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_rol_permisos
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_rol_permisos (
  id_rol INT(11) NOT NULL,
  id_permiso INT(11) NOT NULL,
  PRIMARY KEY (id_rol, id_permiso),
  FOREIGN KEY (id_rol) REFERENCES tb_roles (id_rol) ON DELETE CASCADE,
  FOREIGN KEY (id_permiso) REFERENCES tb_permisos (id_permiso) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_usersys
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_usersys (
  id_userSys INT(11) NOT NULL AUTO_INCREMENT,
  id_rol INT(11) NOT NULL,
  tipo_documento ENUM('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') NOT NULL,
  numero_documento VARCHAR(20) NOT NULL,
  nombres_sys VARCHAR(50) NOT NULL,
  apellidos_sys VARCHAR(50) NOT NULL,
  numero_contacto VARCHAR(14) NOT NULL,
  username VARCHAR(16) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  estado ENUM('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (id_userSys),
  UNIQUE KEY uq_documento_sys (tipo_documento, numero_documento),
  UNIQUE KEY uq_correo (correo),
  FOREIGN KEY (id_rol) REFERENCES tb_roles (id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: password_resets
-- -----------------------------
CREATE TABLE IF NOT EXISTS password_resets (
  id_PassRest INT(11) NOT NULL AUTO_INCREMENT,
  correo VARCHAR(100) NOT NULL,
  token VARCHAR(255) NOT NULL,
  hora_fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (id_PassRest),
  KEY (correo),
  FOREIGN KEY (correo) REFERENCES tb_usersys(correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_actividades
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_actividades (
  id_activi INT(11) NOT NULL AUTO_INCREMENT,
  id_userSys INT(11) NOT NULL,
  accion VARCHAR(255) NOT NULL,
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (id_activi),
  FOREIGN KEY (id_userSys) REFERENCES tb_usersys (id_userSys)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_userpark
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_userpark (
  id_userPark INT(11) NOT NULL AUTO_INCREMENT,
  tipo_user ENUM('servidor_público','contratista','trabajador_oficial','visitante_autorizado','aprendiz','instructor') NOT NULL,
  tipo_documento ENUM('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') NOT NULL,
  numero_documento VARCHAR(20) NOT NULL,
  nombres_park VARCHAR(50) NOT NULL,
  apellidos_park VARCHAR(50) NOT NULL,
  edificio ENUM('CMD','CGI','CENIGRAF') NOT NULL,
  tarjeta_propiedad VARCHAR(100),
  numero_contacto VARCHAR(20),
  estado ENUM('activo','inactivo'),
  PRIMARY KEY (id_userPark),
  UNIQUE KEY uq_documento (tipo_documento, numero_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_vehiculos
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_vehiculos (
  id_vehiculo INT(11) NOT NULL AUTO_INCREMENT,
  id_userPark INT(11) NOT NULL,
  placa VARCHAR(10) NOT NULL UNIQUE,
  tipo ENUM('Oficial','Automóvil','Motocicleta','Moto','Otro') NOT NULL,
  modelo VARCHAR(50),
  color VARCHAR(50),
  PRIMARY KEY (id_vehiculo),
  FOREIGN KEY (id_userPark) REFERENCES tb_userpark (id_userPark) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_accesos
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_accesos (
  id_acceso INT(11) NOT NULL AUTO_INCREMENT,
  id_vehiculo INT(11) NOT NULL,
  id_userSys INT(11) NOT NULL,
  tipo_accion ENUM('ingreso','salida') NOT NULL,
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  espacio_asignado INT(11),
  PRIMARY KEY (id_acceso),
  FOREIGN KEY (id_vehiculo) REFERENCES tb_vehiculos (id_vehiculo),
  FOREIGN KEY (id_userSys) REFERENCES tb_usersys (id_userSys)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------
-- TABLA: tb_configpark
-- -----------------------------
CREATE TABLE IF NOT EXISTS tb_configpark (
  id_config INT(11) NOT NULL AUTO_INCREMENT,
  adelante_carros INT(11) DEFAULT 20,
  adelante_motos INT(11) DEFAULT 10,
  adelante_ciclas INT(11) DEFAULT 50,
  trasera_carros INT(11) DEFAULT 20,
  PRIMARY KEY (id_config)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
>>>>>>> 68d8565 (Cambios db)
