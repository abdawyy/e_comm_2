
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
DROP TABLE IF EXISTS `bundle_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bundle_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bundle_images_bundle_id_foreign` (`bundle_id`),
  CONSTRAINT `bundle_images_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `bundle_images` WRITE;
/*!40000 ALTER TABLE `bundle_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `bundle_images` ENABLE KEYS */;
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
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1792935185),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1792935185;',1792935185),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1793024565),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1793024565;',1793024565),('laravel-cache-cms.strings','a:9:{s:15:\"home.hero_title\";a:2:{s:2:\"en\";s:27:\"Coffee for the neighborhood\";s:2:\"ar\";s:30:\"قهوة بطعم الحارة\";}s:18:\"home.hero_subtitle\";a:2:{s:2:\"en\";s:75:\"Sidamo, Yirgacheffe, espresso, and Turkish coffee, ground the way you brew.\";s:2:\"ar\";s:113:\"سيدامو ويرغاتشيف وإسبريسو وقهوة تركي، تُطحن على طريقة تحضيرك.\";}s:11:\"home.kicker\";a:2:{s:2:\"en\";s:16:\"Roasted in Cairo\";s:2:\"ar\";s:25:\"تحميص القاهرة\";}s:16:\"home.story_title\";a:2:{s:2:\"en\";s:25:\"From the sack to your cup\";s:2:\"ar\";s:39:\"من الشوال إلى الفنجان\";}s:15:\"home.story_body\";a:2:{s:2:\"en\";s:107:\"Whole beans by the pack or the kilo, a grind for your pot, and delivery across Cairo, Giza, and Alexandria.\";s:2:\"ar\";s:168:\"حبوب كاملة بالعبوة أو بالكيلو، وطحن على قدر الإبريق، وتوصيل في القاهرة والجيزة والإسكندرية.\";}s:14:\"trust.shipping\";a:2:{s:2:\"en\";s:51:\"Coffee delivered across Cairo, Giza, and Alexandria\";s:2:\"ar\";s:81:\"توصيل القهوة في القاهرة والجيزة والإسكندرية\";}s:13:\"trust.payment\";a:2:{s:2:\"en\";s:24:\"Cash on delivery or Visa\";s:2:\"ar\";s:48:\"الدفع عند الاستلام أو فيزا\";}s:13:\"trust.support\";a:2:{s:2:\"en\";s:47:\"Arabic and English, from the roast to your door\";s:2:\"ar\";s:84:\"دعم بالعربية والإنجليزية من التحميص حتى الباب\";}s:12:\"footer.blurb\";a:2:{s:2:\"en\";s:43:\"Specialty coffee you can reorder from home.\";s:2:\"ar\";s:56:\"قهوة مختصة تعيد طلبها من البيت.\";}}',2108382794),('laravel-cache-design.assets','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',2108039168),('laravel-cache-design.assets.v2','a:9:{i:0;a:7:{s:2:\"id\";i:1;s:3:\"key\";s:4:\"logo\";s:10:\"image_path\";s:15:\"design/logo.svg\";s:3:\"alt\";s:62:\"{\"en\":\"Atlas coffee mark\",\"ar\":\"علامة قهوة أطلس\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-26 13:52:25\";}i:1;a:7:{s:2:\"id\";i:2;s:3:\"key\";s:9:\"logo_dark\";s:10:\"image_path\";s:20:\"design/logo_dark.svg\";s:3:\"alt\";s:62:\"{\"en\":\"Atlas coffee mark\",\"ar\":\"علامة قهوة أطلس\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-26 13:52:25\";}i:2;a:7:{s:2:\"id\";i:3;s:3:\"key\";s:7:\"favicon\";s:10:\"image_path\";s:18:\"design/favicon.svg\";s:3:\"alt\";s:62:\"{\"en\":\"Atlas coffee mark\",\"ar\":\"علامة قهوة أطلس\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-26 13:50:17\";}i:3;a:7:{s:2:\"id\";i:4;s:3:\"key\";s:4:\"hero\";s:10:\"image_path\";s:15:\"design/hero.svg\";s:3:\"alt\";s:66:\"{\"en\":\"A cup of Atlas coffee\",\"ar\":\"فنجان قهوة أطلس\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-26 13:50:17\";}i:4;a:7:{s:2:\"id\";i:5;s:3:\"key\";s:10:\"og_default\";s:10:\"image_path\";s:21:\"design/og_default.svg\";s:3:\"alt\";s:78:\"{\"en\":\"Atlas Market specialty coffee\",\"ar\":\"قهوة أطلس المختصة\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-26 13:50:17\";}i:5;a:7:{s:2:\"id\";i:6;s:3:\"key\";s:10:\"empty_cart\";s:10:\"image_path\";s:21:\"design/empty_cart.svg\";s:3:\"alt\";s:66:\"{\"en\":\"Atlas Market empty_cart\",\"ar\":\"سوق أطلس empty_cart\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-22 14:25:38\";}i:6;a:7:{s:2:\"id\";i:7;s:3:\"key\";s:10:\"empty_shop\";s:10:\"image_path\";s:21:\"design/empty_shop.svg\";s:3:\"alt\";s:66:\"{\"en\":\"Atlas Market empty_shop\",\"ar\":\"سوق أطلس empty_shop\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-22 14:25:38\";}i:7;a:7:{s:2:\"id\";i:8;s:3:\"key\";s:9:\"auth_side\";s:10:\"image_path\";s:20:\"design/auth_side.svg\";s:3:\"alt\";s:64:\"{\"en\":\"Atlas Market auth_side\",\"ar\":\"سوق أطلس auth_side\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-22 14:25:38\";}i:8;a:7:{s:2:\"id\";i:9;s:3:\"key\";s:11:\"footer_mark\";s:10:\"image_path\";s:22:\"design/footer_mark.svg\";s:3:\"alt\";s:62:\"{\"en\":\"Atlas coffee mark\",\"ar\":\"علامة قهوة أطلس\"}\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-26 13:50:17\";}}',2108382794),('laravel-cache-geo.neighbourhood.30.05,31.25','s:21:\"باب الشعرية\";',1793393376),('laravel-cache-geo.place.a3083687ee111912f7daf2d0bfb3647c7a4ec90a05984cbdebac4203036a9735','a:5:{s:12:\"country_code\";s:2:\"EG\";s:7:\"country\";s:5:\"Egypt\";s:6:\"region\";s:17:\"Cairo Governorate\";s:4:\"city\";s:5:\"Cairo\";s:4:\"area\";s:21:\"باب الشعرية\";}',1793104325),('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:1;',1793025359),('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1793025359;',1793025359),('laravel-cache-site.theme','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',2108039191),('laravel-cache-site.theme.v2','a:15:{s:2:\"id\";i:1;s:10:\"brand_name\";s:12:\"Atlas Market\";s:7:\"primary\";s:7:\"#1A2B28\";s:12:\"primary_dark\";s:7:\"#0F1B19\";s:13:\"primary_light\";s:7:\"#2F4F47\";s:6:\"accent\";s:7:\"#C9A55C\";s:12:\"accent_light\";s:7:\"#E0C58A\";s:6:\"canvas\";s:7:\"#DDD6CA\";s:7:\"surface\";s:7:\"#F5EFE6\";s:6:\"border\";s:7:\"#C9A55C\";s:7:\"success\";s:7:\"#2F4F47\";s:6:\"danger\";s:7:\"#8E3B32\";s:11:\"theme_color\";s:7:\"#1A2B28\";s:10:\"created_at\";s:19:\"2026-10-22 14:25:38\";s:10:\"updated_at\";s:19:\"2026-10-23 21:12:44\";}',2108149983);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
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

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_02_000000_create_store_tables',1),(5,'2026_01_02_000001_create_personal_access_tokens_table',1),(6,'2026_01_02_000002_create_permission_tables',1),(7,'2026_10_23_000001_add_area_to_site_visitors',1),(8,'2026_10_25_000001_create_signup_offers_table',1),(9,'2026_10_26_000001_create_social_links_table',1),(10,'2026_10_29_000001_create_bundle_images_table',2),(11,'2026_11_01_000001_add_internal_products_and_mail_status',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
  `mail_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `mail_error` text COLLATE utf8mb4_unicode_ci,
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
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `gateway` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `intention_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_secret` text COLLATE utf8mb4_unicode_ci,
  `paymob_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymob_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_cents` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `masked_pan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `hmac_valid` tinyint(1) NOT NULL DEFAULT '0',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_transactions_paymob_transaction_id_unique` (`paymob_transaction_id`),
  KEY `payment_transactions_order_id_foreign` (`order_id`),
  KEY `payment_transactions_paymob_order_id_index` (`paymob_order_id`),
  KEY `payment_transactions_special_reference_index` (`special_reference`),
  CONSTRAINT `payment_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `payment_transactions` WRITE;
