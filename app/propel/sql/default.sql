
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- item
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` TEXT(100) NOT NULL,
    `description` TEXT(10000),
    `date` DATE NOT NULL,
    `user_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL,
    `password` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `unique_column` (`username`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `content` TEXT NOT NULL,
    `date` DATE NOT NULL,
    `user_id` INTEGER NOT NULL,
    `item_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
