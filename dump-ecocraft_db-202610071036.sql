-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: ecocraft_db
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id_admins` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_admins`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Shandy Shulton Shihab','shandy@gmail.com','super_admin','081290983455','$2y$10$5S6oVX2D6YZJ2GYWpeHbReXU/nK8RWpwWpE2HxYn5W0YS8cKO/d1C','Jl. Mesjid II Street No. 1','male',NULL,'2026-09-06 20:48:27','2026-09-14 00:59:52'),(2,'Admin EcoCraft','admin@ecocraft.test','admin','081200000000','$2y$10$dUjdNeL1rB6n5KdUJPDafuDr3Yauu41jeGE1HjlMuGgXCrxsAoJl2','Kantor EcoCraft','other',NULL,'2026-09-14 00:59:52','2026-09-14 00:59:52');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coin_transactions`
--

DROP TABLE IF EXISTS `coin_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coin_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `type` enum('earn','redeem') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `balance_after` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coin_transactions_order_id_foreign` (`order_id`),
  KEY `coin_transactions_customer_id_index` (`customer_id`),
  CONSTRAINT `coin_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE CASCADE,
  CONSTRAINT `coin_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=295 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coin_transactions`
--

LOCK TABLES `coin_transactions` WRITE;
/*!40000 ALTER TABLE `coin_transactions` DISABLE KEYS */;
INSERT INTO `coin_transactions` VALUES (72,1,596,'earn',690,690,'Koin dari pesanan ORD-20260916070538-9310','2026-09-16 00:05:38','2026-09-16 00:05:38'),(94,724,713,'earn',185,185,'Koin dari pesanan ORD-20260916071250-9422','2026-09-16 00:12:50','2026-09-16 00:12:50'),(95,724,714,'earn',185,370,'Koin dari pesanan ORD-20260916071334-6535','2026-09-16 00:13:34','2026-09-16 00:13:34'),(194,1,1450,'earn',420,1110,'Koin dari pesanan ORD-20260916090329-4981','2026-09-16 02:03:29','2026-09-16 02:03:29'),(293,1,2248,'earn',189,1299,'Koin dari pesanan ORD-20260917043800-9162','2026-09-16 21:38:00','2026-09-16 21:38:00'),(294,1,2249,'earn',189,1488,'Koin dari pesanan ORD-20260917043843-2011','2026-09-16 21:38:43','2026-09-16 21:38:43');
/*!40000 ALTER TABLE `coin_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `community_stories`
--

DROP TABLE IF EXISTS `community_stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `community_stories` (
  `id_stories` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_stories`),
  UNIQUE KEY `community_stories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community_stories`
--

