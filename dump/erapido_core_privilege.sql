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
-- Table structure for table `core_privilege`
--

DROP TABLE IF EXISTS `core_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `core_privilege` (
  `privilege_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `description` tinytext,
  `module_name` varchar(150) DEFAULT NULL,
  `controller_name` varchar(150) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_privilege`
--

LOCK TABLES `core_privilege` WRITE;
/*!40000 ALTER TABLE `core_privilege` DISABLE KEYS */;
INSERT INTO `core_privilege` VALUES (1,'adicionar','Adiconar conteúdo','erapido','conteudo','2012-04-03 12:35:00','2012-04-03 12:35:00'),(2,'adicionar','Adiconar fotos','erapido','foto','2012-04-03 12:35:00','2012-04-03 12:35:00'),(3,'adicionar','Adiconar vídeos','erapido','video','2012-04-03 12:35:00','2012-04-03 12:35:00'),(4,'editar','Editar conteúdo','erapido','conteudo','2012-04-03 12:35:00','2012-04-03 12:35:00'),(5,'editar','Editar foto','erapido','foto','2012-04-03 12:35:00','2012-04-03 12:35:00'),(6,'editar','Editar vídeo','erapido','video','2012-04-03 12:35:00','2012-04-03 12:35:00'),(7,'apagar','Apagar conteúdo','erapido','conteudo','2012-04-03 12:35:00','2012-04-03 12:35:00'),(8,'apagar','Apagar foto','erapido','foto','2012-04-03 12:35:00','2012-04-03 12:35:00'),(9,'listar','Listar conteúdos','erapido','conteudo','2012-04-03 12:35:00','2012-04-03 12:35:00'),(10,'listar','Listar fotos','erapido','foto','2012-04-03 12:35:00','2012-04-03 12:35:00'),(11,'listar','Listar vídeos','erapido','video','2012-04-03 12:35:00','2012-04-03 12:35:00'),(12,'index','Página principal ','erapido','index','2012-04-03 12:35:00','2012-04-03 12:35:00'),(17,'index','Gerenciar conteúdos','erapido','conteudo','2012-04-03 12:35:00','2012-04-03 12:35:00'),(18,'index','Gerenciar usuários','erapido','user','2012-04-03 12:35:00','2012-04-03 12:35:00'),(19,'adicionar','Adicionar usuários','erapido','user','2012-04-03 12:35:00','2012-04-03 12:35:00'),(20,'editar','Editar usuário','erapido','user','2012-04-03 12:35:00','2012-04-03 12:35:00'),(21,'apagar','Apagar usuário','erapido','user','2012-04-03 12:35:00','2012-04-03 12:35:00'),(22,'adicionar','Adicionar nível','erapido','nivel','2012-04-03 12:35:00','2012-04-03 12:35:00'),(23,'listar','Listar usuários','erapido','user','2012-04-03 12:35:00','2012-04-03 12:35:00'),(24,'listar','Listar níveis','erapido','nivel','2012-04-03 12:35:00','2012-04-03 12:35:00'),(25,'editar','Editar nível','erapido','nivel','2012-04-03 12:35:00','2012-04-03 12:35:00'),(26,'apagar','Apagar nível','erapido','nivel','2012-04-03 12:35:00','2012-04-03 12:35:00'),(27,'atribuir','Atribuir nível aos usuários','erapido','nivel','2012-04-03 12:35:00','2012-04-03 12:35:00'),(28,'index','Gerenciar vídeos','erapido','video','2012-04-03 12:35:00','2012-04-03 12:35:00'),(29,'index','Gerenciar fotos','erapido','foto','2012-04-03 12:35:00','2012-04-03 12:35:00'),(30,'index','Gerenciar níveis','erapido','nivel',NULL,NULL),(31,'alterar-senha','Alterar senha','erapido','user','2012-04-03 12:35:00','2012-04-03 12:35:00');
/*!40000 ALTER TABLE `core_privilege` ENABLE KEYS */;
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
