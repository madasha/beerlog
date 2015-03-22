-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: wp
-- ------------------------------------------------------
-- Server version	5.1.73

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
-- Table structure for table `wpp_beerlog_beers`
--

DROP TABLE IF EXISTS `wpp_beerlog_beers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpp_beerlog_beers` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brewery_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `style_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` text,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `brewery_id` (`brewery_id`),
  KEY `style_id` (`style_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpp_beerlog_beers`
--

LOCK TABLES `wpp_beerlog_beers` WRITE;
/*!40000 ALTER TABLE `wpp_beerlog_beers` DISABLE KEYS */;
INSERT INTO `wpp_beerlog_beers` VALUES (1,'Bernard 10',1,1,'Bernard pivo 10','2015-03-22 09:51:32');
/*!40000 ALTER TABLE `wpp_beerlog_beers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wpp_beerlog_breweries`
--

DROP TABLE IF EXISTS `wpp_beerlog_breweries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpp_beerlog_breweries` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner_brewery_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `description` text,
  `address_country_code` char(2) DEFAULT NULL,
  `address_state` char(2) DEFAULT NULL,
  `address_street` tinytext,
  `address_city` tinytext,
  `address_zip` varchar(20) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `owner_brewery_id` (`owner_brewery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpp_beerlog_breweries`
--

LOCK TABLES `wpp_beerlog_breweries` WRITE;
/*!40000 ALTER TABLE `wpp_beerlog_breweries` DISABLE KEYS */;
INSERT INTO `wpp_beerlog_breweries` VALUES (1,'Bernard',0,'Rodynni pivovar Bernard','CZ',NULL,NULL,'Humpolice',NULL,'2015-03-22 09:49:45'),(2,'Glarus',0,'Glarus Craft Beers','BG',NULL,NULL,'Varna',NULL,'2015-03-22 09:50:21');
/*!40000 ALTER TABLE `wpp_beerlog_breweries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-22 15:14:47
