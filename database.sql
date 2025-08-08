-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: bookify_db
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `approval_logs`
--

DROP TABLE IF EXISTS `approval_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approval_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `approver_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','declined') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approval_logs_booking_id_foreign` (`booking_id`),
  KEY `approval_logs_approver_id_foreign` (`approver_id`),
  CONSTRAINT `approval_logs_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `approvers` (`id`),
  CONSTRAINT `approval_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_logs`
--

LOCK TABLES `approval_logs` WRITE;
/*!40000 ALTER TABLE `approval_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `approval_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approvers`
--

DROP TABLE IF EXISTS `approvers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approvers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_type_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `approver_level` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approvers_asset_type_id_foreign` (`asset_type_id`),
  KEY `approvers_user_id_foreign` (`user_id`),
  CONSTRAINT `approvers_asset_type_id_foreign` FOREIGN KEY (`asset_type_id`) REFERENCES `asset_types` (`id`),
  CONSTRAINT `approvers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvers`
--

LOCK TABLES `approvers` WRITE;
/*!40000 ALTER TABLE `approvers` DISABLE KEYS */;
/*!40000 ALTER TABLE `approvers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_details`
--

DROP TABLE IF EXISTS `asset_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_type_id` bigint unsigned NOT NULL,
  `asset_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_seats` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_details_asset_type_id_foreign` (`asset_type_id`),
  CONSTRAINT `asset_details_asset_type_id_foreign` FOREIGN KEY (`asset_type_id`) REFERENCES `asset_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_details`
--

LOCK TABLES `asset_details` WRITE;
/*!40000 ALTER TABLE `asset_details` DISABLE KEYS */;
INSERT INTO `asset_details` VALUES (14,1,'Executive Board Room','2nd Floor, Admin Building',NULL,NULL,NULL,NULL,21,'2025-08-06 09:08:50','2025-08-06 09:08:50'),(15,2,'Toyota Hiace',NULL,'Toyota','Hiace GL Grandia','Silver','NAX 1234',12,'2025-08-06 09:16:26','2025-08-06 09:16:26');
/*!40000 ALTER TABLE `asset_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_files`
--

DROP TABLE IF EXISTS `asset_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_detail_id` bigint unsigned NOT NULL,
  `file_attachments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_files_asset_detail_id_foreign` (`asset_detail_id`),
  CONSTRAINT `asset_files_asset_detail_id_foreign` FOREIGN KEY (`asset_detail_id`) REFERENCES `asset_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_files`
--

LOCK TABLES `asset_files` WRITE;
/*!40000 ALTER TABLE `asset_files` DISABLE KEYS */;
INSERT INTO `asset_files` VALUES (14,14,'asset_images/1754545791_R_(1).jpg','2025-08-07 05:49:51','2025-08-07 05:49:51'),(15,15,'asset_images/1754545804_WIGO.jpg','2025-08-07 05:50:04','2025-08-07 05:50:04');
/*!40000 ALTER TABLE `asset_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_types`
--

DROP TABLE IF EXISTS `asset_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_types`
--

LOCK TABLES `asset_types` WRITE;
/*!40000 ALTER TABLE `asset_types` DISABLE KEYS */;
INSERT INTO `asset_types` VALUES (1,'Conference room','2025-08-06 09:02:29','2025-08-06 09:02:29'),(2,'Vehicle','2025-08-06 09:02:36','2025-08-06 09:02:36');
/*!40000 ALTER TABLE `asset_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booked_guests`
--

DROP TABLE IF EXISTS `booked_guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booked_guests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booked_guests_booking_id_foreign` (`booking_id`),
  CONSTRAINT `booked_guests_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booked_guests`
--

LOCK TABLES `booked_guests` WRITE;
/*!40000 ALTER TABLE `booked_guests` DISABLE KEYS */;
INSERT INTO `booked_guests` VALUES (62,70,'sawaljiahmae24@gmail.com','2025-08-06 09:31:49','2025-08-06 09:31:49'),(65,72,'sawaljiahmae24@gmail.com','2025-08-06 09:58:21','2025-08-06 09:58:21'),(66,73,'delapos.christian2002@gmail.com','2025-08-07 03:59:39','2025-08-07 03:59:39'),(67,74,'delapos.christian2002@gmail.com','2025-08-07 04:01:22','2025-08-07 04:01:22'),(68,76,'r.soliva@gaisanogroup.com','2025-08-07 06:18:31','2025-08-07 06:18:31');
/*!40000 ALTER TABLE `booked_guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_type_id` bigint unsigned NOT NULL,
  `asset_detail_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `asset_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_seats` int NOT NULL,
  `destination` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','ended') COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_approver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_approved_at` timestamp NULL DEFAULT NULL,
  `second_approver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `disapprove_reason` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `bookings_asset_type_id_foreign` (`asset_type_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_asset_detail_id_foreign` (`asset_detail_id`),
  CONSTRAINT `bookings_asset_detail_id_foreign` FOREIGN KEY (`asset_detail_id`) REFERENCES `asset_details` (`id`),
  CONSTRAINT `bookings_asset_type_id_foreign` FOREIGN KEY (`asset_type_id`) REFERENCES `asset_types` (`id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (70,1,14,32,'Executive Board Room','Meeting',21,'2nd Floor, Admin Building','2025-08-07','09:00:00','09:30:00','meeting','rejected','FPM In Charge','2025-08-06 09:55:23',NULL,NULL,'2025-08-06 09:31:49','2025-08-07 04:04:24','Sorry'),(72,2,NULL,32,NULL,'Laag',7,'Japan','2025-08-07','12:00:00','16:00:00','Laag nata','approved','Dept Head','2025-08-06 09:58:39','FPM In Charge','2025-08-06 09:59:02','2025-08-06 09:58:21','2025-08-06 09:59:08',NULL),(73,2,NULL,32,NULL,'Mag hatod og Durian sa Davao',12,'Davao','2025-08-08','00:00:00','23:59:00','Mag pa dala mog manok?','approved','Dept Head','2025-08-07 04:04:59','FPM In Charge','2025-08-07 04:06:06','2025-08-07 03:59:39','2025-08-07 04:06:11',NULL),(74,1,14,32,'Executive Board Room','Meeting',21,'2nd Floor, Admin Building','2025-08-07','14:00:00','15:00:00','Meeting unya','approved','Dept Head','2025-08-07 04:03:27','FPM In Charge','2025-08-07 04:05:24','2025-08-07 04:01:22','2025-08-07 04:05:24',NULL),(75,1,14,32,'Executive Board Room','test',7,'2nd Floor, Admin Building','2025-08-07','15:30:00','16:00:00',NULL,'approved','Dept Head','2025-08-07 06:22:02','FPM In Charge','2025-08-07 06:23:50','2025-08-07 06:16:12','2025-08-07 06:23:50',NULL),(76,2,NULL,32,NULL,'test',6,'davao','2025-08-09','08:00:00','08:30:00','test','approved','Dept Head','2025-08-07 06:22:45','FPM In Charge','2025-08-07 06:24:18','2025-08-07 06:18:31','2025-08-07 06:24:23',NULL),(80,1,14,32,'Executive Board Room','21',21,'2nd Floor, Admin Building','2025-08-19','08:00:00','08:30:00','dd','pending',NULL,NULL,NULL,NULL,'2025-08-08 02:37:28','2025-08-08 02:37:28',NULL);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (37,'Bajada','2025-08-06 08:29:24','2025-08-06 08:29:24');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_units`
--

DROP TABLE IF EXISTS `business_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_units`
--

LOCK TABLES `business_units` WRITE;
/*!40000 ALTER TABLE `business_units` DISABLE KEYS */;
INSERT INTO `business_units` VALUES (9,'Default Business Unit','2025-08-06 08:24:21','2025-08-06 08:24:21');
/*!40000 ALTER TABLE `business_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:2;',1754545847),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1754545847;',1754545847),('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb','i:2;',1754447670),('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer','i:1754447670;',1754447670),('laravel-cache-approver1@example.com|10.10.99.81','i:1;',1754474128),('laravel-cache-approver1@example.com|10.10.99.81:timer','i:1754474128;',1754474128);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_codes`
--

DROP TABLE IF EXISTS `company_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_codes`
--

LOCK TABLES `company_codes` WRITE;
/*!40000 ALTER TABLE `company_codes` DISABLE KEYS */;
INSERT INTO `company_codes` VALUES (8,'Default Company','2025-08-06 08:24:21','2025-08-06 08:24:21');
/*!40000 ALTER TABLE `company_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (24,'General','2025-08-06 08:24:21','2025-08-06 08:24:21'),(25,'ICT','2025-08-06 08:29:32','2025-08-06 08:29:32');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drivers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (9,'Christian Delapos',1,'2025-08-06 08:31:07','2025-08-06 08:31:07'),(10,'test',0,'2025-08-07 06:11:22','2025-08-07 06:11:32');
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
INSERT INTO `failed_jobs` VALUES (1,'30712a74-1321-460b-ade5-8b261b91d01e','database','default','{\"uuid\":\"30712a74-1321-460b-ade5-8b261b91d01e\",\"displayName\":\"App\\\\Mail\\\\BookingApprovedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\BookingApprovedMail\\\":3:{s:7:\\\"booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:50;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"christian.delapos6@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1754460829,\"delay\":null}','Symfony\\Component\\Mailer\\Exception\\TransportException: Connection to \"smtp.gmail.com:587\" has been closed unexpectedly. in D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\AbstractStream.php:88\nStack trace:\n#0 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(350): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\AbstractStream->readLine()\n#1 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(197): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->getFullResponse()\n#2 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(150): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->executeCommand()\n#3 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\Auth\\LoginAuthenticator.php(34): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->executeCommand()\n#4 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(243): Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\LoginAuthenticator->authenticate()\n#5 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(199): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->handleAuth()\n#6 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(150): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->doEhloCommand()\n#7 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(255): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->executeCommand()\n#8 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(281): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doHeloCommand()\n#9 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(211): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#10 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend()\n#11 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(138): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send()\n#12 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(584): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send()\n#13 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage()\n#14 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send()\n#15 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#16 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale()\n#17 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send()\n#18 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#19 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#21 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#22 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#23 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#24 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#25 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#27 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#28 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#29 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#30 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#31 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#32 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#33 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process()\n#35 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#36 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#37 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#38 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#41 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#42 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#43 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call()\n#44 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute()\n#45 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#46 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run()\n#47 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand()\n#48 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun()\n#49 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#50 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#51 D:\\Small-Proj\\schedulink\\artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#52 {main}','2025-08-06 06:15:13'),(2,'dc1194c3-aaf6-4421-aeb1-74bd711605a4','database','default','{\"uuid\":\"dc1194c3-aaf6-4421-aeb1-74bd711605a4\",\"displayName\":\"App\\\\Mail\\\\BookingApprovedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\BookingApprovedMail\\\":4:{s:7:\\\"booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:53;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:9:\\\"requester\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"christian.delapos6@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1754462229,\"delay\":null}','Symfony\\Component\\Mailer\\Exception\\TransportException: Unable to connect with STARTTLS: stream_socket_enable_crypto(): SSL: An existing connection was forcibly closed by the remote host in D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php:171\nStack trace:\n#0 [internal function]: Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\{closure}()\n#1 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php(174): stream_socket_enable_crypto()\n#2 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(185): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->startTLS()\n#3 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(150): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->doEhloCommand()\n#4 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(255): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->executeCommand()\n#5 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(281): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doHeloCommand()\n#6 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(211): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#7 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend()\n#8 D:\\Small-Proj\\schedulink\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(138): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send()\n#9 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(584): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send()\n#10 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage()\n#11 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send()\n#12 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale()\n#14 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send()\n#15 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#16 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#18 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#19 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#20 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#21 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#22 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#23 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#24 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#25 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#26 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#27 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#28 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#29 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#30 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process()\n#32 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#33 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#34 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#35 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#38 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#39 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#40 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call()\n#41 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute()\n#42 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#43 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run()\n#44 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand()\n#45 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun()\n#46 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#47 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#48 D:\\Small-Proj\\schedulink\\artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#49 {main}','2025-08-06 06:37:17'),(3,'e55baac2-562c-45d9-87b7-822d88bcfd10','database','default','{\"uuid\":\"e55baac2-562c-45d9-87b7-822d88bcfd10\",\"displayName\":\"App\\\\Mail\\\\BookingApprovedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\BookingApprovedMail\\\":4:{s:7:\\\"booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:59;s:9:\\\"relations\\\";a:4:{i:0;s:12:\\\"bookedGuests\\\";i:1;s:4:\\\"user\\\";i:2;s:11:\\\"assetDetail\\\";i:3;s:9:\\\"assetType\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:13:\\\"recipientType\\\";s:9:\\\"requester\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"christian.delapos6@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1754465422,\"delay\":null}','ErrorException: Undefined variable $recipientType in D:\\Small-Proj\\schedulink\\storage\\framework\\views\\1ef10548e502b6cb9821f5e9ce10dd73.php:12\nStack trace:\n#0 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Bootstrap\\HandleExceptions.php(256): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError()\n#1 D:\\Small-Proj\\schedulink\\storage\\framework\\views\\1ef10548e502b6cb9821f5e9ce10dd73.php(12): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->Illuminate\\Foundation\\Bootstrap\\{closure}()\n#2 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'...\')\n#3 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#4 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire()\n#5 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#6 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(88): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath()\n#7 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get()\n#8 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get()\n#9 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#10 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#11 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#12 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView()\n#13 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent()\n#14 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send()\n#15 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#16 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale()\n#17 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send()\n#18 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#19 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#21 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#22 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#23 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#24 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#25 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#27 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#28 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#29 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#30 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#31 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#32 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#33 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process()\n#35 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#36 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#37 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#38 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#41 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#42 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#43 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call()\n#44 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute()\n#45 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#46 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run()\n#47 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand()\n#48 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun()\n#49 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#50 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#51 D:\\Small-Proj\\schedulink\\artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#52 {main}\n\nNext Illuminate\\View\\ViewException: Undefined variable $recipientType (View: D:\\Small-Proj\\schedulink\\resources\\views\\emails\\booking-approved.blade.php) in D:\\Small-Proj\\schedulink\\storage\\framework\\views\\1ef10548e502b6cb9821f5e9ce10dd73.php:12\nStack trace:\n#0 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException()\n#1 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException()\n#2 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#3 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(88): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath()\n#4 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get()\n#5 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get()\n#6 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView()\n#10 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent()\n#11 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send()\n#12 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale()\n#14 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send()\n#15 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#16 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#18 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#19 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#20 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#21 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#22 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#23 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#24 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#25 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#26 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#27 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#28 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#29 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#30 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process()\n#32 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#33 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#34 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#35 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#38 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#39 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#40 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call()\n#41 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute()\n#42 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#43 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run()\n#44 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand()\n#45 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun()\n#46 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#47 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#48 D:\\Small-Proj\\schedulink\\artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#49 {main}','2025-08-06 07:30:24'),(4,'68c7969d-360b-4686-91ee-2b252f6be482','database','default','{\"uuid\":\"68c7969d-360b-4686-91ee-2b252f6be482\",\"displayName\":\"App\\\\Mail\\\\BookingApprovedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\BookingApprovedMail\\\":5:{s:7:\\\"booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:59;s:9:\\\"relations\\\";a:4:{i:0;s:12:\\\"bookedGuests\\\";i:1;s:4:\\\"user\\\";i:2;s:11:\\\"assetDetail\\\";i:3;s:9:\\\"assetType\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:13:\\\"recipientType\\\";s:5:\\\"guest\\\";s:10:\\\"guestEmail\\\";s:24:\\\"sawaljiahmae24@gmail.com\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"sawaljiahmae24@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1754465422,\"delay\":null}','ErrorException: Undefined variable $recipientType in D:\\Small-Proj\\schedulink\\storage\\framework\\views\\1ef10548e502b6cb9821f5e9ce10dd73.php:12\nStack trace:\n#0 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Bootstrap\\HandleExceptions.php(256): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError()\n#1 D:\\Small-Proj\\schedulink\\storage\\framework\\views\\1ef10548e502b6cb9821f5e9ce10dd73.php(12): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->Illuminate\\Foundation\\Bootstrap\\{closure}()\n#2 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'...\')\n#3 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#4 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire()\n#5 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#6 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath()\n#7 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get()\n#8 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get()\n#9 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#10 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#11 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#12 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView()\n#13 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent()\n#14 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send()\n#15 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#16 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale()\n#17 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send()\n#18 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#19 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#21 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#22 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#23 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#24 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#25 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#27 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#28 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#29 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#30 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#31 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#32 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#33 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process()\n#35 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#36 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#37 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#38 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#41 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#42 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#43 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call()\n#44 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute()\n#45 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#46 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run()\n#47 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand()\n#48 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun()\n#49 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#50 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#51 D:\\Small-Proj\\schedulink\\artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#52 {main}\n\nNext Illuminate\\View\\ViewException: Undefined variable $recipientType (View: D:\\Small-Proj\\schedulink\\resources\\views\\emails\\booking-approved.blade.php) in D:\\Small-Proj\\schedulink\\storage\\framework\\views\\1ef10548e502b6cb9821f5e9ce10dd73.php:12\nStack trace:\n#0 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException()\n#1 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException()\n#2 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#3 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath()\n#4 D:\\Small-Proj\\schedulink\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get()\n#5 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get()\n#6 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView()\n#10 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent()\n#11 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send()\n#12 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale()\n#14 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send()\n#15 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#16 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#18 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#19 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#20 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#21 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#22 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#23 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#24 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#25 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#26 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#27 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#28 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#29 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#30 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process()\n#32 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#33 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#34 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#35 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#38 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#39 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call()\n#40 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call()\n#41 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute()\n#42 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#43 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run()\n#44 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand()\n#45 D:\\Small-Proj\\schedulink\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun()\n#46 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#47 D:\\Small-Proj\\schedulink\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#48 D:\\Small-Proj\\schedulink\\artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#49 {main}','2025-08-06 07:30:24');
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_07_24_052628_create_business_units_table',1),(4,'2025_07_24_052631_create_company_codes_table',1),(5,'2025_07_24_052633_create_branches_table',1),(6,'2025_07_24_052636_create_departments_table',1),(7,'2025_07_24_052639_create_roles_table',1),(8,'2025_07_24_052642_create_asset_types_table',1),(9,'2025_07_24_052645_create_asset_details_table',1),(10,'2025_07_24_052649_create_asset_files_table',1),(11,'2025_07_24_052651_create_drivers_table',1),(12,'2025_07_24_052654_create_users_table',1),(13,'2025_07_24_052656_create_bookings_table',1),(14,'2025_07_24_052658_create_booked_guests_table',1),(15,'2025_07_24_052701_create_vehicle_driver_assignments_table',1),(16,'2025_07_24_052706_create_approvers_table',1),(17,'2025_07_24_052709_create_approval_logs_table',1),(18,'2025_07_28_043657_create_sessions_table',1),(19,'2025_07_29_000703_make_asset_detail_id_nullable_in_bookings_table',2),(20,'2025_07_31_101710_add_approver_fields_to_bookings_table',3),(21,'2025_07_31_133539_add_disapprove_reason_to_bookings_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` enum('Admin','Approver','User','Driver','Admin Staff') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','2025-08-06 08:25:32','2025-08-06 08:25:32'),(2,'Approver','2025-08-06 08:25:32','2025-08-06 08:25:32'),(3,'Admin Staff','2025-08-06 08:25:32','2025-08-06 08:25:32'),(4,'User','2025-08-06 08:25:32','2025-08-06 08:25:32');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('TlFgpH7gSagFXmoiQwn9QmHHyYBfo8lXywU1vKtE',32,'10.10.99.81','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOUVBcEthVFFzcWh1c3d1TmdXQjRnSERtVzRyRGtGcHdOa0RXekwydiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEwLjEwLjk5LjgxL2FkbWluL2Fzc2V0cyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTAuMTAuOTkuODEvcmVxdWVzdGVyL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMyO30=',1754639822);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `business_unit_id` bigint unsigned NOT NULL,
  `company_code_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_branch_id_foreign` (`branch_id`),
  KEY `users_department_id_foreign` (`department_id`),
  KEY `users_business_unit_id_foreign` (`business_unit_id`),
  KEY `users_company_code_id_foreign` (`company_code_id`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `users_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `users_company_code_id_foreign` FOREIGN KEY (`company_code_id`) REFERENCES `company_codes` (`id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,1,1,1,1,'System','Admin','9123456789','admin@example.com','$2y$12$z93ub436akllQI9JnSXgs.6tTPCBv.yWLJKa4beF50BZ1whiJFwTq',1,'2025-07-27 20:44:27','2025-07-27 20:44:27'),(29,37,25,9,8,3,'Admin','Staff','09486131348','adminstaff@gmail.com','$2y$12$MYS78Yv76XlJK5qcGvUw4OPLfMP8KpnmcUG7PepkT8H.AQIurGSlW',1,'2025-08-06 08:30:53','2025-08-06 08:30:53'),(30,37,25,9,8,2,'Dept','Head','09486131348','depthead@gmail.com','$2y$12$VsiEP/g8od9Gxdchi6kVc.Bg.QezV2uv7./p6KH76zVTJQQnAyvsW',1,'2025-08-06 08:31:49','2025-08-06 08:31:49'),(31,37,24,9,8,2,'FPM','In Charge','09486131348','fpmincharge@gmail.com','$2y$12$XdkQ0MveYzmkFA8qqzhQS.boZxHAS6vj1vNwZsy6UrH513UHIH3ou',1,'2025-08-06 08:32:31','2025-08-06 08:32:31'),(32,37,25,9,8,4,'Christian','Delapos','09486131348','christian.delapos6@gmail.com','$2y$12$ZyuAQ8j0WKblOjuU4ilbGuQbpGZgX4ELjQGZjEHRf8qsrYPVNdVo2',1,'2025-08-06 08:33:34','2025-08-06 08:33:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_driver_assignments`
--

DROP TABLE IF EXISTS `vehicle_driver_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_driver_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `asset_detail_id` bigint unsigned NOT NULL,
  `assigned_date` date NOT NULL,
  `assigned_by` bigint unsigned NOT NULL,
  `odometer_start` decimal(10,2) DEFAULT NULL,
  `odometer_end` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_driver_assignments_booking_id_foreign` (`booking_id`),
  KEY `vehicle_driver_assignments_driver_id_foreign` (`driver_id`),
  KEY `vehicle_driver_assignments_asset_detail_id_foreign` (`asset_detail_id`),
  KEY `vehicle_driver_assignments_assigned_by_foreign` (`assigned_by`),
  CONSTRAINT `vehicle_driver_assignments_asset_detail_id_foreign` FOREIGN KEY (`asset_detail_id`) REFERENCES `asset_details` (`id`),
  CONSTRAINT `vehicle_driver_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vehicle_driver_assignments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `vehicle_driver_assignments_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_driver_assignments`
--

LOCK TABLES `vehicle_driver_assignments` WRITE;
/*!40000 ALTER TABLE `vehicle_driver_assignments` DISABLE KEYS */;
INSERT INTO `vehicle_driver_assignments` VALUES (22,72,9,15,'2025-08-06',29,123.00,1234.00,'2025-08-06 10:00:22','2025-08-06 10:00:22'),(23,73,9,15,'2025-08-07',29,11111.00,22222.00,'2025-08-07 04:07:09','2025-08-07 04:07:09'),(24,76,9,15,'2025-08-07',29,1233.00,1234.00,'2025-08-07 06:27:19','2025-08-07 06:27:19');
/*!40000 ALTER TABLE `vehicle_driver_assignments` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-08 16:09:18
