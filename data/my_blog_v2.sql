-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 17 oct. 2025 à 11:30
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `my_blog`
--
DROP DATABASE IF EXISTS `my_blog`;
CREATE DATABASE IF NOT EXISTS `my_blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `my_blog`;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
                                         `article_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                         `article_title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
    `article_slug` varchar(124) COLLATE utf8mb4_unicode_ci NOT NULL,
    `article_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `article_date_create` datetime DEFAULT CURRENT_TIMESTAMP,
    `article_date_publish` datetime DEFAULT NULL,
    `article_visibility` tinyint UNSIGNED DEFAULT '0' COMMENT '0 => non publie\n1 => en relecture\n2 => publie\n3 => supprime',
    `article_user_id` int UNSIGNED NOT NULL,
    PRIMARY KEY (`article_id`),
    UNIQUE KEY `article_slug_UNIQUE` (`article_slug`),
    KEY `fk_article_user1_idx` (`article_user_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`article_id`, `article_title`, `article_slug`, `article_text`, `article_date_create`, `article_date_publish`, `article_visibility`, `article_user_id`) VALUES
                                                                                                                                                                                  (1, 'Introduction to PHP', 'introduction-to-php', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.', '2025-10-16 09:08:58', '2025-10-16 09:08:58', 2, 2),
                                                                                                                                                                                  (2, 'JavaScript Fundamentals', 'javascript-fundamentals', 'Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat.', '2025-10-16 09:08:58', '2025-10-16 09:08:58', 2, 2),
                                                                                                                                                                                  (3, 'Understanding SQL Joins', 'understanding-sql-joins', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2025-10-16 09:08:58', '2025-10-16 09:09:57', 2, 1),
                                                                                                                                                                                  (4, 'Youpie', 'youpie', 'The installer - which requires that you have PHP already installed - will download Composer for you and set up your PATH environment variable so you can simply call composer from any directory.\r\n\r\nDownload and run Composer-Setup.exe - it will install the latest composer version whenever it is executed.', '2025-10-16 09:40:45', '2025-10-16 09:41:13', 2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `article_has_category`
--

DROP TABLE IF EXISTS `article_has_category`;
CREATE TABLE IF NOT EXISTS `article_has_category` (
                                                      `article_article_id` int UNSIGNED NOT NULL,
                                                      `category_category_id` smallint UNSIGNED NOT NULL,
                                                      PRIMARY KEY (`article_article_id`,`category_category_id`),
    KEY `fk_article_has_category_category1_idx` (`category_category_id`),
    KEY `fk_article_has_category_article1_idx` (`article_article_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article_has_category`
--

INSERT INTO `article_has_category` (`article_article_id`, `category_category_id`) VALUES
                                                                                      (1, 1),
                                                                                      (2, 2),
                                                                                      (1, 3),
                                                                                      (3, 3),
                                                                                      (3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
                                          `category_id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
                                          `category_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `category_slug` varchar(104) COLLATE utf8mb4_unicode_ci NOT NULL,
    `category_description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `category_parent` smallint UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`category_id`),
    UNIQUE KEY `category_slug_UNIQUE` (`category_slug`)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`category_id`, `category_title`, `category_slug`, `category_description`, `category_parent`) VALUES
                                                                                                                         (1, 'PHP', 'php', 'Articles about PHP programming language.', 0),
                                                                                                                         (2, 'JavaScript', 'javascript', 'Articles about JavaScript programming language.', 0),
                                                                                                                         (3, 'Databases', 'databases', 'General articles about databases.', 0),
                                                                                                                         (4, 'MySQL', 'mysql', 'Articles specific to MySQL database.', 0),
                                                                                                                         (5, 'Intelligence artificielle', 'intelligence-artificielle', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
                                         `comment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                         `comment_text` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
    `comment_create` datetime DEFAULT CURRENT_TIMESTAMP,
    `comment_parent` int UNSIGNED NOT NULL DEFAULT '0',
    `comment_visibility` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=> pas valide\n1 => valide\n2 => banni',
    `comment_user_id` int UNSIGNED NOT NULL,
    `comment_article_id` int UNSIGNED NOT NULL,
    PRIMARY KEY (`comment_id`),
    KEY `fk_comment_user1_idx` (`comment_user_id`),
    KEY `fk_comment_article1_idx` (`comment_article_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`comment_id`, `comment_text`, `comment_create`, `comment_parent`, `comment_visibility`, `comment_user_id`, `comment_article_id`) VALUES
                                                                                                                                                            (1, 'Great introduction! Very helpful for beginners.', '2025-10-16 09:08:58', 0, 1, 3, 1),
                                                                                                                                                            (2, 'Thanks for this article. Looking forward to more content.', '2025-10-16 09:08:58', 0, 1, 4, 1),
                                                                                                                                                            (3, 'I have a question about closures. Can you explain them in more detail?', '2025-10-16 09:08:58', 0, 1, 3, 2),
                                                                                                                                                            (4, 'I agree, it is a very good article. I would also add that PHP 8 has many new features.', '2025-10-16 09:08:58', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
                                      `role_id` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
                                      `role_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
    `role_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`role_id`),
    UNIQUE KEY `role_name_UNIQUE` (`role_name`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_description`) VALUES
                                                                    (1, 'Admin', 'Administrator with full access.'),
                                                                    (2, 'Editor', 'User who can write and manage articles.'),
                                                                    (3, 'User', 'Registered user who can comment.');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
                                      `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                      `user_login` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_pwd` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'binaire pour les accents etc... long pour le password_hash\r\n\r\nLe mot de passe est le user_login',
    `user_mail` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_real_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'vrai nom',
    `user_date_inscription` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'pour validation via mail',
    `user_hidden_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sert pour le mail de validation (ou autre action cachee de cet utilisateur), pourra etre regenere au besoin',
    `user_activate` tinyint UNSIGNED DEFAULT '0' COMMENT '0 => pas valide\n1 => valide\n2 => banni\n3 ...',
    `user_role_id` smallint UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `user_login_UNIQUE` (`user_login`),
    UNIQUE KEY `user_mail_UNIQUE` (`user_mail`),
    KEY `fk_user_role_idx` (`user_role_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `user_login`, `user_pwd`, `user_mail`, `user_real_name`, `user_date_inscription`, `user_hidden_id`, `user_activate`, `user_role_id`) VALUES
                                                                                                                                                                        (1, 'admin', '$2y$10$frf81co4qvbVvXlIbjoOQev7zJqJum5SUwUYEwlLkezziR5gBLz46', 'admin@example.com', 'John Doe', '2025-10-16 09:08:58', 'uid_admin_60b8d29f2c1a3', 1, 1),
                                                                                                                                                                        (2, 'editor', '$2y$10$2xySwxmRLkzdZ6.qORGtV.VxrbOXnqqtuIdVAw.2PCjZmDiQJPvhe', 'editor@example.com', 'Jane Smith', '2025-10-16 09:08:58', 'uid_editor_60b8d29f2c1b4', 1, 2),
                                                                                                                                                                        (3, 'user1', '$2y$10$39ilfnNKn5Bu0UTcBDRbzucpEet.lTH3c00bu2cthgN/g1ZFz0zri', 'user1@example.com', 'Lorem Ipsum', '2025-10-16 09:08:58', 'uid_user1_60b8d29f2c1c5', 1, 3),
                                                                                                                                                                        (4, 'user2', '$2y$10$j3YKXYlcoJdUv/LtnFN.BOXo67qACi9ormT0CCO4eRQ9jjkM3d3CG', 'user2@example.com', NULL, '2025-10-16 09:08:58', 'uid_user2_60b8d29f2c1d6', 1, 3);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
    ADD CONSTRAINT `fk_article_user1` FOREIGN KEY (`article_user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `article_has_category`
--
ALTER TABLE `article_has_category`
    ADD CONSTRAINT `fk_article_has_category_article1` FOREIGN KEY (`article_article_id`) REFERENCES `article` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_has_category_category1` FOREIGN KEY (`category_category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
    ADD CONSTRAINT `fk_comment_article1` FOREIGN KEY (`comment_article_id`) REFERENCES `article` (`article_id`),
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`comment_user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
    ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`user_role_id`) REFERENCES `role` (`role_id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
