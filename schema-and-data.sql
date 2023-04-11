-- MariaDB dump 10.19  Distrib 10.6.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: hotel
-- ------------------------------------------------------
-- Server version	10.6.12-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_startdate` date NOT NULL,
  `booking_enddate` date NOT NULL,
  `booking_status` enum('active','cancelled') NOT NULL DEFAULT 'active',
  `booking_uuid` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `fk_booking_user_id` (`user_id`),
  KEY `fk_booking_room_id` (`room_id`),
  CONSTRAINT `fk_booking_room_id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_booking_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,'2023-03-15','2023-03-22','active','ba4912f0-e045-46c7-b73e-fb844f7185a2',1,5),(2,'2023-03-12','2023-03-19','active','f6d2992a-9094-4695-b303-341ff6c3adb0',2,5),(3,'2023-03-20','2023-03-23','active','f1c4e9a3-a261-478a-b28a-9da9aa0fecd1',1,1),(8,'2023-03-23','2023-03-31','active','31ed7a16-8d13-4fea-8915-202ca11f1b8c',2,4),(9,'2023-03-01','2023-03-05','active','195cb26d-1477-4f0d-bc59-33382a1dc94e',2,6),(10,'2023-03-01','2023-03-05','active','c15f6341-912a-4dcf-8b71-6cada814dbc8',2,2),(11,'2023-03-08','2023-03-16','active','7a3ac2bf-1877-4e25-bce4-341006e2afab',1,5),(12,'2023-03-07','2023-03-17','active','abb0df1d-0ef2-476f-beb7-3daf6d8994a8',1,6),(13,'2023-04-09','2023-04-10','active','0ff5b690-886a-4684-93b7-0bd516b7caaa',1,5),(14,'2023-04-25','2023-04-29','active','70e7087c-023f-4e69-a55a-ca33dafcb2d0',1,2),(15,'2023-04-25','2023-04-29','active','2786d4d6-3bef-4025-9ea3-2b33813d2a9a',1,2),(16,'2023-04-25','2023-04-29','active','059b7da9-2f59-41a6-997a-5882c935c41e',1,2),(17,'2023-04-25','2023-04-29','active','8e709d57-635b-470b-8cbc-19125e460408',1,2),(18,'2023-04-19','2023-04-20','active','f94ca064-c6e6-4ea8-a920-11719e381486',1,1);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_pictures`
--

DROP TABLE IF EXISTS `hotel_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_pictures` (
  `hp_id` int(11) NOT NULL AUTO_INCREMENT,
  `hp_filename` varchar(100) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  PRIMARY KEY (`hp_id`),
  UNIQUE KEY `hp_filename` (`hp_filename`),
  KEY `fk_hp_hotel_id` (`hotel_id`),
  CONSTRAINT `fk_hotel_id` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_pictures`
--

LOCK TABLES `hotel_pictures` WRITE;
/*!40000 ALTER TABLE `hotel_pictures` DISABLE KEYS */;
INSERT INTO `hotel_pictures` VALUES (5,'b868dd4a-3f38-45dc-8a4a-bc2cfcb01b84.jpg',3),(6,'8f590381-65e5-4935-8058-cc03dc704d16.jpg',1),(7,'b839f64d-8f25-4558-83c9-0b5e95bc844e.jpg',1),(8,'9a93bfed-15df-4ff1-9956-a7f396c6c77c.jpg',1);
/*!40000 ALTER TABLE `hotel_pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotels` (
  `hotel_id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_name` varchar(50) NOT NULL,
  `hotel_address` varchar(255) NOT NULL,
  `hotel_rating` int(11) NOT NULL,
  PRIMARY KEY (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotels`
--

LOCK TABLES `hotels` WRITE;
/*!40000 ALTER TABLE `hotels` DISABLE KEYS */;
INSERT INTO `hotels` VALUES (1,'Awesome Paws','Rustenburg',5),(3,'Totally Pawsome','Johannesburg',4);
/*!40000 ALTER TABLE `hotels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_pictures`
--

DROP TABLE IF EXISTS `room_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_pictures` (
  `rp_id` int(11) NOT NULL AUTO_INCREMENT,
  `rp_filename` varchar(100) NOT NULL,
  `room_id` int(11) NOT NULL,
  PRIMARY KEY (`rp_id`),
  UNIQUE KEY `rp_filename` (`rp_filename`),
  KEY `fk_rp_room_id` (`room_id`),
  CONSTRAINT `fk_rp_room_id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_pictures`
--

LOCK TABLES `room_pictures` WRITE;
/*!40000 ALTER TABLE `room_pictures` DISABLE KEYS */;
INSERT INTO `room_pictures` VALUES (1,'c0554287-6be4-4521-b87b-40fc58832f6b.jpg',4);
/*!40000 ALTER TABLE `room_pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_label` varchar(50) NOT NULL,
  `room_price` decimal(10,2) DEFAULT NULL,
  `hotel_id` int(11) NOT NULL,
  PRIMARY KEY (`room_id`),
  KEY `fk_room_hotel_id` (`hotel_id`),
  CONSTRAINT `fk_room_hotel_id` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'Tennis Ball',100.00,3),(2,'Squeaky Toy',90.00,3),(4,'Pulling Tyre',80.00,3),(5,'Awesome 1',200.00,1),(6,'Awesome 2',250.00,1);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_firstname` varchar(50) NOT NULL,
  `staff_lastname` varchar(50) NOT NULL,
  `staff_email` varchar(50) NOT NULL,
  `staff_password` varchar(100) NOT NULL,
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `staff_email` (`staff_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Ozzy','Hairndy','ozzy@bathead.com','$2y$10$yWft3TMxwZcmc3VF39dYl.8afepH48KFNcMV07GMoJTQCMId3Pr3y');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_title` varchar(10) NOT NULL,
  `user_firstname` varchar(50) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mr.','Ozzy','Hairndy','ozzy@bathead.com','+27721236547','99 Sleepy Road, Napville, 2345','$2y$10$8GJc7nP.k8.46kk8g.yo9.Ub.G4Lju6b2TSb3c07ZTvXJmWSjqzVm'),(2,'Ms.','Stevie','Lynn','stevie@treaties.com','+27769871234','99 Treat Street, Nomville, 1234','$2y$10$jlS48SupA6f7zGtutwlvduFKl1fiuQBtagR2DaD/B9HVcxTjagVj2');
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

-- Dump completed on 2023-04-11 12:47:59
