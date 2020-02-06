| Comments | CREATE TABLE `Comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(250) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `story_id` (`story_id`),
  CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `UserInfo` (`id`),
  CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 |

| stories | CREATE TABLE `stories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `text` text NOT NULL,
  `link` varchar(250) NOT NULL,
  `rating` smallint(6) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `stories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `UserInfo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 |

| UserInfo | CREATE TABLE `UserInfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL,
  `hashed_password` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 |