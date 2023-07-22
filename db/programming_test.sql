CREATE TABLE `users` (
	`user_id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(30) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`password` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`fullname` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`date_created` DATETIME NOT NULL DEFAULT current_timestamp(),
	`date_modified` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`user_id`) USING BTREE
) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB;

CREATE TABLE `articles` (
	`article_id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NOT NULL,
	`title` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`excerpt` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`description` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`date_created` DATETIME NOT NULL DEFAULT current_timestamp(),
	`date_modified` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`article_id`) USING BTREE
) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB;
