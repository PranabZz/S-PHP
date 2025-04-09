CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `email` VARCHAR(255) UNIQUE,
    `username` VARCHAR(255),
    `password` VARCHAR(255),
    `verified` BOOLEAN DEFAULT '0',
    `remember_me` VARCHAR(255) DEFAULT '',
    `status` VARCHAR(255),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);