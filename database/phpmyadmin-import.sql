
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
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_02_000000_create_store_tables',1),(5,'2026_01_02_000001_create_personal_access_tokens_table',1),(6,'2026_01_02_000002_create_permission_tables',1),(7,'2026_10_23_000001_add_area_to_site_visitors',1),(8,'2026_10_25_000001_create_signup_offers_table',1),(9,'2026_10_26_000001_create_social_links_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL,
  `shipping_fee` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'{\"ar\": \"القاهرة\", \"en\": \"Cairo\"}',35.0000,1,1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(2,'{\"ar\": \"الجيزة\", \"en\": \"Giza\"}',40.0000,1,2,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(3,'{\"ar\": \"الإسكندرية\", \"en\": \"Alexandria\"}',65.0000,1,3,'2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;
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
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_city_id_foreign` (`city_id`),
  KEY `users_phone_index` (`phone`),
  CONSTRAINT `users_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Atlas Admin','admin@example.test','2026-10-22 11:25:38','$2y$12$kn9uViIrjDMzEYGmO8U5VeCh4hW6bdooUtikyueDOJmD1MRIOcaVK',NULL,'2026-10-22 11:25:38','2026-10-22 11:25:38','01000000000',NULL,NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `description` json DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'{\"ar\": \"المؤن\", \"en\": \"Pantry\"}','{\"ar\": \"almon\", \"en\": \"pantry\"}','{\"ar\": \"أساسيات الأسبوع\", \"en\": \"Staples for the week\"}',NULL,1,1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(2,'{\"ar\": \"قهوة\", \"en\": \"Coffee\"}','{\"ar\": \"mshrobat\", \"en\": \"drinks\"}','{\"ar\": \"حبوب كاملة وقهوة مطحونة، بالعبوة أو بالكيلو.\", \"en\": \"Whole beans and ground coffee, sold by the pack or the kilo.\"}','categories/coffee.svg',2,1,'2026-10-22 11:25:38','2026-10-26 10:50:16');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `description` json DEFAULT NULL,
  `price_per_unit` decimal(14,4) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'piece',
  `secondary_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_price` decimal(14,4) DEFAULT NULL,
  `track_stock` tinyint(1) NOT NULL DEFAULT '1',
  `stock_quantity` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'ATL-OIL','{\"ar\": \"زيت زيتون أطلس\", \"en\": \"Atlas olive oil\"}','{\"ar\": \"zyt-zyton-atls\", \"en\": \"atlas-olive-oil\"}','{\"ar\": \"معصور على البارد ويباع بالكيلو.\", \"en\": \"Cold pressed, sold by the kilo.\"}',180.0000,'kg',NULL,NULL,1,40.0000,NULL,NULL,0,1,'2026-10-22 11:25:38','2026-10-26 10:53:02'),(2,1,'ATL-DATE','{\"ar\": \"بلح مجهول\", \"en\": \"Medjool dates\"}','{\"ar\": \"blh-mghol\", \"en\": \"medjool-dates\"}','{\"ar\": \"بلح طري بالكيلو.\", \"en\": \"Soft dates by the kilo.\"}',95.0000,'kg',NULL,NULL,1,25.0000,NULL,NULL,0,1,'2026-10-22 11:25:38','2026-10-26 10:53:02'),(3,2,'ATL-COFFEE','{\"ar\": \"قهوة سيدامو\", \"en\": \"Sidamo coffee\"}','{\"ar\": \"kho-sydamo\", \"en\": \"sidamo-coffee\"}','{\"ar\": \"حبوب كاملة من سيدامو بنكهة التوت والكاكاو. تباع بالعبوة، ويمكن طحنها لإبريقك.\", \"en\": \"Berry and cocoa whole beans from Sidamo. Sold by the pack, and we can grind them for your pot.\"}',140.0000,'pack',NULL,NULL,1,30.0000,'products/ATL-COFFEE.svg',NULL,1,1,'2026-10-22 11:25:38','2026-10-26 10:50:16'),(4,2,'ATL-YIRG','{\"ar\": \"حبوب يرغاتشيف\", \"en\": \"Yirgacheffe beans\"}','{\"ar\": \"hbob-yrghatshyf\", \"en\": \"yirgacheffe-beans\"}','{\"ar\": \"حبوب يرغاتشيف الزهرية بالكيلو، للترشيح والتحضير البطيء.\", \"en\": \"Floral Yirgacheffe whole beans, sold by the kilo for pour-over and filter.\"}',320.0000,'kg',NULL,NULL,1,18.0000,'products/ATL-YIRG.svg',NULL,1,1,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(5,2,'ATL-SANTOS','{\"ar\": \"قهوة سانتوس\", \"en\": \"Santos coffee\"}','{\"ar\": \"kho-santos\", \"en\": \"santos-coffee\"}','{\"ar\": \"حبوب سانتوس البرازيلية الناعمة في عبوة. فنجان يومي خفيف بنهاية جوزية.\", \"en\": \"Soft Brazilian Santos beans in a pack. A gentle daily cup with a nutty finish.\"}',165.0000,'pack',NULL,NULL,1,24.0000,'products/ATL-SANTOS.svg',NULL,1,1,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(6,2,'ATL-ESPRESSO','{\"ar\": \"إسبريسو الدار\", \"en\": \"House espresso\"}','{\"ar\": \"asbryso-aldar\", \"en\": \"house-espresso\"}','{\"ar\": \"خلطة الدار الداكنة للإسبريسو ومشروبات الحليب، تباع بالعبوة.\", \"en\": \"A dark house blend for espresso and milk drinks, sold by the pack.\"}',155.0000,'pack',NULL,NULL,1,22.0000,'products/ATL-ESPRESSO.svg',NULL,1,1,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(7,2,'ATL-TURK','{\"ar\": \"قهوة تركي\", \"en\": \"Turkish coffee\"}','{\"ar\": \"kho-trky\", \"en\": \"turkish-coffee\"}','{\"ar\": \"قهوة ناعمة للركوة، تباع بالعبوة. لا تحتاج طحناً إضافياً.\", \"en\": \"Fine ground coffee for a cezve, sold by the pack. No extra grind needed.\"}',95.0000,'pack',NULL,NULL,1,40.0000,'products/ATL-TURK.svg',NULL,0,1,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(8,2,'ATL-DECAF','{\"ar\": \"خلطة منزوعة الكافيين\", \"en\": \"Decaf blend\"}','{\"ar\": \"khlt-mnzoaa-alkafyyn\", \"en\": \"decaf-blend\"}','{\"ar\": \"خلطة خفيفة منزوعة الكافيين للمساء، تباع بالكيلو.\", \"en\": \"A mild decaf blend for the evening, sold by the kilo.\"}',280.0000,'kg',NULL,NULL,1,12.0000,'products/ATL-DECAF.svg',NULL,0,1,'2026-10-26 10:27:28','2026-10-26 10:50:17');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `preparation_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparation_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL,
  `description` json DEFAULT NULL,
  `surcharge_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `surcharge_value` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `preparation_services` WRITE;
/*!40000 ALTER TABLE `preparation_services` DISABLE KEYS */;
INSERT INTO `preparation_services` VALUES (1,'{\"ar\": \"طحن\", \"en\": \"Grind\"}',NULL,'percent',5.0000,1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(2,'{\"ar\": \"بطاقة إهداء\", \"en\": \"Gift note\"}',NULL,'fixed',15.0000,1,'2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `preparation_services` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_preparation_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_preparation_service` (
  `product_id` bigint unsigned NOT NULL,
  `preparation_service_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`preparation_service_id`),
  KEY `product_preparation_service_preparation_service_id_foreign` (`preparation_service_id`),
  CONSTRAINT `product_preparation_service_preparation_service_id_foreign` FOREIGN KEY (`preparation_service_id`) REFERENCES `preparation_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_preparation_service_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_preparation_service` WRITE;
/*!40000 ALTER TABLE `product_preparation_service` DISABLE KEYS */;
INSERT INTO `product_preparation_service` VALUES (3,1),(4,1),(5,1),(6,1),(8,1),(1,2);
/*!40000 ALTER TABLE `product_preparation_service` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `packaging_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packaging_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL,
  `description` json DEFAULT NULL,
  `surcharge_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `surcharge_value` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'line',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `packaging_types` WRITE;
/*!40000 ALTER TABLE `packaging_types` DISABLE KEYS */;
INSERT INTO `packaging_types` VALUES (1,'{\"ar\": \"برطمان زجاج\", \"en\": \"Glass jar\"}',NULL,'fixed',12.0000,'line',1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(2,'{\"ar\": \"علبة هدية\", \"en\": \"Gift box\"}',NULL,'fixed',25.0000,'order',1,'2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `packaging_types` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_packaging_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_packaging_type` (
  `product_id` bigint unsigned NOT NULL,
  `packaging_type_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`packaging_type_id`),
  KEY `product_packaging_type_packaging_type_id_foreign` (`packaging_type_id`),
  CONSTRAINT `product_packaging_type_packaging_type_id_foreign` FOREIGN KEY (`packaging_type_id`) REFERENCES `packaging_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_packaging_type_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_packaging_type` WRITE;
/*!40000 ALTER TABLE `product_packaging_type` DISABLE KEYS */;
INSERT INTO `product_packaging_type` VALUES (1,1);
/*!40000 ALTER TABLE `product_packaging_type` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bundles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `description` json DEFAULT NULL,
  `price` decimal(14,4) NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_subscribable` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `bundles` WRITE;
/*!40000 ALTER TABLE `bundles` DISABLE KEYS */;
INSERT INTO `bundles` VALUES (1,'{\"ar\": \"صندوق إفطار نهاية الأسبوع\", \"en\": \"Weekend breakfast box\"}','{\"ar\": \"sndok-aftar-nhay-alasboaa\", \"en\": \"weekend-breakfast-box\"}','{\"ar\": \"زيت وبلح وقهوة.\", \"en\": \"Oil, dates, and coffee.\"}',390.0000,NULL,NULL,1,1,'2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `bundles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `bundle_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bundle_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(14,4) NOT NULL DEFAULT '1.0000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bundle_items_bundle_id_foreign` (`bundle_id`),
  KEY `bundle_items_product_id_foreign` (`product_id`),
  CONSTRAINT `bundle_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bundle_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `bundle_items` WRITE;
/*!40000 ALTER TABLE `bundle_items` DISABLE KEYS */;
INSERT INTO `bundle_items` VALUES (1,1,1,0.5000,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(2,1,2,0.5000,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(3,1,3,1.0000,'2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `bundle_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(14,4) NOT NULL,
  `usage_limit` int unsigned DEFAULT NULL,
  `used_count` int unsigned NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `min_subtotal` decimal(14,4) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10','percent',10.0000,100,0,NULL,NULL,NULL,1,'2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_gateway` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EGP',
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `order_packaging_fee` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `order_packaging_snapshot` json DEFAULT NULL,
  `shipping_fee` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(14,4) NOT NULL,
  `amount_cents` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `invoice_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `confirmation_sent_at` timestamp NULL DEFAULT NULL,
  `paymob_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `masked_pan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_reference_unique` (`reference`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_city_id_foreign` (`city_id`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_payment_status_index` (`payment_status`),
  CONSTRAINT `orders_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `kind` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `bundle_id` bigint unsigned DEFAULT NULL,
  `name` json NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(14,4) NOT NULL,
  `quantity` decimal(14,4) NOT NULL,
  `services_snapshot` json DEFAULT NULL,
  `packaging_snapshot` json DEFAULT NULL,
  `line_base` decimal(14,4) NOT NULL,
  `services_fee` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `packaging_fee` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `line_subtotal` decimal(14,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_bundle_id_foreign` (`bundle_id`),
  CONSTRAINT `order_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `order_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'note',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_notes_order_id_foreign` (`order_id`),
  KEY `order_notes_user_id_foreign` (`user_id`),
  CONSTRAINT `order_notes_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `order_notes` WRITE;
/*!40000 ALTER TABLE `order_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_notes` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bundle_id` bigint unsigned NOT NULL,
  `quantity` decimal(14,4) NOT NULL DEFAULT '1.0000',
  `interval` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `next_order_at` date NOT NULL,
  `payment_gateway` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `city_id` bigint unsigned DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`),
  KEY `subscriptions_bundle_id_foreign` (`bundle_id`),
  KEY `subscriptions_city_id_foreign` (`city_id`),
  CONSTRAINT `subscriptions_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `content_strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_strings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_strings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `content_strings` WRITE;
/*!40000 ALTER TABLE `content_strings` DISABLE KEYS */;
INSERT INTO `content_strings` VALUES (1,'home.hero_title','{\"ar\": \"قهوة بطعم الحارة\", \"en\": \"Coffee for the neighborhood\"}','2026-10-22 11:25:38','2026-10-26 10:50:17'),(2,'home.hero_subtitle','{\"ar\": \"سيدامو ويرغاتشيف وإسبريسو وقهوة تركي، تُطحن على طريقة تحضيرك.\", \"en\": \"Sidamo, Yirgacheffe, espresso, and Turkish coffee, ground the way you brew.\"}','2026-10-22 11:25:38','2026-10-26 10:50:17'),(3,'home.kicker','{\"ar\": \"تحميص القاهرة\", \"en\": \"Roasted in Cairo\"}','2026-10-26 10:50:17','2026-10-26 10:50:17'),(4,'home.story_title','{\"ar\": \"من الشوال إلى الفنجان\", \"en\": \"From the sack to your cup\"}','2026-10-26 10:50:17','2026-10-26 10:50:17'),(5,'home.story_body','{\"ar\": \"حبوب كاملة بالعبوة أو بالكيلو، وطحن على قدر الإبريق، وتوصيل في القاهرة والجيزة والإسكندرية.\", \"en\": \"Whole beans by the pack or the kilo, a grind for your pot, and delivery across Cairo, Giza, and Alexandria.\"}','2026-10-26 10:50:17','2026-10-26 10:50:17'),(6,'trust.shipping','{\"ar\": \"توصيل القهوة في القاهرة والجيزة والإسكندرية\", \"en\": \"Coffee delivered across Cairo, Giza, and Alexandria\"}','2026-10-26 10:50:17','2026-10-26 10:50:17'),(7,'trust.payment','{\"ar\": \"الدفع عند الاستلام أو فيزا\", \"en\": \"Cash on delivery or Visa\"}','2026-10-26 10:50:17','2026-10-26 10:50:17'),(8,'trust.support','{\"ar\": \"دعم بالعربية والإنجليزية من التحميص حتى الباب\", \"en\": \"Arabic and English, from the roast to your door\"}','2026-10-26 10:50:17','2026-10-26 10:50:17'),(9,'footer.blurb','{\"ar\": \"قهوة مختصة تعيد طلبها من البيت.\", \"en\": \"Specialty coffee you can reorder from home.\"}','2026-10-26 10:50:17','2026-10-26 10:50:17');
/*!40000 ALTER TABLE `content_strings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `seo_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `route_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` json DEFAULT NULL,
  `meta_description` json DEFAULT NULL,
  `og_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_settings_route_key_unique` (`route_key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `seo_settings` WRITE;
/*!40000 ALTER TABLE `seo_settings` DISABLE KEYS */;
INSERT INTO `seo_settings` VALUES (1,'home','{\"ar\": \"قهوة مختصة في القاهرة | سوق أطلس\", \"en\": \"Specialty coffee in Cairo | Atlas Market\"}','{\"ar\": \"اطلب سيدامو ويرغاتشيف والإسبريسو والقهوة التركي والمنزوعة الكافيين. حبوب كاملة أو مطحونة، مع التوصيل في مصر.\", \"en\": \"Order Sidamo, Yirgacheffe, espresso, Turkish coffee, and decaf. Whole beans or ground, with delivery in Egypt.\"}','design/og_default.svg','2026-10-22 11:25:38','2026-10-26 10:50:17'),(2,'shop','{\"ar\": \"تسوق القهوة | سوق أطلس\", \"en\": \"Shop coffee | Atlas Market\"}','{\"ar\": \"تصفح القهوة الحبّة والمطحونة بالعبوة أو بالكيلو.\", \"en\": \"Browse whole-bean and ground coffee sold by the pack or the kilo.\"}','design/og_default.svg','2026-10-26 10:50:17','2026-10-26 10:50:17'),(3,'category','{\"ar\": \"أرفف القهوة | سوق أطلس\", \"en\": \"Coffee shelves | Atlas Market\"}','{\"ar\": \"قهوة مختصة من أرفف سوق أطلس.\", \"en\": \"Specialty coffee from the Atlas Market shelves.\"}','design/og_default.svg','2026-10-26 10:50:17','2026-10-26 10:50:17'),(4,'product','{\"ar\": \"قهوة | سوق أطلس\", \"en\": \"Coffee | Atlas Market\"}','{\"ar\": \"قهوة مختصة من سوق أطلس، تُطحن على طريقة تحضيرك.\", \"en\": \"Specialty coffee from Atlas Market, ground the way you brew.\"}','design/og_default.svg','2026-10-26 10:50:17','2026-10-26 10:50:17'),(5,'bundles','{\"ar\": \"حزم القهوة | سوق أطلس\", \"en\": \"Coffee bundles | Atlas Market\"}','{\"ar\": \"حزم قهوة للأسبوع، مع التوصيل في مصر.\", \"en\": \"Coffee bundles for the week, delivered in Egypt.\"}','design/og_default.svg','2026-10-26 10:50:17','2026-10-26 10:50:17');
/*!40000 ALTER TABLE `seo_settings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `home_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` json NOT NULL,
  `subtitle` json DEFAULT NULL,
  `badge` json DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gradient_start` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gradient_end` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buttons` json DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `home_banners` WRITE;
/*!40000 ALTER TABLE `home_banners` DISABLE KEYS */;
INSERT INTO `home_banners` VALUES (1,'{\"ar\": \"حبوب كاملة وطحنك\", \"en\": \"Whole beans, your grind\"}','{\"ar\": \"سيدامو ويرغاتشيف وسانتوس. اطلب الطحن عند الإضافة.\", \"en\": \"Sidamo, Yirgacheffe, and Santos. Ask for a grind when you add them.\"}','{\"ar\": \"محمّص\", \"en\": \"Roasted\"}','banners/coffee-beans.svg',NULL,'#1A2B28','#C9A55C','[{\"url\": \"/shop\", \"label\": {\"ar\": \"تسوق القهوة\", \"en\": \"Shop coffee\"}}]',NULL,NULL,1,1,'2026-10-22 11:25:38','2026-10-26 10:50:17'),(2,'{\"ar\": \"قهوة تركي للركوة\", \"en\": \"Turkish coffee for the cezve\"}','{\"ar\": \"قهوة ناعمة جاهزة للركوة.\", \"en\": \"Fine ground coffee, ready for the pot.\"}','{\"ar\": \"خلطة الدار\", \"en\": \"House blend\"}','banners/coffee-cup.svg',NULL,'#0F1B19','#2F4F47','[{\"url\": \"/products/turkish-coffee\", \"label\": {\"ar\": \"شاهد العبوة\", \"en\": \"See the pack\"}}]',NULL,NULL,2,1,'2026-10-26 10:50:17','2026-10-26 10:50:17');
/*!40000 ALTER TABLE `home_banners` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `design_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `design_assets_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `design_assets` WRITE;
/*!40000 ALTER TABLE `design_assets` DISABLE KEYS */;
INSERT INTO `design_assets` VALUES (1,'logo','design/logo.svg','{\"ar\": \"علامة قهوة أطلس\", \"en\": \"Atlas coffee mark\"}',1,'2026-10-22 11:25:38','2026-10-26 10:52:25'),(2,'logo_dark','design/logo_dark.svg','{\"ar\": \"علامة قهوة أطلس\", \"en\": \"Atlas coffee mark\"}',1,'2026-10-22 11:25:38','2026-10-26 10:52:25'),(3,'favicon','design/favicon.svg','{\"ar\": \"علامة قهوة أطلس\", \"en\": \"Atlas coffee mark\"}',1,'2026-10-22 11:25:38','2026-10-26 10:50:17'),(4,'hero','design/hero.svg','{\"ar\": \"فنجان قهوة أطلس\", \"en\": \"A cup of Atlas coffee\"}',1,'2026-10-22 11:25:38','2026-10-26 10:50:17'),(5,'og_default','design/og_default.svg','{\"ar\": \"قهوة أطلس المختصة\", \"en\": \"Atlas Market specialty coffee\"}',1,'2026-10-22 11:25:38','2026-10-26 10:50:17'),(6,'empty_cart','design/empty_cart.svg','{\"ar\": \"سوق أطلس empty_cart\", \"en\": \"Atlas Market empty_cart\"}',1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(7,'empty_shop','design/empty_shop.svg','{\"ar\": \"سوق أطلس empty_shop\", \"en\": \"Atlas Market empty_shop\"}',1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(8,'auth_side','design/auth_side.svg','{\"ar\": \"سوق أطلس auth_side\", \"en\": \"Atlas Market auth_side\"}',1,'2026-10-22 11:25:38','2026-10-22 11:25:38'),(9,'footer_mark','design/footer_mark.svg','{\"ar\": \"علامة قهوة أطلس\", \"en\": \"Atlas coffee mark\"}',1,'2026-10-22 11:25:38','2026-10-26 10:50:17');
/*!40000 ALTER TABLE `design_assets` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `site_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_themes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_dark` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_light` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accent` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accent_light` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `canvas` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surface` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `border` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `danger` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `site_themes` WRITE;
/*!40000 ALTER TABLE `site_themes` DISABLE KEYS */;
INSERT INTO `site_themes` VALUES (1,'Atlas Market','#1A2B28','#0F1B19','#2F4F47','#C9A55C','#E0C58A','#DDD6CA','#F5EFE6','#C9A55C','#2F4F47','#8E3B32','#1A2B28','2026-10-22 11:25:38','2026-10-23 18:12:44');
/*!40000 ALTER TABLE `site_themes` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'ViewAny:Category','web','2026-10-22 11:25:29','2026-10-22 11:25:29'),(2,'View:Category','web','2026-10-22 11:25:29','2026-10-22 11:25:29'),(3,'Create:Category','web','2026-10-22 11:25:29','2026-10-22 11:25:29'),(4,'Update:Category','web','2026-10-22 11:25:29','2026-10-22 11:25:29'),(5,'Delete:Category','web','2026-10-22 11:25:29','2026-10-22 11:25:29'),(6,'DeleteAny:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(7,'Restore:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(8,'ForceDelete:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(9,'ForceDeleteAny:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(10,'RestoreAny:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(11,'Replicate:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(12,'Reorder:Category','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(13,'ViewAny:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(14,'View:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(15,'Create:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(16,'Update:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(17,'Delete:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(18,'DeleteAny:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(19,'Restore:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(20,'ForceDelete:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(21,'ForceDeleteAny:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(22,'RestoreAny:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(23,'Replicate:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(24,'Reorder:Product','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(25,'ViewAny:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(26,'View:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(27,'Create:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(28,'Update:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(29,'Delete:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(30,'DeleteAny:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(31,'Restore:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(32,'ForceDelete:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(33,'ForceDeleteAny:Bundle','web','2026-10-22 11:25:30','2026-10-22 11:25:30'),(34,'RestoreAny:Bundle','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(35,'Replicate:Bundle','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(36,'Reorder:Bundle','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(37,'ViewAny:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(38,'View:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(39,'Create:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(40,'Update:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(41,'Delete:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(42,'DeleteAny:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(43,'Restore:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(44,'ForceDelete:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(45,'ForceDeleteAny:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(46,'RestoreAny:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(47,'Replicate:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(48,'Reorder:Coupon','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(49,'ViewAny:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(50,'View:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(51,'Create:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(52,'Update:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(53,'Delete:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(54,'DeleteAny:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(55,'Restore:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(56,'ForceDelete:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(57,'ForceDeleteAny:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(58,'RestoreAny:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(59,'Replicate:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(60,'Reorder:Order','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(61,'ViewAny:City','web','2026-10-22 11:25:31','2026-10-22 11:25:31'),(62,'View:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(63,'Create:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(64,'Update:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(65,'Delete:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(66,'DeleteAny:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(67,'Restore:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(68,'ForceDelete:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(69,'ForceDeleteAny:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(70,'RestoreAny:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(71,'Replicate:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(72,'Reorder:City','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(73,'ViewAny:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(74,'View:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(75,'Create:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(76,'Update:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(77,'Delete:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(78,'DeleteAny:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(79,'Restore:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(80,'ForceDelete:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(81,'ForceDeleteAny:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(82,'RestoreAny:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(83,'Replicate:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(84,'Reorder:PreparationService','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(85,'ViewAny:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(86,'View:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(87,'Create:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(88,'Update:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(89,'Delete:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(90,'DeleteAny:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(91,'Restore:PackagingType','web','2026-10-22 11:25:32','2026-10-22 11:25:32'),(92,'ForceDelete:PackagingType','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(93,'ForceDeleteAny:PackagingType','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(94,'RestoreAny:PackagingType','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(95,'Replicate:PackagingType','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(96,'Reorder:PackagingType','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(97,'ViewAny:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(98,'View:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(99,'Create:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(100,'Update:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(101,'Delete:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(102,'DeleteAny:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(103,'Restore:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(104,'ForceDelete:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(105,'ForceDeleteAny:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(106,'RestoreAny:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(107,'Replicate:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(108,'Reorder:User','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(109,'ViewAny:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(110,'View:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(111,'Create:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(112,'Update:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(113,'Delete:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(114,'DeleteAny:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(115,'Restore:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(116,'ForceDelete:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(117,'ForceDeleteAny:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(118,'RestoreAny:ContentString','web','2026-10-22 11:25:33','2026-10-22 11:25:33'),(119,'Replicate:ContentString','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(120,'Reorder:ContentString','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(121,'ViewAny:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(122,'View:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(123,'Create:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(124,'Update:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(125,'Delete:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(126,'DeleteAny:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(127,'Restore:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(128,'ForceDelete:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(129,'ForceDeleteAny:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(130,'RestoreAny:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(131,'Replicate:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(132,'Reorder:SeoSetting','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(133,'ViewAny:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(134,'View:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(135,'Create:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(136,'Update:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(137,'Delete:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(138,'DeleteAny:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(139,'Restore:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(140,'ForceDelete:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(141,'ForceDeleteAny:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(142,'RestoreAny:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(143,'Replicate:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(144,'Reorder:HomeBanner','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(145,'ViewAny:SiteVisitor','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(146,'View:SiteVisitor','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(147,'Create:SiteVisitor','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(148,'Update:SiteVisitor','web','2026-10-22 11:25:34','2026-10-22 11:25:34'),(149,'Delete:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(150,'DeleteAny:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(151,'Restore:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(152,'ForceDelete:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(153,'ForceDeleteAny:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(154,'RestoreAny:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(155,'Replicate:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(156,'Reorder:SiteVisitor','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(157,'ViewAny:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(158,'View:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(159,'Create:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(160,'Update:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(161,'Delete:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(162,'DeleteAny:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(163,'Restore:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(164,'ForceDelete:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(165,'ForceDeleteAny:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(166,'RestoreAny:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(167,'Replicate:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(168,'Reorder:DesignAsset','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(169,'ViewAny:SiteTheme','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(170,'View:SiteTheme','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(171,'Create:SiteTheme','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(172,'Update:SiteTheme','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(173,'Delete:SiteTheme','web','2026-10-22 11:25:35','2026-10-22 11:25:35'),(174,'DeleteAny:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(175,'Restore:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(176,'ForceDelete:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(177,'ForceDeleteAny:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(178,'RestoreAny:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(179,'Replicate:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(180,'Reorder:SiteTheme','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(181,'ViewAny:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(182,'View:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(183,'Create:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(184,'Update:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(185,'Delete:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(186,'DeleteAny:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(187,'Restore:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(188,'ForceDelete:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(189,'ForceDeleteAny:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(190,'RestoreAny:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(191,'Replicate:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(192,'Reorder:PaymentTransaction','web','2026-10-22 11:25:36','2026-10-22 11:25:36'),(193,'View:TrafficDashboard','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(194,'View:AdminGuide','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(195,'View:SalesDashboard','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(196,'ViewAny:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(197,'View:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(198,'Create:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(199,'Update:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(200,'Delete:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(201,'DeleteAny:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(202,'Restore:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(203,'ForceDelete:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(204,'ForceDeleteAny:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(205,'RestoreAny:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(206,'Replicate:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58'),(207,'Reorder:SocialLink','web','2026-10-26 11:11:58','2026-10-26 11:11:58');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(2,'orders_manager','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(3,'catalog_manager','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(4,'content_manager','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(5,'design_manager','web','2026-10-22 11:25:37','2026-10-22 11:25:37'),(6,'analytics_viewer','web','2026-10-22 11:25:38','2026-10-22 11:25:38'),(7,'finance_viewer','web','2026-10-22 11:25:38','2026-10-22 11:25:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1),(101,1),(102,1),(103,1),(104,1),(105,1),(106,1),(107,1),(108,1),(109,1),(110,1),(111,1),(112,1),(113,1),(114,1),(115,1),(116,1),(117,1),(118,1),(119,1),(120,1),(121,1),(122,1),(123,1),(124,1),(125,1),(126,1),(127,1),(128,1),(129,1),(130,1),(131,1),(132,1),(133,1),(134,1),(135,1),(136,1),(137,1),(138,1),(139,1),(140,1),(141,1),(142,1),(143,1),(144,1),(145,1),(146,1),(147,1),(148,1),(149,1),(150,1),(151,1),(152,1),(153,1),(154,1),(155,1),(156,1),(157,1),(158,1),(159,1),(160,1),(161,1),(162,1),(163,1),(164,1),(165,1),(166,1),(167,1),(168,1),(169,1),(170,1),(171,1),(172,1),(173,1),(174,1),(175,1),(176,1),(177,1),(178,1),(179,1),(180,1),(181,1),(182,1),(183,1),(184,1),(185,1),(186,1),(187,1),(188,1),(189,1),(190,1),(191,1),(192,1),(193,1),(194,1),(195,1),(196,1),(197,1),(198,1),(199,1),(200,1),(201,1),(202,1),(203,1),(204,1),(205,1),(206,1),(207,1),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(97,2),(98,2),(194,2),(195,2),(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(11,3),(12,3),(13,3),(14,3),(15,3),(16,3),(17,3),(18,3),(19,3),(20,3),(21,3),(22,3),(23,3),(24,3),(25,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(32,3),(33,3),(34,3),(35,3),(36,3),(37,3),(38,3),(39,3),(40,3),(41,3),(42,3),(43,3),(44,3),(45,3),(46,3),(47,3),(48,3),(61,3),(62,3),(63,3),(64,3),(65,3),(66,3),(67,3),(68,3),(69,3),(70,3),(71,3),(72,3),(73,3),(74,3),(75,3),(76,3),(77,3),(78,3),(79,3),(80,3),(81,3),(82,3),(83,3),(84,3),(85,3),(86,3),(87,3),(88,3),(89,3),(90,3),(91,3),(92,3),(93,3),(94,3),(95,3),(96,3),(194,3),(109,4),(110,4),(111,4),(112,4),(113,4),(114,4),(115,4),(116,4),(117,4),(118,4),(119,4),(120,4),(121,4),(122,4),(123,4),(124,4),(125,4),(126,4),(127,4),(128,4),(129,4),(130,4),(131,4),(132,4),(133,4),(134,4),(135,4),(136,4),(137,4),(138,4),(139,4),(140,4),(141,4),(142,4),(143,4),(144,4),(194,4),(196,4),(197,4),(198,4),(199,4),(200,4),(201,4),(202,4),(203,4),(204,4),(205,4),(206,4),(207,4),(133,5),(134,5),(135,5),(136,5),(137,5),(138,5),(139,5),(140,5),(141,5),(142,5),(143,5),(144,5),(157,5),(158,5),(159,5),(160,5),(161,5),(162,5),(163,5),(164,5),(165,5),(166,5),(167,5),(168,5),(169,5),(170,5),(171,5),(172,5),(173,5),(174,5),(175,5),(176,5),(177,5),(178,5),(179,5),(180,5),(194,5),(196,5),(197,5),(198,5),(199,5),(200,5),(201,5),(202,5),(203,5),(204,5),(205,5),(206,5),(207,5),(97,6),(98,6),(145,6),(146,6),(193,6),(194,6),(195,6),(49,7),(50,7),(181,7),(182,7),(194,7);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `signup_offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signup_offers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `delay_seconds` int unsigned NOT NULL DEFAULT '15',
  `coupon_id` bigint unsigned DEFAULT NULL,
  `heading_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heading_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_ar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signup_offers_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `signup_offers_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `signup_offers` WRITE;
/*!40000 ALTER TABLE `signup_offers` DISABLE KEYS */;
INSERT INTO `signup_offers` VALUES (1,1,15,1,'Sign up and save','سجل واحصل على خصم','Leave your email and we will send your discount coupon.','اترك بريدك وسنرسل كوبون الخصم إلى بريدك.','2026-10-25 10:30:16','2026-10-25 10:36:01');
/*!40000 ALTER TABLE `signup_offers` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `signup_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signup_leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `signup_leads_email_coupon_id_unique` (`email`,`coupon_id`),
  KEY `signup_leads_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `signup_leads_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `signup_leads` WRITE;
/*!40000 ALTER TABLE `signup_leads` DISABLE KEYS */;
INSERT INTO `signup_leads` VALUES (1,'visitor@example.test',1,'en','2026-10-25 10:32:05','2026-10-25 10:32:05','2026-10-25 10:32:05');
/*!40000 ALTER TABLE `signup_leads` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `social_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_links_platform_unique` (`platform`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `social_links` WRITE;
/*!40000 ALTER TABLE `social_links` DISABLE KEYS */;
INSERT INTO `social_links` VALUES (1,'facebook','https://facebook.com',1,1,'2026-10-26 11:11:58','2026-10-26 11:11:58'),(2,'instagram','https://instagram.com',2,1,'2026-10-26 11:11:58','2026-10-26 11:11:58'),(3,'tiktok','https://www.tiktok.com',3,1,'2026-10-26 11:11:58','2026-10-26 11:11:58'),(4,'youtube','https://youtube.com',4,1,'2026-10-26 11:11:58','2026-10-26 11:11:58'),(5,'whatsapp','https://whatsapp.com',5,1,'2026-10-26 11:11:58','2026-10-26 11:11:58'),(6,'x','https://x.com',6,1,'2026-10-26 11:11:58','2026-10-26 11:11:58');
/*!40000 ALTER TABLE `social_links` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

