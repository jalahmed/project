/*
SQLyog Enterprise - MySQL GUI v6.5
MySQL - 5.0.51b-community-nt : Database - birthday
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

create database if not exists `birthday`;

USE `birthday`;

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `favorite_gift` */

DROP TABLE IF EXISTS `favorite_gift`;

CREATE TABLE `favorite_gift` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) default NULL,
  `gift_id` int(10) default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_favorite_gift` (`user_id`),
  KEY `FK_favorite_gift2` (`gift_id`),
  CONSTRAINT `FK_favorite_gift` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_favorite_gift2` FOREIGN KEY (`gift_id`) REFERENCES `gifts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `gift_location` */

DROP TABLE IF EXISTS `gift_location`;

CREATE TABLE `gift_location` (
  `id` int(10) NOT NULL auto_increment,
  `gift_id` int(10) default NULL,
  `location_id` int(10) default NULL,
  `user_id` int(10) default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gift_location` (`gift_id`),
  KEY `FK_gift_location1` (`location_id`),
  CONSTRAINT `FK_gift_location` FOREIGN KEY (`gift_id`) REFERENCES `gifts` (`id`),
  CONSTRAINT `FK_gift_location1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `gifts` */

DROP TABLE IF EXISTS `gifts`;

CREATE TABLE `gifts` (
  `id` int(10) NOT NULL auto_increment,
  `gift_intro` text,
  `gift_value` float default NULL,
  `gift_p_day` int(50) default NULL,
  `Description` text,
  `image` text,
  `user_id` int(10) default NULL,
  `is_active` int(5) default NULL,
  `rating` int(10) default NULL,
  `created_on` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gifts` (`user_id`),
  CONSTRAINT `FK_gifts` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` int(10) NOT NULL auto_increment,
  `business_address` text,
  `business_phone` text,
  `city` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `zip` int(30) default NULL,
  `user_id` int(10) default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_locations` (`user_id`),
  CONSTRAINT `FK_locations` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `order_detail` */

DROP TABLE IF EXISTS `order_detail`;

CREATE TABLE `order_detail` (
  `id` int(10) NOT NULL auto_increment,
  `order_id` int(10) default NULL,
  `location_id` int(10) default NULL,
  `amount` float default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Table structure for table `rating` */

DROP TABLE IF EXISTS `rating`;

CREATE TABLE `rating` (
  `id` int(10) NOT NULL auto_increment,
  `gift_id` int(10) default NULL,
  `user_id` int(10) default NULL,
  `rating` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `user_claim_gift` */

DROP TABLE IF EXISTS `user_claim_gift`;

CREATE TABLE `user_claim_gift` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) default NULL,
  `gift_id` int(10) default NULL,
  `claim_date` date default NULL,
  `copen_code` text,
  PRIMARY KEY  (`id`),
  KEY `FK_user_claim_gift` (`user_id`),
  KEY `FK_user_claim_gift1` (`gift_id`),
  CONSTRAINT `FK_user_claim_gift` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_user_claim_gift1` FOREIGN KEY (`gift_id`) REFERENCES `gifts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `user_order` */

DROP TABLE IF EXISTS `user_order`;

CREATE TABLE `user_order` (
  `id` int(10) NOT NULL auto_increment,
  `address` text,
  `city` varchar(50) default NULL,
  `state` varchar(50) default NULL,
  `zip` int(20) default NULL,
  `card_name` varchar(50) default NULL,
  `cc_number` int(20) default NULL,
  `exp_date` varchar(20) default NULL,
  `cvv_code` int(20) default NULL,
  `order_date` datetime default NULL,
  `order_status` varchar(20) default NULL,
  `term_selected` varchar(30) default NULL,
  `user_id` int(10) default NULL,
  `amount` decimal(11,2) default NULL,
  `gift_id` int(11) default NULL,
  `trans_id` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Table structure for table `user_profile` */

DROP TABLE IF EXISTS `user_profile`;

CREATE TABLE `user_profile` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) default NULL,
  `gender` varchar(30) default NULL,
  `first_name` varchar(50) default NULL,
  `last_name` varchar(50) default NULL,
  `address` text,
  `city` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `image` varchar(50) default NULL,
  `business_email` varchar(50) default NULL,
  `phone` text,
  `website` text,
  PRIMARY KEY  (`id`),
  KEY `FK_user_profile` (`user_id`),
  CONSTRAINT `FK_user_profile` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(40) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) NOT NULL auto_increment,
  `email` varchar(50) default NULL,
  `password` varchar(50) default NULL,
  `zipcode` int(50) default NULL,
  `dob` varchar(50) default NULL,
  `business_name` varchar(50) default NULL,
  `user_role_id` int(10) default NULL,
  `cat_id` int(10) default NULL,
  `term_is_active` int(10) default NULL,
  `email_validate_code` varchar(255) default NULL,
  `user_is_active` int(10) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `NewIndex1` (`email`),
  KEY `FK_users_9` (`user_role_id`),
  KEY `FK_users` (`cat_id`),
  CONSTRAINT `FK_users` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_users_9` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