/*!40000 ALTER TABLE `payment_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_transactions` ENABLE KEYS */;
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
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `phone_otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phone_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `consumed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phone_otps_phone_index` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `phone_otps` WRITE;
/*!40000 ALTER TABLE `phone_otps` DISABLE KEYS */;
/*!40000 ALTER TABLE `phone_otps` ENABLE KEYS */;
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
DROP TABLE IF EXISTS `product_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `visited_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_views_user_id_foreign` (`user_id`),
  KEY `product_views_product_id_foreign` (`product_id`),
  KEY `product_views_session_id_index` (`session_id`),
  KEY `product_views_visited_at_index` (`visited_at`),
  CONSTRAINT `product_views_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_views` WRITE;
/*!40000 ALTER TABLE `product_views` DISABLE KEYS */;
INSERT INTO `product_views` VALUES (1,'XQwDrqzXpxtw1pnhUhywWpPXMekeFqovdZDKevaO',NULL,1,'2026-10-22 11:27:28'),(2,'XQwDrqzXpxtw1pnhUhywWpPXMekeFqovdZDKevaO',NULL,1,'2026-10-22 11:27:34'),(3,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 15:55:15'),(4,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 15:56:16'),(5,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 15:56:18'),(6,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 15:59:15'),(7,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 15:59:21'),(8,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 15:59:46'),(9,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:01:26'),(10,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:01:37'),(11,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:01:39'),(12,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:01:45'),(13,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:03:32'),(14,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:03:36'),(15,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:05:24'),(16,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 16:44:12'),(17,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:44:57'),(18,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 16:46:40'),(19,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:46:41'),(20,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:46:50'),(21,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 16:46:51'),(22,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:47:00'),(23,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 16:47:01'),(24,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:47:06'),(25,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 16:47:07'),(26,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:47:29'),(27,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:47:54'),(28,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 16:48:13'),(29,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,1,'2026-10-23 16:48:48'),(30,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:04:12'),(31,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 17:42:20'),(32,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,3,'2026-10-23 17:42:26'),(33,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:49:11'),(34,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:49:19'),(35,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:49:31'),(36,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:49:35'),(37,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:49:43'),(38,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:49:59'),(39,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:50:11'),(40,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:50:19'),(41,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:50:23'),(42,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:50:25'),(43,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:50:44'),(44,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:50:49'),(45,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:51:10'),(46,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:53:38'),(47,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,3,'2026-10-23 17:54:12'),(48,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:29:39'),(49,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:29:42'),(50,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:48:57'),(51,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:49:01'),(52,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:49:07'),(53,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:49:10'),(54,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:49:22'),(55,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:49:34'),(56,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:49:45'),(57,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:50:09'),(58,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,3,'2026-10-26 10:50:56');
/*!40000 ALTER TABLE `product_views` ENABLE KEYS */;
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
  `is_internal` tinyint(1) NOT NULL DEFAULT '0',
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
INSERT INTO `products` VALUES (1,1,'ATL-OIL','{\"ar\": \"زيت زيتون أطلس\", \"en\": \"Atlas olive oil\"}','{\"ar\": \"zyt-zyton-atls\", \"en\": \"atlas-olive-oil\"}','{\"ar\": \"معصور على البارد ويباع بالكيلو.\", \"en\": \"Cold pressed, sold by the kilo.\"}',180.0000,'kg',NULL,NULL,1,40.0000,NULL,NULL,0,1,0,'2026-10-22 11:25:38','2026-10-26 10:53:02'),(2,1,'ATL-DATE','{\"ar\": \"بلح مجهول\", \"en\": \"Medjool dates\"}','{\"ar\": \"blh-mghol\", \"en\": \"medjool-dates\"}','{\"ar\": \"بلح طري بالكيلو.\", \"en\": \"Soft dates by the kilo.\"}',95.0000,'kg',NULL,NULL,1,25.0000,NULL,NULL,0,1,0,'2026-10-22 11:25:38','2026-10-26 10:53:02'),(3,2,'ATL-COFFEE','{\"ar\": \"قهوة سيدامو\", \"en\": \"Sidamo coffee\"}','{\"ar\": \"kho-sydamo\", \"en\": \"sidamo-coffee\"}','{\"ar\": \"حبوب كاملة من سيدامو بنكهة التوت والكاكاو. تباع بالعبوة، ويمكن طحنها لإبريقك.\", \"en\": \"Berry and cocoa whole beans from Sidamo. Sold by the pack, and we can grind them for your pot.\"}',140.0000,'pack',NULL,NULL,1,30.0000,'products/ATL-COFFEE.svg',NULL,1,1,0,'2026-10-22 11:25:38','2026-10-26 10:50:16'),(4,2,'ATL-YIRG','{\"ar\": \"حبوب يرغاتشيف\", \"en\": \"Yirgacheffe beans\"}','{\"ar\": \"hbob-yrghatshyf\", \"en\": \"yirgacheffe-beans\"}','{\"ar\": \"حبوب يرغاتشيف الزهرية بالكيلو، للترشيح والتحضير البطيء.\", \"en\": \"Floral Yirgacheffe whole beans, sold by the kilo for pour-over and filter.\"}',320.0000,'kg',NULL,NULL,1,18.0000,'products/ATL-YIRG.svg',NULL,1,1,0,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(5,2,'ATL-SANTOS','{\"ar\": \"قهوة سانتوس\", \"en\": \"Santos coffee\"}','{\"ar\": \"kho-santos\", \"en\": \"santos-coffee\"}','{\"ar\": \"حبوب سانتوس البرازيلية الناعمة في عبوة. فنجان يومي خفيف بنهاية جوزية.\", \"en\": \"Soft Brazilian Santos beans in a pack. A gentle daily cup with a nutty finish.\"}',165.0000,'pack',NULL,NULL,1,24.0000,'products/ATL-SANTOS.svg',NULL,1,1,0,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(6,2,'ATL-ESPRESSO','{\"ar\": \"إسبريسو الدار\", \"en\": \"House espresso\"}','{\"ar\": \"asbryso-aldar\", \"en\": \"house-espresso\"}','{\"ar\": \"خلطة الدار الداكنة للإسبريسو ومشروبات الحليب، تباع بالعبوة.\", \"en\": \"A dark house blend for espresso and milk drinks, sold by the pack.\"}',155.0000,'pack',NULL,NULL,1,22.0000,'products/ATL-ESPRESSO.svg',NULL,1,1,0,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(7,2,'ATL-TURK','{\"ar\": \"قهوة تركي\", \"en\": \"Turkish coffee\"}','{\"ar\": \"kho-trky\", \"en\": \"turkish-coffee\"}','{\"ar\": \"قهوة ناعمة للركوة، تباع بالعبوة. لا تحتاج طحناً إضافياً.\", \"en\": \"Fine ground coffee for a cezve, sold by the pack. No extra grind needed.\"}',95.0000,'pack',NULL,NULL,1,40.0000,'products/ATL-TURK.svg',NULL,0,1,0,'2026-10-26 10:27:28','2026-10-26 10:50:17'),(8,2,'ATL-DECAF','{\"ar\": \"خلطة منزوعة الكافيين\", \"en\": \"Decaf blend\"}','{\"ar\": \"khlt-mnzoaa-alkafyyn\", \"en\": \"decaf-blend\"}','{\"ar\": \"خلطة خفيفة منزوعة الكافيين للمساء، تباع بالكيلو.\", \"en\": \"A mild decaf blend for the evening, sold by the kilo.\"}',280.0000,'kg',NULL,NULL,1,12.0000,'products/ATL-DECAF.svg',NULL,0,1,0,'2026-10-26 10:27:28','2026-10-26 10:50:17');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.20.21 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36','eyJfdG9rZW4iOiJMaGZrQUp6ekdDdnZDQUxnTU1UVUphNlhNT2NOSkZlWU1mZnJFamdrIiwibG9jYWxlIjoiZW4iLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9sb2dpbiIsInJvdXRlIjoiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImNhcnQiOnsibGluZXMiOnsiNWQ5ZjdkZTQ1NmUyYzJhMyI6eyJraW5kIjoicHJvZHVjdCIsInByb2R1Y3RfaWQiOjMsInF1YW50aXR5IjoiMS4wMDAwIiwidW5pdCI6InBhY2siLCJzZXJ2aWNlX2lkcyI6W10sInBhY2thZ2luZ19pZCI6bnVsbCwia2V5IjoiNWQ5ZjdkZTQ1NmUyYzJhMyJ9fX0sImNoZWNrb3V0Ijp7Im5vbmNlIjoiMzNiYzQ4ZDYwMGMxODljZDAxNjU3MWE2Y2E2OWY2MjEifSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9zb2NpYWwtbGlua3MifX0=',1793025724),('aAeV1BqUKCecBHiKCfl4EENnQKUiIWCrW2zkn5gV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJBdHV0RlBXb2FqU0VWaFZIYVV2NGttdzV6MjFkU3htZGhqTm1Sa0lMIiwibG9jYWxlIjoiZW4iLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793022794),('P4eIDLsj1fnU4rLX8fvmXz7jw3MBNOUn8rmNUHLG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJGSk44SUp2dTIwWmlxQWlqdWNPUlhSNmoycGR5SjJWSDBZc3Fwb01lIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2NhbGUiOiJlbiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvcmVnaXN0ZXIiLCJyb3V0ZSI6InJlZ2lzdGVyIn19',1793026251),('q3QOekf0FfuKAuA6sUoVfqKknM5FgioWGZ8iy3FI',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJyV1d2V0U3WmR4dERuV0ZPM2ppazR4Rjl1VUdRV0o0eDU5bzhTT0dkIiwibG9jYWxlIjoiZW4iLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793022661),('tX8scZkwFBvZ1nTaoMZS3KhsjJlR7Y8uJl33Obcp',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJYakJKZmlac2hVcEFWaGtBMUpZQzRUaWpQZGZTaWRVeUdIS1hvOFoxIiwibG9jYWxlIjoiZW4iLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793022636);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
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
DROP TABLE IF EXISTS `site_page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_page_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_visitor_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `viewed_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_page_views_site_visitor_id_foreign` (`site_visitor_id`),
  KEY `site_page_views_viewed_at_index` (`viewed_at`),
  CONSTRAINT `site_page_views_site_visitor_id_foreign` FOREIGN KEY (`site_visitor_id`) REFERENCES `site_visitors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `site_page_views` WRITE;
/*!40000 ALTER TABLE `site_page_views` DISABLE KEYS */;
INSERT INTO `site_page_views` VALUES (1,1,'/','2026-10-22 11:26:55'),(2,1,'/products/atlas-olive-oil','2026-10-22 11:27:28'),(3,1,'/products/atlas-olive-oil','2026-10-22 11:27:34'),(4,1,'/cart','2026-10-22 11:28:19'),(5,1,'/cart','2026-10-22 11:28:31'),(6,2,'/','2026-10-23 14:29:29'),(7,2,'/shop','2026-10-23 14:29:44'),(8,2,'/bundles','2026-10-23 14:29:47'),(9,2,'/shop','2026-10-23 14:29:50'),(10,2,'/','2026-10-23 14:29:52'),(11,3,'/track','2026-10-23 14:59:55'),(12,3,'/track','2026-10-23 15:18:23'),(13,4,'/','2026-10-23 15:24:34'),(14,3,'/track','2026-10-23 15:24:57'),(15,4,'/','2026-10-23 15:24:58'),(16,4,'/','2026-10-23 15:25:08'),(17,3,'/track','2026-10-23 15:25:11'),(18,4,'/','2026-10-23 15:25:16'),(19,3,'/track','2026-10-23 15:25:17'),(20,4,'/shop','2026-10-23 15:25:21'),(21,4,'/shop','2026-10-23 15:25:38'),(22,3,'/track','2026-10-23 15:25:38'),(23,3,'/track','2026-10-23 15:25:42'),(24,4,'/shop','2026-10-23 15:25:43'),(25,3,'/track','2026-10-23 15:26:05'),(26,4,'/shop','2026-10-23 15:26:06'),(27,3,'/track','2026-10-23 15:26:11'),(28,4,'/shop','2026-10-23 15:26:12'),(29,3,'/track','2026-10-23 15:26:16'),(30,4,'/shop','2026-10-23 15:26:17'),(31,3,'/track','2026-10-23 15:26:20'),(32,4,'/shop','2026-10-23 15:26:21'),(33,3,'/track','2026-10-23 15:26:25'),(34,4,'/shop','2026-10-23 15:26:25'),(35,3,'/track','2026-10-23 15:26:28'),(36,4,'/shop','2026-10-23 15:26:28'),(37,4,'/shop','2026-10-23 15:32:50'),(38,4,'/shop','2026-10-23 15:37:08'),(39,4,'/shop','2026-10-23 15:37:14'),(40,5,'/shop','2026-10-23 15:54:55'),(41,5,'/products/atlas-olive-oil','2026-10-23 15:55:15'),(42,5,'/products/atlas-olive-oil','2026-10-23 15:56:16'),(43,5,'/products/atlas-olive-oil','2026-10-23 15:56:18'),(44,4,'/shop','2026-10-23 15:56:24'),(45,4,'/bundles','2026-10-23 15:56:37'),(46,4,'/','2026-10-23 15:56:40'),(47,4,'/','2026-10-23 15:59:13'),(48,5,'/products/atlas-olive-oil','2026-10-23 15:59:15'),(49,5,'/products/atlas-olive-oil','2026-10-23 15:59:21'),(50,4,'/','2026-10-23 15:59:22'),(51,5,'/shop','2026-10-23 15:59:31'),(52,5,'/products/atlas-olive-oil','2026-10-23 15:59:46'),(53,4,'/','2026-10-23 16:01:24'),(54,5,'/products/atlas-olive-oil','2026-10-23 16:01:26'),(55,4,'/','2026-10-23 16:01:36'),(56,5,'/products/atlas-olive-oil','2026-10-23 16:01:37'),(57,5,'/products/atlas-olive-oil','2026-10-23 16:01:39'),(58,4,'/','2026-10-23 16:01:40'),(59,5,'/products/atlas-olive-oil','2026-10-23 16:01:45'),(60,4,'/','2026-10-23 16:03:26'),(61,4,'/','2026-10-23 16:03:31'),(62,5,'/products/atlas-olive-oil','2026-10-23 16:03:32'),(63,5,'/products/atlas-olive-oil','2026-10-23 16:03:36'),(64,5,'/products/atlas-olive-oil','2026-10-23 16:05:24'),(65,4,'/','2026-10-23 16:05:25'),(66,5,'/shop','2026-10-23 16:05:35'),(67,5,'/shop','2026-10-23 16:06:57'),(68,4,'/','2026-10-23 16:06:57'),(69,5,'/shop','2026-10-23 16:07:20'),(70,5,'/shop','2026-10-23 16:08:00'),(71,5,'/','2026-10-23 16:09:27'),(72,5,'/bundles','2026-10-23 16:09:33'),(73,4,'/','2026-10-23 16:13:33'),(74,5,'/bundles','2026-10-23 16:13:34'),(75,5,'/bundles','2026-10-23 16:13:50'),(76,4,'/','2026-10-23 16:13:50'),(77,5,'/bundles','2026-10-23 16:13:57'),(78,4,'/','2026-10-23 16:13:58'),(79,5,'/','2026-10-23 16:14:48'),(80,5,'/','2026-10-23 16:15:54'),(81,4,'/','2026-10-23 16:15:55'),(82,5,'/shop','2026-10-23 16:16:14'),(83,4,'/','2026-10-23 16:17:03'),(84,4,'/','2026-10-23 16:17:15'),(85,4,'/','2026-10-23 16:17:18'),(86,4,'/','2026-10-23 16:43:32'),(87,4,'/products/sidamo-coffee','2026-10-23 16:44:12'),(88,5,'/products/sidamo-coffee','2026-10-23 16:44:57'),(89,4,'/products/sidamo-coffee','2026-10-23 16:46:40'),(90,5,'/products/sidamo-coffee','2026-10-23 16:46:41'),(91,5,'/products/sidamo-coffee','2026-10-23 16:46:50'),(92,4,'/products/sidamo-coffee','2026-10-23 16:46:51'),(93,5,'/products/sidamo-coffee','2026-10-23 16:47:00'),(94,4,'/products/sidamo-coffee','2026-10-23 16:47:01'),(95,5,'/products/sidamo-coffee','2026-10-23 16:47:06'),(96,4,'/products/sidamo-coffee','2026-10-23 16:47:07'),(97,5,'/products/sidamo-coffee','2026-10-23 16:47:29'),(98,5,'/products/sidamo-coffee','2026-10-23 16:47:54'),(99,5,'/products/sidamo-coffee','2026-10-23 16:48:13'),(100,5,'/products/atlas-olive-oil','2026-10-23 16:48:48'),(101,5,'/','2026-10-23 17:02:27'),(102,5,'/','2026-10-23 17:03:44'),(103,5,'/products/sidamo-coffee','2026-10-23 17:04:12'),(104,4,'/products/sidamo-coffee','2026-10-23 17:42:20'),(105,4,'/products/sidamo-coffee','2026-10-23 17:42:26'),(106,4,'/','2026-10-23 17:42:43'),(107,4,'/track','2026-10-23 17:42:44'),(108,4,'/bundles','2026-10-23 17:42:46'),(109,4,'/shop','2026-10-23 17:42:48'),(110,4,'/','2026-10-23 17:42:50'),(111,5,'/products/sidamo-coffee','2026-10-23 17:49:11'),(112,4,'/','2026-10-23 17:49:12'),(113,5,'/products/sidamo-coffee','2026-10-23 17:49:19'),(114,5,'/products/sidamo-coffee','2026-10-23 17:49:31'),(115,5,'/products/sidamo-coffee','2026-10-23 17:49:35'),(116,5,'/products/sidamo-coffee','2026-10-23 17:49:43'),(117,5,'/products/sidamo-coffee','2026-10-23 17:49:59'),(118,5,'/products/sidamo-coffee','2026-10-23 17:50:11'),(119,5,'/products/sidamo-coffee','2026-10-23 17:50:19'),(120,5,'/products/sidamo-coffee','2026-10-23 17:50:23'),(121,5,'/products/sidamo-coffee','2026-10-23 17:50:25'),(122,5,'/products/sidamo-coffee','2026-10-23 17:50:44'),(123,5,'/products/sidamo-coffee','2026-10-23 17:50:49'),(124,5,'/products/sidamo-coffee','2026-10-23 17:51:10'),(125,5,'/products/sidamo-coffee','2026-10-23 17:53:38'),(126,4,'/','2026-10-23 17:53:38'),(127,5,'/products/sidamo-coffee','2026-10-23 17:54:12'),(128,4,'/','2026-10-23 17:54:13'),(129,5,'/shop','2026-10-23 17:54:14'),(130,5,'/shop','2026-10-23 17:54:51'),(131,4,'/','2026-10-23 17:54:51'),(132,5,'/shop','2026-10-23 17:54:53'),(133,4,'/','2026-10-23 17:54:54'),(134,5,'/shop','2026-10-23 17:54:55'),(135,4,'/','2026-10-23 17:54:56'),(136,5,'/shop','2026-10-23 17:55:09'),(137,4,'/','2026-10-23 17:55:09'),(138,5,'/shop','2026-10-23 17:56:23'),(139,5,'/shop','2026-10-23 17:59:35'),(140,4,'/','2026-10-23 17:59:36'),(141,5,'/shop','2026-10-23 17:59:42'),(142,4,'/','2026-10-23 17:59:43'),(143,5,'/shop','2026-10-23 18:00:04'),(144,4,'/','2026-10-23 18:00:05'),(145,5,'/shop','2026-10-23 18:00:07'),(146,4,'/','2026-10-23 18:00:07'),(147,5,'/shop','2026-10-23 18:00:09'),(148,4,'/','2026-10-23 18:00:09'),(149,4,'/','2026-10-23 18:00:35'),(150,5,'/shop','2026-10-23 18:00:36'),(151,5,'/shop','2026-10-23 18:00:42'),(152,5,'/shop','2026-10-23 18:00:52'),(153,5,'/','2026-10-23 18:13:03'),(154,4,'/','2026-10-23 18:20:11'),(155,4,'/','2026-10-23 18:22:38'),(156,6,'/','2026-10-23 18:24:10'),(157,6,'/','2026-10-23 18:24:15'),(158,6,'/','2026-10-23 18:24:17'),(159,6,'/cart','2026-10-23 18:25:25'),(160,6,'/login','2026-10-23 18:25:38'),(161,6,'/register','2026-10-23 18:25:52'),(162,6,'/login','2026-10-23 18:26:36'),(163,6,'/login','2026-10-23 18:26:43'),(164,7,'/','2026-10-25 09:20:40'),(165,8,'/','2026-10-25 09:20:45'),(166,8,'/','2026-10-25 09:26:55'),(167,8,'/','2026-10-25 09:37:56'),(168,9,'/','2026-10-25 10:31:12'),(169,10,'/','2026-10-25 10:34:31'),(170,10,'/','2026-10-25 10:34:54'),(171,10,'/','2026-10-25 10:36:05'),(172,9,'/','2026-10-25 10:36:55'),(173,10,'/','2026-10-25 10:36:59'),(174,9,'/','2026-10-25 10:37:15'),(175,9,'/','2026-10-25 10:37:40'),(176,11,'/','2026-10-26 05:14:25'),(177,12,'/','2026-10-26 05:16:54'),(178,13,'/','2026-10-26 09:32:02'),(179,14,'/','2026-10-26 09:32:07'),(180,14,'/shop','2026-10-26 10:27:45'),(181,14,'/shop','2026-10-26 10:28:37'),(182,14,'/shop','2026-10-26 10:28:41'),(183,14,'/shop','2026-10-26 10:28:52'),(184,14,'/products/sidamo-coffee','2026-10-26 10:29:39'),(185,14,'/products/sidamo-coffee','2026-10-26 10:29:42'),(186,14,'/products/sidamo-coffee','2026-10-26 10:48:57'),(187,14,'/products/sidamo-coffee','2026-10-26 10:49:01'),(188,14,'/products/sidamo-coffee','2026-10-26 10:49:07'),(189,14,'/products/sidamo-coffee','2026-10-26 10:49:10'),(190,14,'/products/sidamo-coffee','2026-10-26 10:49:22'),(191,14,'/products/sidamo-coffee','2026-10-26 10:49:34'),(192,14,'/products/sidamo-coffee','2026-10-26 10:49:45'),(193,14,'/products/sidamo-coffee','2026-10-26 10:50:09'),(194,15,'/','2026-10-26 10:50:36'),(195,14,'/products/sidamo-coffee','2026-10-26 10:50:56'),(196,14,'/','2026-10-26 10:51:00'),(197,16,'/','2026-10-26 10:51:01'),(198,14,'/','2026-10-26 10:51:29'),(199,14,'/','2026-10-26 10:51:49'),(200,14,'/','2026-10-26 10:52:18'),(201,14,'/','2026-10-26 10:52:40'),(202,17,'/','2026-10-26 10:53:15'),(203,17,'/shop','2026-10-26 10:53:33'),(204,17,'/bundles','2026-10-26 10:53:40'),(205,17,'/shop','2026-10-26 10:53:43'),(206,17,'/cart','2026-10-26 10:59:54'),(207,17,'/checkout','2026-10-26 10:59:59'),(208,17,'/checkout','2026-10-26 11:00:27'),(209,14,'/','2026-10-26 11:00:30'),(210,17,'/checkout','2026-10-26 11:00:36'),(211,17,'/cart','2026-10-26 11:00:39'),(212,17,'/shop','2026-10-26 11:00:40'),(213,17,'/bundles','2026-10-26 11:00:44'),(214,17,'/shop','2026-10-26 11:00:46'),(215,17,'/','2026-10-26 11:00:48'),(216,17,'/cart','2026-10-26 11:00:56'),(217,14,'/','2026-10-26 11:02:04'),(218,17,'/cart','2026-10-26 11:02:05'),(219,14,'/shop','2026-10-26 11:02:09'),(220,14,'/shop','2026-10-26 11:02:29'),(221,17,'/cart','2026-10-26 11:02:30'),(222,14,'/shop','2026-10-26 11:04:18'),(223,17,'/cart','2026-10-26 11:04:19'),(224,14,'/shop','2026-10-26 11:04:25'),(225,17,'/cart','2026-10-26 11:04:25'),(226,14,'/shop','2026-10-26 11:04:31'),(227,17,'/cart','2026-10-26 11:04:32'),(228,14,'/shop','2026-10-26 11:04:49'),(229,17,'/cart','2026-10-26 11:04:50'),(230,14,'/cart','2026-10-26 11:05:18'),(231,14,'/cart','2026-10-26 11:05:54'),(232,17,'/cart','2026-10-26 11:05:55'),(233,14,'/cart','2026-10-26 11:05:58'),(234,14,'/checkout','2026-10-26 11:06:08'),(235,14,'/checkout','2026-10-26 11:06:12'),(236,17,'/checkout','2026-10-26 11:06:49'),(237,17,'/cart','2026-10-26 11:06:57'),(238,14,'/checkout','2026-10-26 11:10:19'),(239,17,'/cart','2026-10-26 11:10:19'),(240,14,'/checkout','2026-10-26 11:10:23'),(241,17,'/cart','2026-10-26 11:10:24'),(242,14,'/checkout','2026-10-26 11:10:33'),(243,17,'/cart','2026-10-26 11:10:34'),(244,14,'/checkout','2026-10-26 11:10:35'),(245,17,'/cart','2026-10-26 11:10:36'),(246,14,'/checkout','2026-10-26 11:10:41'),(247,17,'/cart','2026-10-26 11:10:42'),(248,14,'/','2026-10-26 11:12:17'),(249,14,'/','2026-10-26 11:12:48'),(250,17,'/cart','2026-10-26 11:12:48'),(251,14,'/track','2026-10-26 11:20:54'),(252,17,'/cart','2026-10-26 11:21:45'),(253,17,'/bundles','2026-10-26 11:21:49'),(254,17,'/shop','2026-10-26 11:21:51'),(255,17,'/track','2026-10-26 11:21:55'),(256,17,'/','2026-10-26 11:21:57'),(257,14,'/track','2026-10-26 11:28:39'),(258,17,'/','2026-10-26 11:28:40'),(259,14,'/track','2026-10-26 11:29:12'),(260,17,'/','2026-10-26 11:29:13'),(261,17,'/','2026-10-26 11:33:45'),(262,17,'/bundles','2026-10-26 11:33:48'),(263,17,'/bundles/weekend-breakfast-box','2026-10-26 11:33:53'),(264,17,'/bundles','2026-10-26 11:34:12'),(265,14,'/track','2026-10-26 11:36:50'),(266,14,'/track','2026-10-26 11:40:31'),(267,14,'/track','2026-10-26 11:40:39'),(268,14,'/track','2026-10-26 11:40:42'),(269,14,'/track','2026-10-26 11:40:44'),(270,14,'/track','2026-10-26 11:40:59'),(271,14,'/track','2026-10-26 11:41:01'),(272,14,'/track','2026-10-26 11:41:04'),(273,14,'/track','2026-10-26 11:41:09'),(274,14,'/track','2026-10-26 11:41:22'),(275,18,'/','2026-10-26 11:49:54'),(276,18,'/login','2026-10-26 11:50:07'),(277,18,'/login','2026-10-26 11:50:29'),(278,18,'/login','2026-10-26 11:50:33'),(279,18,'/login','2026-10-26 11:50:40'),(280,18,'/register','2026-10-26 11:50:51');
/*!40000 ALTER TABLE `site_page_views` ENABLE KEYS */;
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
DROP TABLE IF EXISTS `site_visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_visitors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer` text COLLATE utf8mb4_unicode_ci,
  `referrer_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_term` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `geo_resolved_at` timestamp NULL DEFAULT NULL,
  `first_seen_at` timestamp NOT NULL,
  `last_seen_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_visitors_session_id_unique` (`session_id`),
  KEY `site_visitors_user_id_foreign` (`user_id`),
  KEY `site_visitors_last_seen_at_index` (`last_seen_at`),
  CONSTRAINT `site_visitors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `site_visitors` WRITE;
/*!40000 ALTER TABLE `site_visitors` DISABLE KEYS */;
INSERT INTO `site_visitors` VALUES (1,'XQwDrqzXpxtw1pnhUhywWpPXMekeFqovdZDKevaO',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-22 11:26:55','2026-10-22 11:28:31','2026-10-22 11:26:55','2026-10-22 11:28:31'),(2,'AkFWAiRBTkqomeWx1gXejb7UT9IcHapaRyx4O4Ev',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-23 14:29:29','2026-10-23 14:29:52','2026-10-23 14:29:29','2026-10-23 14:29:52'),(3,'lyimrzXwlRuyGwvgPZOYncmuAMeNEO05fJOBkN7T',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-23 14:59:55','2026-10-23 15:26:28','2026-10-23 14:59:55','2026-10-23 15:26:28'),(4,'0lauLuK1hSZrAsWYkyqFwBBJbvbz1Do9UhVax3zS',1,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-23 17:53:38','2026-10-23 15:24:34','2026-10-23 18:22:38','2026-10-23 15:24:34','2026-10-23 18:22:39'),(5,'mtYF9ywl83q8oDASHsIS2XYugmUiP1rWu2hv1vbS',1,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-23 17:49:34','2026-10-23 15:54:55','2026-10-23 18:13:03','2026-10-23 15:54:55','2026-10-23 18:13:03'),(6,'XlkwG85gf6AiSPUs5GIYfR0CCutNLJzi9TV7kbYP',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','5b8513f7090570626ff01d7abcb48ef532d897a9a2b4b0ffd8b6421bd0c8ee19','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-23 18:24:10','2026-10-23 18:24:10','2026-10-23 18:26:43','2026-10-23 18:24:10','2026-10-23 18:26:43'),(7,'q47aTp8xan9QgiqGTNlmrjV7YkktjJom7eF3mG9u',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-25 09:20:43','2026-10-25 09:20:40','2026-10-25 09:20:40','2026-10-25 09:20:43','2026-10-25 09:20:43'),(8,'GCnJgF1bxnTKW81mjd9lf6MF2QJkaOQw2iQI24cC',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-25 09:20:45','2026-10-25 09:20:45','2026-10-25 09:37:56','2026-10-25 09:20:45','2026-10-25 09:37:56'),(9,'0ltxbamudPb63zonaJdS2jrx7KGaPphlImUgszn3',1,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-25 10:31:12','2026-10-25 10:31:12','2026-10-25 10:37:40','2026-10-25 10:31:12','2026-10-25 10:37:40'),(10,'GdeO0TgRfoqjkX6RuCu2MLglmifThYHBlHuhsdrf',1,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-25 10:34:31','2026-10-25 10:34:31','2026-10-25 10:36:59','2026-10-25 10:34:31','2026-10-25 10:36:59'),(11,'LT6amlkptnDk7Gr5P5ZOFa5kzuo7MB1yAEkoqTDw',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop','http://127.0.0.1:8000/','127.0.0.1',NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 05:14:25','2026-10-26 05:14:25','2026-10-26 05:14:25','2026-10-26 05:14:25','2026-10-26 05:14:25'),(12,'hZqlleRCoMwiCPg8m6buMnj7GKbSZAbMIMvLPIzt',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop','http://127.0.0.1:8000/','127.0.0.1',NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 05:16:54','2026-10-26 05:16:54','2026-10-26 05:16:54','2026-10-26 05:16:54','2026-10-26 05:16:54'),(13,'xFPs3hvL9ma9moa1JZ4JI9l59OIIrrwAa1PBbVZY',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 09:32:05','2026-10-26 09:32:02','2026-10-26 09:32:02','2026-10-26 09:32:05','2026-10-26 09:32:05'),(14,'9M3qz4xNQ89AHvtjSu13pysCqHKsBGq9kGDbz81R',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','6233dc8678c9b2318adc55904cc1155762db557f8af9dec134629812187f85c0','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 09:32:07','2026-10-26 09:32:07','2026-10-26 11:41:22','2026-10-26 09:32:07','2026-10-26 11:41:22'),(15,'tX8scZkwFBvZ1nTaoMZS3KhsjJlR7Y8uJl33Obcp',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','c4c9b1f4d57b6706da2d630e38a6bea0468b47b031180e6341021c7a396dd5cf','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 10:50:36','2026-10-26 10:50:36','2026-10-26 10:50:36','2026-10-26 10:50:36','2026-10-26 10:50:36'),(16,'q3QOekf0FfuKAuA6sUoVfqKknM5FgioWGZ8iy3FI',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','c4c9b1f4d57b6706da2d630e38a6bea0468b47b031180e6341021c7a396dd5cf','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 10:51:01','2026-10-26 10:51:01','2026-10-26 10:51:01','2026-10-26 10:51:01','2026-10-26 10:51:01'),(17,'CCOdp6A5v81t6KsCbmWJC6MgvhGd2NOzQlvam1UE',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 10:53:15','2026-10-26 10:53:15','2026-10-26 11:34:12','2026-10-26 10:53:15','2026-10-26 11:34:12'),(18,'P4eIDLsj1fnU4rLX8fvmXz7jw3MBNOUn8rmNUHLG',NULL,'ca0b0b5c5e61aa26f0c7478d982f51a53fc63eef238e21a4d8f48409ba1f3792','d6a888fce2842eab6242797380d30f42d9a066670dde2bbe030c0807a301c077','desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'EG','Egypt','Cairo Governorate','Cairo','باب الشعرية','2026-10-26 11:49:54','2026-10-26 11:49:54','2026-10-26 11:50:51','2026-10-26 11:49:54','2026-10-26 11:50:51');
/*!40000 ALTER TABLE `site_visitors` ENABLE KEYS */;
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
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

