-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: senaparking_db
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id_PassRest` int NOT NULL AUTO_INCREMENT,
  `correoUsys` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `horaFecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_PassRest`),
  KEY `idx_correo` (`correoUsys`),
  CONSTRAINT `fk_password_resets_usersys` FOREIGN KEY (`correoUsys`) REFERENCES `tb_usersys` (`correoUsys`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_accesos`
--

DROP TABLE IF EXISTS `tb_accesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_accesos` (
  `id_acceso` int NOT NULL AUTO_INCREMENT,
  `id_vehiculo` int NOT NULL,
  `id_userSys` int NOT NULL,
  `tipoAccionAcc` enum('ingreso','salida') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fechaHoraAcc` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `espacioAsignadoAcc` int DEFAULT NULL,
  PRIMARY KEY (`id_acceso`),
  KEY `idx_id_vehiculo` (`id_vehiculo`),
  KEY `idx_id_userSys` (`id_userSys`),
  KEY `idx_fecha_hora` (`fechaHoraAcc`),
  CONSTRAINT `fk_accesos_usersys` FOREIGN KEY (`id_userSys`) REFERENCES `tb_usersys` (`id_userSys`) ON DELETE RESTRICT,
  CONSTRAINT `fk_accesos_vehiculos` FOREIGN KEY (`id_vehiculo`) REFERENCES `tb_vehiculos` (`id_vehiculo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_accesos`
--

LOCK TABLES `tb_accesos` WRITE;
/*!40000 ALTER TABLE `tb_accesos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_accesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_actividades`
--

DROP TABLE IF EXISTS `tb_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_actividades` (
  `id_activi` int NOT NULL AUTO_INCREMENT,
  `id_userSys` int NOT NULL,
  `accionActi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fechaHoraActi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_activi`),
  KEY `idx_id_userSys` (`id_userSys`),
  KEY `idx_fecha_hora` (`fechaHoraActi`),
  CONSTRAINT `fk_actividades_usersys` FOREIGN KEY (`id_userSys`) REFERENCES `tb_usersys` (`id_userSys`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_actividades`
--

LOCK TABLES `tb_actividades` WRITE;
/*!40000 ALTER TABLE `tb_actividades` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_actividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_userpark`
--

DROP TABLE IF EXISTS `tb_userpark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_userpark` (
  `id_userPark` int NOT NULL AUTO_INCREMENT,
  `tipoUserUpark` enum('servidor_público','contratista','trabajador_oficial','visitante_autorizado','aprendiz','instructor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipoDocumentoUpark` enum('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeroDocumentoUpark` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombresUpark` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidosUpark` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `edificioUpark` enum('CMD','CGI','CENIGRAF') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeroContactoUpark` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estadoUpark` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  PRIMARY KEY (`id_userPark`),
  UNIQUE KEY `uq_documento_upark` (`tipoDocumentoUpark`,`numeroDocumentoUpark`),
  UNIQUE KEY `uq_num_doc_upark` (`numeroDocumentoUpark`),
  KEY `idx_edificio` (`edificioUpark`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userpark`
--

LOCK TABLES `tb_userpark` WRITE;
/*!40000 ALTER TABLE `tb_userpark` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_userpark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usersys`
--

DROP TABLE IF EXISTS `tb_usersys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_usersys` (
  `id_userSys` int NOT NULL AUTO_INCREMENT,
  `rolUsys` enum('admin','supervisor','guardia') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipoDocumentoUsys` enum('cedula_ciudadania','tarjeta_identidad','cedula_extranjeria','pasaporte','otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeroDocumentoUsys` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombresUsys` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidosUsys` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeroContactoUsys` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usernameUsys` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correoUsys` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passwordUsys` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estadoUsys` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  PRIMARY KEY (`id_userSys`),
  UNIQUE KEY `uq_documento_usys` (`tipoDocumentoUsys`,`numeroDocumentoUsys`),
  UNIQUE KEY `uq_correo_usys` (`correoUsys`),
  UNIQUE KEY `uq_num_doc_usys` (`numeroDocumentoUsys`),
  KEY `idx_username` (`usernameUsys`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usersys`
--

LOCK TABLES `tb_usersys` WRITE;
/*!40000 ALTER TABLE `tb_usersys` DISABLE KEYS */;
INSERT INTO `tb_usersys` VALUES (1,'admin','cedula_ciudadania','10983838','admin','sys','320555555','admin','admin@gmail.com','$2y$10$rvRb2KCR3osn.uONFaaqDutLcnrpRyq44k9RkKXLAtywXs6ZymQ4W','activo'),(2,'supervisor','cedula_ciudadania','10980998','supervisor','sys','322097888','supervisor','supervisor@gmail.com','$2y$10$XnF1OjPiQlMiKHN8QFcZ3OVdPQgHBNmf8gT3aEIrtCjBfL0vRWG5S','activo'),(3,'guardia','cedula_ciudadania','5987555','Diego','Duarte','350977889','guardia','guardia@gmail.com','$2y$10$LnFrsmLwm0DukhLSZMrefeYqy2UzPRdDZ2TNMetHGMw.3GNl1y346','activo');
/*!40000 ALTER TABLE `tb_usersys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_vehiculos`
--

DROP TABLE IF EXISTS `tb_vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_vehiculos` (
  `id_vehiculo` int NOT NULL AUTO_INCREMENT,
  `id_userPark` int NOT NULL,
  `placaVeh` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tarjetaPropiedadVeh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipoVeh` enum('Oficial','Automóvil','Bicicleta','Motocicleta','Otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modeloVeh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colorVeh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_vehiculo`),
  UNIQUE KEY `uq_placa` (`placaVeh`),
  KEY `idx_id_userPark` (`id_userPark`),
  CONSTRAINT `fk_vehiculos_userPark` FOREIGN KEY (`id_userPark`) REFERENCES `tb_userpark` (`id_userPark`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_vehiculos`
--

LOCK TABLES `tb_vehiculos` WRITE;
/*!40000 ALTER TABLE `tb_vehiculos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_vehiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_accesos_detalle`
--

DROP TABLE IF EXISTS `vw_accesos_detalle`;
/*!50001 DROP VIEW IF EXISTS `vw_accesos_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_accesos_detalle` AS SELECT 
 1 AS `id_acceso`,
 1 AS `tipoAccionAcc`,
 1 AS `fechaHoraAcc`,
 1 AS `espacioAsignadoAcc`,
 1 AS `placaVeh`,
 1 AS `tipoVehiculo`,
 1 AS `operador`,
 1 AS `rolOperador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_user_roles`
--

DROP TABLE IF EXISTS `vw_user_roles`;
/*!50001 DROP VIEW IF EXISTS `vw_user_roles`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_user_roles` AS SELECT 
 1 AS `id_userSys`,
 1 AS `rol`,
 1 AS `nombresUsys`,
 1 AS `apellidosUsys`,
 1 AS `usernameUsys`,
 1 AS `correoUsys`,
 1 AS `estadoUsys`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_userpark_info`
--

DROP TABLE IF EXISTS `vw_userpark_info`;
/*!50001 DROP VIEW IF EXISTS `vw_userpark_info`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_userpark_info` AS SELECT 
 1 AS `id_userPark`,
 1 AS `nombresUpark`,
 1 AS `apellidosUpark`,
 1 AS `tipoUserUpark`,
 1 AS `edificioUpark`,
 1 AS `cantidadVehiculos`,
 1 AS `estadoUpark`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_vehiculos_detalle`
--

DROP TABLE IF EXISTS `vw_vehiculos_detalle`;
/*!50001 DROP VIEW IF EXISTS `vw_vehiculos_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_vehiculos_detalle` AS SELECT 
 1 AS `id_vehiculo`,
 1 AS `placaVeh`,
 1 AS `tipoVeh`,
 1 AS `modeloVeh`,
 1 AS `colorVeh`,
 1 AS `propietario`,
 1 AS `apellidosUpark`,
 1 AS `tipoUserUpark`,
 1 AS `edificioUpark`,
 1 AS `estadoUpark`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_accesos_detalle`
--

/*!50001 DROP VIEW IF EXISTS `vw_accesos_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_accesos_detalle` AS select `a`.`id_acceso` AS `id_acceso`,`a`.`tipoAccionAcc` AS `tipoAccionAcc`,`a`.`fechaHoraAcc` AS `fechaHoraAcc`,`a`.`espacioAsignadoAcc` AS `espacioAsignadoAcc`,`v`.`placaVeh` AS `placaVeh`,`v`.`tipoVeh` AS `tipoVehiculo`,`u`.`nombresUsys` AS `operador`,`u`.`rolUsys` AS `rolOperador` from ((`tb_accesos` `a` join `tb_vehiculos` `v` on((`a`.`id_vehiculo` = `v`.`id_vehiculo`))) join `tb_usersys` `u` on((`a`.`id_userSys` = `u`.`id_userSys`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_user_roles`
--

/*!50001 DROP VIEW IF EXISTS `vw_user_roles`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_user_roles` AS select `tb_usersys`.`id_userSys` AS `id_userSys`,`tb_usersys`.`rolUsys` AS `rol`,`tb_usersys`.`nombresUsys` AS `nombresUsys`,`tb_usersys`.`apellidosUsys` AS `apellidosUsys`,`tb_usersys`.`usernameUsys` AS `usernameUsys`,`tb_usersys`.`correoUsys` AS `correoUsys`,`tb_usersys`.`estadoUsys` AS `estadoUsys` from `tb_usersys` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_userpark_info`
--

/*!50001 DROP VIEW IF EXISTS `vw_userpark_info`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_userpark_info` AS select `up`.`id_userPark` AS `id_userPark`,`up`.`nombresUpark` AS `nombresUpark`,`up`.`apellidosUpark` AS `apellidosUpark`,`up`.`tipoUserUpark` AS `tipoUserUpark`,`up`.`edificioUpark` AS `edificioUpark`,count(`v`.`id_vehiculo`) AS `cantidadVehiculos`,`up`.`estadoUpark` AS `estadoUpark` from (`tb_userpark` `up` left join `tb_vehiculos` `v` on((`up`.`id_userPark` = `v`.`id_userPark`))) group by `up`.`id_userPark` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_vehiculos_detalle`
--

/*!50001 DROP VIEW IF EXISTS `vw_vehiculos_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_vehiculos_detalle` AS select `v`.`id_vehiculo` AS `id_vehiculo`,`v`.`placaVeh` AS `placaVeh`,`v`.`tipoVeh` AS `tipoVeh`,`v`.`modeloVeh` AS `modeloVeh`,`v`.`colorVeh` AS `colorVeh`,`u`.`nombresUpark` AS `propietario`,`u`.`apellidosUpark` AS `apellidosUpark`,`u`.`tipoUserUpark` AS `tipoUserUpark`,`u`.`edificioUpark` AS `edificioUpark`,`u`.`estadoUpark` AS `estadoUpark` from (`tb_vehiculos` `v` join `tb_userpark` `u` on((`v`.`id_userPark` = `u`.`id_userPark`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-04 15:07:07
