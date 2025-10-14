-- MySQL Workbench Forward Engineering
-- Les données ont été remplies par Gemini de Google
-- Nous les modifierons pour qu'ils soient valides
-- et en adéquation avec nos besoins

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
CREATE SCHEMA IF NOT EXISTS `my_blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
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

CREATE UNIQUE INDEX `role_name_UNIQUE` ON `my_blog`.`role` (`role_name` ASC) ;


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

CREATE UNIQUE INDEX `user_login_UNIQUE` ON `my_blog`.`user` (`user_login` ASC) ;

CREATE UNIQUE INDEX `user_mail_UNIQUE` ON `my_blog`.`user` (`user_mail` ASC) ;

CREATE INDEX `fk_user_role_idx` ON `my_blog`.`user` (`user_role_id` ASC) ;


-- -----------------------------------------------------
-- Table `my_blog`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `my_blog`.`article` ;

CREATE TABLE IF NOT EXISTS `my_blog`.`article` (
                                                   `article_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                   `article_title` VARCHAR(120) NOT NULL,
    `article_slug` VARCHAR(124) NOT NULL,
    `article_text` TEXT NOT NULL,
    `article_date_create` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `article_date_publish` DATETIME NULL,
    `article_visibility` TINYINT UNSIGNED NULL DEFAULT 0 COMMENT '0 => non publie\n1 => en relecture\n2 => publie\n3 => supprime',
    `article_user_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`article_id`),
    CONSTRAINT `fk_article_user1`
    FOREIGN KEY (`article_user_id`)
    REFERENCES `my_blog`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `article_slug_UNIQUE` ON `my_blog`.`article` (`article_slug` ASC);

CREATE INDEX `fk_article_user1_idx` ON `my_blog`.`article` (`article_user_id` ASC);


-- -----------------------------------------------------
-- Table `my_blog`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `my_blog`.`category` ;

CREATE TABLE IF NOT EXISTS `my_blog`.`category` (
                                                    `category_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                    `category_title` VARCHAR(100) NOT NULL,
    `category_slug` VARCHAR(104) NOT NULL,
    `category_description` VARCHAR(400) NULL,
    `category_parent` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`category_id`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `category_slug_UNIQUE` ON `my_blog`.`category` (`category_slug` ASC) ;


-- -----------------------------------------------------
-- Table `my_blog`.`article_has_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `my_blog`.`article_has_category` ;

CREATE TABLE IF NOT EXISTS `my_blog`.`article_has_category` (
                                                                `article_article_id` INT UNSIGNED NOT NULL,
                                                                `category_category_id` SMALLINT UNSIGNED NOT NULL,
                                                                PRIMARY KEY (`article_article_id`, `category_category_id`),
    CONSTRAINT `fk_article_has_category_article1`
    FOREIGN KEY (`article_article_id`)
    REFERENCES `my_blog`.`article` (`article_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `fk_article_has_category_category1`
    FOREIGN KEY (`category_category_id`)
    REFERENCES `my_blog`.`category` (`category_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE INDEX `fk_article_has_category_category1_idx` ON `my_blog`.`article_has_category` (`category_category_id` ASC) ;

CREATE INDEX `fk_article_has_category_article1_idx` ON `my_blog`.`article_has_category` (`article_article_id` ASC) ;


-- -----------------------------------------------------
-- Table `my_blog`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `my_blog`.`comment` ;

CREATE TABLE IF NOT EXISTS `my_blog`.`comment` (
                                                   `comment_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                   `comment_text` VARCHAR(600) NOT NULL,
    `comment_create` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `comment_parent` INT UNSIGNED NOT NULL DEFAULT 0,
    `comment_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=> pas valide\n1 => valide\n2 => banni',
    `comment_user_id` INT UNSIGNED NOT NULL,
    `comment_article_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`comment_id`),
    CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`comment_user_id`)
    REFERENCES `my_blog`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_comment_article1`
    FOREIGN KEY (`comment_article_id`)
    REFERENCES `my_blog`.`article` (`article_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

