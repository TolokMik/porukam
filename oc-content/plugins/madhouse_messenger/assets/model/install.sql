
CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_status (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(30) NOT NULL,
	PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';
INSERT INTO /*TABLE_PREFIX*/t_mmessenger_status(name) VALUES('processing');
INSERT INTO /*TABLE_PREFIX*/t_mmessenger_status(name) VALUES('scheduled');
INSERT INTO /*TABLE_PREFIX*/t_mmessenger_status(name) VALUES('accepted');
INSERT INTO /*TABLE_PREFIX*/t_mmessenger_status(name) VALUES('refused');

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_events (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(30) NOT NULL,
	PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';
INSERT INTO /*TABLE_PREFIX*/t_mmessenger_events(name) VALUES('status_change');
INSERT INTO /*TABLE_PREFIX*/t_mmessenger_events(name) VALUES('contact_details_sent');

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_message (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(50),
	`content` TEXT NOT NULL,
	`sentOn` DATETIME,
	`hidden` BOOL,
	`reported` BOOL,
	`root_id` INT(10) UNSIGNED NULL,
	`sender_id` INT(10) UNSIGNED NOT NULL,
	`item_id` INT(10) UNSIGNED DEFAULT 0,
	`status_id` INT(10) UNSIGNED NULL,
	`event_id` INT(10) UNSIGNED NULL,
	CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_message_root` FOREIGN KEY(`root_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_message(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_message_status` FOREIGN KEY(`status_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_status(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_message_events` FOREIGN KEY(`event_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_events(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_recipients (
	message_id INT(10) UNSIGNED NOT NULL,
	recipient_id INT(10) UNSIGNED NOT NULL,
	readOn DATETIME,
	hidden BOOL DEFAULT FALSE,
	CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_recipients_message` FOREIGN KEY(`message_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_message(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY (`message_id`, `recipient_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';
