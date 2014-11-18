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
-- Table structure for table `core_user`
--

DROP TABLE IF EXISTS `core_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `core_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_info_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `session_id` varchar(150) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `session_id` (`session_id`),
  KEY `user_info_id` (`user_info_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `core_user_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `core_session` (`session_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `core_user_ibfk_2` FOREIGN KEY (`user_info_id`) REFERENCES `core_user_info` (`user_info_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `core_user_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `core_role` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_user`
--

LOCK TABLES `core_user` WRITE;
/*!40000 ALTER TABLE `core_user` DISABLE KEYS */;
INSERT INTO `core_user` VALUES (1,1,'renato','ca76dc6170b1cd6c88f1a1173e37c5a83e2dab35',1,'bjh0b3s3jrlra7ofjlfb3at295',1,'1983-01-02 02:30:00','2012-06-27 13:58:27'),(3,2,'gustavo','8985465163455',1,NULL,1,'2012-04-03 12:35:00','2012-06-19 17:31:18'),(14,14,'amanda','7c4a8d09ca3762af61e59520943dc26494f8941b',1,NULL,21,'2012-06-11 23:16:41','2012-06-28 20:46:23'),(15,15,'andre','123456',1,NULL,21,'2012-06-12 10:12:43','2012-06-28 20:46:11');
/*!40000 ALTER TABLE `core_user` ENABLE KEYS */;
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
