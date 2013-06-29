--
-- Database: `who`
--

-- --------------------------------------------------------

--
-- Table structure for table `who_blacklist`
--

CREATE TABLE IF NOT EXISTS `who_blacklist` (
  `blacklist_id` int(6) NOT NULL auto_increment,
  `blacklist_MAC` varchar(17) collate cp1251_bulgarian_ci NOT NULL,
  `blacklist_comment` varchar(100) collate cp1251_bulgarian_ci NOT NULL,
  PRIMARY KEY  (`blacklist_id`),
  UNIQUE KEY `blacklist_MAC` (`blacklist_MAC`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `who_devices`
--

CREATE TABLE IF NOT EXISTS `who_devices` (
  `device_id` int(10) NOT NULL auto_increment,
  `device_MAC` varchar(17) collate cp1251_bulgarian_ci NOT NULL,
  `device_uid` int(6) NOT NULL,
  `device_comment` varchar(100) COLLATE cp1251_bulgarian_ci NOT NULL,
  PRIMARY KEY  (`device_id`),
  UNIQUE KEY `device_MAC` (`device_MAC`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `who_online`
--

CREATE TABLE IF NOT EXISTS `who_online` (
  `online_id` int(6) NOT NULL auto_increment,
  `online_MAC` varchar(17) collate cp1251_bulgarian_ci NOT NULL,
  `online_IP` varchar(30) collate cp1251_bulgarian_ci NOT NULL,
  PRIMARY KEY  (`online_id`),
  UNIQUE KEY `online_MAC` (`online_MAC`,`online_IP`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `who_users`
--

CREATE TABLE IF NOT EXISTS `who_users` (
  `user_id` int(6) NOT NULL auto_increment,
  `user_name1` varchar(40) collate cp1251_bulgarian_ci NOT NULL,
  `user_name2` varchar(40) collate cp1251_bulgarian_ci NOT NULL,
  `user_twitter` varchar(100) collate cp1251_bulgarian_ci NOT NULL,
  `user_facebook` varchar(100) collate cp1251_bulgarian_ci NOT NULL,
  `user_tel` varchar(16) COLLATE cp1251_bulgarian_ci NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci AUTO_INCREMENT=1 ;

