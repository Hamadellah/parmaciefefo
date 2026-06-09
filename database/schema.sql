-- PharmaFEFO Database Schema
-- Run this file to create the database and tables
DROP DATABASE pharmafefo;
CREATE DATABASE IF NOT EXISTS pharmafefo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pharmafefo;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'PREPARATEUR', 'PHARMACIEN') NOT NULL DEFAULT 'PREPARATEUR',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category VARCHAR(100) DEFAULT 'General',
    unit VARCHAR(50) DEFAULT 'unité',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Stock batches table
CREATE TABLE stock_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    batch_number VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    expiry_date DATE NOT NULL,
    status ENUM('ACTIVE', 'EXPIRED', 'DEPLETED') NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_expiry (product_id, expiry_date),
    INDEX idx_expiry (expiry_date)
) ENGINE=InnoDB;

-- Stock movements table
CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('IN', 'OUT') NOT NULL,
    quantity INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES stock_batches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Alerts table
CREATE TABLE alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    level ENUM('green', 'orange', 'red', 'expired') NOT NULL,
    message VARCHAR(255) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES stock_batches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Supplier returns table
CREATE TABLE returns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    quantity INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES stock_batches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Loss reports table
CREATE TABLE loss_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    quantity INT NOT NULL,
    reason TEXT NOT NULL,
    reported_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES stock_batches(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
