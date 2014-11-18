CREATE DATABASE  IF NOT EXISTS `erapido` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `erapido`;
-- MySQL dump 10.13  Distrib 5.5.16, for osx10.5 (i386)
--
-- Host: 192.168.0.104    Database: erapido
-- ------------------------------------------------------
-- Server version	5.1.49-3

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
-- Table structure for table `core_rule`
--

DROP TABLE IF EXISTS `core_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `core_rule` (
  `rule_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `privilege_id` int(11) DEFAULT NULL,
  `allow` tinyint(3) DEFAULT NULL,
  `resource_name` varchar(150) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`rule_id`),
  KEY `role_id` (`role_id`),
  KEY `privilege_id` (`privilege_id`),
  CONSTRAINT `core_rule_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `core_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `core_rule_ibfk_2` FOREIGN KEY (`privilege_id`) REFERENCES `core_privilege` (`privilege_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_rule`
--

LOCK TABLES `core_rule` WRITE;
/*!40000 ALTER TABLE `core_rule` DISABLE KEYS */;
INSERT INTO `core_rule` VALUES (1,1,1,1,'erapido:conteudo',NULL,NULL),(5,1,2,1,'erapido:foto',NULL,NULL),(6,1,3,1,'erapido:video',NULL,NULL),(7,1,4,1,'erapido:conteudo',NULL,NULL),(8,1,5,1,'erapido:foto',NULL,NULL),(9,1,6,1,'erapido:video',NULL,NULL),(10,1,7,1,'erapido:conteudo',NULL,NULL),(11,1,8,1,'erapido:foto',NULL,NULL),(12,1,9,1,'erapido:conteudo',NULL,NULL),(13,1,10,1,'erapido:foto',NULL,NULL),(14,1,11,1,'erapido:video',NULL,NULL),(15,1,12,1,'erapido:index',NULL,NULL),(16,1,17,1,'erapido:conteudo',NULL,NULL),(17,1,18,1,'erapido:user',NULL,NULL),(18,1,19,1,'erapido:user',NULL,NULL),(19,1,20,1,'erapido:user',NULL,NULL),(20,1,21,1,'erapido:user',NULL,NULL),(21,1,22,1,'erapido:nivel',NULL,NULL),(22,1,23,1,'erapido:user',NULL,NULL),(23,1,30,1,'erapido:nivel',NULL,NULL),(24,1,24,1,'erapido:nivel',NULL,NULL),(25,1,25,1,'erapido:nivel',NULL,NULL),(26,1,26,1,'erapido:nivel',NULL,NULL),(27,1,27,1,'erapido:nivel',NULL,NULL),(28,1,28,1,'erapido:video',NULL,NULL),(29,1,29,1,'erapido:foto',NULL,NULL),(30,1,30,1,'erapido:nivel',NULL,NULL),(31,1,31,1,'erapido:user',NULL,NULL);
/*!40000 ALTER TABLE `core_rule` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-07-02 17:27:47
