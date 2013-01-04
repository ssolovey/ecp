# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 198.57.137.46 (MySQL 5.1.66-cll)
# Database: magento
# Generation Time: 2012-12-26 10:05:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table reman_warranties
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reman_warranties`;

CREATE TABLE `reman_warranties` (
  `warranty_id` int(2) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Warranty ID',
  `warranty` varchar(35) DEFAULT NULL COMMENT 'Warranty text',
  PRIMARY KEY (`warranty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Warranty table';

LOCK TABLES `reman_warranties` WRITE;
/*!40000 ALTER TABLE `reman_warranties` DISABLE KEYS */;

INSERT INTO `reman_warranties` (`warranty_id`, `warranty`)
VALUES
	(13,'6 Month/6,000 Mile Warranty'),
	(15,'12 Month/12,000 Mile Warranty'),
	(16,'12 Month/Unlimited Miles Warranty'),
	(17,'18 Month/18,000 Mile Warranty'),
	(19,'24 Month/Unlimited Miles Warranty'),
	(23,'36 Month/100,000 Mile Warranty');

/*!40000 ALTER TABLE `reman_warranties` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
