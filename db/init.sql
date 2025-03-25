

-- -- CREATE TABLE users (
-- --     `id` VARCHAR(36) PRIMARY KEY,
-- --     `display_name` VARCHAR(50),
-- --     `email` UNIQUE NOT NULL,
-- --     `user_name` VARCHAR(50),
-- --     `password` VARCHAR(100)
-- -- );
-- CREATE TABLE items (
--     `id` VARCHAR(36) PRIMARY KEY,
--     `name` VARCHAR(50),
--     `description` VARCHAR(100),
--     `price` DECIMAL(10, 2),
--     `user_id` VARCHAR(36),
--     FOREIGN KEY (`user_id`) REFERENCES users(`id`)
-- );
-- CREATE TABLE supplier(
--     `id` VARCHAR(36) PRIMARY KEY,
--     `name` VARCHAR(50),
--     `email` VARCHAR(50),
--     `phone_number` VARCHAR(50),
--     `status` VARCHAR(50)
-- );

-- CREATE TABLE orders(
--     `id` VARCHAR(36) PRIMARY KEY,
--     `users_id` VARCHAR(36),
--     `employee_id` VARCHAR(36),
--     `order_detail` VARCHAR(50), 
--     `status` VARCHAR(50),
--     FOREIGN KEY (`item_id`) REFERENCES items(`id`),
--     FOREIGN KEY (`supplier_id`) REFERENCES supplier(`id`)
-- );

-- CREATE TABLE import(
--     `id` VARCHAR(36) PRIMARY KEY,
--     `item_id` VARCHAR(36),
--     `supplier_id` VARCHAR(36),
--     `status` VARCHAR(50),
--     `import_detail` VARCHAR(50),
--     FOREIGN KEY (`item_id`) REFERENCES items(`id`),
--     FOREIGN KEY (`supplier_id`) REFERENCES supplier(`id`)
-- );

-- CREATE TABLE warranty(
--     `id` VARCHAR(36) PRIMARY KEY,
--     `item_id` VARCHAR(36),
--     `user_id` VARCHAR(36),
--     `supplier_id` VARCHAR(36),
--     `issue_date` DATE,
--     `expiration_date` DATE,
--     FOREIGN KEY (`item_id`) REFERENCES items(`id`),
--     FOREIGN KEY (`user_id`) REFERENCES users(`id`),
--     FOREIGN KEY (`supplier_id`) REFERENCES supplier(`id`)
-- );

-- CREATE TABLE cart(
--     `user_id` VARCHAR(36),
--     `item_id` VARCHAR(36),
--     `quantity` INT,
--     FOREIGN KEY (`user_id`) REFERENCES users(`id`),
--     FOREIGN KEY (`item_id`) REFERENCES items(`id`)
-- );

-- CREATE TABLE profile(
--     `id`INT,
--     `fullname` VARCHAR(256),
--     `phone_number` VARCHAR(10),
--     `email` VARCHAR(45),
--     `avatar` VARCHAR(256)
-- );

-- CREATE TABLE account(
--     `id` INT,
--     `user_name` VARCHAR(256),
--     `pass_word` VARCHAR(256),
--     `rule` INT,
--     `status` ENUM(),
--     created DATETIME,
--     update
    
-- );

-- MySQL dump 10.13  Distrib 8.0.37, for Win64 (x86_64)
--
-- Host: localhost    Database: technology_webstore
-- ------------------------------------------------------
-- Server version	8.0.37

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
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account` (
  `id` int NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `rule` int DEFAULT NULL,
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `status_UNIQUE` (`status`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `account-rule-rule_idx` (`rule`),
  CONSTRAINT `account-rule-rule` FOREIGN KEY (`rule`) REFERENCES `rule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `id` int NOT NULL,
  `profile_id` int NOT NULL,
  `phone_number` varchar(45) NOT NULL,
  `street` varchar(45) DEFAULT NULL,
  `ward` varchar(45) DEFAULT NULL,
  `district` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address-profile-profile_id_idx` (`profile_id`),
  CONSTRAINT `address-profile-profile_id` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `profile_id` int NOT NULL,
  `product_id` int NOT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`profile_id`,`product_id`),
  KEY `cart-profile-profile_id_id_idx` (`profile_id`),
  KEY `cart-product-product_id_id_idx` (`product_id`),
  CONSTRAINT `cart-product-product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `cart-profile-profile_id` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `function`
