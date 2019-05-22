-- MySQL dump 10.16  Distrib 10.2.17-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u738775250_fw
-- ------------------------------------------------------
-- Server version	10.2.17-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2018_11_20_080752_create_products_table',1),(4,'2018_11_20_080814_create_promotions_table',1),(7,'2018_11_22_105436_create_sessions_table',2),(9,'2018_11_20_080830_create_orders_table',3),(10,'2018_11_20_082831_create_order_details_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,10,1,1,'23','#E0B51A','2018-11-30 03:03:37','2018-11-30 03:03:37'),(2,4,2,1,'20','#120202','2018-11-30 04:33:06','2018-11-30 04:33:06'),(3,32,3,1,'20','#EBDCDC','2018-11-30 12:05:42','2018-11-30 12:05:42'),(4,33,4,1,'26','#36AEA2','2018-11-30 12:10:08','2018-11-30 12:10:08'),(5,33,5,1,'25','#D03E3E','2018-11-30 12:14:16','2018-11-30 12:14:16');
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` char(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `promotion_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_promotion_id_foreign` (`promotion_id`),
  CONSTRAINT `orders_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'Dai','0375509533','Hanoi',5,NULL,'2018-11-30 03:03:37','2018-11-30 12:09:09'),(2,1,'Dai','056478336','Hanoi',1,NULL,'2018-11-30 04:33:06','2018-11-30 04:33:06'),(3,1,'Dai','01365498','Hanoi',3,NULL,'2018-11-30 12:05:42','2018-11-30 12:06:45'),(4,1,'Dai Vuong','03168465','Hanoi',1,NULL,'2018-11-30 12:10:08','2018-11-30 12:10:08'),(5,1,'Dai','0312354656','Hanoi',2,NULL,'2018-11-30 12:14:16','2018-12-01 11:52:22');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (2,'e877b5c0-ed9e-11e8-90ec-71d9b4a4f2f7','POD-S3.1 Shoes','originals','men','CG6884','20,21,22','#100101,#F1E2E2',150,'2018-11-21 01:05:42','2018-11-21 01:05:42'),(3,'09993b40-ed9f-11e8-a8fe-03a31d07e884','Arkyn Primeknit Shoes','originals','women','D96760','20,21,22','#D36379,#F4E6E6',129,'2018-11-21 01:06:37','2018-11-21 01:06:37'),(4,'2831ba10-ed9f-11e8-8b9b-a19a7c1acced','Deerupt Runner Shoes','originals','kids','B41876','20,21,22','#120202',130,'2018-11-21 01:07:33','2018-11-21 01:07:33'),(5,'7a8c2a40-ed9f-11e8-8316-450d813c9be4','Pureboost X TR 3.0 Shoes','training','women','CG3529','23,24','#F4EEEE',179,'2018-11-21 01:09:47','2018-11-21 01:09:47'),(6,'987727b0-ed9f-11e8-9218-b1b998c86b78','Pureboost RBL Shoes','training','men','CM8312','24,25','#290707,#0A0909',169,'2018-11-21 01:10:37','2018-11-21 01:10:37'),(7,'b278dff0-ed9f-11e8-9770-2384eb7f6d05','Alphabounce Beyond Shoes','training','kids','B42283','20,21','#919191',145,'2018-11-21 01:11:20','2018-11-21 01:11:20'),(8,'f280e020-ed9f-11e8-b746-b7be31b985f6','X18.1 Firm Ground Gareth Bale Boots','football','women','DB2251','22,23','#F1ED08',200,'2018-11-21 01:13:08','2018-11-21 01:13:08'),(9,'1078fcc0-eda0-11e8-8a02-331d48260407','X Tango 17.3 Turf Boots','football','kids','CP9024','20,21','#646609',250,'2018-11-21 01:13:58','2018-11-21 01:13:58'),(10,'34d4c590-eda0-11e8-991e-45cd2abc7ddc','Predator Tango 18.3 Turf Boots','football','men','DB2132','23,24,25','#E49191,#E0B51A',249,'2018-11-21 01:14:59','2018-11-21 01:14:59'),(11,'7296c880-eda0-11e8-adc7-ef73258288b7','Edge Lux 2 Shoes','running','women','CG5537','22,23,24','#F88989,#A066A6',299,'2018-11-21 01:16:43','2018-11-21 01:16:43'),(12,'8e1a5dd0-eda0-11e8-86f1-39910f291c22','Pureboost Go Shoes','running','kids','B43505','20,21','#170202,#584D4D',179,'2018-11-21 01:17:29','2018-11-21 01:17:29'),(13,'adb17b70-eda0-11e8-8c66-edb907d8368f','Pureboost RBL Shoes','running','men','CM8312','20,21,23,25','#180202,#665252',230,'2018-11-21 01:18:22','2018-11-21 01:18:22'),(14,'bff518a0-eec8-11e8-9e48-21bf63ba06f3','Ultraboost Shoes','running','women','BB6496','22,24','#F4E5E5,#EF377A',180,'2018-11-23 02:37:42','2018-11-23 02:37:42'),(15,'bb2a1860-eecc-11e8-b40a-cd75c575a158','Ultraboost Mid Shoes','running','men','G26842','20','#F5F0F0,#181515',200,'2018-11-23 03:06:12','2018-11-23 03:06:12'),(16,'11dfbfe0-eecd-11e8-8465-c3abce3cf478','NIZZA SHOES','originals','women','CQ2539','22','#D33333,#22E4EB',256,'2018-11-23 03:08:37','2018-11-23 03:08:37'),(17,'ac380650-eecd-11e8-bf65-f1a2bde2dc3e','PROPHERE SHOES','originals','women','B37660','24,25','#29F5BF,#211D1D,#AE29DE,#C3DE12',453,'2018-11-23 03:12:56','2018-11-23 03:12:56'),(18,'06a12ba0-eece-11e8-8ae0-19597a779826','YUNG 1 SHOES','training','men','AQ0902','26,24,22,21','#ED2E2E,#2DB438,#2168AA',534,'2018-11-23 03:15:28','2018-11-23 03:15:28'),(19,'938aec20-eece-11e8-b325-292ae26d9469','PUREBOOST X TR 3.0 SHOES','training','women','CG3528','24,22,21,20,25,23','#FAF3F2,#352A2A',334,'2018-11-23 03:19:24','2018-11-23 03:19:24'),(20,'5ecaf7a0-eecf-11e8-983b-814cf9bcbcfa','FORTARUN X BEAT  THE WINTER SHOES','running','women','B22658','24,23,22,21,26','#DC55DE',543,'2018-11-23 03:25:05','2018-11-23 03:25:05'),(21,'4934bf20-eed1-11e8-99eb-476e5fadd48f','NMD_TS1 PRIMEKNIT SHOES','training','men','B37634','25,23,21,20','#4A2E2E',342,'2018-11-23 03:38:48','2018-11-23 03:38:48'),(22,'7cf514b0-eed2-11e8-9ff2-edf6efefe1c2','SOBAKOV SHOES','originals','men','B41967','22,24,25,20,21','#8EE398',123,'2018-11-23 03:47:24','2018-11-23 03:47:24'),(23,'268dbd50-eed4-11e8-87b9-936e35f039b3','SOBAKOV  Black SHOES','football','men','B41968','21,22,25,23','#2D2C2C,#97DCA6',233,'2018-11-23 03:59:19','2018-11-23 03:59:19'),(24,'74d93970-eed4-11e8-a0ae-8fb54f496e74','SOBAKOV  White SHOES','football','men','B41966','20,21,22,23,24','#E3EEE8',255,'2018-11-23 04:01:30','2018-11-23 04:01:30'),(25,'bb0f0110-eed4-11e8-8c17-872e0ce2822c','NMD_R1 STLT PARLEY PRIMEKNIT SHOES','running','men','AQ0943','21,23,25,26','#100F0F',444,'2018-11-23 04:03:27','2018-11-23 04:03:27'),(26,'0753e860-eed5-11e8-af33-45156ecaaf13','DEERUPT SHOES','training','men','B42063','21,23,25,26','#353C42',321,'2018-11-23 04:05:35','2018-11-23 04:05:35'),(27,'438c0470-eed5-11e8-8481-eb26485c6262','ZX 500 RM SHOES','originals','men','BB7443','21,23,24','#373131',322,'2018-11-23 04:07:16','2018-11-23 04:07:16'),(28,'f561aba0-eed7-11e8-95dc-33e3a7b2df65','NMD_RACER PRIMEKNIT SHOES','training','men','BB7040','20,22,26,25','#332D2D',355,'2018-11-23 04:26:34','2018-11-23 04:26:34'),(29,'38aa6bb0-eed8-11e8-93a8-29e936d87580','CRAZY BYW X SHOES','running','men','B42240','20,22,23,24','#262222',566,'2018-11-23 04:28:27','2018-11-23 04:28:27'),(30,'ea97e4f0-eed8-11e8-8ce8-29bbccb03c53','EQT SUPPORT MID ADV SHOES','training','men','B3741','23,24,26,25','#1E1818',556,'2018-11-23 04:33:25','2018-11-23 04:33:25'),(31,'f568f060-eed9-11e8-8c77-dbdceae886a2','X 17.3 FIRM GROUND BOOTS','football','kids','CP8993','21,20','#50EADA',711,'2018-11-23 04:40:53','2018-11-23 04:40:53'),(32,'6387ddc0-eeda-11e8-8b89-af07014a935d','POD-S3.1 SHOES','football','kids','B42071','20','#EBDCDC,#322F2F',145,'2018-11-23 04:43:57','2018-11-23 04:43:57'),(33,'17abf720-eedb-11e8-aa5b-bb736a81e0b9','DEERUPT RUNNER SHOES','training','kids','D96721','24,25,26,20,21','#281D1D,#D03E3E,#36AEA2',222,'2018-11-23 04:49:00','2018-11-23 04:49:00'),(34,'919fe2b0-eedb-11e8-8e2d-bf0c65191b4b','PROPHERE SHOES','originals','kids','B41886','20,21,22','#F5E6E6,#100D0D',223,'2018-11-23 04:52:24','2018-11-23 04:52:24'),(35,'e50ef5f0-eedc-11e8-8d95-fba4710527bb','NIZZA SHOES','originals','men','CQ2062','20,21,22','#2F59C4',200,'2018-11-23 05:01:54','2018-11-23 05:01:54'),(36,'68520aa0-eedd-11e8-9d05-ef6af1d65943','X TANGO 17.3 TURF BOOTS','originals','kids','CP9024','20,21,22,23','#CD8B2E',443,'2018-11-23 05:05:34','2018-11-23 05:05:34'),(37,'c71815d0-eedd-11e8-80e4-899ce579c8b2','NEMEZIZ 17.1 FIRM GROUND BOOTS','originals','kids','CP9152','21,20,22','#A94343,#413063',1,'2018-11-23 05:08:13','2018-11-28 14:12:13');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES (2,'ABC1234567',40),(3,'fasdf13213',30),(4,'fasdfasdf',20),(5,'fasd',20),(6,'fasdfasdf',50),(7,'vzxcvzxcvvzxcv',10),(8,'vzxcvzxcvzxcverqwer',40),(9,'v1z32x1cv3',30),(10,'kbvkjhk322',40),(11,'fasdf000022',20);
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` char(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Đại Vương','vxuandai','vxuandai@gmail.com',NULL,'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm','LGmmTfavxYYGdf1jCIRNyCqVEXRxJQJsHN6nLiL3R7M9HVfh9VZEzP4a2Og4','superadmin','2018-11-21 23:24:10','2018-11-21 23:24:10'),(2,'test','test','test@gmail.com',NULL,'$2y$10$6AXimhNYfRX.izyll0LwluON8L3/ylpRkVOQBiqqEvQnFoGHll6KW','eie5ZyMAGnr6nYsKwLmW5ctBQAzMkrH8GeeArCTPvzfsO1P7VLfWKcnafpJA','customer','2018-11-22 12:13:12','2018-11-22 12:13:12'),(3,'omega123','omega123','omega123@gmail.com',NULL,'$2y$10$.GM4WUbP32MeunAslmEmsedfScYIEmjepjW11syfcrtaBcQujGFhm','PauaFMwkn9MGljfIesAY6xa9OQxORuk5x0daSgg3mr9IiBomP7mo4wdK53tJ','customer','2018-11-23 03:14:50','2018-11-23 03:14:50'),(4,'Diutt','diutt','cogainhietdoi15@gmail.com',NULL,'$2y$10$yDsfvPih4NLacEQ1Jwlv8.MVBcaLnOqrSf60cuYavgKOKOZEWS6m6','iQ6q9IX1ekSeSUueFBVakBMUII41VRngaRWemsy5JEDCiGREuPb8FFAIFzUB','customer','2018-12-02 01:37:22','2018-12-02 01:37:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Dumping routines for database 'u738775250_fw'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-07 20:18:02
