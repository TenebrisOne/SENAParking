-- =========================================================
-- 🚀 BASE DE DATOS COMPLETA: senaparking_db
-- =========================================================
-- Autor: TenebrisOne (Cristian Ruiz)
-- Descripción: Estructura completa del sistema SENAPARKING
-- Incluye: Tablas, Relaciones, Datos iniciales, Vistas, 
-- Triggers, Procedimientos y Consultas útiles.
-- =========================================================

CREATE DATABASE IF NOT EXISTS senaparking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE senaparking_db;

-- =========================================================
-- 1️⃣ TABLAS PRINCIPALES
-- =========================================================

-- 1.1. Usuarios del sistema
CREATE TABLE IF NOT EXISTS tb_usersys (
  id_userSys INT AUTO_INCREMENT PRIMARY KEY,
  nombres_sys VARCHAR(100) NOT NULL,
  apellidos_sys VARCHAR(100) NOT NULL,
  tipo_doc ENUM('CC','CE','TI') NOT NULL,
  num_doc VARCHAR(20) UNIQUE NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  correo VARCHAR(100) UNIQUE NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('1','2','3') NOT NULL COMMENT '1=Admin, 2=Supervisor, 3=Guardia',
  estado ENUM('activo','inactivo') DEFAULT 'activo',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 1.2. Restablecimiento de contraseñas
CREATE TABLE IF NOT EXISTS password_resets (
  id_reset INT AUTO_INCREMENT PRIMARY KEY,
  correo VARCHAR(100) NOT NULL,
  token VARCHAR(255) NOT NULL,
  fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 1.3. Usuarios del parqueadero
CREATE TABLE IF NOT EXISTS tb_userpark (
  id_userPark INT AUTO_INCREMENT PRIMARY KEY,
  nombres_park VARCHAR(100) NOT NULL,
  apellidos_park VARCHAR(100) NOT NULL,
  tipo_doc ENUM('CC','CE','TI') NOT NULL,
  num_doc VARCHAR(20) UNIQUE NOT NULL,
  correo VARCHAR(100),
  telefono VARCHAR(20),
  tipo_user ENUM('residente','visitante','funcionario') NOT NULL,
  edificio VARCHAR(100),
  estado ENUM('activo','inactivo') DEFAULT 'activo',
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 1.4. Vehículos
CREATE TABLE IF NOT EXISTS tb_vehiculos (
  id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
  id_userPark INT NOT NULL,
  placa VARCHAR(10) UNIQUE NOT NULL,
  tipo ENUM('carro','moto','bicicleta') NOT NULL,
  modelo VARCHAR(50),
  color VARCHAR(50),
  FOREIGN KEY (id_userPark) REFERENCES tb_userpark(id_userPark)
) ENGINE=InnoDB;

-- 1.5. Accesos
CREATE TABLE IF NOT EXISTS tb_accesos (
  id_acceso INT AUTO_INCREMENT PRIMARY KEY,
  id_vehiculo INT NOT NULL,
  id_userSys INT NOT NULL,
  tipo_accion ENUM('ingreso','salida') NOT NULL,
  espacio_asignado INT,
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_vehiculo) REFERENCES tb_vehiculos(id_vehiculo),
  FOREIGN KEY (id_userSys) REFERENCES tb_usersys(id_userSys)
) ENGINE=InnoDB;

-- 1.6. Actividades del sistema
CREATE TABLE IF NOT EXISTS tb_actividades (
  id_actividad INT AUTO_INCREMENT PRIMARY KEY,
  id_userSys INT NOT NULL,
  accion TEXT NOT NULL,
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_userSys) REFERENCES tb_usersys(id_userSys)
) ENGINE=InnoDB;

-- =========================================================
-- 2️⃣ DATOS INICIALES
-- =========================================================

INSERT INTO tb_usersys (nombres_sys, apellidos_sys, tipo_doc, num_doc, username, correo, contrasena, rol, estado)
VALUES
('Admin', 'General', 'CC', '1000000001', 'admin', 'admin@senaparking.com', '12345', '1', 'activo'),
('Sonia', 'Supervisor', 'CC', '1000000002', 'sonia', 'sonia@senaparking.com', '12345', '2', 'activo'),
('Carlos', 'Guardia', 'CC', '1000000003', 'carlos', 'carlos@senaparking.com', '12345', '3', 'activo');

-- =========================================================
-- 3️⃣ VISTAS (vw_)
-- =========================================================

CREATE OR REPLACE VIEW vw_user_roles AS
SELECT 
  id_userSys,
  nombres_sys,
  apellidos_sys,
  username,
  correo,
  CASE rol
    WHEN '1' THEN 'Administrador'
    WHEN '2' THEN 'Supervisor'
    WHEN '3' THEN 'Guardia'
  END AS nombre_rol,
  estado
FROM tb_usersys;

CREATE OR REPLACE VIEW vw_userpark_info AS
SELECT 
  up.id_userPark,
  up.nombres_park,
  up.apellidos_park,
  up.tipo_user,
  up.edificio,
  COUNT(v.id_vehiculo) AS cantidad_vehiculos,
  up.estado
FROM tb_userpark up
LEFT JOIN tb_vehiculos v ON up.id_userPark = v.id_userPark
GROUP BY up.id_userPark;

CREATE OR REPLACE VIEW vw_vehiculos_detalle AS
SELECT 
  v.id_vehiculo,
  v.placa,
  v.tipo,
  v.modelo,
  v.color,
  up.nombres_park AS propietario,
  up.tipo_user,
  up.edificio,
  up.estado AS estado_propietario
FROM tb_vehiculos v
INNER JOIN tb_userpark up ON v.id_userPark = up.id_userPark;

CREATE OR REPLACE VIEW vw_accesos_detalle AS
SELECT 
  a.id_acceso,
  a.tipo_accion,
  a.fecha_hora,
  a.espacio_asignado,
  v.placa,
  v.tipo AS tipo_vehiculo,
  us.nombres_sys AS operador,
  CASE us.rol
    WHEN '1' THEN 'Administrador'
    WHEN '2' THEN 'Supervisor'
    WHEN '3' THEN 'Guardia'
  END AS rol_operador
FROM tb_accesos a
INNER JOIN tb_vehiculos v ON a.id_vehiculo = v.id_vehiculo
INNER JOIN tb_usersys us ON a.id_userSys = us.id_userSys;

-- =========================================================
-- 4️⃣ TRIGGERS (tr_)
-- =========================================================

DELIMITER //

CREATE TRIGGER tr_registrar_actividad_insert
AFTER INSERT ON tb_usersys
FOR EACH ROW
BEGIN
  INSERT INTO tb_actividades (id_userSys, accion)
  VALUES (NEW.id_userSys, CONCAT('Se creó un nuevo usuario: ', NEW.username));
END //

CREATE TRIGGER tr_log_acceso
AFTER INSERT ON tb_accesos
FOR EACH ROW
BEGIN
  INSERT INTO tb_actividades (id_userSys, accion)
  VALUES (NEW.id_userSys, CONCAT('Registró un ', NEW.tipo_accion, ' del vehículo con placa ', 
         (SELECT placa FROM tb_vehiculos WHERE id_vehiculo = NEW.id_vehiculo)));
END //

DELIMITER ;

-- =========================================================
-- 5️⃣ PROCEDIMIENTOS ALMACENADOS (sp_)
-- =========================================================

DELIMITER //

CREATE PROCEDURE sp_registrar_acceso(
  IN p_idVehiculo INT,
  IN p_idUserSys INT,
  IN p_tipoAccion ENUM('ingreso','salida'),
  IN p_espacioAsignado INT
)
BEGIN
  INSERT INTO tb_accesos (id_vehiculo, id_userSys, tipo_accion, espacio_asignado)
  VALUES (p_idVehiculo, p_idUserSys, p_tipoAccion, p_espacioAsignado);
END //

CREATE PROCEDURE sp_buscar_usuario_sys(IN p_username VARCHAR(50))
BEGIN
  SELECT * FROM tb_usersys WHERE username = p_username;
END //

CREATE PROCEDURE sp_vehiculos_por_usuario(IN p_idUserPark INT)
BEGIN
  SELECT * FROM tb_vehiculos WHERE id_userPark = p_idUserPark;
END //

CREATE PROCEDURE sp_historial_accesos(IN p_idVehiculo INT)
BEGIN
  SELECT * FROM tb_accesos WHERE id_vehiculo = p_idVehiculo ORDER BY fecha_hora DESC;
END //

CREATE PROCEDURE sp_resumen_estadisticas()
BEGIN
  SELECT 
    (SELECT COUNT(*) FROM tb_userpark) AS total_usuarios,
    (SELECT COUNT(*) FROM tb_vehiculos) AS total_vehiculos,
    (SELECT COUNT(*) FROM tb_accesos WHERE tipo_accion='ingreso' AND DATE(fecha_hora)=CURDATE()) AS ingresos_hoy,
    (SELECT COUNT(*) FROM tb_accesos WHERE tipo_accion='salida' AND DATE(fecha_hora)=CURDATE()) AS salidas_hoy;
END //

DELIMITER ;

-- =========================================================
-- 6️⃣ CONSULTAS COMUNES (para dashboards o reportes)
-- =========================================================

-- Vehículos activos registrados por tipo
SELECT tipo, COUNT(*) AS total FROM tb_vehiculos GROUP BY tipo;

-- Usuarios activos del parqueadero
SELECT tipo_user, COUNT(*) AS total FROM tb_userpark WHERE estado='activo' GROUP BY tipo_user;

-- Últimos ingresos y salidas
SELECT * FROM vw_accesos_detalle ORDER BY fecha_hora DESC LIMIT 10;

-- Actividad reciente del sistema
SELECT a.fecha_hora, u.username, a.accion 
FROM tb_actividades a
INNER JOIN tb_usersys u ON a.id_userSys = u.id_userSys
ORDER BY a.fecha_hora DESC
LIMIT 20;

-- Buscar vehículo por placa
SELECT * FROM vw_vehiculos_detalle WHERE placa = 'ABC123';

-- =========================================================
-- ✅ FIN DEL SCRIPT COMPLETO
-- =========================================================
