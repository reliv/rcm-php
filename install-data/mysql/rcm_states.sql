-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: wespresslocal
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

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
-- Table structure for table `rcm_states`
--

DROP TABLE IF EXISTS `rcm_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rcm_states` (
  `country` varchar(3) NOT NULL,
  `state` varchar(45) NOT NULL,
  PRIMARY KEY (`country`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rcm_states`
--

LOCK TABLES `rcm_states` WRITE;
/*!40000 ALTER TABLE `rcm_states` DISABLE KEYS */;
INSERT INTO `rcm_states` VALUES ('CAN','AB'),('CAN','BC'),('CAN','BL'),('CAN','MB'),('CAN','NB'),('CAN','NS'),('CAN','NT'),('CAN','NU'),('CAN','ON'),('CAN','PE'),('CAN','QC'),('CAN','SK'),('CAN','YT'),('USA','AK'),('USA','AL'),('USA','AR'),('USA','AZ'),('USA','CA'),('USA','CO'),('USA','CT'),('USA','DC'),('USA','DE'),('USA','FL'),('USA','GA'),('USA','HI'),('USA','IA'),('USA','ID'),('USA','IL'),('USA','IN'),('USA','KS'),('USA','KY'),('USA','LA'),('USA','MA'),('USA','MD'),('USA','ME'),('USA','MI'),('USA','MN'),('USA','MO'),('USA','MS'),('USA','MT'),('USA','NC'),('USA','ND'),('USA','NE'),('USA','NH'),('USA','NJ'),('USA','NM'),('USA','NV'),('USA','NY'),('USA','OH'),('USA','OK'),('USA','OR'),('USA','PA'),('USA','RI'),('USA','SC'),('USA','SD'),('USA','TN'),('USA','TX'),('USA','UT'),('USA','VA'),('USA','VT'),('USA','WA'),('USA','WI'),('USA','WV'),('USA','WY');
/*!40000 ALTER TABLE `rcm_states` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-05 11:58:25
