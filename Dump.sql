CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(1024) DEFAULT NULL,
  `short_description` varchar(2048) DEFAULT NULL,
  `text` text,
  `img` varchar(1024) DEFAULT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `news` (`id`, `header`, `short_description`, `text`, `img`, `tags`, `status`, `time_created`, `time_updated`)
VALUES
	(1,'News #1','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore \' +\n            \'et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\' +\n            \' ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu \' +\n            \'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt \' +\n            \'mollit anim id est laborum.','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore \' +\n            \'et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\' +\n            \' ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu \' +\n            \'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt \' +\n            \'mollit anim id est laborum.','/assets/images/news/cover-news-20180808.png','кек,чебурек','open','2018-08-30 16:31:14','2018-10-11 04:37:16'),
	(3,'Эх, чужд кайф, сплющь','<p>Широкая электрификация южных губерний даст мощный толчок подъёму сельского хозяйства.<br></p>','<<<<<<<p>Эй, жлоб! Где туз? Прячь юных <u><b>съёмщиц</b></u> в шкаф. Съешь [же] ещё этих мягких <span style=\"background-color: rgb(255, 255, 0);\">французских</span> булок да выпей чаю. В чащах юга жил бы цитрус? Да, но фальшивый экземпляр! Эх, чужак! Общий съём <a href=\"#\" target=\"_blank\">цен</a> шляп (юфть) — вдрызг!<br></p>','/assets/images/news/3.jpg',NULL,'open','2018-10-11 04:33:27','2018-11-13 04:17:04');

CREATE TABLE `news_like` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `news_id` INT(11) NOT NULL,
  `ip` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `news_id` INT(11) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `comment_like` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_id` INT(11) NOT NULL,
  `ip` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


ALTER TABLE `news_like`
ADD INDEX `idx-news_like-news_id` (`news_id`);

ALTER TABLE `news_like`
ADD CONSTRAINT `fk-news_like-news_id`
FOREIGN KEY (`news_id`) REFERENCES `news` (`id`);

ALTER TABLE `comment`
ADD INDEX `idx-comment-news_id` (`news_id`);

ALTER TABLE `comment`
ADD CONSTRAINT `fk-comment-news_id`
FOREIGN KEY (`news_id`) REFERENCES `news` (`id`);

ALTER TABLE `comment_like`
ADD INDEX `idx-comment_like-comment_id` (`comment_id`);

ALTER TABLE `comment_like`
ADD CONSTRAINT `fk-comment_like-comment_id`
FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

INSERT INTO `news` (
  `id`,
  `header`,
  `short_description`,
  `text`,
  `img`,
  `tags`,
  `status`
) VALUES
(
  4,
  'Новость 4',
  'Краткое описание новости 4',
  'Содержимое новости 4',
  '/assets/images/news/4.jpg',
  NULL,
  'open'
),
(
  5,
  'Новость 5',
  'Краткое описание новости 5',
  'Содержимое новости 5',
  '/assets/images/news/5.jpg',
  NULL,
  'open'
),
(
  6,
  'Новость 6',
  'Краткое описание новости 6',
  'Содержимое новости 6',
  '/assets/images/news/6.jpg',
  NULL,
  'open'
);
