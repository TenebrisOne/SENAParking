-- =========================================================
-- 🚀 BASE DE DATOS: senaparking_db (versión final optimizada)
-- =========================================================
-- Autor: TenebrisOne (Cristian Ruiz)
-- Descripción: Estructura completa del sistema SENAPARKING
-- Incluye: Tablas, Relaciones, Datos iniciales, Vistas, 
-- Triggers, Procedimientos y Consultas útiles.
-- =========================================================

DROP DATABASE IF EXISTS senaparking_db;
CREATE DATABASE senaparking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senaparking_db;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;
SET time_zone = '-05:00';
START TRANSACTION;

-- =========================================================
-- 1️⃣ TABLAS PRINCIPALES
-- =========================================================


-- =========================================================
-- 🧱 TABLA: tb_usersys
-- =========================================================
CREATE TABLE tb_usersys (
  id_userSys INT NOT NULL AUTO_INCREMENT,
  rolUsys ENUM('admin','supervisor','guardia') NOT NULL,
  tipoDocumentoUsys ENUM('cedula_ciudadania', 'tarjeta_identidad', 'cedula_extranjeria', 'pasaporte', 'otro') NOT NULL,
  numeroDocumentoUsys VARCHAR(20) NOT NULL,
  nombresUsys VARCHAR(50) NOT NULL,
  apellidosUsys VARCHAR(50) NOT NULL,
  numeroContactoUsys VARCHAR(14) NOT NULL,
  usernameUsys VARCHAR(16) NOT NULL,
  correoUsys VARCHAR(100) NOT NULL,
  passwordUsys VARCHAR(255) NOT NULL,
  estadoUsys ENUM('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (id_userSys),
  UNIQUE KEY uq_documento_usys (tipoDocumentoUsys, numeroDocumentoUsys),
  UNIQUE KEY uq_correo_usys (correoUsys),
  KEY idx_username (usernameUsys)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE tb_usersys
ADD UNIQUE KEY uq_num_doc_usys (numeroDocumentoUsys);


-- =========================================================
-- 🧱 TABLA: password_resets
-- =========================================================
CREATE TABLE password_resets (
  id_PassRest INT NOT NULL AUTO_INCREMENT,
  correoUsys VARCHAR(100) NOT NULL,
  token VARCHAR(255) NOT NULL,
  horaFecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_PassRest),
  KEY idx_correo (correoUsys),
  CONSTRAINT fk_password_resets_usersys FOREIGN KEY (correoUsys)
    REFERENCES tb_usersys (correoUsys)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 🧱 TABLA: tb_userpark
-- =========================================================
CREATE TABLE tb_userpark (
  id_userPark INT NOT NULL AUTO_INCREMENT,
  tipoUserUpark ENUM('servidor_público','contratista','trabajador_oficial','visitante_autorizado','aprendiz','instructor') NOT NULL,
  tipoDocumentoUpark ENUM('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') NOT NULL,
  numeroDocumentoUpark VARCHAR(20) NOT NULL,
  nombresUpark VARCHAR(50) NOT NULL,
  apellidosUpark VARCHAR(50) NOT NULL,
  edificioUpark ENUM('CMD','CGI','CENIGRAF') NOT NULL,
  numeroContactoUpark VARCHAR(14) DEFAULT NULL,
  estadoUpark ENUM('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (id_userPark),
  UNIQUE KEY uq_documento_upark (tipoDocumentoUpark, numeroDocumentoUpark),
  KEY idx_edificio (edificioUpark)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE tb_userpark
ADD UNIQUE KEY uq_num_doc_upark (numeroDocumentoUpark);

-- =========================================================
-- 🧱 TABLA: tb_vehiculos
-- =========================================================
CREATE TABLE tb_vehiculos (
  id_vehiculo INT NOT NULL AUTO_INCREMENT,
  id_userPark INT NOT NULL,
  placaVeh VARCHAR(10) NOT NULL,
  tarjetaPropiedadVeh VARCHAR(100) DEFAULT NULL,
  tipoVeh ENUM('Oficial','Automóvil','Bicicleta','Motocicleta','Otro') NOT NULL,
  modeloVeh VARCHAR(50) DEFAULT NULL,
  colorVeh VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_vehiculo),
  UNIQUE KEY uq_placa (placaVeh),
  KEY idx_id_userPark (id_userPark),
  CONSTRAINT fk_vehiculos_userPark FOREIGN KEY (id_userPark)
    REFERENCES tb_userpark (id_userPark)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 🧱 TABLA: tb_accesos
-- =========================================================
CREATE TABLE tb_accesos (
  id_acceso INT NOT NULL AUTO_INCREMENT,
  id_vehiculo INT NOT NULL,
  id_userSys INT NOT NULL,
  tipoAccionAcc ENUM('ingreso','salida') NOT NULL,
  fechaHoraAcc TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  espacioAsignadoAcc INT DEFAULT NULL,
  PRIMARY KEY (id_acceso),
  KEY idx_id_vehiculo (id_vehiculo),
  KEY idx_id_userSys (id_userSys),
  KEY idx_fecha_hora (fechaHoraAcc),
  CONSTRAINT fk_accesos_usersys FOREIGN KEY (id_userSys)
    REFERENCES tb_usersys (id_userSys)
    ON DELETE RESTRICT,
  CONSTRAINT fk_accesos_vehiculos FOREIGN KEY (id_vehiculo)
    REFERENCES tb_vehiculos (id_vehiculo)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 🧱 TABLA: tb_actividades
-- =========================================================
CREATE TABLE tb_actividades (
  id_activi INT NOT NULL AUTO_INCREMENT,
  id_userSys INT NOT NULL,
  accionActi VARCHAR(255) NOT NULL,
  fechaHoraActi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_activi),
  KEY idx_id_userSys (id_userSys),
  KEY idx_fecha_hora (fechaHoraActi),
  CONSTRAINT fk_actividades_usersys FOREIGN KEY (id_userSys)
    REFERENCES tb_usersys (id_userSys)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 2️⃣ DATOS INICIALES
-- =========================================================

INSERT INTO tb_usersys (rolUsys, tipoDocumentoUsys, numeroDocumentoUsys, nombresUsys, apellidosUsys, numeroContactoUsys, usernameUsys, correoUsys, passwordUsys, estadoUsys)
VALUES
('admin','cedula_ciudadania','10983838','admin','sys','320555555','admin','admin@gmail.com','$2y$10$rvRb2KCR3osn.uONFaaqDutLcnrpRyq44k9RkKXLAtywXs6ZymQ4W','activo'),
('supervisor','cedula_ciudadania','10980998','supervisor','sys','322097888','supervisor','supervisor@gmail.com','$2y$10$XnF1OjPiQlMiKHN8QFcZ3OVdPQgHBNmf8gT3aEIrtCjBfL0vRWG5S','activo'),
('guardia','cedula_ciudadania','5987555','guardia','sys','350977889','guardia','guardia@gmail.com','$2y$10$LnFrsmLwm0DukhLSZMrefeYqy2UzPRdDZ2TNMetHGMw.3GNl1y346','activo');


-- =========================================================
-- 👀 VISTAS (vw_)
-- =========================================================

CREATE OR REPLACE VIEW vw_user_roles AS
SELECT 
  id_userSys,
  rolUsys AS rol,
  nombresUsys,
  apellidosUsys,
  usernameUsys,
  correoUsys,
  estadoUsys
FROM tb_usersys;

CREATE OR REPLACE VIEW vw_userpark_info AS
SELECT 
  up.id_userPark,
  up.nombresUpark,
  up.apellidosUpark,
  up.tipoUserUpark,
  up.edificioUpark,
  COUNT(v.id_vehiculo) AS cantidadVehiculos,
  up.estadoUpark
FROM tb_userpark up
LEFT JOIN tb_vehiculos v ON up.id_userPark = v.id_userPark
GROUP BY up.id_userPark;

CREATE OR REPLACE VIEW vw_accesos_detalle AS
SELECT 
  a.id_acceso,
  a.tipoAccionAcc,
  a.fechaHoraAcc,
  a.espacioAsignadoAcc,
  v.placaVeh,
  v.tipoVeh AS tipoVehiculo,
  u.nombresUsys AS operador,
  u.rolUsys AS rolOperador
FROM tb_accesos a
INNER JOIN tb_vehiculos v ON a.id_vehiculo = v.id_vehiculo
INNER JOIN tb_usersys u ON a.id_userSys = u.id_userSys;

CREATE OR REPLACE VIEW vw_vehiculos_detalle AS
SELECT 
  v.id_vehiculo,
  v.placaVeh,
  v.tipoVeh,
  v.modeloVeh,
  v.colorVeh,
  u.nombresUpark AS propietario,
  u.apellidosUpark,
  u.tipoUserUpark,
  u.edificioUpark,
  u.estadoUpark
FROM tb_vehiculos v
INNER JOIN tb_userpark u ON v.id_userPark = u.id_userPark;

-- =========================================================
-- ⚙️ PROCEDIMIENTOS ALMACENADOS
-- =========================================================
DELIMITER //

CREATE PROCEDURE sp_registrar_acceso(
  IN p_idVehiculo INT,
  IN p_idUserSys INT,
  IN p_tipoAccion ENUM('ingreso','salida'),
  IN p_espacioAsignado INT
)
BEGIN
  INSERT INTO tb_accesos (id_vehiculo, id_userSys, tipoAccionAcc, espacioAsignadoAcc)
  VALUES (p_idVehiculo, p_idUserSys, p_tipoAccion, p_espacioAsignado);

  INSERT INTO tb_actividades (id_userSys, accionActi)
  VALUES (p_idUserSys, CONCAT('Registro de ', p_tipoAccion, ' del vehículo ID ', p_idVehiculo));
END //

CREATE PROCEDURE sp_listar_accesos()
BEGIN
  SELECT * FROM vw_accesos_detalle ORDER BY fechaHoraAcc DESC;
END //

CREATE PROCEDURE sp_listar_vehiculos()
BEGIN
  SELECT * FROM vw_vehiculos_detalle ORDER BY placaVeh ASC;
END //

CREATE PROCEDURE sp_listar_userpark()
BEGIN
  SELECT * FROM vw_userpark_info ORDER BY nombresUpark;
END //

DELIMITER ;

-- =========================================================
-- 🔔 TRIGGERS
-- =========================================================
DELIMITER //

CREATE TRIGGER tr_log_acceso
AFTER INSERT ON tb_accesos
FOR EACH ROW
BEGIN
  INSERT INTO tb_actividades (id_userSys, accionActi)
  VALUES (NEW.id_userSys, CONCAT('Nuevo ', NEW.tipoAccionAcc, ' registrado en acceso ID ', NEW.id_acceso));
END //

CREATE TRIGGER tr_log_userSys_update
AFTER UPDATE ON tb_usersys
FOR EACH ROW
BEGIN
  INSERT INTO tb_actividades (id_userSys, accionActi)
  VALUES (NEW.id_userSys, CONCAT('Actualización de datos de usuario Sys: ', NEW.usernameUsys));
END //

DELIMITER ;

-- =========================================================
-- 📊 CONSULTAS DE REFERENCIA
-- =========================================================
-- Usuarios activos del sistema
SELECT * FROM tb_usersys WHERE estadoUsys = 'activo';

-- Vehículos registrados por usuario
SELECT u.nombresUpark, u.apellidosUpark, v.placaVeh
FROM tb_userpark u
INNER JOIN tb_vehiculos v ON u.id_userPark = v.id_userPark;

-- Historial de accesos recientes
SELECT * FROM vw_accesos_detalle ORDER BY fechaHoraAcc DESC;

-- =========================================================
-- ✅ FINALIZAR TRANSACCIÓN
-- =========================================================
COMMIT;
-- =========================================================
-- ✅ FIN DEL SCRIPT COMPLETO
-- =========================================================
