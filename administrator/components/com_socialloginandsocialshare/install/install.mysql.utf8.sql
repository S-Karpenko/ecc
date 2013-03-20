CREATE TABLE IF NOT EXISTS `#__LoginRadius_settings` (
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`setting` varchar(255) NOT NULL,
	`value` varchar(1000) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__LoginRadius_users` (
	`id` int(11),
	`LoginRadius_id` varchar(255) NULL,
	`provider` varchar(255) NULL,
	`lr_picture` varchar(255) NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'useapi',
	`value` =  '1';

INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'k2group',
	`value` =  '2';

INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'sendemail',
	`value` =  '1';

INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'dummyemail',
	`value` =  '1';
	
INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'showname',
	`value` =  '1';

INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'showwithicons',
	`value` =  '1';
	
INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'showlogout',
	`value` =  '1';
	
INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'showicons',
	`value` =  '0';

INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'setredirct',
	`value` =  'default';

INSERT IGNORE INTO `#__LoginRadius_settings` SET
	`setting` = 'linkaccount',
	`value` =  '1';