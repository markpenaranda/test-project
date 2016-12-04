-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.10-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table exam.i_test_user
CREATE TABLE IF NOT EXISTS `i_test_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `email` varchar(125) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table exam.i_test_user: ~9 rows (approximately)
/*!40000 ALTER TABLE `i_test_user` DISABLE KEYS */;
INSERT INTO `i_test_user` (`user_id`, `name`, `phone_number`, `email`, `is_deleted`, `date_created`, `date_updated`) VALUES
	(1, 'Wallace', '+563123121', 'wallace@yahoo.com', 0, '2016-09-24 12:30:45', '2016-10-10 09:51:40'),
	(2, 'Huo', '+97123123123', 'huo@yahoo.com', 0, '2016-09-24 12:30:45', '2016-10-10 09:51:40'),
	(3, 'Fereeq', '+9711231111', 'Fereeq@yahoo.com', 0, '2016-09-24 12:30:45', '2016-10-10 09:51:40'),
	(4, 'Jeremiah Facundo', '+6392323232323', 'jeremiah@gmail.com', 0, '2016-09-24 14:22:47', '2016-10-10 09:51:40'),
	(5, 'Feraq Jihad', '+6512322221', 'Huhuhakusho@yahoo.com', 0, '2016-09-24 14:49:29', '2016-10-10 09:51:40'),
	(6, 'Nishodo', '+97112312112', 'Nishodo@gmail.com', 0, '2016-09-24 14:50:35', '2016-10-10 09:51:40'),
	(7, 'Kerala Feer', '+97155676211', 'Kerala@gmail.com', 0, '2016-09-24 14:53:52', '2016-10-10 09:51:40'),
	(8, 'Jay1', '+1231232132131', 'sample@yahoo.com', 1, '2016-10-10 08:54:21', '2016-10-10 09:51:40'),
	(9, 'Wallaceas1', '+9715312321', 'jay@yahoo.com', 1, '2016-10-10 08:56:26', '2016-10-10 09:51:40');
/*!40000 ALTER TABLE `i_test_user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
