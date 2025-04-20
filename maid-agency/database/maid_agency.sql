/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: maid_agency
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `maids`
--

DROP TABLE IF EXISTS `maids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `maids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `availability_status` enum('available','on_leave') NOT NULL DEFAULT 'available',
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_maid_availability` (`availability_status`),
  CONSTRAINT `chk_salary` CHECK (`salary` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maids`
--

LOCK TABLES `maids` WRITE;
/*!40000 ALTER TABLE `maids` DISABLE KEYS */;
INSERT INTO `maids` VALUES
(23,'Belfast','Head Maid',8500000.00,'available','+6281187654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(24,'Dido','Assistant Head Maid',7500000.00,'available','+6281287654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(25,'Sirius','Tea Service Specialist',7000000.00,'available','+6281387654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(26,'Sheffield','Silverware Expert',7200000.00,'on_leave','+6285787654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(27,'Newcastle','Butler Trainer',6800000.00,'available','+6281487654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(28,'Edinburgh','Accounting Maid',6500000.00,'available','+6287887654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(29,'Glasgow','Security Maid',6300000.00,'available','+6285887654321','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(30,'Curacoa','Event Planning Maid',6200000.00,'available','+6281287654322','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(31,'Curlew','Floral Arrangement Maid',6000000.00,'on_leave','+6287887654322','2025-04-20 13:15:14','2025-04-20 13:15:14'),
(32,'Little Bel','Junior Maid-in-Training',4500000.00,'available','+6285887654322','2025-04-20 13:15:14','2025-04-20 13:15:14');
/*!40000 ALTER TABLE `maids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_maid` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `job_type` varchar(100) NOT NULL,
  `address_of_job` text NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `date` date DEFAULT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_transaction_status` (`status`),
  KEY `idx_transaction_user` (`id_user`),
  KEY `idx_transaction_maid` (`id_maid`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`id_maid`) REFERENCES `maids` (`id`),
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `chk_total_cost` CHECK (`total_cost` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES
(7,26,22,'Assassination','Jalan Kebangsaan No 01 RT 01 RW 01 Kecamatan','Bunuh Pak Prabowo','confirmed','2025-04-25',99900000.00,'2025-04-20 13:27:22','2025-04-20 13:31:51'),
(8,23,17,'Best Wife','Jalan Rumah Wisnu Raya 02','Please roleplay as my wife for one day, my husband is currently away at vacation and i need an accompany.','confirmed','2025-04-26',20000000.00,'2025-04-20 13:31:41','2025-04-20 13:31:41');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(13,'Ayu Lestari','+628111234567','ayu.lestari@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(14,'Budi Santoso','+628121234567','budi.santoso@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(15,'Citra Dewi','+628131234567','citra.dewi@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(16,'Dedi Gunawan','+628141234567','dedi.gunawan@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(17,'Eka Prasetya','+628151234567','eka.prasetya@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(18,'Fitri Anindya','+628161234567','fitri.anindya@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(19,'Gilang Saputra','+628171234567','gilang.saputra@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(20,'Hana Kartika','+628181234567','hana.kartika@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(21,'Indra Permana','+628191234567','indra.permana@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02'),
(22,'Joko Widodo','+628211234567','joko.widodo@example.com','2025-04-20 13:19:02','2025-04-20 13:19:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-04-20 20:33:12
