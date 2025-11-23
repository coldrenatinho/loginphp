-- create-phpmyadmin-db.sql
-- Create a database for phpMyAdmin configuration storage
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create a user for phpMyAdmin
CREATE USER IF NOT EXISTS 'pma'@'%' IDENTIFIED BY 'pmapass';

-- Grant all privileges on the phpmyadmin database to the pma user
GRANT ALL PRIVILEGES ON `phpmyadmin`.* TO 'pma'@'%';

-- Grant usage on all databases to the pma user
-- This is required for some phpMyAdmin features
GRANT USAGE ON *.* TO 'pma'@'%';

FLUSH PRIVILEGES;
