--  Database Authentication
-- (c) dsphinx
--

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Authentication
-- -----------------------------------------------------
-- Base Auth
DROP SCHEMA IF EXISTS `Authentication` ;

-- -----------------------------------------------------
-- Schema Authentication
--
-- Base Auth
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Authentication` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Authentication` ;

-- -----------------------------------------------------
-- Table `Authentication`.`AuthDB`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`AuthDB` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`AuthDB` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(25) NOT NULL COMMENT '',
  `description` VARCHAR(45) NULL COMMENT '',
  `trash` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`AuthDB` (`id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`AuthLevels`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`AuthLevels` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`AuthLevels` (
  `id` INT(11) UNSIGNED NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '',
  `descriptions` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '',
  `AuthDB_id` INT UNSIGNED NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'User Levels';

CREATE UNIQUE INDEX `name_UNIQUE` ON `Authentication`.`AuthLevels` (`name` ASC)  COMMENT '';

CREATE INDEX `fk_AuthLevels_AuthDB1_idx` ON `Authentication`.`AuthLevels` (`AuthDB_id` ASC)  COMMENT '';

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`AuthLevels` (`id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`AuthSessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`AuthSessions` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`AuthSessions` (
  `id` CHAR(32) NOT NULL COMMENT '',
  `contents` MEDIUMTEXT NOT NULL COMMENT '',
  `modify_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `Authentication`.`Auth`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`Auth` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`Auth` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `login` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `surname` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `level` INT(11) UNSIGNED NOT NULL DEFAULT '100' COMMENT '',
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '',
  `ipallow` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT '' COMMENT '',
  `email` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT '@' COMMENT '',
  `message` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT '' COMMENT '',
  `passwd` CHAR(128) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `salt` CHAR(128) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT '',
  `AuthDB_id` TINYINT UNSIGNED NULL DEFAULT 0 COMMENT '',
  `locked` INT(1) NOT NULL DEFAULT '0' COMMENT '',
  `counter` INT(11) NULL DEFAULT '0' COMMENT '',
  `session_id` VARCHAR(32) CHARACTER SET 'utf8' NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 54
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
COMMENT = 'Authentication system - Base System\n';

CREATE UNIQUE INDEX `login` ON `Authentication`.`Auth` (`login` ASC)  COMMENT '';

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`Auth` (`id` ASC)  COMMENT '';

CREATE INDEX `fk_Auth_AuthLevels1_idx` ON `Authentication`.`Auth` (`level` ASC)  COMMENT '';

CREATE INDEX `fk_Auth_AuthDB1_idx` ON `Authentication`.`Auth` (`AuthDB_id` ASC)  COMMENT '';

CREATE INDEX `fk_Auth_AuthSessions1_idx` ON `Authentication`.`Auth` (`session_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`AuthValidActions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`AuthValidActions` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`AuthValidActions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `Auth_level_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '',
  `Action` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE INDEX `fk_Auth_Actions_Auth_Levels1_idx` ON `Authentication`.`AuthValidActions` (`Auth_level_id` ASC)  COMMENT '';

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`AuthValidActions` (`id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`AuthLogging`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`AuthLogging` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`AuthLogging` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `IP` VARCHAR(30) NOT NULL COMMENT '',
  `User_Agent` VARCHAR(255) NOT NULL COMMENT '',
  `Date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '',
  `Auth_id` INT(11) NOT NULL COMMENT '',
  `Geolocation` VARCHAR(50) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 312
DEFAULT CHARACTER SET = utf8
COMMENT = 'Autheticanted Users Logging';

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`AuthLogging` (`id` ASC)  COMMENT '';

CREATE INDEX `fk_Auth_Logging_Auth1_idx` ON `Authentication`.`AuthLogging` (`Auth_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`Logging`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`Logging` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`Logging` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `ip` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '',
  `section` VARCHAR(150) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `browser` VARCHAR(300) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `message` VARCHAR(150) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' COMMENT '',
  `coordinates` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1015
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
COMMENT = 'Logging ';


-- -----------------------------------------------------
-- Table `Authentication`.`Languages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`Languages` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`Languages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(2) NULL COMMENT 'ISO code',
  `longName` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`Languages` (`id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`DBPage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`DBPage` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`DBPage` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `page` VARCHAR(45) NOT NULL COMMENT '',
  `trash` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `Language_ID` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`DBPage` (`id` ASC)  COMMENT '';

CREATE INDEX `fk_DBPage_Languages1_idx` ON `Authentication`.`DBPage` (`Language_ID` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `Authentication`.`DBPageContent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Authentication`.`DBPageContent` ;

CREATE TABLE IF NOT EXISTS `Authentication`.`DBPageContent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `DBPage_id` INT NOT NULL COMMENT '',
  `title` VARCHAR(245) NOT NULL COMMENT '',
  `html` TEXT NULL COMMENT '',
  `trash` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;

CREATE UNIQUE INDEX `id_UNIQUE` ON `Authentication`.`DBPageContent` (`id` ASC)  COMMENT '';

CREATE INDEX `fk_DBPageContent_DBPage1_idx` ON `Authentication`.`DBPageContent` (`DBPage_id` ASC)  COMMENT '';

USE `Authentication` ;

-- -----------------------------------------------------
-- Placeholder table for view `Authentication`.`onlineSessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Authentication`.`onlineSessions` (`id` INT);

-- -----------------------------------------------------
-- Placeholder table for view `Authentication`.`onlineUsers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Authentication`.`onlineUsers` (`login` INT, `Session` INT, `message` INT, `secs` INT, `db` INT);

-- -----------------------------------------------------
-- Placeholder table for view `Authentication`.`showUsers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Authentication`.`showUsers` (`username` INT, `surname` INT, `staff` INT, `db` INT, `email` INT, `message` INT, `locked` INT, `counter` INT, `OnlineSeconds` INT);

-- -----------------------------------------------------
-- Placeholder table for view `Authentication`.`showUsersPermissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Authentication`.`showUsersPermissions` (`staff` INT, `level` INT, `Action` INT, `db` INT, `AuthDB_id` INT);

-- -----------------------------------------------------
-- procedure resetLogs
-- -----------------------------------------------------

USE `Authentication`;
DROP procedure IF EXISTS `Authentication`.`resetLogs`;

DELIMITER $$
USE `Authentication`$$
CREATE PROCEDURE `resetLogs` ()
BEGIN
TRUNCATE  `diagnosis`;
INSERT INTO `Logging` (`ip`, `date`, `section`, `browser`, `message`) VALUES ('127.0.0.1', now(), 'Development', 'internal', ' Empty all   -- Development stage');
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure optimize_tables
-- -----------------------------------------------------

USE `Authentication`;
DROP procedure IF EXISTS `Authentication`.`optimize_tables`;

DELIMITER $$
USE `Authentication`$$


/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `optimize_tables`(db_name VARCHAR(64))
BEGIN
DECLARE t VARCHAR(64);
DECLARE done INT DEFAULT 0;
DECLARE c CURSOR FOR
SELECT table_name FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = db_name AND TABLE_TYPE = 'BASE TABLE';
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
OPEN c;
tables_loop: LOOP
FETCH c INTO t; IF done THEN
LEAVE tables_loop; END IF;
SET @stmt_text := CONCAT("OPTIMIZE TABLE ", db_name, ".", t); PREPARE stmt FROM @stmt_text;
EXECUTE stmt;

DEALLOCATE PREPARE stmt; END LOOP;
CLOSE c; END */$$

DELIMITER ;

-- -----------------------------------------------------
-- View `Authentication`.`onlineSessions`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `Authentication`.`onlineSessions` ;
DROP TABLE IF EXISTS `Authentication`.`onlineSessions`;
USE `Authentication`;
CREATE  OR REPLACE VIEW `onlineSessions` AS
    select
        `AuthSessions`.`id` AS `id`
    from
        `AuthSessions`
    where
        (`AuthSessions`.`modify_date` > (now() - interval 1440 second));

-- -----------------------------------------------------
-- View `Authentication`.`onlineUsers`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `Authentication`.`onlineUsers` ;
DROP TABLE IF EXISTS `Authentication`.`onlineUsers`;
USE `Authentication`;
CREATE  OR REPLACE VIEW `onlineUsers` AS
  select
    `Auth`.`login` AS `login`,
    `Auth`.`session_id` AS `Session`,
    `Auth`.`message` AS `message`,
    (now() - `AuthSessions`.modify_date) as secs,
	(Select name from AuthDB where AuthDB.id=AuthDB_id ) as db
from
    (`Auth`
    join `onlineSessions`
    join `AuthSessions`)
where
    (`Auth`.`session_id` = `onlineSessions`.`id`)
        AND (`AuthSessions`.`id` = `onlineSessions`.`id`)
;

-- -----------------------------------------------------
-- View `Authentication`.`showUsers`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `Authentication`.`showUsers` ;
DROP TABLE IF EXISTS `Authentication`.`showUsers`;
USE `Authentication`;
CREATE  OR REPLACE VIEW `showUsers` AS
    select
        Auth.login as username,
        Auth.surname,
        AuthLevels.name as staff,
        (Select
                name
            from
                AuthDB
            where
                AuthDB.id = Auth.AuthDB_id) as db,
        Auth.email,
        Auth.message,
        Auth.locked,
        Auth.counter,
        (select
                secs
            from
                onlineUsers
            where
                login = Auth.login) as OnlineSeconds
    from
        Auth,
        AuthLevels
    where
        Auth.level = AuthLevels.id;

-- -----------------------------------------------------
-- View `Authentication`.`showUsersPermissions`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `Authentication`.`showUsersPermissions` ;
DROP TABLE IF EXISTS `Authentication`.`showUsersPermissions`;
USE `Authentication`;
CREATE  OR REPLACE VIEW `showUsersPermissions` AS
    select
        AuthLevels.name as staff,
        AuthLevels.id as level,
        AuthValidActions.Action,
        (Select
                name
            from
                AuthDB
            where
                AuthDB.id = AuthLevels.AuthDB_id) as db,
        AuthLevels.AuthDB_id
    from
        AuthLevels,
        AuthValidActions
    where
        AuthValidActions.Auth_level_id = AuthLevels.id
;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `Authentication`.`AuthDB`
-- -----------------------------------------------------
START TRANSACTION;
USE `Authentication`;
INSERT INTO `Authentication`.`AuthDB` (`id`, `name`, `description`, `trash`) VALUES (1, 'Authentication', 'DB SELF - dsphinx', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `Authentication`.`AuthLevels`
-- -----------------------------------------------------
START TRANSACTION;
USE `Authentication`;
INSERT INTO `Authentication`.`AuthLevels` (`id`, `name`, `descriptions`, `AuthDB_id`) VALUES (1, 'Administrator', 'Administrator Level', 1);
INSERT INTO `Authentication`.`AuthLevels` (`id`, `name`, `descriptions`, `AuthDB_id`) VALUES (100, 'Guest', 'Guest', 1);
INSERT INTO `Authentication`.`AuthLevels` (`id`, `name`, `descriptions`, `AuthDB_id`) VALUES (5, 'Manager', 'Manager', 1);
INSERT INTO `Authentication`.`AuthLevels` (`id`, `name`, `descriptions`, `AuthDB_id`) VALUES (10, 'Staff', 'staff', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `Authentication`.`Auth`
-- -----------------------------------------------------
START TRANSACTION;
USE `Authentication`;
---   admin - @administrator
INSERT INTO `Authentication`.`Auth` (`id`, `login`, `surname`, `level`, `date`, `ipallow`, `email`, `message`, `passwd`, `salt`, `AuthDB_id`, `locked`, `counter`, `session_id`) VALUES (1, 'admin', 'Constantinos Melisides', 1, DEFAULT, NULL, 'dsphinx@gmail.com', 'welcome ng', 'bc643ad4f14c8649f3e16453c9cc41fd6007e6dcd67f6af822e8dec53e6a8412dc066ececc1fadd5b76ea8a675903f31159f354e837b92fe6ca6cacdeea9414c', '9590becada23c949b7cfcd4108189bf3ba54ca4b4e96b606474c977c04e32d0b35a5c2bd59aa8e3dc210156afbfbcf0e730478d309bdc4cc21f949d5223840bc', NULL, 0, 0, NULL);
INSERT INTO `Authentication`.`Auth` (`id`, `login`, `surname`, `level`, `date`, `ipallow`, `email`, `message`, `passwd`, `salt`, `AuthDB_id`, `locked`, `counter`, `session_id`) VALUES (1000, 'guest', 'Guest account', 1000, DEFAULT, NULL, 'dsphinx@gmail.com', 'Guest', 'bc643ad4f14c8649f3e16453c9cc41fd6007e6dcd67f6af822e8dec53e6a8412dc066ececc1fadd5b76ea8a675903f31159f354e837b92fe6ca6cacdeea9414c', '9590becada23c949b7cfcd4108189bf3ba54ca4b4e96b606474c977c04e32d0b35a5c2bd59aa8e3dc210156afbfbcf0e730478d309bdc4cc21f949d5223840bc', NULL, 0, 0, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `Authentication`.`AuthValidActions`
-- -----------------------------------------------------
START TRANSACTION;
USE `Authentication`;
INSERT INTO `Authentication`.`AuthValidActions` (`id`, `Auth_level_id`, `Action`) VALUES (DEFAULT, 1, 'all');
INSERT INTO `Authentication`.`AuthValidActions` (`id`, `Auth_level_id`, `Action`) VALUES (DEFAULT, 1, 'create user');
INSERT INTO `Authentication`.`AuthValidActions` (`id`, `Auth_level_id`, `Action`) VALUES (DEFAULT, 1, 'view logs');
INSERT INTO `Authentication`.`AuthValidActions` (`id`, `Auth_level_id`, `Action`) VALUES (DEFAULT, 2, 'view logs');
INSERT INTO `Authentication`.`AuthValidActions` (`id`, `Auth_level_id`, `Action`) VALUES (DEFAULT, 1, 'sendmail');

COMMIT;


-- -----------------------------------------------------
-- Data for table `Authentication`.`Languages`
-- -----------------------------------------------------
START TRANSACTION;
USE `Authentication`;
INSERT INTO `Authentication`.`Languages` (`id`, `name`, `longName`) VALUES (1, 'GR', 'Ελληνικά');
INSERT INTO `Authentication`.`Languages` (`id`, `name`, `longName`) VALUES (2, 'EN', 'English');

COMMIT;


-- -----------------------------------------------------
-- Data for table `Authentication`.`DBPage`
-- -----------------------------------------------------
START TRANSACTION;
USE `Authentication`;
INSERT INTO `Authentication`.`DBPage` (`id`, `page`, `trash`, `Language_ID`) VALUES (1, 'main', NULL, 1);

COMMIT;

