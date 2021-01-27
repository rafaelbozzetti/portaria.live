-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: portarialive
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access_log`
--

DROP TABLE IF EXISTS `access_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `people_id` int(10) unsigned NOT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_log`
--

LOCK TABLES `access_log` WRITE;
/*!40000 ALTER TABLE `access_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `access_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blocks_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocks`
--

LOCK TABLES `blocks` WRITE;
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (20190403144359,'CreateBlocks','2021-01-27 22:58:11','2021-01-27 22:58:11',0),(20190404150340,'CreateUnits','2021-01-27 23:05:47','2021-01-27 23:05:47',0),(20190404150341,'CreatePeople','2021-01-27 23:06:58','2021-01-27 23:06:58',0),(20190404150355,'CreateVisitors','2021-01-27 23:06:58','2021-01-27 23:06:59',0),(20190404150356,'CreateVehicles','2021-01-27 23:06:59','2021-01-27 23:06:59',0),(20190409012256,'CreateAccessLog','2021-01-27 23:06:59','2021-01-27 23:06:59',0),(20190410001500,'CreateUsers','2021-01-27 23:06:59','2021-01-27 23:06:59',0),(20190417230822,'CreatePhones','2021-01-27 23:06:59','2021-01-27 23:06:59',0),(20190418010000,'CreateRegistry','2021-01-27 23:06:59','2021-01-27 23:07:00',0),(20190425141720,'VisitorLog','2021-01-27 23:07:00','2021-01-27 23:07:00',0),(20190506231328,'CreatePersonView','2021-01-27 23:07:00','2021-01-27 23:07:00',0),(20190507002153,'CreateVehiclesView','2021-01-27 23:07:00','2021-01-27 23:07:00',0),(20190507002158,'CreateVisitorLogView','2021-01-27 23:07:00','2021-01-27 23:07:00',0),(20190508004604,'IncludePictureOnPerson','2021-01-27 23:07:00','2021-01-27 23:07:00',0),(20190508010205,'IncludePictureOnVisitor','2021-01-27 23:07:00','2021-01-27 23:07:00',0);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `img` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `people_unit_id_index` (`unit_id`),
  KEY `people_id_index` (`id`),
  CONSTRAINT `people_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `people`
--

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `people_information`
--

DROP TABLE IF EXISTS `people_information`;
/*!50001 DROP VIEW IF EXISTS `people_information`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `people_information` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `email`,
 1 AS `type`,
 1 AS `model`,
 1 AS `color`,
 1 AS `plate`,
 1 AS `unit_name`,
 1 AS `block_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('phone','mobile','exten') COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `people_id` int(10) unsigned DEFAULT NULL,
  `visitor_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phones_people_id_index` (`people_id`),
  KEY `phones_visitor_id_index` (`visitor_id`),
  KEY `phones_id_index` (`id`),
  CONSTRAINT `phones_people_id_foreign` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`),
  CONSTRAINT `phones_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registry`
--

DROP TABLE IF EXISTS `registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registry`
--

LOCK TABLES `registry` WRITE;
/*!40000 ALTER TABLE `registry` DISABLE KEYS */;
/*!40000 ALTER TABLE `registry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `block_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `units_block_id_index` (`block_id`),
  KEY `units_id_index` (`id`),
  CONSTRAINT `units_block_id_foreign` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@portarialive.com.br','d033e22ae348aeb5660fc2140aec35850c4da997',1,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `people_id` int(10) unsigned DEFAULT NULL,
  `visitor_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicles_people_id_index` (`people_id`),
  KEY `vehicles_visitor_id_index` (`visitor_id`),
  KEY `vehicles_id_index` (`id`),
  CONSTRAINT `vehicles_people_id_foreign` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`),
  CONSTRAINT `vehicles_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vehicles_information`
--

DROP TABLE IF EXISTS `vehicles_information`;
/*!50001 DROP VIEW IF EXISTS `vehicles_information`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vehicles_information` AS SELECT 
 1 AS `id`,
 1 AS `model`,
 1 AS `color`,
 1 AS `plate`,
 1 AS `type`,
 1 AS `people_name`,
 1 AS `visitor_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `visitor_log`
--

DROP TABLE IF EXISTS `visitor_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitor_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `people_id` int(10) unsigned NOT NULL,
  `visitor_id` int(10) unsigned NOT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitor_log`
--

LOCK TABLES `visitor_log` WRITE;
/*!40000 ALTER TABLE `visitor_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitor_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `visitor_log_information`
--

DROP TABLE IF EXISTS `visitor_log_information`;
/*!50001 DROP VIEW IF EXISTS `visitor_log_information`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `visitor_log_information` AS SELECT 
 1 AS `id`,
 1 AS `type`,
 1 AS `created_at`,
 1 AS `model`,
 1 AS `color`,
 1 AS `plate`,
 1 AS `people_name`,
 1 AS `visitor_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `service_provider` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `img` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitors_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `people_information`
--

/*!50001 DROP VIEW IF EXISTS `people_information`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `people_information` AS select `people`.`id` AS `id`,`people`.`name` AS `name`,`people`.`email` AS `email`,`people`.`type` AS `type`,`vehicles`.`model` AS `model`,`vehicles`.`color` AS `color`,`vehicles`.`plate` AS `plate`,`units`.`name` AS `unit_name`,`blocks`.`name` AS `block_name` from (((`people` left join `vehicles` on((`vehicles`.`people_id` = `people`.`id`))) left join `units` on((`units`.`id` = `people`.`unit_id`))) left join `blocks` on((`units`.`block_id` = `blocks`.`id`))) where (`people`.`type` = 1) order by `people`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vehicles_information`
--

/*!50001 DROP VIEW IF EXISTS `vehicles_information`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vehicles_information` AS select `vehicles`.`id` AS `id`,`vehicles`.`model` AS `model`,`vehicles`.`color` AS `color`,`vehicles`.`plate` AS `plate`,`vehicles`.`type` AS `type`,`people`.`name` AS `people_name`,`visitors`.`name` AS `visitor_name` from ((`vehicles` left join `people` on((`vehicles`.`people_id` = `people`.`id`))) left join `visitors` on((`vehicles`.`visitor_id` = `visitors`.`id`))) order by `people`.`id` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `visitor_log_information`
--

/*!50001 DROP VIEW IF EXISTS `visitor_log_information`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `visitor_log_information` AS select `visitor_log`.`id` AS `id`,`visitor_log`.`type` AS `type`,`visitors`.`created_at` AS `created_at`,`vehicles`.`model` AS `model`,`vehicles`.`color` AS `color`,`vehicles`.`plate` AS `plate`,`people`.`name` AS `people_name`,`visitors`.`name` AS `visitor_name` from (((`visitor_log` left join `people` on((`visitor_log`.`people_id` = `people`.`id`))) left join `visitors` on((`visitor_log`.`visitor_id` = `visitors`.`id`))) left join `vehicles` on((`visitor_log`.`vehicle_id` = `vehicles`.`id`))) order by `visitor_log`.`id` desc */;
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

-- Dump completed on 2021-01-27 20:08:14
