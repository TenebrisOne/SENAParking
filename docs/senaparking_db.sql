DROP DATABASE IF EXISTS senaparking_db;
CREATE DATABASE senaparking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senaparking_db;

-- Tabla tb_configPark
CREATE TABLE tb_configPark (
	id_config INT(11) NOT NULL AUTO_INCREMENT,
	adelante_carros INT(11) DEFAULT 20,
	adelante_motos INT(11) DEFAULT 10,
	adelante_ciclas INT(11) DEFAULT 50,
	trasera_carros INT(11) DEFAULT 20,
	PRIMARY KEY (id_config)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_permisos
CREATE TABLE tb_permisos (
	id_permiso INT(11) NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(50) NOT NULL,
	PRIMARY KEY (id_permiso),
	UNIQUE KEY uq_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_roles
CREATE TABLE tb_roles (
	id_rol INT(11) NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(50) NOT NULL,
	PRIMARY KEY (id_rol),
	UNIQUE KEY uq_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_rol_permisos
CREATE TABLE tb_rol_permisos (
	id_rol INT(11) NOT NULL,
	id_permiso INT(11) NOT NULL,
	PRIMARY KEY (id_rol, id_permiso),
	KEY idx_id_permiso (id_permiso),
	CONSTRAINT fk_rolpermisos_rol FOREIGN KEY (id_rol) REFERENCES tb_roles(id_rol) ON DELETE CASCADE,
	CONSTRAINT fk_rolpermisos_permiso FOREIGN KEY (id_permiso) REFERENCES tb_permisos(id_permiso) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_userPark
CREATE TABLE tb_userPark (
	id_userPark INT(11) NOT NULL AUTO_INCREMENT,
	tipo_user ENUM('servidor_público','contratista','trabajador_oficial','visitante_autorizado','aprendiz','instructor') NOT NULL,
	tipo_documento ENUM('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') NOT NULL,
	numero_documento VARCHAR(20) NOT NULL,
	nombres_park VARCHAR(50) NOT NULL,
	apellidos_park VARCHAR(50) NOT NULL,
	edificio ENUM('CMD','CGI','CENIGRAF') NOT NULL,
	tarjeta_propiedad VARCHAR(100) DEFAULT NULL,
	numero_contacto VARCHAR(20) DEFAULT NULL,
	estado ENUM ('activo','inactivo'),
	PRIMARY KEY (id_userPark),
	UNIQUE KEY uq_documento (tipo_documento, numero_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_usersys
CREATE TABLE tb_usersys (
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
	KEY idx_id_rol (id_rol),
	CONSTRAINT fk_usersys_rol FOREIGN KEY (id_rol) REFERENCES tb_roles(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_vehiculos
CREATE TABLE tb_vehiculos (
	id_vehiculo INT(11) NOT NULL AUTO_INCREMENT,
	id_userPark INT(11) NOT NULL,
	placa VARCHAR(10) NOT NULL,
	tipo ENUM('Oficial','Automóvil','Motocicleta','Moto','Otro') NOT NULL, -- mejor usar ENUM para uniformidad
	modelo VARCHAR(50) DEFAULT NULL,
	color VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (id_vehiculo),
	UNIQUE KEY uq_placa (placa),
	KEY idx_id_userPark (id_userPark),
	CONSTRAINT fk_vehiculos_userPark FOREIGN KEY (id_userPark) REFERENCES tb_userPark(id_userPark) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla tb_accesos
CREATE TABLE tb_accesos (
	id_acceso INT(11) NOT NULL AUTO_INCREMENT,
	id_vehiculo INT(11) NOT NULL,
	id_userSys INT(11) NOT NULL,
	tipo_accion ENUM('ingreso','salida') NOT NULL,
	fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	espacio_asignado INT(11) DEFAULT NULL,
	PRIMARY KEY (id_acceso),
	KEY idx_id_vehiculo (id_vehiculo),
	KEY idx_id_userSys (id_userSys),
	CONSTRAINT fk_accesos_vehiculos FOREIGN KEY (id_vehiculo) REFERENCES tb_vehiculos(id_vehiculo),
	CONSTRAINT fk_accesos_usersys FOREIGN KEY (id_userSys) REFERENCES tb_usersys(id_userSys)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tabla tb_actividades
CREATE TABLE tb_actividades (
	id_activi INT(11) NOT NULL AUTO_INCREMENT,
	id_userSys INT(11) NOT NULL,
	accion VARCHAR(255) NOT NULL,
	fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id_activi),
	KEY idx_id_userSys (id_userSys),
	CONSTRAINT fk_actividades_usersys FOREIGN KEY (id_userSys) REFERENCES tb_usersys(id_userSys)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de prueba (solo ejemplo para tb_roles y tb_userPark)
INSERT INTO tb_roles (nombre) VALUES
('admin'), ('supervisor'), ('guardia');

INSERT INTO tb_userPark (tipo_user, tipo_documento, numero_documento, nombres_park, apellidos_park, edificio, tarjeta_propiedad, numero_contacto) VALUES
('instructor', 'tarjeta_identidad', '3333111', 'Juanito', 'Qeqeqe', 'CMD', '1e12qwe221e', '123123123');

INSERT INTO `tb_usersys` (`id_userSys`, `id_rol`, `tipo_documento`, `numero_documento`, `nombres_sys`, `apellidos_sys`, `numero_contacto`, `username`, `correo`, `password`, `estado`) VALUES
(1, 1, 'cedula_ciudadania', '1234567891', 'pepito', 'perez', '3213578545', 'Pepito', 'pepito@gmail.com', '$2y$10$9qt8mRjVmArNgFxXbQcSSe23AtC4PhM9OUajaek0tFbT6qAhEjxXS', 'activo'),
(2, 3, 'cedula_ciudadania', '46896435', 'Gustavo', 'Lopez', '1232456787', 'Gustavo', 'gustavo@gmail.com', '$2y$10$5tqAXXTpBoBBZ1BlXwkFm.QwsRIxVnhW/A.UwXDVIim/uRoiK.yO6', 'inactivo'),
(3, 2, 'cedula_ciudadania', '1234567', 'Nicol', 'Barragan', '3145678971', 'Nicol', 'nicol@gmail.com', '$2y$10$eYjrfKF3EWvOCaGJObuO6eylAKRXqDdlK9Wyxe0cE8LYWXUXrPiN6', 'activo'),
(4, 3, 'cedula_ciudadania', '12039458355', 'Pedro', 'Rojas', '3134567895', 'Pedro', 'pedro@gmail.com', '$2y$10$MwdltQErFs6496ORg8G0deiJLd8Ui9p9Y7BT1lWtlku.e3yUMquJu', 'activo'),
(6, 3, 'pasaporte', '1254896347', 'Ana', 'Sanchez', '3145879244', 'Ana', 'ana@gmail.com', '$2y$10$L2rw2uo5Ppi81MMMm14L6eHvBB6Iluo5r37HuYoqh3fex6CdDkBKu', 'activo'),
(7, 1, 'cedula_ciudadania', '12547896', 'nikki', 'barragan', '3254964178', 'nikki', 'nikki@gmail.com', '$2y$10$PiTKnvOcLiiQ6xl1UVwWwOfacG9XpfAcurkNr6sJ.g7Iau/FzpVRa', 'activo'),
(22, 1, 'cedula_ciudadania', '3215478965', 'Ana', 'Sanchez', '3298735577', 'Anita', 'ana@gmail.com', '$2y$10$7KTpiQCwYqdBnPwTPH7DK.CIgqX4URx4vXG0Ge.A4F5G6hw/VgLr.', 'activo'),
(23, 1, 'cedula_extranjeria', '1234567891', 'Susana', 'Lopez', '32154874', 'susana', 'susana@gmail.com', '$2y$10$6Iq3VFdXR55qIlQC1.5R9OjM4u4bAPdCUBS34XDAn9BdGxtwvGRBK', 'activo'),
(24, 3, 'cedula_ciudadania', '11422782144', 'Susana', 'parra', '1232456787', 'parra', 'parra@gmail.com', '$2y$10$SCeHMl1wIjW29k69mV1zxOQneTllw3gRiN9oZumBrfPWZOourLxHu', 'activo'),
(25, 2, 'cedula_extranjeria', '15476245', 'juan', 'alvarez', '54581233745', 'juan', 'juan@gmail.com', '$2y$10$Uqjs6ERma4sHpdArSiF4x.eqCQI0mNfh56t4REvsjeMRqhjH7nzkS', 'activo');

