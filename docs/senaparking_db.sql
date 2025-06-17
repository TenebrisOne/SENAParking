DROP DATABASE IF EXISTS senaparking_db;
CREATE DATABASE senaparking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senaparking_db;

CREATE TABLE tb_roles (
	id_rol INT AUTO_INCREMENT PRIMARY KEY, 
	nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO `tb_roles` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(3, 'guardia'),
(2, 'supervisor');

CREATE TABLE tb_permisos (
	id_permiso INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE tb_rol_permisos (
	id_rol INT NOT NULL,
	id_permiso INT NOT NULL,
	PRIMARY KEY (id_rol, id_permiso),
	FOREIGN KEY (id_rol) REFERENCES tb_roles(id_rol) ON DELETE CASCADE,
	FOREIGN KEY (id_permiso) REFERENCES tb_permisos(id_permiso) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `tb_usersys` (
  `id_userSys` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `id_rol` int(11) NOT NULL,
  tipo_documento ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `nombres_sys` varchar(20) NOT NULL,
  `apellidos_sys` varchar(20) NOT NULL,
  `numero_contacto` varchar(14) NOT NULL,
  `username` varchar(16) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  FOREIGN KEY (id_rol) REFERENCES tb_roles(id_rol),
  UNIQUE (tipo_documento, numero_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_userPark (
	id_userPark INT AUTO_INCREMENT PRIMARY KEY,
	tipo_user ENUM ('servidor_público', 'contratista', 'trabajador_oficial', 'visitante_autorizado', 'aprendiz', 'instructor') NOT NULL,
	tipo_documento ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
	numero_documento VARCHAR(20) NOT NULL,
	nombres_park VARCHAR(20) NOT NULL,
	apellidos_park VARCHAR(20) NOT NULL,
	edificio ENUM('CMD', 'CGI', 'CENIGRAF') NOT NULL,
	tarjeta_propiedad VARCHAR(100),
	numero_contacto VARCHAR(20),
	UNIQUE (tipo_documento, numero_documento)
) ENGINE=InnoDB;

CREATE TABLE tb_vehiculos (
	id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
	id_userPark INT NOT NULL,
	placa VARCHAR(10) NOT NULL UNIQUE,
	tipo ENUM('carro', 'moto', 'cicla', 'vehiculo_oficial', 'aula_movil') NOT NULL,
	modelo VARCHAR(50),
	color VARCHAR(50), 
	FOREIGN KEY (id_userPark) REFERENCES tb_userPark(id_userPark) ON DELETE CASCADE 
) ENGINE=InnoDB;

CREATE TABLE tb_accesos (
	id_acceso INT AUTO_INCREMENT PRIMARY KEY,
	id_vehiculo INT NOT NULL,
	id_userSys INT NOT NULL, 
	tipo_accion ENUM('ingreso','salida') NOT NULL,
	fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	espacio_asignado VARCHAR(10),
	FOREIGN KEY (id_vehiculo) REFERENCES tb_vehiculos(id_vehiculo),
	FOREIGN KEY (id_userSys) REFERENCES tb_userSys(id_userSys)
) ENGINE=InnoDB;

CREATE TABLE tb_configPark (
	id_config INT AUTO_INCREMENT PRIMARY KEY,
	adelante_carros INT DEFAULT 20,
	adelante_motos INT DEFAULT 10,
	adelante_ciclas INT DEFAULT 50,
	trasera_carros INT DEFAULT 20
) ENGINE=InnoDB;

CREATE TABLE tb_actividades (
	id_activi INT AUTO_INCREMENT PRIMARY KEY,
	id_userSys INT NOT NULL,
	accion VARCHAR(255) NOT NULL,
	fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (id_userSys) REFERENCES tb_userSys(id_userSys)
) ENGINE=InnoDB;

