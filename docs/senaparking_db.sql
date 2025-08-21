DROP DATABASE IF EXISTS senaparking_db;
CREATE DATABASE senaparking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senaparking_db;

--
-- Base de datos: `senaparking_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id_PassRest` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `hora_fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_accesos`
--

CREATE TABLE `tb_accesos` (
  `id_acceso` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_userSys` int(11) NOT NULL,
  `tipo_accion` enum('ingreso','salida') NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `espacio_asignado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_accesos`
--

INSERT INTO `tb_accesos` (`id_acceso`, `id_vehiculo`, `id_userSys`, `tipo_accion`, `fecha_hora`, `espacio_asignado`) VALUES
(1, 1, 1, 'ingreso', '2025-07-04 07:26:44', 99),
(2, 1, 1, 'salida', '2025-07-04 07:27:55', 134),
(3, 2, 1, 'ingreso', '2025-07-04 07:49:11', 93),
(4, 2, 1, 'salida', '2025-07-04 07:49:46', 144),
(6, 3, 1, 'ingreso', '2025-08-16 23:38:32', 18),
(7, 3, 1, 'salida', '2025-08-17 06:39:14', 57),
(8, 1, 1, 'salida', '2025-08-16 23:50:14', 29),
(9, 3, 1, 'ingreso', '2025-08-17 02:07:42', 122),
(10, 3, 1, 'salida', '2025-08-17 02:08:34', 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_actividades`
--

CREATE TABLE `tb_actividades` (
  `id_activi` int(11) NOT NULL,
  `id_userSys` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_actividades`
--

INSERT INTO `tb_actividades` (`id_activi`, `id_userSys`, `accion`, `fecha_hora`) VALUES
(1, 1, 'Inicio de sesion', '2025-06-30 17:10:58'),
(2, 1, 'Cierre de sesion', '2025-06-30 17:11:26'),
(3, 1, 'Inicio de sesion', '2025-06-30 17:12:05'),
(4, 1, 'Cierre de sesion', '2025-06-30 17:45:53'),
(5, 1, 'Inicio de sesion', '2025-06-30 17:46:20'),
(6, 1, 'Cierre de sesion', '2025-06-30 17:51:27'),
(7, 1, 'Cierre de sesion', '2025-06-30 17:51:28'),
(8, 1, 'Inicio de sesion', '2025-06-30 17:51:43'),
(9, 1, 'Inicio de sesion', '2025-06-30 18:00:15'),
(10, 1, 'Inicio de sesion', '2025-06-30 18:03:08'),
(11, 1, 'Inicio de sesion', '2025-06-30 18:06:36'),
(12, 1, 'Inicio de sesion', '2025-07-03 23:24:48'),
(13, 1, 'Cierre de sesion', '2025-07-03 23:33:43'),
(14, 3, 'Inicio de sesion', '2025-07-03 23:34:18'),
(15, 3, 'Cierre de sesion', '2025-07-03 23:34:31'),
(16, 2, 'Inicio de sesion', '2025-07-03 23:34:58'),
(17, 2, 'Cierre de sesion', '2025-07-03 23:46:39'),
(18, 1, 'Inicio de sesion', '2025-07-03 23:47:08'),
(19, 3, 'Inicio de sesion', '2025-07-03 23:50:39'),
(20, 1, 'Cierre de sesion', '2025-07-03 23:53:18'),
(21, 1, 'Inicio de sesion', '2025-07-03 23:53:28'),
(22, 1, 'Cierre de sesion', '2025-07-03 23:53:38'),
(23, 2, 'Inicio de sesion', '2025-07-03 23:53:52'),;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_configpark`
--

CREATE TABLE `tb_configpark` (
  `id_config` int(11) NOT NULL,
  `adelante_carros` int(11) DEFAULT 20,
  `adelante_motos` int(11) DEFAULT 10,
  `adelante_ciclas` int(11) DEFAULT 50,
  `trasera_carros` int(11) DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_permisos`
--

CREATE TABLE `tb_permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_roles`
--

CREATE TABLE `tb_roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_roles`
--

INSERT INTO `tb_roles` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(3, 'guardia'),
(2, 'supervisor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_rol_permisos`
--

CREATE TABLE `tb_rol_permisos` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_userpark`
--

CREATE TABLE `tb_userpark` (
  `id_userPark` int(11) NOT NULL,
  `tipo_user` enum('servidor_público','contratista','trabajador_oficial','visitante_autorizado','aprendiz','instructor') NOT NULL,
  `tipo_documento` enum('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `nombres_park` varchar(50) NOT NULL,
  `apellidos_park` varchar(50) NOT NULL,
  `edificio` enum('CMD','CGI','CENIGRAF') NOT NULL,
  `numero_contacto` varchar(20) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_userpark`
--

INSERT INTO `tb_userpark` (`id_userPark`, `tipo_user`, `tipo_documento`, `numero_documento`, `nombres_park`, `apellidos_park`, `edificio`, `numero_contacto`, `estado`) VALUES
(1, 'instructor', 'tarjeta_identidad', '3333', 'Juanito', 'Perez', 'CMD', '123123123', 'activo'),
(2, 'trabajador_oficial', 'cedula_ciudadania', '2456876', 'Estefany', 'Barragan', 'CMD', '23456577', 'inactivo'),
(10, 'aprendiz', 'cedula_ciudadania', '2124785', 'Nicol', 'Cobo', 'CMD', '32148522', 'activo'),
(11, 'trabajador_oficial', 'cedula_ciudadania', '254564', 'Pepito', 'Rojas', 'CGI', '2345765432', 'inactivo'),
(12, 'instructor', 'cedula_ciudadania', '123456', 'Susana', 'Perez', 'CGI', '32255666', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usersys`
--

CREATE TABLE `tb_usersys` (
  `id_userSys` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `tipo_documento` enum('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `nombres_sys` varchar(50) NOT NULL,
  `apellidos_sys` varchar(50) NOT NULL,
  `numero_contacto` varchar(14) NOT NULL,
  `username` varchar(16) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_usersys`
--

INSERT INTO `tb_usersys` (`id_userSys`, `id_rol`, `tipo_documento`, `numero_documento`, `nombres_sys`, `apellidos_sys`, `numero_contacto`, `username`, `correo`, `password`, `estado`) VALUES
(1, 1, 'cedula_ciudadania', '1234567891', 'Pepito', 'Perez', '321357854', 'Pepito', 'pepito@gmail.com', '$2y$10$9qt8mRjVmArNgFxXbQcSSe23AtC4PhM9OUajaek0tFbT6qAhEjxXS', 'activo'),
(2, 3, 'cedula_ciudadania', '46896435', 'Gustavo', 'Lopez', '1232456787', 'Gustavo', 'gustavo@gmail.com', '$2y$10$5tqAXXTpBoBBZ1BlXwkFm.QwsRIxVnhW/A.UwXDVIim/uRoiK.yO6', 'activo'),
(3, 2, 'cedula_ciudadania', '1234567', 'Nicol', 'Barragan', '3145678971', 'Nicol', 'nicol@gmail.com', '$2y$10$eYjrfKF3EWvOCaGJObuO6eylAKRXqDdlK9Wyxe0cE8LYWXUXrPiN6', 'activo'),
(40, 2, 'cedula_ciudadania', '10318508', 'Dominick', 'Cobo', '3124245564', 'Dominick', 'dom@gmail.com', '$2y$10$wGG0iZ3MqO1D0W4r2RqbhetFta8u5K5y21wM1mbfcT5AmzyFNymlG', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_vehiculos`
--

CREATE TABLE `tb_vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `id_userPark` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `tarjeta_propiedad` varchar(50) NOT NULL,
  `tipo` enum('Oficial','Automóvil','Motocicleta','Moto','Otro') NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_vehiculos`
--

INSERT INTO `tb_vehiculos` (`id_vehiculo`, `id_userPark`, `placa`, `tarjeta_propiedad`, `tipo`, `modelo`, `color`) VALUES
(1, 2, 'NCB214', '', 'Automóvil', '1996', 'Rojo'),
(2, 1, 'NCB345', '', 'Automóvil', '2000', 'Azul'),
(3, 1, 'ASD/122', '', 'Automóvil', '2000', 'morado'),
(4, 11, 'THR124', '', 'Automóvil', '2003', 'blanco'),
(5, 2, 'THR125', '', 'Automóvil', '2010', 'negro'),
(6, 12, 'HYM234', '', 'Automóvil', '2002', 'Plateado'),
(7, 12, 'ABC156', '', 'Automóvil', '2011', 'Rojo'),
(8, 11, 'AWE124', '13489577415', 'Automóvil', '2011', 'Negro');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id_PassRest`),
  ADD KEY `correo` (`correo`);

--
-- Indices de la tabla `tb_accesos`
--
ALTER TABLE `tb_accesos`
  ADD PRIMARY KEY (`id_acceso`),
  ADD KEY `idx_id_vehiculo` (`id_vehiculo`),
  ADD KEY `idx_id_userSys` (`id_userSys`);

--
-- Indices de la tabla `tb_actividades`
--
ALTER TABLE `tb_actividades`
  ADD PRIMARY KEY (`id_activi`),
  ADD KEY `idx_id_userSys` (`id_userSys`);

--
-- Indices de la tabla `tb_configpark`
--
ALTER TABLE `tb_configpark`
  ADD PRIMARY KEY (`id_config`);

--
-- Indices de la tabla `tb_permisos`
--
ALTER TABLE `tb_permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD UNIQUE KEY `uq_nombre` (`nombre`);

--
-- Indices de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `uq_nombre` (`nombre`);

--
-- Indices de la tabla `tb_rol_permisos`
--
ALTER TABLE `tb_rol_permisos`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `idx_id_permiso` (`id_permiso`);

--
-- Indices de la tabla `tb_userpark`
--
ALTER TABLE `tb_userpark`
  ADD PRIMARY KEY (`id_userPark`),
  ADD UNIQUE KEY `uq_documento` (`tipo_documento`,`numero_documento`);

--
-- Indices de la tabla `tb_usersys`
--
ALTER TABLE `tb_usersys`
  ADD PRIMARY KEY (`id_userSys`),
  ADD UNIQUE KEY `uq_documento_sys` (`tipo_documento`,`numero_documento`),
  ADD KEY `idx_id_rol` (`id_rol`),
  ADD KEY `idx_corre` (`correo`);

--
-- Indices de la tabla `tb_vehiculos`
--
ALTER TABLE `tb_vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`),
  ADD UNIQUE KEY `uq_placa` (`placa`),
  ADD KEY `idx_id_userPark` (`id_userPark`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id_PassRest` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_accesos`
--
ALTER TABLE `tb_accesos`
  MODIFY `id_acceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tb_actividades`
--
ALTER TABLE `tb_actividades`
  MODIFY `id_activi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT de la tabla `tb_configpark`
--
ALTER TABLE `tb_configpark`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_permisos`
--
ALTER TABLE `tb_permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_userpark`
--
ALTER TABLE `tb_userpark`
  MODIFY `id_userPark` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tb_usersys`
--
ALTER TABLE `tb_usersys`
  MODIFY `id_userSys` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `tb_vehiculos`
--
ALTER TABLE `tb_vehiculos`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`correo`) REFERENCES `tb_usersys` (`correo`);

--
-- Filtros para la tabla `tb_accesos`
--
ALTER TABLE `tb_accesos`
  ADD CONSTRAINT `fk_accesos_usersys` FOREIGN KEY (`id_userSys`) REFERENCES `tb_usersys` (`id_userSys`),
  ADD CONSTRAINT `fk_accesos_vehiculos` FOREIGN KEY (`id_vehiculo`) REFERENCES `tb_vehiculos` (`id_vehiculo`);

--
-- Filtros para la tabla `tb_actividades`
--
ALTER TABLE `tb_actividades`
  ADD CONSTRAINT `fk_actividades_usersys` FOREIGN KEY (`id_userSys`) REFERENCES `tb_usersys` (`id_userSys`);

--
-- Filtros para la tabla `tb_rol_permisos`
--
ALTER TABLE `tb_rol_permisos`
  ADD CONSTRAINT `fk_rolpermisos_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `tb_permisos` (`id_permiso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rolpermisos_rol` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_usersys`
--
ALTER TABLE `tb_usersys`
  ADD CONSTRAINT `fk_usersys_rol` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`);

--
-- Filtros para la tabla `tb_vehiculos`
--
ALTER TABLE `tb_vehiculos`
  ADD CONSTRAINT `fk_vehiculos_userPark` FOREIGN KEY (`id_userPark`) REFERENCES `tb_userpark` (`id_userPark`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
