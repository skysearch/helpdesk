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
-- Table structure for table `core_session`
--

DROP TABLE IF EXISTS `core_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `core_session` (
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `data` text,
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_session`
--

LOCK TABLES `core_session` WRITE;
/*!40000 ALTER TABLE `core_session` DISABLE KEYS */;
INSERT INTO `core_session` VALUES ('83gaqtqu9i8qs6lp8hdpprctv2','Zend_Auth|a:1:{s:7:\"storage\";a:3:{s:4:\"user\";a:8:{s:7:\"user_id\";s:1:\"1\";s:12:\"user_info_id\";s:1:\"1\";s:8:\"username\";s:6:\"renato\";s:6:\"status\";s:1:\"1\";s:10:\"session_id\";s:26:\"83gaqtqu9i8qs6lp8hdpprctv2\";s:7:\"role_id\";s:1:\"1\";s:7:\"created\";s:19:\"1983-01-02 02:30:00\";s:8:\"modified\";s:19:\"2012-06-20 21:08:15\";}s:4:\"info\";a:5:{s:12:\"user_info_id\";s:1:\"1\";s:4:\"name\";s:21:\"Renato David Oliveira\";s:5:\"email\";s:17:\"renato@db9.com.br\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}s:4:\"role\";a:6:{s:7:\"role_id\";s:1:\"1\";s:4:\"name\";s:13:\"administrador\";s:11:\"description\";s:15:\"Administradores\";s:6:\"locked\";s:1:\"1\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}}}',1340238698,864000),('bjh0b3s3jrlra7ofjlfb3at295','Zend_Auth|a:1:{s:7:\"storage\";a:3:{s:4:\"user\";a:8:{s:7:\"user_id\";s:1:\"1\";s:12:\"user_info_id\";s:1:\"1\";s:8:\"username\";s:6:\"renato\";s:6:\"status\";s:1:\"1\";s:10:\"session_id\";s:26:\"bjh0b3s3jrlra7ofjlfb3at295\";s:7:\"role_id\";s:1:\"1\";s:7:\"created\";s:19:\"1983-01-02 02:30:00\";s:8:\"modified\";s:19:\"2012-06-20 21:13:41\";}s:4:\"info\";a:5:{s:12:\"user_info_id\";s:1:\"1\";s:4:\"name\";s:21:\"Renato David Oliveira\";s:5:\"email\";s:17:\"renato@db9.com.br\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}s:4:\"role\";a:6:{s:7:\"role_id\";s:1:\"1\";s:4:\"name\";s:13:\"administrador\";s:11:\"description\";s:15:\"Administradores\";s:6:\"locked\";s:1:\"1\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}}}',1340976833,864000),('mv78i0q2irjdunq00ujvr9vnj6','Zend_Auth|a:1:{s:7:\"storage\";a:3:{s:4:\"user\";a:8:{s:7:\"user_id\";s:1:\"1\";s:12:\"user_info_id\";s:1:\"1\";s:8:\"username\";s:6:\"renato\";s:6:\"status\";s:1:\"1\";s:10:\"session_id\";s:26:\"mv78i0q2irjdunq00ujvr9vnj6\";s:7:\"role_id\";s:1:\"1\";s:7:\"created\";s:19:\"1983-01-02 02:30:00\";s:8:\"modified\";s:19:\"2012-06-11 22:22:46\";}s:4:\"info\";a:5:{s:12:\"user_info_id\";s:1:\"1\";s:4:\"name\";s:21:\"Renato David Oliveira\";s:5:\"email\";s:17:\"renato@db9.com.br\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}s:4:\"role\";a:6:{s:7:\"role_id\";s:1:\"1\";s:4:\"name\";s:13:\"administrador\";s:11:\"description\";s:15:\"Administradores\";s:6:\"locked\";s:1:\"1\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}}}',1339467443,864000),('vf20qu5ka35uoka8fgsqkdsc55','Zend_Auth|a:1:{s:7:\"storage\";a:3:{s:4:\"user\";a:8:{s:7:\"user_id\";s:1:\"1\";s:12:\"user_info_id\";s:1:\"1\";s:8:\"username\";s:6:\"renato\";s:6:\"status\";s:1:\"1\";s:10:\"session_id\";s:26:\"vf20qu5ka35uoka8fgsqkdsc55\";s:7:\"role_id\";s:1:\"1\";s:7:\"created\";s:19:\"1983-01-02 02:30:00\";s:8:\"modified\";s:19:\"2012-06-20 20:01:23\";}s:4:\"info\";a:5:{s:12:\"user_info_id\";s:1:\"1\";s:4:\"name\";s:21:\"Renato David Oliveira\";s:5:\"email\";s:17:\"renato@db9.com.br\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}s:4:\"role\";a:6:{s:7:\"role_id\";s:1:\"1\";s:4:\"name\";s:13:\"administrador\";s:11:\"description\";s:15:\"Administradores\";s:6:\"locked\";s:1:\"1\";s:7:\"created\";s:19:\"2012-04-03 12:35:00\";s:8:\"modified\";s:19:\"2012-04-03 12:35:00\";}}}',1340370203,864000);
/*!40000 ALTER TABLE `core_session` ENABLE KEYS */;
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