LOCK TABLES `community_stories` WRITE;
/*!40000 ALTER TABLE `community_stories` DISABLE KEYS */;
INSERT INTO `community_stories` VALUES (1,'Sanggar Kayu Resik, Jepara','sanggar-kayu-resik-jepara','Kayu & Mebel','Potongan mebel diolah menjadi piranti meja yang bernilai dan memberdayakan pengrajin pesisir.','Berawal dari keprihatinan atas potongan kayu yang terbuang di bengkel-bengkel mebel Jepara, Sanggar Kayu Resik mengumpulkan sisa produksi itu menjadi bahan baku baru. Setiap potongan dipilah berdasarkan serat dan kekuatannya, lalu dirancang ulang menjadi piranti meja yang utuh secara visual maupun struktural.\n\nDi sanggar ini, pengrajin pesisir dilatih membaca karakter kayu sebelum mengolahnya. Hasilnya bukan sekadar furnitur — setiap meja membawa cerita kayu yang diberi kesempatan kedua, sekaligus menjadi sumber penghidupan bagi perajin lokal Jepara.','assets/images/collection/banner welcome.png','Di Balik Proses','kayu',1,1,1,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(2,'Anyaman Lestari, Tasikmalaya','anyaman-lestari-tasikmalaya','Anyaman','Serat alami dipintal dengan teknik tradisional untuk menghadirkan koleksi rumah yang hangat.','Di Tasikmalaya, Anyaman Lestari menjaga teknik anyam tradisional tetap hidup. Serat alami seperti mendong dan pandan dipintal serta dianyam dengan pola warisan turun-temurun, menghasilkan koleksi rumah yang hangat dan berkarakter.\n\nSetiap helai serat melewati proses perendaman dan penjemuran alami sebelum dianyam. Pengerjaan sepenuhnya manual membuat setiap produk sedikit berbeda — ciri khas yang justru menjadi nilainya.','assets/images/collection/arrivals1.png','Di Balik Proses','anyaman',1,1,2,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(3,'Rumah Rotan, Cirebon','rumah-rotan-cirebon','Rotan','Material rotan pilihan dirawat menjadi dekorasi sederhana untuk ruang dengan karakter.','Cirebon lama dikenal sebagai pusat rotan Nusantara, dan Rumah Rotan meneruskan tradisi itu dengan pendekatan yang lebih sadar lingkungan. Rotan pilihan dirawat, dikeringkan secara alami, lalu dianyam menjadi dekorasi sederhana untuk ruang berkarakter.\n\nRotan adalah material yang tumbuh cepat dan ramah lingkungan. Dengan perawatan yang tepat, produk Rumah Rotan awet digunakan bertahun-tahun dan mudah diperbaiki — bentuk lain dari keberlanjutan.','assets/images/collection/arrivals2.png','Bahan Berkelanjutan','rotan',1,1,3,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(4,'Kain Pesisir, Pekalongan','kain-pesisir-pekalongan','Tekstil','Sisa kain produksi dipadukan kembali menjadi aksesori yang lebih tahan lama.','Kain Pesisir lahir dari sisa kain produksi batik dan tenun Pekalongan yang semula berakhir di tumpukan limbah. Kain-kain bermotif itu dipilah berdasarkan warna dan teksturnya, lalu dipadukan kembali menjadi aksesori baru yang lebih tahan lama.\n\nPerajin di baliknya bekerja dengan palet terbatas dari sisa produksi, sehingga tidak ada dua aksesori yang benar-benar sama. Pendekatan ini menekan limbah tekstil sekaligus merayakan motif pesisir yang kaya.','assets/images/collection/arrivals3.png','Kriya Nusantara','tekstil',1,1,4,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(5,'Bambu Bumi, Yogyakarta','bambu-bumi-yogyakarta','Bambu','Peralatan harian dari bambu tumbuh cepat dengan proses produksi yang lebih ringan.','Bambu Bumi mengolah bambu tumbuh cepat dari kaki Gunung Merapi menjadi peralatan harian yang ringan dan kuat. Prosesnya mengutamakan pengeringan bertahap dan perlakuan alami agar bambu tahan lama tanpa bahan kimia berlebih.\n\nPemanenan bambu yang bijak — memilih batang tua dan menyisakan rumpun muda — membuat sanggar ini tetap lestari. Produknya membuktikan bahwa peralatan sederhana bisa dibuat dengan proses yang lebih ringan bagi bumi.','assets/images/collection/arrivals4.png','Di Balik Proses','bambu',1,1,5,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(6,'Kriya Tanah, Kasongan','kriya-tanah-kasongan','Keramik','Tanah liat lokal dibentuk menjadi benda pakai yang tenang, kuat, dan mudah dirawat.','Kasongan telah lama menjadi kampung perajin tanah liat. Kriya Tanah mengambil lempung lokal, membentuknya dengan tangan, lalu membakarnya dalam tungku sederhana menjadi benda pakai yang tenang, kuat, dan mudah dirawat.\n\nGlazur yang digunakan berasal dari campuran abu dan tanah lokal, memberi warna alami yang hangat. Dari cangkir hingga wadah penyimpanan, setiap karya adalah hasil dialog antara tangan perajin dan material setempat.','assets/images/collection/arrivals5.png','Bahan Berkelanjutan','keramik',1,1,6,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(7,'Daur Ulang Kota, Bandung','daur-ulang-kota-bandung','Daur Ulang','Bahan kemasan bekas dipilah dan dirakit menjadi produk kecil yang fungsional.','Daur Ulang Kota mengubah bahan kemasan bekas dari rumah-rumah di Bandung menjadi produk kecil yang fungsional. Kertas, karton, dan plastik kemasan dipilah, dibersihkan, lalu dirakit menjadi barang yang benar-benar dipakai sehari-hari.\n\nKomunitas ini bekerja sama dengan bank sampah lingkungan sekitar untuk memasok bahan baku, sehingga alurnya ikut mendorong budaya memilah dari sumbernya. Setiap produk jadi membawa cerita tentang sampah yang diberi nilai baru.','assets/images/collection/arrivals6.png','Daur Ulang','daur ulang',1,1,7,'2026-09-09 00:54:33','2026-09-09 01:04:31'),(8,'Kayu Pulih, Surabaya','kayu-pulih-surabaya','Daur Ulang','Kayu lama diberi kesempatan kedua melalui bentuk baru yang cocok untuk rumah modern.','Kayu Pulih mengumpulkan kayu bekas — palet, bongkaran rumah, dan limbah pertukangan dari Surabaya — lalu memberinya kesempatan kedua lewat bentuk baru yang cocok untuk rumah modern.\n\nSetiap batang dibersihkan, disortir, dan diolah ulang tanpa menghilangkan karakter lamanya. Bekas paku dan serat yang menua justru menjadi jejak yang membuat setiap karyanya unik.','assets/images/collection/arrivals7.png','Daur Ulang','kayu',1,1,8,'2026-09-09 00:54:33','2026-09-09 01:04:31');
/*!40000 ALTER TABLE `community_stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `community_story_comments`
--

DROP TABLE IF EXISTS `community_story_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `community_story_comments` (
  `id_comments` bigint unsigned NOT NULL AUTO_INCREMENT,
  `story_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_comments`),
  KEY `community_story_comments_story_id_foreign` (`story_id`),
  KEY `community_story_comments_customer_id_foreign` (`customer_id`),
  CONSTRAINT `community_story_comments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE SET NULL,
  CONSTRAINT `community_story_comments_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `community_stories` (`id_stories`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community_story_comments`
--

LOCK TABLES `community_story_comments` WRITE;
/*!40000 ALTER TABLE `community_story_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `community_story_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courier_users`
--

DROP TABLE IF EXISTS `courier_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courier_users` (
  `id_courier_users` bigint unsigned NOT NULL AUTO_INCREMENT,
  `courier_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_courier_users`),
  UNIQUE KEY `courier_users_email_unique` (`email`),
  KEY `courier_users_courier_id_foreign` (`courier_id`),
  CONSTRAINT `courier_users_courier_id_foreign` FOREIGN KEY (`courier_id`) REFERENCES `couriers` (`id_couriers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=646 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courier_users`
--

LOCK TABLES `courier_users` WRITE;
/*!40000 ALTER TABLE `courier_users` DISABLE KEYS */;
INSERT INTO `courier_users` VALUES (1,8,'Bagas Kurir','kurir@ecocraft.test','081200000099','$2y$10$aSMXpqMhLWuBCgYdiXjGXe2YyfAK9wbzAid6xAmG/ZCZBXtRhZKq6',1,NULL,'2026-09-15 21:48:03','2026-09-15 21:48:03');
/*!40000 ALTER TABLE `courier_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `couriers`
--

DROP TABLE IF EXISTS `couriers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `couriers` (
  `id_couriers` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tracking_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_local_delivery` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_couriers`),
  UNIQUE KEY `couriers_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=977 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `couriers`
--

LOCK TABLES `couriers` WRITE;
/*!40000 ALTER TABLE `couriers` DISABLE KEYS */;
INSERT INTO `couriers` VALUES (1,'jne','JNE','https://www.jne.co.id',0,1,1,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(2,'jnt','J&T Express','https://www.jet.co.id',0,1,2,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(3,'sicepat','SiCepat Ekspres','https://www.sicepat.com',0,1,3,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(4,'anteraja','AnterAja','https://anteraja.id',0,1,4,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(5,'ninja','Ninja Xpress','https://www.ninjaxpress.co.id',0,1,5,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(6,'pos','POS Indonesia','https://www.posindonesia.co.id',0,1,6,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(7,'lion','Lion Parcel','https://lionparcel.com',0,1,7,'2026-09-15 20:32:17','2026-09-15 20:32:17'),(8,'ecocraft_local','Kurir Lokal EcoCraft',NULL,1,1,8,'2026-09-15 20:32:17','2026-09-15 21:43:28');
/*!40000 ALTER TABLE `couriers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_addresses`
--

DROP TABLE IF EXISTS `customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_addresses` (
  `id_addresses` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_addresses`),
  KEY `customer_addresses_customer_id_is_default_index` (`customer_id`,`is_default`),
  CONSTRAINT `customer_addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=802 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_addresses`
--

LOCK TABLES `customer_addresses` WRITE;
/*!40000 ALTER TABLE `customer_addresses` DISABLE KEYS */;
INSERT INTO `customer_addresses` VALUES (12,1,'Rumah','SHANDY SHULTON SHIHAB','081212181182','Kp. Bulak Teko, Taman RT 010','Jakarta Barat','DKI Jakarta','11840',1,'2026-09-14 21:45:15','2026-09-14 21:45:15'),(204,724,NULL,'QA Checkout','081200000200','Jl. Uji 1','Yogyakarta','DIY','55111',1,'2026-09-15 22:04:15','2026-09-15 22:04:15');
/*!40000 ALTER TABLE `customer_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_vouchers`
--

DROP TABLE IF EXISTS `customer_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `source` enum('claim','reward') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'claim',
  `status` enum('available','used') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `order_id` bigint unsigned DEFAULT NULL,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_vouchers_voucher_id_foreign` (`voucher_id`),
  KEY `customer_vouchers_order_id_foreign` (`order_id`),
  KEY `customer_vouchers_customer_id_status_index` (`customer_id`,`status`),
  CONSTRAINT `customer_vouchers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE CASCADE,
  CONSTRAINT `customer_vouchers_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE SET NULL,
  CONSTRAINT `customer_vouchers_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_vouchers`
--

LOCK TABLES `customer_vouchers` WRITE;
/*!40000 ALTER TABLE `customer_vouchers` DISABLE KEYS */;
INSERT INTO `customer_vouchers` VALUES (7,1,1,'claim','used',596,'2026-09-14 03:01:27','2026-09-16 00:05:38','2026-09-14 03:01:27','2026-09-16 00:05:38'),(8,1,724,'claim','available',NULL,'2026-09-15 23:52:01',NULL,'2026-09-15 23:52:01','2026-09-15 23:52:01');
/*!40000 ALTER TABLE `customer_vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id_customers` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_customers` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coin_balance` int unsigned NOT NULL DEFAULT '0',
  `reward_milestones` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_customers`)
) ENGINE=InnoDB AUTO_INCREMENT=3276 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'SHANDY SHULTON SHIHAB','ssshandy60@gmail.com','081212181182','2005-02-04','male','Kp. Bulak Teko','Jakarta Barat','DKI Jakarta','$2y$10$T01VuzRhHXpS68dXIMd95uftQNjbyThr.2gB3Kk5N46WBSXSatY5y',NULL,1488,0,'2026-09-07 00:00:57','2026-09-16 21:38:43'),(724,'QA Checkout','qa-checkout@example.test','081200000200','1995-01-01','female','Jl. Uji 1','Yogyakarta','DIY','$2y$10$85OgSageTAB7ptCmVzu6aOShIhePSPXJq.i0AhSBjncyIrXipBwMO',NULL,370,0,'2026-09-15 22:03:19','2026-09-16 00:13:34');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impact_factors`
--

DROP TABLE IF EXISTS `impact_factors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `impact_factors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `material_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waste_per_item` decimal(8,2) NOT NULL DEFAULT '1.20',
  `carbon_per_item` decimal(8,2) NOT NULL DEFAULT '2.70',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `impact_factors_material_type_unique` (`material_type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impact_factors`
--

LOCK TABLES `impact_factors` WRITE;
/*!40000 ALTER TABLE `impact_factors` DISABLE KEYS */;
INSERT INTO `impact_factors` VALUES (1,'Bambu',1.50,2.20,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(2,'Rotan',1.30,2.00,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(3,'Kayu',2.50,3.80,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(4,'Tanah Liat',1.00,1.60,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(5,'Tenun',0.80,1.40,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(6,'Anyaman Mendong',0.90,1.30,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(7,'Plastik Daur Ulang',2.00,3.20,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(8,'Kertas Daur Ulang',0.60,0.90,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(9,'Logam',3.00,4.50,'2026-09-14 02:09:13','2026-09-14 02:09:13'),(10,'Kaca',1.80,2.60,'2026-09-14 02:09:13','2026-09-14 02:09:13');
/*!40000 ALTER TABLE `impact_factors` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_04_24_130744_create_admins_table',1),(6,'2025_04_24_130756_create_customers_table',1),(7,'2025_04_24_130804_create_sellers_table',1),(8,'2025_06_01_085926_create_products_table',1),(9,'2025_06_02_000000_create_orders_table',2),(10,'2025_06_02_000001_create_order_items_table',2),(11,'2026_09_09_000000_create_community_stories_table',3),(12,'2026_09_09_000001_add_detail_fields_to_community_stories_table',4),(13,'2026_09_09_000002_create_community_story_comments_table',5),(16,'2026_09_14_000001_create_product_inquiries_table',6),(17,'2026_09_14_000002_create_product_inquiry_messages_table',6),(18,'2026_09_14_000003_add_role_to_admins_table',7),(19,'2026_09_14_000004_add_impact_factors_to_products_table',8),(20,'2026_09_14_000005_create_impact_factors_table',9),(21,'2026_09_14_000006_change_image_gallery_to_text_on_products',10),(22,'2026_09_14_000007_add_coins_to_customers_table',11),(23,'2026_09_14_000008_create_vouchers_table',11),(24,'2026_09_14_000009_create_customer_vouchers_table',11),(25,'2026_09_14_000010_create_coin_transactions_table',11),(26,'2026_09_14_000011_add_discounts_to_orders_table',11),(27,'2026_09_15_000001_create_warranty_claims_table',12),(28,'2026_09_15_000002_create_customer_addresses_table',13),(29,'2026_09_16_000001_create_couriers_table',14),(30,'2026_09_16_000002_create_shipments_table',14),(31,'2026_09_16_000003_create_order_tracking_events_table',14),(32,'2026_09_16_000004_backfill_shipments_for_existing_orders',14),(33,'2026_09_16_000005_add_local_delivery_to_couriers_table',15),(34,'2026_09_16_000006_add_delivery_proof_to_shipments_table',15),(35,'2026_09_16_000007_add_customer_source_to_order_tracking_events_table',15),(36,'2026_09_16_000008_create_courier_users_table',16),(37,'2026_09_16_000009_add_courier_user_to_shipments_table',16),(38,'2026_09_16_000010_add_payment_to_orders_table',17);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_seller_id_foreign` (`seller_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id_products`) ON DELETE SET NULL,
  CONSTRAINT `order_items_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id_sellers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2052 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (10,1,1,1,'Vas Bumi Sekam Kasongan',5,185000.00,925000.00,'2026-09-14 21:20:04','2026-09-14 21:20:04'),(649,596,3,1,'Rak Dinding Rotan Cirebon',2,345000.00,690000.00,'2026-09-16 00:05:38','2026-09-16 00:05:38'),(739,713,1,1,'Vas Bumi Sekam Kasongan',1,185000.00,185000.00,'2026-09-16 00:12:50','2026-09-16 00:12:50'),(740,714,1,1,'Vas Bumi Sekam Kasongan',1,185000.00,185000.00,'2026-09-16 00:13:34','2026-09-16 00:13:34'),(1364,1450,5,1,'Lampu Gantung Bambu',1,420000.00,420000.00,'2026-09-16 02:03:29','2026-09-16 02:03:29'),(2050,2248,4,1,'Tas Anyaman Mendong',1,189000.00,189000.00,'2026-09-16 21:38:00','2026-09-16 21:38:00'),(2051,2249,4,1,'Tas Anyaman Mendong',1,189000.00,189000.00,'2026-09-16 21:38:43','2026-09-16 21:38:43');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_tracking_events`
--

DROP TABLE IF EXISTS `order_tracking_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_tracking_events` (
  `id_events` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('system','seller','admin','courier','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `happened_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_events`),
  KEY `order_tracking_events_shipment_id_happened_at_index` (`shipment_id`,`happened_at`),
  CONSTRAINT `order_tracking_events_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id_shipments`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2921 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_tracking_events`
--

LOCK TABLES `order_tracking_events` WRITE;
/*!40000 ALTER TABLE `order_tracking_events` DISABLE KEYS */;
INSERT INTO `order_tracking_events` VALUES (1,1,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-07 02:05:23','2026-09-07 02:05:23','2026-09-07 02:05:23'),(756,429,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-16 00:05:38','2026-09-16 00:05:38','2026-09-16 00:05:38'),(880,499,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-16 00:12:50','2026-09-16 00:12:50','2026-09-16 00:12:50'),(881,500,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-16 00:13:34','2026-09-16 00:13:34','2026-09-16 00:13:34'),(940,429,'Packed','Paket dikemas dan siap diserahkan ke kurir.','Sortir DC Cakung','seller','2026-09-16 00:14:56','2026-09-16 00:14:56','2026-09-16 00:14:56'),(1566,429,'Shipped','Paket diserahkan ke JNE.',NULL,'seller','2026-09-16 01:22:43','2026-09-16 01:22:43','2026-09-16 01:22:43'),(1567,1,'Packed','Paket dikemas dan siap diserahkan ke kurir.',NULL,'seller','2026-09-16 01:23:38','2026-09-16 01:23:38','2026-09-16 01:23:38'),(1568,1,'Packed','Bagas Kurir mengambil paket ini untuk diantar.',NULL,'courier','2026-09-16 01:24:00','2026-09-16 01:24:00','2026-09-16 01:24:00'),(1643,1,'Shipped','Paket tiba di Hub Jakarta Utara','Hub Jakarta Utara','courier','2026-09-16 01:56:39','2026-09-16 01:56:39','2026-09-16 01:56:39'),(1797,984,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-16 02:03:29','2026-09-16 02:03:29','2026-09-16 02:03:29'),(1798,984,'Packed','Paket dikemas dan siap diserahkan ke kurir.',NULL,'seller','2026-09-16 02:04:15','2026-09-16 02:04:15','2026-09-16 02:04:15'),(1799,984,'Shipped','Bagas Kurir mengambil paket ini untuk diantar.',NULL,'courier','2026-09-16 02:05:07','2026-09-16 02:05:07','2026-09-16 02:05:07'),(1800,984,'Delivered','Kurir mencatat paket diterima oleh Jones.',NULL,'courier','2026-09-16 02:07:01','2026-09-16 02:07:01','2026-09-16 02:07:01'),(2919,1530,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-16 21:38:00','2026-09-16 21:38:00','2026-09-16 21:38:00'),(2920,1531,'Pending','Paket disiapkan oleh pengrajin.',NULL,'system','2026-09-16 21:38:43','2026-09-16 21:38:43','2026-09-16 21:38:43');
/*!40000 ALTER TABLE `order_tracking_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id_orders` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `customer_voucher_id` bigint unsigned DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_method` enum('Reguler','Express','Sameday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('COD','Transfer Bank','QRIS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('Unpaid','Paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `virtual_account` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `voucher_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `coins_used` int unsigned NOT NULL DEFAULT '0',
  `coin_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `coins_earned` int unsigned NOT NULL DEFAULT '0',
  `total` decimal(15,2) NOT NULL,
  `status` enum('Hold','Processing','Shipped','Delivered','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hold',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_orders`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_customer_voucher_id_foreign` (`customer_voucher_id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_voucher_id_foreign` FOREIGN KEY (`customer_voucher_id`) REFERENCES `customer_vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2250 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,NULL,NULL,'ORD-20260907090523-7382','SHANDY SHULTON SHIHAB','ssshandy60@gmail.com','081212181182','Kp. Bulak Teko','Jakarta Barat','DKI Jakarta','11840','Express','COD','Unpaid',NULL,NULL,925000.00,0.00,0,0.00,0.00,0,925000.00,'Shipped','2026-09-07 02:05:23','2026-09-16 01:56:39'),(596,1,7,'WELCOME10','ORD-20260916070538-9310','SHANDY SHULTON SHIHAB','ssshandy60@gmail.com','081212181182','Kp. Bulak Teko, Taman RT 010','Jakarta Barat','DKI Jakarta','11840','Express','Transfer Bank','Paid','88080000000596','2026-09-16 00:13:24',690000.00,50000.00,0,0.00,50000.00,690,640000.00,'Shipped','2026-09-16 00:05:38','2026-09-16 01:22:43'),(713,724,NULL,NULL,'ORD-20260916071250-9422','QA Checkout','qa-checkout@example.test','081200000200','Jl. Uji 1','Yogyakarta','DIY','55111','Reguler','Transfer Bank','Paid','88080000000713','2026-09-16 00:13:17',185000.00,0.00,0,0.00,0.00,185,185000.00,'Processing','2026-09-16 00:12:50','2026-09-16 00:13:17'),(714,724,NULL,NULL,'ORD-20260916071334-6535','QA Checkout','qa-checkout@example.test','081200000200','Jl. Uji 1','Yogyakarta','DIY','55111','Reguler','QRIS','Unpaid',NULL,NULL,185000.00,0.00,0,0.00,0.00,185,185000.00,'Processing','2026-09-16 00:13:34','2026-09-16 00:13:34'),(1450,1,NULL,NULL,'ORD-20260916090329-4981','SHANDY SHULTON SHIHAB','ssshandy60@gmail.com','081212181182','Kp. Bulak Teko, Taman RT 010','Jakarta Barat','DKI Jakarta','11840','Express','Transfer Bank','Paid','88080000001450','2026-09-16 02:03:35',420000.00,0.00,0,0.00,0.00,420,420000.00,'Delivered','2026-09-16 02:03:29','2026-09-16 02:07:01'),(2248,1,NULL,NULL,'ORD-20260917043800-9162','SHANDY SHULTON SHIHAB','ssshandy60@gmail.com','081212181182','Kp. Bulak Teko, Taman RT 010','Jakarta Barat','DKI Jakarta','11840','Reguler','COD','Unpaid',NULL,NULL,189000.00,0.00,0,0.00,0.00,189,189000.00,'Processing','2026-09-16 21:38:00','2026-09-16 21:38:00'),(2249,1,NULL,NULL,'ORD-20260917043843-2011','SHANDY SHULTON SHIHAB','ssshandy60@gmail.com','081212181182','Kp. Bulak Teko, Taman RT 010','Jakarta Barat','DKI Jakarta','11840','Express','Transfer Bank','Paid','88080000002249','2026-09-16 21:39:27',189000.00,0.00,0,0.00,0.00,189,189000.00,'Processing','2026-09-16 21:38:43','2026-09-16 21:39:27');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_inquiries`
--

DROP TABLE IF EXISTS `product_inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_inquiries` (
  `id_inquiries` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `seller_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_inquiries`),
  KEY `product_inquiries_product_id_foreign` (`product_id`),
  KEY `product_inquiries_customer_id_foreign` (`customer_id`),
  KEY `product_inquiries_seller_id_customer_id_product_id_index` (`seller_id`,`customer_id`,`product_id`),
  CONSTRAINT `product_inquiries_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE CASCADE,
  CONSTRAINT `product_inquiries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id_products`) ON DELETE SET NULL,
  CONSTRAINT `product_inquiries_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id_sellers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_inquiries`
--

LOCK TABLES `product_inquiries` WRITE;
/*!40000 ALTER TABLE `product_inquiries` DISABLE KEYS */;
INSERT INTO `product_inquiries` VALUES (1,2,1,1,'Meja Kopi Kayu Resik','2026-09-14 00:31:31','2026-09-13 23:45:17','2026-09-14 00:31:31');
/*!40000 ALTER TABLE `product_inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_inquiry_messages`
--

DROP TABLE IF EXISTS `product_inquiry_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_inquiry_messages` (
  `id_messages` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inquiry_id` bigint unsigned NOT NULL,
  `sender_type` enum('customer','seller') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint unsigned DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_messages`),
  KEY `product_inquiry_messages_inquiry_id_created_at_index` (`inquiry_id`,`created_at`),
  CONSTRAINT `product_inquiry_messages_inquiry_id_foreign` FOREIGN KEY (`inquiry_id`) REFERENCES `product_inquiries` (`id_inquiries`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_inquiry_messages`
--

LOCK TABLES `product_inquiry_messages` WRITE;
/*!40000 ALTER TABLE `product_inquiry_messages` DISABLE KEYS */;
INSERT INTO `product_inquiry_messages` VALUES (1,1,'customer',1,'apakah produk ini masih ada?','2026-09-14 00:31:21','2026-09-13 23:45:17','2026-09-14 00:31:21'),(2,1,'seller',1,'ya masih ada','2026-09-14 00:31:56','2026-09-14 00:31:31','2026-09-14 00:31:56');
/*!40000 ALTER TABLE `product_inquiry_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id_products` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waste_factor` decimal(8,2) NOT NULL DEFAULT '1.20',
  `carbon_factor` decimal(8,2) NOT NULL DEFAULT '2.70',
  `in_stock` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_gallery` text COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_products`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_seller_id_foreign` (`seller_id`),
  CONSTRAINT `products_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id_sellers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2341 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Vas Bumi Sekam Kasongan','vas-bumi-sekam-kasongan','Vas keramik artisanal dari abu sekam padi daur ulang, dibuat perajin Kasongan dengan teknik bakar tradisional. Cocok untuk rangkaian bunga kering.',185000.00,'Home Decor','Keramik Daur Ulang (Abu Sekam)',1.20,2.70,1,1,'approved','product_images/QJGfga2TjiIPOCpbl1ZPeKtE6JApN2zBqgUdEB11.jpg',NULL,24,'2026-09-07 01:57:20','2026-09-14 02:19:49'),(2,1,'Meja Kopi Kayu Resik','meja-kopi-kayu-resik','Meja kopi dari potongan kayu jati pilihan, dirakit tangan oleh pengrajin Jepara.',875000.00,'Furniture','kayu',1.20,2.70,1,1,'approved','product_images/meja-kopi-kayu-resik.jpg','[\"product_images\\/meja-kopi-kayu-resik-2.jpg\",\"product_images\\/meja-kopi-kayu-resik-3.jpg\"]',8,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(3,1,'Rak Dinding Rotan Cirebon','rak-dinding-rotan-cirebon','Rak dinding anyaman rotan kering alami, cocok untuk dekorasi ruang tamu berkarakter.',345000.00,'Home Decor','rotan',1.20,2.70,1,1,'approved','product_images/rak-dinding-rotan-cirebon.png','[\"product_images\\/rak-dinding-rotan-cirebon-2.jpg\",\"product_images\\/rak-dinding-rotan-cirebon-3.jpg\"]',9,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(4,1,'Tas Anyaman Mendong','tas-anyaman-mendong','Tas tangan anyaman serat mendong yang ringan, kuat, dan hangat dipandang.',189000.00,'Clothing & Accessories','anyaman',1.20,2.70,1,1,'approved','product_images/tas-anyaman-mendong.png','[\"product_images\\/tas-anyaman-mendong-2.jpg\",\"product_images\\/tas-anyaman-mendong-3.jpg\"]',10,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(5,1,'Lampu Gantung Bambu','lampu-gantung-bambu','Lampu gantung dari bambu pilihan dengan pola anyaman terbuka yang hangat.',420000.00,'Home Decor','bambu',1.20,2.70,1,1,'approved','product_images/lampu-gantung-bambu.png','[\"product_images\\/lampu-gantung-bambu-2.jpg\",\"product_images\\/lampu-gantung-bambu-3.png\"]',11,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(6,1,'Vas Bunga Tanah Liat','vas-bunga-tanah-liat','Vas keramik tanah liat Kasongan dengan glazur alami dari abu dan tanah lokal.',185000.00,'Home Decor','keramik',1.20,2.70,1,1,'approved','product_images/vas-bunga-tanah-liat.png','[\"product_images\\/vas-bunga-tanah-liat-2.jpg\",\"product_images\\/vas-bunga-tanah-liat-3.png\"]',12,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(7,1,'Taplak Tenun Pesisir','taplak-tenun-pesisir','Taplak dari sisa kain tenun Pekalongan, dijahit ulang menjadi tekstil rumah yang tahan lama.',260000.00,'Home Decor','tekstil',1.20,2.70,1,1,'approved','product_images/taplak-tenun-pesisir.png','[\"product_images\\/taplak-tenun-pesisir-2.jpg\",\"product_images\\/taplak-tenun-pesisir-3.jpg\"]',13,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(8,1,'Sofa Kayu Pulih','sofa-kayu-pulih','Sofa santai dari kayu bekas yang dipulihkan, dengan bantal tenun yang dapat dilepas.',2450000.00,'Furniture','kayu',1.20,2.70,1,1,'approved','product_images/sofa-kayu-pulih.jpg','[\"product_images\\/sofa-kayu-pulih-2.jpg\",\"product_images\\/sofa-kayu-pulih-3.jpg\"]',14,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(9,1,'Lampu Sendok Plastik Daur Ulang','lampu-sendok-plastik-daur-ulang','Lampu meja dari sendok plastik bekas yang dipilah dan dirakit menjadi karya artistik.',275000.00,'Home Decor','daur ulang',1.20,2.70,1,1,'approved','product_images/lampu-sendok-plastik-daur-ulang.jpg','[\"product_images\\/lampu-sendok-plastik-daur-ulang-2.png\",\"product_images\\/lampu-sendok-plastik-daur-ulang-3.jpg\"]',15,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(10,1,'Tas Kemasan Kopi Daur Ulang','tas-kemasan-kopi-daur-ulang','Tas jinjing dari kemasan kopi bekas yang dibersihkan dan dijahit ulang.',155000.00,'Clothing & Accessories','daur ulang',1.20,2.70,1,1,'approved','product_images/tas-kemasan-kopi-daur-ulang.jpg','[\"product_images\\/tas-kemasan-kopi-daur-ulang-2.jpg\",\"product_images\\/tas-kemasan-kopi-daur-ulang-3.jpg\"]',16,'2026-09-09 02:29:53','2026-09-16 20:06:05'),(11,1,'Hiasan Perahu Botol','hiasan-perahu-botol','Hiasan kapal dari botol plastik bekas, hadiah ramah lingkungan yang penuh cerita.',95000.00,'Toys','daur ulang',1.20,2.70,1,1,'approved','product_images/hiasan-perahu-botol.png','[\"product_images\\/hiasan-perahu-botol-2.jpg\",\"product_images\\/hiasan-perahu-botol-3.jpg\"]',17,'2026-09-09 02:29:53','2026-09-16 20:06:05');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sellers` (
  `id_sellers` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_sellers` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ktp_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_sellers`)
) ENGINE=InnoDB AUTO_INCREMENT=2644 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sellers`
--

LOCK TABLES `sellers` WRITE;
/*!40000 ALTER TABLE `sellers` DISABLE KEYS */;
INSERT INTO `sellers` VALUES (1,'SHANDY SHULTON SHIHAB','anyamanjawa@gmail.com','081212181182','$2y$10$er4KD1Z0kxnxWkSZZhd.AeXKfuk.6d.CTyvqWOsxblaQIz0vONp6e','Kp. Bulak Teko','male','DKI Jakarta','Jakarta Barat','Anyaman Jawa','ktp_sellers/lbG6mMYuyF5MTJ05XlCRydxq2YqVjTFVrnUSrt5D.jpg','approved','profile-photos/A0kkxzwAQVdmrxCnQLuO2q1DDi7Bbwf4N71SmYTG.png','2026-09-07 01:26:58','2026-09-14 00:35:50'),(1324,'QA Seller','qa-seller@example.test','081200000400','$2y$10$svhpvDZV0Q4BKSw3Te0m4uK87Ol84WcImLLI6IwVCyUcmE9kWQnBC','Jl. QA 1','female','DIY','Yogyakarta','QA Store','ktp/test.jpg','approved',NULL,'2026-09-16 01:02:09','2026-09-16 01:02:09');
/*!40000 ALTER TABLE `sellers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id_shipments` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `courier_id` bigint unsigned DEFAULT NULL,
  `courier_user_id` bigint unsigned DEFAULT NULL,
  `tracking_number` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Packed','Shipped','In Transit','Delivered','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `note` text COLLATE utf8mb4_unicode_ci,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_shipments`),
  UNIQUE KEY `shipments_order_id_seller_id_unique` (`order_id`,`seller_id`),
  KEY `shipments_seller_id_foreign` (`seller_id`),
  KEY `shipments_courier_id_foreign` (`courier_id`),
  KEY `shipments_status_index` (`status`),
  KEY `shipments_courier_user_id_foreign` (`courier_user_id`),
  CONSTRAINT `shipments_courier_id_foreign` FOREIGN KEY (`courier_id`) REFERENCES `couriers` (`id_couriers`) ON DELETE SET NULL,
  CONSTRAINT `shipments_courier_user_id_foreign` FOREIGN KEY (`courier_user_id`) REFERENCES `courier_users` (`id_courier_users`) ON DELETE SET NULL,
  CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE,
  CONSTRAINT `shipments_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id_sellers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1532 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
INSERT INTO `shipments` VALUES (1,1,1,8,1,'ECO09162026',NULL,NULL,'Shipped',NULL,'2026-09-16 01:56:39',NULL,'2026-09-07 02:05:23','2026-09-16 01:56:39'),(429,596,1,1,NULL,'CM84226238370',NULL,NULL,'Shipped',NULL,'2026-09-16 01:22:43',NULL,'2026-09-16 00:05:38','2026-09-16 01:22:43'),(499,713,1,NULL,NULL,NULL,NULL,NULL,'Pending',NULL,NULL,NULL,'2026-09-16 00:12:50','2026-09-16 00:12:50'),(500,714,1,1,NULL,'JNE-QA-123456','Ibu Sari','delivery-proofs/bukti-qa.jpg','Delivered',NULL,NULL,'2026-09-16 02:09:38','2026-09-16 00:13:34','2026-09-16 02:09:38'),(984,1450,1,8,1,'ECO091620261','Jones','delivery-proofs/Y6cH16nblLH8FmI05wQIbG8uA0WkGqB6i45I2W4X.jpg','Delivered',NULL,'2026-09-16 02:05:07','2026-09-16 02:07:01','2026-09-16 02:03:29','2026-09-16 02:07:01'),(1530,2248,1,NULL,NULL,NULL,NULL,NULL,'Pending',NULL,NULL,NULL,'2026-09-16 21:38:00','2026-09-16 21:38:00'),(1531,2249,1,NULL,NULL,NULL,NULL,NULL,'Pending',NULL,NULL,NULL,'2026-09-16 21:38:43','2026-09-16 21:38:43');
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('fixed','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `min_spend` decimal(15,2) NOT NULL DEFAULT '0.00',
  `max_discount` decimal(15,2) DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int unsigned DEFAULT NULL,
  `used_count` int unsigned NOT NULL DEFAULT '0',
  `per_customer_limit` int unsigned NOT NULL DEFAULT '1',
  `is_claimable` tinyint(1) NOT NULL DEFAULT '1',
  `is_reward` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vouchers_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (1,'WELCOME10','Diskon Selamat Datang','Potongan 10% untuk pembelianmu, maksimal Rp 50.000.','percent',10.00,0.00,50000.00,NULL,NULL,NULL,1,1,1,0,1,'2026-09-14 02:51:05','2026-09-16 00:05:38'),(2,'REWARD5','Hadiah Koin Sirkular','Voucher reward otomatis tiap 5 pesanan selesai.','fixed',25000.00,0.00,NULL,NULL,NULL,NULL,0,999,0,1,1,'2026-09-14 02:51:05','2026-09-14 02:51:05');
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_claims`
--

DROP TABLE IF EXISTS `warranty_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warranty_claims` (
  `id_claims` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `order_item_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `category` enum('kerusakan','jahitan','tidak_sesuai','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Submitted','Reviewing','Approved','Rejected','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Submitted',
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_claims`),
  KEY `warranty_claims_order_id_foreign` (`order_id`),
  KEY `warranty_claims_order_item_id_foreign` (`order_item_id`),
  KEY `warranty_claims_product_id_foreign` (`product_id`),
  KEY `warranty_claims_customer_id_status_index` (`customer_id`,`status`),
  KEY `warranty_claims_seller_id_status_index` (`seller_id`,`status`),
  CONSTRAINT `warranty_claims_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customers`) ON DELETE CASCADE,
  CONSTRAINT `warranty_claims_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE,
  CONSTRAINT `warranty_claims_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warranty_claims_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id_products`) ON DELETE SET NULL,
  CONSTRAINT `warranty_claims_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id_sellers`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_claims`
--

LOCK TABLES `warranty_claims` WRITE;
/*!40000 ALTER TABLE `warranty_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ecocraft_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-07 10:36:19
