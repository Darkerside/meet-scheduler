-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.1.72-community - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for crud
DROP DATABASE IF EXISTS `crud`;
CREATE DATABASE IF NOT EXISTS `crud` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crud`;

-- Dumping structure for table crud.notes
DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `note_title` varchar(50) DEFAULT NULL,
  `note_body` text,
  `created_at` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- Dumping data for table crud.notes: 4 rows
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
REPLACE INTO `notes` (`id`, `user_id`, `note_title`, `note_body`, `created_at`) VALUES
	(44, 1, 'uwer', 'asdw', '1644820266'),
	(36, 1, '1231', '123123223123', '1644808228'),
	(34, 1, '6452', '54634', '1644807756'),
	(33, 1, '123', '123123', '1644807748');
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;

-- Dumping structure for table crud.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table crud.roles: 2 rows
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
REPLACE INTO `roles` (`id`, `role_name`) VALUES
	(1, 'Admin'),
	(2, 'user');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table crud.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- Dumping data for table crud.users: 4 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `username`, `password`, `role_id`, `is_active`, `created_at`) VALUES
	(1, 'admin', '$2y$10$bCegF9gfmhNxJjIxLCM21OYxXO9aW978So0YptY.fiJJW.14a3q9e', 1, 1, '1644562176'),
	(2, 'test12345', '$2y$10$bCegF9gfmhNxJjIxLCM21OYxXO9aW978So0YptY.fiJJW.14a3q9e', 2, 1, '1644562996'),
	(12, '456', '$2y$10$c.tBdpeEwqwxzrBugRK2/u7u8xasOKhyN0mq9iRzKDjrbEPhNIFCC', 2, 0, '1644567309'),
	(15, '321', '$2y$10$StXITnkPrOggnzRU7nHu5elXQBFYsAf9tng4v9Oxb7njwiS6aOx7e', 2, 1, '1644826767');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
