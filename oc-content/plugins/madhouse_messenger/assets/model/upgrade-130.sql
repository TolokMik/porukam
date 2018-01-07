
CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_status_description (
  `fk_i_status_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_c_locale_code` CHAR(5) NOT NULL,
  `s_title` VARCHAR(50) NOT NULL,
  `s_text` text,
    PRIMARY KEY (`fk_i_status_id`,`fk_c_locale_code`),
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_status_desc` FOREIGN KEY(`fk_i_status_id`) REFERENCES `/*TABLE_PREFIX*/t_mmessenger_status`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_status_locale` FOREIGN KEY (`fk_c_locale_code`) REFERENCES `/*TABLE_PREFIX*/t_locale`(`pk_c_code`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_events_description (
  `fk_i_event_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_c_locale_code` CHAR(5) NOT NULL,
  `s_text` text,
    PRIMARY KEY (`fk_i_event_id`,`fk_c_locale_code`),
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_events_desc` FOREIGN KEY(`fk_i_event_id`) REFERENCES `/*TABLE_PREFIX*/t_mmessenger_events`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_/*TABLE_PREFIX*/t_mmessenger_events_locale` FOREIGN KEY (`fk_c_locale_code`) REFERENCES `/*TABLE_PREFIX*/t_locale`(`pk_c_code`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';