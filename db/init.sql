

CREATE TABLE users (
    `id` VARCHAR(36) PRIMARY KEY,
    `display_name` VARCHAR(50),
    `email` UNIQUE NOT NULL,
    `user_name` VARCHAR(50),
    `password` VARCHAR(100)
);