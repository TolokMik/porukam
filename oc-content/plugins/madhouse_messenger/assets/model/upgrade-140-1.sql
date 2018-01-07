CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_labels (
    `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `s_name` VARCHAR(30) NOT NULL,
    `b_system` BOOLEAN NOT NULL DEFAULT FALSE,
    `fk_i_user_id` INT(10) UNSIGNED NULL,
    `fk_i_parent_id` INT(10) UNSIGNED NULL,
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_labels` FOREIGN KEY(`fk_i_parent_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_labels(`pk_i_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (`pk_i_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_labels_description (
  `fk_i_label_id` INT(10) UNSIGNED NOT NULL,
  `fk_c_locale_code` CHAR(5) NOT NULL,
  `s_title` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`fk_i_label_id`,`fk_c_locale_code`),
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_labels_title` FOREIGN KEY(`fk_i_label_id`) REFERENCES `/*TABLE_PREFIX*/t_mmessenger_labels`(`pk_i_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_labels_locale` FOREIGN KEY (`fk_c_locale_code`) REFERENCES `/*TABLE_PREFIX*/t_locale`(`pk_c_code`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_message_labels (
    `fk_i_message_id` INT(10) UNSIGNED NOT NULL,
    `fk_i_user_id` INT(10) UNSIGNED NOT NULL,
    `fk_i_label_id` INT(10) UNSIGNED NOT NULL,
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_message_labels_message` FOREIGN KEY(`fk_i_message_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_message(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_message_labels_label` FOREIGN KEY(`fk_i_label_id`) REFERENCES /*TABLE_PREFIX*/t_mmessenger_labels(`pk_i_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (`fk_i_message_id`, `fk_i_user_id`, `fk_i_label_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

ALTER TABLE /*TABLE_PREFIX*/t_mmessenger_message
  DROP
    FOREIGN KEY `fk_/*TABLE_PREFIX*/t_mmessenger_message_status`;

ALTER TABLE /*TABLE_PREFIX*/t_mmessenger_message
  ADD
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_message_status`
    FOREIGN KEY(`status_id`)
    REFERENCES `/*TABLE_PREFIX*/t_mmessenger_status`(`id`) ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE /*TABLE_PREFIX*/t_mmessenger_events_description
  ADD `s_excerpt` VARCHAR(255) NULL AFTER `fk_c_locale_code`;

