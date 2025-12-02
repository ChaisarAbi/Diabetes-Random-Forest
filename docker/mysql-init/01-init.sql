-- Diabetes Prediction System Database Initialization
-- Created: $(date)

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS diabetes_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user and grant privileges
CREATE USER IF NOT EXISTS 'diabetes_user'@'%' IDENTIFIED BY 'diabetes_pass';
GRANT ALL PRIVILEGES ON diabetes_db.* TO 'diabetes_user'@'%';
FLUSH PRIVILEGES;

-- Use the database
USE diabetes_db;

-- Note: Actual table creation will be done by CodeIgniter 4 migrations
-- This script just ensures the database and user exist

-- Create a simple health check table
CREATE TABLE IF NOT EXISTS health_check (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_name VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'healthy',
    last_check TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert initial health check record
INSERT INTO health_check (service_name, status) VALUES ('database', 'healthy')
ON DUPLICATE KEY UPDATE status='healthy', last_check=CURRENT_TIMESTAMP;
