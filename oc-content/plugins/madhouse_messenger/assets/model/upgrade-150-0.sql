
CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_mmessenger_blocked_users (
    `fk_i_user_id` INT(10) UNSIGNED NOT NULL,
    `s_blocked_user` VARCHAR(255) NOT NULL,
    `dt_created` DATETIME,
    PRIMARY KEY (`fk_i_user_id`, `s_blocked_user`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';