--

DROP TABLE IF EXISTS `function`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `function` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `function`
--

LOCK TABLES `function` WRITE;
/*!40000 ALTER TABLE `function` DISABLE KEYS */;
/*!40000 ALTER TABLE `function` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import`
--

DROP TABLE IF EXISTS `import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import` (
  `id` int NOT NULL,
  `suppiler_id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `import-supplier-supplier_id_idx` (`suppiler_id`),
  CONSTRAINT `import-supplier-supplier_id` FOREIGN KEY (`suppiler_id`) REFERENCES `supplier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import`
--

LOCK TABLES `import` WRITE;
/*!40000 ALTER TABLE `import` DISABLE KEYS */;
/*!40000 ALTER TABLE `import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_detail`
--

DROP TABLE IF EXISTS `import_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_detail` (
  `id` int NOT NULL,
  `import_id` int NOT NULL,
  `product_id` int NOT NULL,
  `import_price` int NOT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `import_detail-import-import_id_idx` (`import_id`),
  KEY `import_detail-product-product_id_idx` (`product_id`),
  CONSTRAINT `import_detail-import-import_id` FOREIGN KEY (`import_id`) REFERENCES `import` (`id`),
  CONSTRAINT `import_detail-product-product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_detail`
--

LOCK TABLES `import_detail` WRITE;
/*!40000 ALTER TABLE `import_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `status` enum('pending','processing','shipped','completed','cancelled','returned','failed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `employee_id` int NOT NULL,
  `payment_method` enum('cash','banking','credit_card','e_wallet') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id_2` (`account_id`),
  KEY `order-account-employee_id_idx` (`employee_id`),
  CONSTRAINT `order-account-account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order-account-employee_id` FOREIGN KEY (`employee_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_detail` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `serial` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_UNIQUE` (`serial`),
  KEY `order_id_idx` (`order_id`),
  KEY `order_detail-product-product_id_idx` (`product_id`),
  CONSTRAINT `order_detail-order-order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  CONSTRAINT `order_detail-product-product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_detail`
--

LOCK TABLES `order_detail` WRITE;
/*!40000 ALTER TABLE `order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `category_id` int DEFAULT NULL,
  `attributes` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product-category-category_id_idx` (`category_id`),
  CONSTRAINT `product-category-category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(256) DEFAULT NULL,
  `phone_number` varchar(10) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `avatar` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_number_UNIQUE` (`phone_number`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  CONSTRAINT `profile-account-id` FOREIGN KEY (`id`) REFERENCES `account` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rule`
--

DROP TABLE IF EXISTS `rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rule` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rule`
--

LOCK TABLES `rule` WRITE;
/*!40000 ALTER TABLE `rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rule_function`
--

DROP TABLE IF EXISTS `rule_function`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rule_function` (
  `rule_id` int NOT NULL,
  `function_id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`rule_id`,`function_id`),
  KEY `rule_function-function-function_id_idx` (`function_id`),
  CONSTRAINT `rule_function-function-function_id` FOREIGN KEY (`function_id`) REFERENCES `function` (`id`),
  CONSTRAINT `rule_function-rule-rule_id` FOREIGN KEY (`rule_id`) REFERENCES `rule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rule_function`
--

LOCK TABLES `rule_function` WRITE;
/*!40000 ALTER TABLE `rule_function` DISABLE KEYS */;
/*!40000 ALTER TABLE `rule_function` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `contact_name` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_UNIQUE` (`tax`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier`
--

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty`
--

DROP TABLE IF EXISTS `warranty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warranty` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `issue_date` varchar(45) DEFAULT NULL,
  `expiration_date` varchar(45) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','In Repair','Replaced','Completed','Cancelled') DEFAULT 'Pending',
  `note` text,
  PRIMARY KEY (`id`),
  KEY `warranty-order_detail-product_id_idx` (`product_id`),
  KEY `warranty-supplier-supplier_id_idx` (`supplier_id`),
  CONSTRAINT `warranty-order_detail-product_id` FOREIGN KEY (`product_id`) REFERENCES `order_detail` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warranty-supplier-supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty`
--

LOCK TABLES `warranty` WRITE;
/*!40000 ALTER TABLE `warranty` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-11  7:49:53
