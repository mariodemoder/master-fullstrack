/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.7.36 : Database - api_rest_laravel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`api_rest_laravel` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `api_rest_laravel`;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

LOCK TABLES `categories` WRITE;

insert  into `categories`(`id`,`name`,`created_at`,`updated_at`) values (1,'ordenadores','2022-11-29 10:25:15',NULL),(2,'moviles','2022-11-29 10:25:21',NULL),(3,'tablets','2022-11-29 10:25:30',NULL);

UNLOCK TABLES;

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `category_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_user` (`user_id`),
  KEY `fk_post_category` (`category_id`),
  CONSTRAINT `fk_post_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `posts` */

LOCK TABLES `posts` WRITE;

insert  into `posts`(`id`,`user_id`,`category_id`,`title`,`content`,`image`,`created_at`,`updated_at`) values (1,1,2,'SANMSUNGS G8','A AILSJDÑL FIAJSDÑL FJALÑSDJFLÑASJDFLÑASJIDLSDLKJJAKSDJFHSDAK ASDF AS',NULL,'2022-11-29 10:27:11',NULL),(2,1,1,'MS1 DOS','ALSDJKFÑAS AÑSIDJF ÑASIDF',NULL,'2022-11-29 10:27:22',NULL),(3,1,3,'SONY','SONY JASLDJ LADJ ÑLAIJDLFÑAIJDFLÑAJSDLFÑSF',NULL,'2022-11-29 12:59:59',NULL),(4,1,2,'MOTOROLA 22','LSLKDSJF SLADKF JALSDKFJ ALSDKFJ',NULL,'2022-11-29 13:01:35',NULL);

UNLOCK TABLES;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `descripcion` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_tocken` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`surname`,`role`,`email`,`password`,`descripcion`,`image`,`created_at`,`updated_at`,`remember_tocken`) values (1,'admin','admin','ROLE_ADMIN','admin@gmail.com','admin','asdfasd asdf asdf',NULL,'2022-11-29 10:24:50',NULL,NULL),(6,'Juan','Lopez','ROLE_USER','juan@gmail.com','ed08c290d7e22f7bb324b15cbadce35b0b348564fd2d5f95752388d86d71bcca',NULL,NULL,'2022-12-12 19:00:17','2022-12-12 19:00:17',NULL),(7,'Mario','Muñoz','ROLE_USER','mario@gmail.com','4f53b1ed5abd658ac90831517e2b18deb6f7d89d083635f71113b725bc8368c4',NULL,NULL,'2022-12-12 19:56:35','2022-12-12 19:56:35',NULL),(8,'Pablo a','Adorno','ROLE_USER','pablo@gmail.com','26079e41910bcde04be636fbeecc9045379882b5ad3fe7f70b762436c6d98055',NULL,NULL,'2022-12-13 13:46:37','2022-12-14 13:40:56',NULL),(9,'LuchoM','Adorno','ROLE_USER','lucho@gmail.com','07b0d197cf2fd1901e99851da25ee1742b4d1ffe477ad3991e48d3f6773cdb46',NULL,NULL,'2022-12-14 13:50:34','2022-12-14 13:52:34',NULL),(10,'Luchos','Muñoz','ROLE_USER','luchom@gmail.com','3d3ab4d2a4fe9190733b3585330918881bee8de42b310edde5f106ac1f5500f6',NULL,NULL,'2022-12-14 14:51:46','2022-12-14 14:51:46',NULL),(11,'LoreS','Adorno','ROLE_USER','lore@gmail.com','b6598e838f350a97cb734eca208ce0cdc602dd60afbf65a3b8b65195cbd1a7fe',NULL,NULL,'2022-12-17 01:58:44','2022-12-17 02:00:37',NULL);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
