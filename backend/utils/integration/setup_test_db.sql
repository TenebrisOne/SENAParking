-- Script para crear base de datos de prueba
-- SENAParking Test Database

DROP DATABASE IF EXISTS senaparking_test;
CREATE DATABASE senaparking_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senaparking_test;

-- Tabla de usuarios del sistema
CREATE TABLE tb_usersys (
    id_userSys INT AUTO_INCREMENT PRIMARY KEY,
    nombresUsys VARCHAR(100) NOT NULL,
    apellidosUsys VARCHAR(100) NOT NULL,
    tipoDocumentoUsys VARCHAR(20) NOT NULL,
    numeroDocumentoUsys VARCHAR(50) NOT NULL UNIQUE,
    rolUsys VARCHAR(50) NOT NULL,
    correoUsys VARCHAR(100) NOT NULL UNIQUE,
    numeroContactoUsys VARCHAR(20),
    usernameUsys VARCHAR(50) NOT NULL UNIQUE,
    passwordUsys VARCHAR(255) NOT NULL,
    estadoUsys VARCHAR(20) DEFAULT 'activo'
);

-- Tabla de usuarios del parqueadero
CREATE TABLE tb_userpark (
    id_userPark INT AUTO_INCREMENT PRIMARY KEY,
    tipoUserUpark VARCHAR(50) NOT NULL,
    tipoDocumentoUpark VARCHAR(20) NOT NULL,
    numeroDocumentoUpark VARCHAR(50) NOT NULL UNIQUE,
    nombresUpark VARCHAR(100) NOT NULL,
    apellidosUpark VARCHAR(100) NOT NULL,
    edificioUpark VARCHAR(100),
    numeroContactoUpark VARCHAR(20),
    estadoUpark VARCHAR(20) DEFAULT 'activo'
);

-- Tabla de vehículos
CREATE TABLE tb_vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    id_userPark INT NOT NULL,
    placaVeh VARCHAR(20) NOT NULL UNIQUE,
    tarjetaPropiedadVeh VARCHAR(50),
    tipoVeh VARCHAR(50) NOT NULL,
    modeloVeh VARCHAR(50),
    colorVeh VARCHAR(50),
    FOREIGN KEY (id_userPark) REFERENCES tb_userpark(id_userPark)
);

-- Tabla de accesos
CREATE TABLE tb_accesos (
    id_acceso INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT NOT NULL,
    id_userSys INT NOT NULL,
    tipoAccionAcc VARCHAR(20) NOT NULL,
    fechaHoraAcc DATETIME NOT NULL,
    espacioAsignadoAcc INT,
    FOREIGN KEY (id_vehiculo) REFERENCES tb_vehiculos(id_vehiculo),
    FOREIGN KEY (id_userSys) REFERENCES tb_usersys(id_userSys)
);

-- Tabla de actividades
CREATE TABLE tb_actividades (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    id_userSys INT NOT NULL,
    accionActi VARCHAR(255) NOT NULL,
    fechaHoraActi DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_userSys) REFERENCES tb_usersys(id_userSys)
);

-- Insertar datos de prueba
INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, numeroContactoUsys, usernameUsys, passwordUsys, estadoUsys)
VALUES 
('Admin', 'Test', 'CC', '1234567890', 'admin', 'admin@test.com', '3001234567', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo'),
('Guardia', 'Test', 'CC', '0987654321', 'guardia', 'guardia@test.com', '3009876543', 'guardia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo');

INSERT INTO tb_userpark (tipoUserUpark, tipoDocumentoUpark, numeroDocumentoUpark, nombresUpark, apellidosUpark, edificioUpark, numeroContactoUpark, estadoUpark)
VALUES 
('Estudiante', 'CC', '1111111111', 'Juan', 'Pérez', 'Edificio A', '3111111111', 'activo'),
('Docente', 'CC', '2222222222', 'María', 'González', 'Edificio B', '3222222222', 'activo');

COMMIT;