CREATE INDEX `fk_comment_user1_idx` ON `my_blog`.`comment` (`comment_user_id` ASC) ;

CREATE INDEX `fk_comment_article1_idx` ON `my_blog`.`comment` (`comment_article_id` ASC) ;




-- Inserting data into the 'role' table
INSERT INTO `role` (`role_id`, `role_name`, `role_description`) VALUES
(1, 'Admin', 'Administrator with full access.'),
(2, 'Editor', 'User who can write and manage articles.'),
(3, 'User', 'Registered user who can comment.');

-- Inserting data into the 'user' table
-- Passwords are 'password' hashed with PASSWORD_DEFAULT. Replace with your own hashes.
-- user_hidden_id is a unique id for actions like email validation.
INSERT INTO `user` (`user_id`, `user_login`, `user_pwd`, `user_mail`, `user_real_name`, `user_date_inscription`, `user_hidden_id`, `user_activate`, `user_role_id`) VALUES
(1, 'admin', '$2y$10$3.G.F.sR9.t5v/w5.Y.K.e.1f9G/9v8L.C.E.p4.g.d.a.b.c.d.e', 'admin@example.com', 'John Doe', NOW(), 'uid_admin_60b8d29f2c1a3', 1, 1),
(2, 'editor', '$2y$10$a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y', 'editor@example.com', 'Jane Smith', NOW(), 'uid_editor_60b8d29f2c1b4', 1, 2),
(3, 'user1', '$2y$10$z.y.x.w.v.u.t.s.r.q.p.o.n.m.l.k.j.i.h.g.f.e.d.c.b', 'user1@example.com', 'Lorem Ipsum', NOW(), 'uid_user1_60b8d29f2c1c5', 1, 3),
(4, 'user2', '$2y$10$1.2.3.4.5.6.7.8.9.0.a.b.c.d.e.f.g.h.i.j.k.l.m.n', 'user2@example.com', 'Dolor Sit Amet', NOW(), 'uid_user2_60b8d29f2c1d6', 0, 3);

-- Inserting data into the 'category' table
INSERT INTO `category` (`category_id`, `category_title`, `category_slug`, `category_description`, `category_parent`) VALUES
(1, 'PHP', 'php', 'Articles about PHP programming language.', 0),
(2, 'JavaScript', 'javascript', 'Articles about JavaScript programming language.', 0),
(3, 'Databases', 'databases', 'General articles about databases.', 0),
(4, 'MySQL', 'mysql', 'Articles specific to MySQL database.', 3);

-- Inserting data into the 'article' table
INSERT INTO `article` (`article_id`, `article_title`, `article_slug`, `article_text`, `article_date_create`, `article_date_publish`, `article_visibility`, `article_user_id`) VALUES
(1, 'Introduction to PHP', 'introduction-to-php', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', NOW(), NOW(), 2, 2),
(2, 'JavaScript Fundamentals', 'javascript-fundamentals', 'Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat.', NOW(), NOW(), 2, 2),
(3, 'Understanding SQL Joins', 'understanding-sql-joins', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', NOW(), NULL, 1, 1);

-- Linking articles and categories in 'article_has_category'
INSERT INTO `article_has_category` (`article_article_id`, `category_category_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(3, 4);

-- Inserting data into the 'comment' table
INSERT INTO `comment` (`comment_id`, `comment_text`, `comment_create`, `comment_parent`, `comment_visibility`, `comment_user_id`, `comment_article_id`) VALUES
(1, 'Great introduction! Very helpful for beginners.', NOW(), 0, 1, 3, 1),
(2, 'Thanks for this article. Looking forward to more content.', NOW(), 0, 1, 4, 1),
(3, 'I have a question about closures. Can you explain them in more detail?', NOW(), 0, 1, 3, 2),
(4, 'I agree, it is a very good article. I would also add that PHP 8 has many new features.', NOW(), 1, 1, 1, 1);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;