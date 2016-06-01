CREATE DATABASE  IF NOT EXISTS `beerstoredb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `beerstoredb`;
-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (i386)
--
-- Host: 127.0.0.1    Database: beerstoredb
-- ------------------------------------------------------
-- Server version	5.6.20

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
-- Table structure for table `beer_families`
--

DROP TABLE IF EXISTS `beer_families`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `beer_families` (
  `family_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `family_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`family_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beer_families`
--

LOCK TABLES `beer_families` WRITE;
/*!40000 ALTER TABLE `beer_families` DISABLE KEYS */;
INSERT INTO `beer_families` VALUES (1,'Lager','0000-00-00 00:00:00'),(2,'Ale','0000-00-00 00:00:00'),(3,'IPA','0000-00-00 00:00:00'),(4,'Weizen','0000-00-00 00:00:00'),(5,'Porter','0000-00-00 00:00:00'),(6,'Stout','0000-00-00 00:00:00'),(7,'Pilsner','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `beer_families` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beers`
--

DROP TABLE IF EXISTS `beers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `beers` (
  `beer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `family_id` int(10) unsigned NOT NULL,
  `beer_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `cost` decimal(5,2) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stock` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`beer_id`),
  KEY `family_id` (`family_id`),
  CONSTRAINT `beers_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `beer_families` (`family_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beers`
--

LOCK TABLES `beers` WRITE;
/*!40000 ALTER TABLE `beers` DISABLE KEYS */;
INSERT INTO `beers` VALUES (4,1,'Budweiser',2.99,'2014-11-19 06:34:58','2015-01-11 19:51:34',98,1),(5,2,'andrew\'s ale',6.00,'2014-11-19 06:34:58','2015-01-11 19:53:32',10,1),(6,4,'Blue Moon',4.99,'2014-11-19 06:34:58','2015-01-11 19:51:34',17,1),(7,5,'bob\'s beer',4.99,'2015-01-11 01:40:02','2015-01-11 19:53:14',21,0),(8,1,'My new beer',10.00,'2015-01-11 01:57:50','2015-01-11 07:01:46',22,0);
/*!40000 ALTER TABLE `beers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `order_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `beer_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `last_mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_item_id`),
  KEY `beer_id` (`beer_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`beer_id`) REFERENCES `beers` (`beer_id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (87,5,49,2,'2015-01-11 14:04:57','2015-01-11 19:04:57'),(88,4,50,1,'2015-01-11 14:08:37','2015-01-11 19:08:37'),(89,4,51,1,'2015-01-11 14:51:34','2015-01-11 19:51:34'),(90,5,51,2,'2015-01-11 14:51:34','2015-01-11 19:51:34'),(91,6,51,3,'2015-01-11 14:51:34','2015-01-11 19:51:34'),(92,7,51,4,'2015-01-11 14:51:34','2015-01-11 19:51:34');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_ref`
--

DROP TABLE IF EXISTS `order_status_ref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_ref` (
  `order_status_id` int(10) unsigned NOT NULL,
  `order_status` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_ref`
--

LOCK TABLES `order_status_ref` WRITE;
/*!40000 ALTER TABLE `order_status_ref` DISABLE KEYS */;
INSERT INTO `order_status_ref` VALUES (1,'queued','2015-01-11 14:12:30'),(2,'processed','2015-01-11 14:12:30');
/*!40000 ALTER TABLE `order_status_ref` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `order_date` datetime NOT NULL,
  `order_subtotal` decimal(6,2) NOT NULL,
  `order_tax` decimal(6,2) NOT NULL,
  `order_total` decimal(6,2) NOT NULL,
  `last_mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_status_id` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) on delete cascade
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

#LOCK TABLES `orders` WRITE;
#/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
#INSERT INTO `orders` VALUES (23,2,'2015-01-04 16:02:25',14.95,1.01,15.96,'2015-01-11 19:50:53',2),(29,2,'2015-01-04 16:56:18',0.00,0.00,0.00,'2015-01-11 19:50:32',2),(49,2,'2015-01-11 14:04:57',12.00,0.81,12.81,'2015-01-11 19:50:59',2),(50,2,'2015-01-11 14:08:37',2.99,0.20,3.19,'2015-01-11 19:28:43',2),(51,3,'2015-01-11 14:51:34',49.92,3.37,53.29,'2015-01-11 19:51:34',1);
#/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
#UNLOCK TABLES;
--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `pass` char(60) NOT NULL,
  `join_date` datetime NOT NULL,
  `last_mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `address` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zipcode` char(5) DEFAULT NULL,
  `firstname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

#LOCK TABLES `users` WRITE;
#/*!40000 ALTER TABLE `users` DISABLE KEYS */;
#INSERT INTO `users` VALUES (2,'andrew','$1$FB2mKVPF$7brMsCy.KVJG04c3Bxn8/.','2014-11-19 01:35:18','2015-01-11 18:09:17','3238 Brampton St','Dublin','Oh','43017','Andrew','Harris',1),(3,'bob','$1$PgLt/CD6$8rrDsghKj.1QYfO6WoFOo0','2015-01-10 10:34:25','2015-01-11 19:51:34','123 test st','dayton','oh','43343','bob','barker',0);
#/*!40000 ALTER TABLE `users` ENABLE KEYS */;
#UNLOCK TABLES;
#/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-13 23:22:52
