-- Create syntax for TABLE 'accounts'
CREATE TABLE `accounts` (
  `accounts_pk` int(11) NOT NULL AUTO_INCREMENT,
  `pgid` varchar(45) DEFAULT NULL,
  `token` varchar(45) DEFAULT NULL,
  `minor` tinyint(1) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `number_friends` varchar(45) DEFAULT NULL,
  `intro` varchar(45) DEFAULT NULL,
  `play_mingle` int(20) DEFAULT NULL,
  PRIMARY KEY (`accounts_pk`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'beer_pong'
CREATE TABLE `beer_pong` (
  `beer_pong_pk` int(11) NOT NULL AUTO_INCREMENT,
  `user_a` int(11) NOT NULL,
  `user_b` int(11) NOT NULL DEFAULT '0',
  `state` int(10) DEFAULT NULL,
  PRIMARY KEY (`beer_pong_pk`,`user_a`,`user_b`),
  KEY `fk_beer_pong_accounts1_idx` (`user_a`),
  KEY `fk_beer_pong_accounts2_idx` (`user_b`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'bets'
CREATE TABLE `bets` (
  `bets_pk` int(45) NOT NULL AUTO_INCREMENT,
  `user_fk` varchar(45) DEFAULT NULL,
  `color` int(10) DEFAULT NULL,
  `award` varchar(45) DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bets_pk`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'mingle_status'
CREATE TABLE `mingle_status` (
  `mingle_status_pk` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  `user_a` int(11) NOT NULL,
  `user_b` int(11) NOT NULL,
  PRIMARY KEY (`mingle_status_pk`,`user_a`,`user_b`),
  KEY `fk_mingle_status_accounts1_idx` (`user_a`),
  KEY `fk_mingle_status_accounts2_idx` (`user_b`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'photoshop'
CREATE TABLE `photoshop` (
  `photoshop_pk` int(11) NOT NULL AUTO_INCREMENT,
  `users_fk` int(11) NOT NULL,
  `state` int(10) DEFAULT NULL,
  `image_url` longtext,
  PRIMARY KEY (`photoshop_pk`,`users_fk`),
  KEY `fk_photshop_users_idx` (`users_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'points'
CREATE TABLE `points` (
  `points_pk` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_fk` int(11) NOT NULL,
  `points` int(11) DEFAULT NULL,
  `reason` varchar(455) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`points_pk`),
  KEY `accounts_fk` (`accounts_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=latin1;
