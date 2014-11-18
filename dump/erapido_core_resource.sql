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
-- Table structure for table `core_resource`
--

DROP TABLE IF EXISTS `core_resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `core_resource` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` tinytext,
  `module_name` varchar(150) DEFAULT NULL,
  `controller_name` varchar(255) DEFAULT NULL,
  `nav` varchar(255) DEFAULT NULL,
  `order` int(3) unsigned zerofill NOT NULL,
  `is_visible` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`resource_id`),
  UNIQUE KEY `RESOURCE_UNIQUE` (`module_name`,`controller_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_resource`
--

LOCK TABLES `core_resource` WRITE;
/*!40000 ALTER TABLE `core_resource` DISABLE KEYS */;
INSERT INTO `core_resource` VALUES (1,'Conteúdos','erapido','conteudo','module',001,1,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(2,'Fotos','erapido','foto','module',002,1,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(3,'Vídeos','erapido','video','module',003,1,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(4,'Newsletter','erapido','newsletter','module',004,1,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(5,'Pagina Principal','erapido','index',NULL,000,0,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(6,'Layout','erapido','layout','module',005,1,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(11,'Usuários','erapido','user','admin',001,1,'2012-04-03 12:35:00','2012-04-03 12:35:00'),(12,'Níveis','erapido','nivel','admin',002,1,'2012-04-03 12:35:00','2012-04-03 12:35:00');
/*!40000 ALTER TABLE `core_resource` ENABLE KEYS */;
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
