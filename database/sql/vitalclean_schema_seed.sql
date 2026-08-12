-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: vitalclean
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sys_usuarios`
--

DROP TABLE IF EXISTS `sys_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_usuarios` (
  `id_usuario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('VENDEDOR','OPERADOR','ADMIN') NOT NULL,
  `nombre_completo` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `sys_usuarios_username_unique` (`username`),
  UNIQUE KEY `sys_usuarios_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_usuarios`
--

/*!40000 ALTER TABLE `sys_usuarios` DISABLE KEYS */;
INSERT INTO `sys_usuarios` (`id_usuario`, `username`, `password_hash`, `rol`, `nombre_completo`, `email`, `activo`, `remember_token`, `created_at`, `updated_at`) VALUES (1,'admin.vitalclean','$2y$12$Z1BrMjijwtIo9yKLz4m8/.T5gKKSHmYcgorhhzrKjhwtwCIU/EU3u','ADMIN','Administrador Vital Clean','admin@vitalclean.mx',1,'GG8DEV8vPv','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(2,'vendedor.vitalclean','$2y$12$Z1BrMjijwtIo9yKLz4m8/.T5gKKSHmYcgorhhzrKjhwtwCIU/EU3u','VENDEDOR','Vendedor Ruta 1','vendedor@vitalclean.mx',1,'ZWDShLncNY','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(3,'operador.vitalclean','$2y$12$Z1BrMjijwtIo9yKLz4m8/.T5gKKSHmYcgorhhzrKjhwtwCIU/EU3u','OPERADOR','Operador de Planta','operador@vitalclean.mx',1,'vsev8tG1YW','2026-08-12 04:05:19','2026-08-12 04:05:19');
/*!40000 ALTER TABLE `sys_usuarios` ENABLE KEYS */;

--
-- Table structure for table `cat_clientes`
--

DROP TABLE IF EXISTS `cat_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_clientes` (
  `id_cliente` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_comercial` varchar(150) NOT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email_facturacion` varchar(100) DEFAULT NULL,
  `estatus_credito` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `cat_clientes_rfc_unique` (`rfc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_clientes`
--

/*!40000 ALTER TABLE `cat_clientes` DISABLE KEYS */;
INSERT INTO `cat_clientes` (`id_cliente`, `nombre_comercial`, `razon_social`, `rfc`, `direccion`, `telefono`, `email_facturacion`, `estatus_credito`, `created_at`, `updated_at`) VALUES (1,'Grand Hotel de Mérida','Grand Hotel de Mérida S.A. de C.V.','GHM850101XYZ','Calle 60 #450, Centro, Mérida, Yucatán, C.P. 97000','9997808557','facturacion@grandhotelmerida.com.mx',1,'2026-08-12 04:05:19','2026-08-12 04:05:19');
/*!40000 ALTER TABLE `cat_clientes` ENABLE KEYS */;

--
-- Table structure for table `cat_servicios`
--

DROP TABLE IF EXISTS `cat_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_servicios` (
  `id_servicio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `unidad` enum('PZA','KG') NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_servicios`
--

/*!40000 ALTER TABLE `cat_servicios` DISABLE KEYS */;
INSERT INTO `cat_servicios` (`id_servicio`, `descripcion`, `unidad`, `categoria`, `created_at`, `updated_at`) VALUES (1,'Sábana King Size','PZA','Hotelería','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(2,'Sábana Queen','PZA','Hotelería','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(3,'Toalla de Baño','PZA','Hotelería','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(4,'Toalla de Manos','PZA','Hotelería','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(5,'Funda de Almohada','PZA','Hotelería','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(6,'Mantel','KG','Restaurante','2026-08-12 04:05:19','2026-08-12 04:05:19'),
(7,'Uniforme Personal','PZA','Uniformes','2026-08-12 04:05:19','2026-08-12 04:05:19');
/*!40000 ALTER TABLE `cat_servicios` ENABLE KEYS */;

--
-- Table structure for table `rel_tarifas_cliente`
--

DROP TABLE IF EXISTS `rel_tarifas_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_tarifas_cliente` (
  `id_tarifa` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_servicio` bigint(20) unsigned NOT NULL,
  `precio_pactado` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tarifa`),
  UNIQUE KEY `rel_tarifas_cliente_id_cliente_id_servicio_unique` (`id_cliente`,`id_servicio`),
  KEY `rel_tarifas_cliente_id_servicio_foreign` (`id_servicio`),
  CONSTRAINT `rel_tarifas_cliente_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cat_clientes` (`id_cliente`),
  CONSTRAINT `rel_tarifas_cliente_id_servicio_foreign` FOREIGN KEY (`id_servicio`) REFERENCES `cat_servicios` (`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_tarifas_cliente`
--

/*!40000 ALTER TABLE `rel_tarifas_cliente` DISABLE KEYS */;
INSERT INTO `rel_tarifas_cliente` (`id_tarifa`, `id_cliente`, `id_servicio`, `precio_pactado`, `created_at`, `updated_at`) VALUES (1,1,1,16.55,'2026-08-12 04:05:19','2026-08-12 04:05:19'),
(2,1,2,51.06,'2026-08-12 04:05:19','2026-08-12 04:05:19'),
(3,1,3,41.12,'2026-08-12 04:05:19','2026-08-12 04:05:19'),
(4,1,4,37.12,'2026-08-12 04:05:19','2026-08-12 04:05:19'),
(5,1,5,5.92,'2026-08-12 04:05:19','2026-08-12 04:05:19'),
(6,1,6,55.41,'2026-08-12 04:05:19','2026-08-12 04:05:19'),
(7,1,7,54.63,'2026-08-12 04:05:19','2026-08-12 04:05:19');
/*!40000 ALTER TABLE `rel_tarifas_cliente` ENABLE KEYS */;

--
-- Table structure for table `ope_notas_remision`
--

DROP TABLE IF EXISTS `ope_notas_remision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ope_notas_remision` (
  `folio_sistema` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folio_fisico` varchar(20) NOT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_vendedor` bigint(20) unsigned NOT NULL,
  `fecha_recoleccion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_entrega_prog` date DEFAULT NULL,
  `estatus_orden` enum('RUTA','PLANTA_RECIBIDO','PROCESO','LISTO','ENTREGADO','CANCELADO') NOT NULL DEFAULT 'RUTA',
  `firma_cliente` mediumblob DEFAULT NULL,
  `geolocalizacion` varchar(100) DEFAULT NULL,
  `conteo_bloqueado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`folio_sistema`),
  KEY `ope_notas_remision_id_cliente_foreign` (`id_cliente`),
  KEY `ope_notas_remision_id_vendedor_foreign` (`id_vendedor`),
  KEY `ope_notas_remision_folio_fisico_index` (`folio_fisico`),
  CONSTRAINT `ope_notas_remision_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cat_clientes` (`id_cliente`),
  CONSTRAINT `ope_notas_remision_id_vendedor_foreign` FOREIGN KEY (`id_vendedor`) REFERENCES `sys_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ope_notas_remision`
--

/*!40000 ALTER TABLE `ope_notas_remision` DISABLE KEYS */;
/*!40000 ALTER TABLE `ope_notas_remision` ENABLE KEYS */;

--
-- Table structure for table `ope_detalle_remision`
--

DROP TABLE IF EXISTS `ope_detalle_remision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ope_detalle_remision` (
  `id_detalle` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folio_sistema` bigint(20) unsigned NOT NULL,
  `id_servicio` bigint(20) unsigned NOT NULL,
  `cantidad_entrada` int(11) NOT NULL DEFAULT 0,
  `precio_aplicado` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `observacion_prenda` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `ope_detalle_remision_folio_sistema_foreign` (`folio_sistema`),
  KEY `ope_detalle_remision_id_servicio_foreign` (`id_servicio`),
  CONSTRAINT `ope_detalle_remision_folio_sistema_foreign` FOREIGN KEY (`folio_sistema`) REFERENCES `ope_notas_remision` (`folio_sistema`),
  CONSTRAINT `ope_detalle_remision_id_servicio_foreign` FOREIGN KEY (`id_servicio`) REFERENCES `cat_servicios` (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ope_detalle_remision`
--

/*!40000 ALTER TABLE `ope_detalle_remision` DISABLE KEYS */;
/*!40000 ALTER TABLE `ope_detalle_remision` ENABLE KEYS */;

--
-- Table structure for table `ope_incidencias`
--

DROP TABLE IF EXISTS `ope_incidencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ope_incidencias` (
  `id_incidencia` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_detalle` bigint(20) unsigned NOT NULL,
  `foto_evidencia` varchar(255) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_incidencia`),
  KEY `ope_incidencias_id_detalle_foreign` (`id_detalle`),
  CONSTRAINT `ope_incidencias_id_detalle_foreign` FOREIGN KEY (`id_detalle`) REFERENCES `ope_detalle_remision` (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ope_incidencias`
--

/*!40000 ALTER TABLE `ope_incidencias` DISABLE KEYS */;
/*!40000 ALTER TABLE `ope_incidencias` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-12  4:05:38
