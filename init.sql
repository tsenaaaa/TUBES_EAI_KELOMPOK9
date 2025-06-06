USE payment_db;

DROP TABLE IF EXISTS payments;

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    room_number VARCHAR(10) NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(20) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE DATABASE IF NOT EXISTS menu_db;
USE menu_db;

CREATE TABLE IF NOT EXISTS menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    availability BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS room_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100),
    room_number VARCHAR(10),
    menu_items TEXT,
    total_amount DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
