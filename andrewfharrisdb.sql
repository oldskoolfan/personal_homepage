create database if not exists andrewfharrisdb;
use andrewfharrisdb;

CREATE TABLE `albums` (
  `album_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `album_desc` text,
  `credits` text,
  `release_year` year(4) DEFAULT NULL,
  `label` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

CREATE TABLE `songs` (
  `song_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned DEFAULT NULL,
  `song_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `recorded_date` date DEFAULT NULL,
  `audio` longblob,
  `file_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `credits` text,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`song_id`),
  KEY `albums_songs_fk` (`album_id`),
  CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

CREATE TABLE `thoughts` (
  `thought_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thought_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `thought_text` longtext,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`thought_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `images` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned DEFAULT NULL,
  `thought_id` int(10) unsigned DEFAULT NULL,
  `image_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `image_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `image_data` longblob,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `albums_image_fk` (`album_id`),
  KEY `thoughts_images_fk` (`thought_id`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE,
  CONSTRAINT `images_ibfk_2` FOREIGN KEY (`thought_id`) REFERENCES `thoughts` (`thought_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

