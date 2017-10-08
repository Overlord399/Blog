<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1507358967.
 * Generated on 2017-10-07 09:49:27 
 */
class PropelMigration_1507358967
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE INDEX `comment_fi_29554a` ON `comment` (`user_id`);

CREATE INDEX `comment_fi_5cf635` ON `comment` (`item_id`);

ALTER TABLE `comment` ADD CONSTRAINT `comment_fk_29554a`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`);

ALTER TABLE `comment` ADD CONSTRAINT `comment_fk_5cf635`
    FOREIGN KEY (`item_id`)
    REFERENCES `item` (`id`);

CREATE INDEX `item_fi_29554a` ON `item` (`user_id`);

ALTER TABLE `item` ADD CONSTRAINT `item_fk_29554a`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `comment` DROP FOREIGN KEY `comment_fk_29554a`;

ALTER TABLE `comment` DROP FOREIGN KEY `comment_fk_5cf635`;

DROP INDEX `comment_fi_29554a` ON `comment`;

DROP INDEX `comment_fi_5cf635` ON `comment`;

ALTER TABLE `item` DROP FOREIGN KEY `item_fk_29554a`;

DROP INDEX `item_fi_29554a` ON `item`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}