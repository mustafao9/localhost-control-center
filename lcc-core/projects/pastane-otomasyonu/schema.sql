CREATE DATABASE IF NOT EXISTS `db_pastane` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_pastane`;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key_name` VARCHAR(100) NOT NULL,
  `key_value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`key_name`, `key_value`) VALUES ('site_name', 'pastane-otomasyonu');
