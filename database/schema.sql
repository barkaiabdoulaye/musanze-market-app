-- Musanze Market Database Schema
-- Drop database if exists (for clean installation)
DROP DATABASE IF EXISTS musanze_market;

-- Create database
CREATE DATABASE musanze_market;
USE musanze_market;

-- =====================================================
-- Table: users (Admin/Aggregator accounts)
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table: farmers
-- =====================================================
CREATE TABLE farmers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_name (full_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table: orders
-- =====================================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    farmer_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    pickup_location VARCHAR(255) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE RESTRICT,
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    INDEX idx_farmer (farmer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Create default admin user (password: admin123)
-- =====================================================
INSERT INTO users (username, password) VALUES 
('admin', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj4NQK6KxZfi');

-- =====================================================
-- Sample data for testing
-- =====================================================

-- Sample farmers
INSERT INTO farmers (full_name, phone, location) VALUES
('Jean Pierre', '0788123456', 'Musanze'),
('Marie Claire', '0788234567', 'Ruhengeri'),
('Peter Habimana', '0788345678', 'Kinigi'),
('Alice Uwase', '0788456789', 'Cyuve'),
('John Kagame', '0788567890', 'Gataraga');

-- Sample orders
INSERT INTO orders (farmer_id, quantity, unit_price, pickup_location, status, created_at) VALUES
(1, 50.5, 600, 'Musanze Market', 'completed', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 30.0, 600, 'Musanze Market', 'completed', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 75.0, 550, 'Ruhengeri Collection Point', 'pending', DATE_SUB(NOW(), INTERVAL 12 HOUR)),
(3, 100.0, 580, 'Kinigi Center', 'pending', NOW()),
(4, 45.5, 620, 'Cyuve Market', 'cancelled', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 80.0, 590, 'Gataraga Village', 'completed', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 65.0, 550, 'Ruhengeri', 'pending', DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(3, 120.0, 580, 'Kinigi', 'completed', DATE_SUB(NOW(), INTERVAL 4 DAY));

-- =====================================================
-- Views for common queries
-- =====================================================

-- View: farmer_statistics
CREATE VIEW farmer_statistics AS
SELECT 
    f.id,
    f.full_name,
    f.phone,
    COUNT(o.id) as total_orders,
    COALESCE(SUM(o.total_amount), 0) as total_value,
    COALESCE(AVG(o.total_amount), 0) as avg_order_value,
    MAX(o.created_at) as last_order_date
FROM farmers f
LEFT JOIN orders o ON f.id = o.farmer_id
GROUP BY f.id;

-- View: daily_summary
CREATE VIEW daily_summary AS
SELECT 
    DATE(created_at) as date,
    COUNT(*) as order_count,
    COALESCE(SUM(total_amount), 0) as total_value,
    COUNT(DISTINCT farmer_id) as active_farmers
FROM orders
GROUP BY DATE(created_at)
ORDER BY date DESC;

-- =====================================================
-- Indexes for performance
-- =====================================================
CREATE INDEX idx_orders_date ON orders(created_at);
CREATE INDEX idx_orders_status_date ON orders(status, created_at);