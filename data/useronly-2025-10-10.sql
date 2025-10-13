-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema my_blog
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `my_blog` ;

-- -----------------------------------------------------
-- Schema my_blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `my_blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `my_blog` ;

-- -----------------------------------------------------
-- Table `my_blog`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `my_blog`.`role` ;

CREATE TABLE IF NOT EXISTS `my_blog`.`role` (
                                                `role_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                `role_name` VARCHAR(45) NOT NULL,
    `role_description` VARCHAR(500) NULL,
    PRIMARY KEY (`role_id`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `role_name_UNIQUE` ON `my_blog`.`role` (`role_name` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `my_blog`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `my_blog`.`user` ;

CREATE TABLE IF NOT EXISTS `my_blog`.`user` (
                                                `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                `user_login` VARCHAR(45) NOT NULL,
    `user_pwd` VARCHAR(300) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_bin' NOT NULL COMMENT 'binaire pour les accents etc... long pour le password_hash',
    `user_mail` VARCHAR(160) NOT NULL,
    `user_real_name` VARCHAR(150) NULL COMMENT 'vrai nom',
    `user_date_inscription` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'pour validation via mail',
    `user_hidden_id` VARCHAR(100) NOT NULL COMMENT 'sert pour le mail de validation (ou autre action cachee de cet utilisateur), pourra etre regenere au besoin',
    `user_activate` TINYINT UNSIGNED NULL DEFAULT 0 COMMENT '0 => pas valide\n1 => valide\n2 => banni\n3 ...',
    `user_role_id` SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_user_role`
    FOREIGN KEY (`user_role_id`)
    REFERENCES `my_blog`.`role` (`role_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_login_UNIQUE` ON `my_blog`.`user` (`user_login` ASC) VISIBLE;

CREATE UNIQUE INDEX `user_mail_UNIQUE` ON `my_blog`.`user` (`user_mail` ASC) VISIBLE;

CREATE INDEX `fk_user_role_idx` ON `my_blog`.`user` (`user_role_id` ASC) VISIBLE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
