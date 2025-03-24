

CREATE TABLE users (
    `id` VARCHAR(36) PRIMARY KEY,
    `display_name` VARCHAR(50),
    `email` UNIQUE NOT NULL,
    `user_name` VARCHAR(50),
    `password` VARCHAR(100)
);
CREATE TABLE items (
    `id` VARCHAR(36) PRIMARY KEY,
    `name` VARCHAR(50),
    `description` VARCHAR(100),
    `price` DECIMAL(10, 2),
    `user_id` VARCHAR(36),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`)
);
CREATE TABLE supplier(
    `id` VARCHAR(36) PRIMARY KEY,
    `name` VARCHAR(50),
    `email` VARCHAR(50),
    `phone_number` VARCHAR(50),
    `status` VARCHAR(50)
);

CREATE TABLE orders(
    `id` VARCHAR(36) PRIMARY KEY,
    `users_id` VARCHAR(36),
    `employee_id` VARCHAR(36),
    `order_detail` VARCHAR(50), 
    `status` VARCHAR(50),
    FOREIGN KEY (`item_id`) REFERENCES items(`id`),
    FOREIGN KEY (`supplier_id`) REFERENCES supplier(`id`)
);

CREATE TABLE import(
    `id` VARCHAR(36) PRIMARY KEY,
    `item_id` VARCHAR(36),
    `supplier_id` VARCHAR(36),
    `status` VARCHAR(50),
    `import_detail` VARCHAR(50),
    FOREIGN KEY (`item_id`) REFERENCES items(`id`),
    FOREIGN KEY (`supplier_id`) REFERENCES supplier(`id`)
);

CREATE TABLE warranty(
    `id` VARCHAR(36) PRIMARY KEY,
    `item_id` VARCHAR(36),
    `user_id` VARCHAR(36),
    `supplier_id` VARCHAR(36),
    `issue_date` DATE,
    `expiration_date` DATE,
    FOREIGN KEY (`item_id`) REFERENCES items(`id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`),
    FOREIGN KEY (`supplier_id`) REFERENCES supplier(`id`)
);

CREATE TABLE cart(
    `user_id` VARCHAR(36),
    `item_id` VARCHAR(36),
    `quantity` INT,
    FOREIGN KEY (`user_id`) REFERENCES users(`id`),
    FOREIGN KEY (`item_id`) REFERENCES items(`id`)
);