-- 
-- Structure for table `tbl_social`
-- 

DROP TABLE IF EXISTS `tbl_social`;
CREATE TABLE IF NOT EXISTS `tbl_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yiiuser` int(11) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `provideruser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `tbl_user_sessions`
-- 

DROP TABLE IF EXISTS `tbl_user_sessions`;
CREATE TABLE IF NOT EXISTS `tbl_user_sessions` (
  `user_id` int(11) NOT NULL COMMENT 'refer to your user id on your application',
  `hybridauth_session` text NOT NULL COMMENT 'will contain the hybridauth session data',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
