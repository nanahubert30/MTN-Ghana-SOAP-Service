-- =====================================================
-- MTN Ghana SOAP Service Database Setup
-- =====================================================
-- This script creates the complete database structure
-- for the MTN Ghana SOAP web service
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS mtn_ghana;
USE mtn_ghana;

-- =====================================================
-- CUSTOMERS TABLE
-- =====================================================
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    email VARCHAR(100),
    balance DECIMAL(10, 2) DEFAULT 0.00,
    subscription_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_status (status),
    INDEX idx_subscription_date (subscription_date)
);

-- =====================================================
-- TRANSACTIONS TABLE
-- =====================================================
CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    transaction_type ENUM('recharge', 'call', 'sms', 'data', 'payment') DEFAULT 'recharge',
    description VARCHAR(255),
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    reference_number VARCHAR(50),
    status ENUM('completed', 'pending', 'failed') DEFAULT 'completed',
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_customer_id (customer_id),
    INDEX idx_transaction_date (transaction_date),
    INDEX idx_type (transaction_type)
);

-- =====================================================
-- SAMPLE DATA - CUSTOMERS
-- =====================================================
INSERT INTO customers (name, phone, email, balance, status) VALUES
('Kwame Asante', '0244123456', 'kwame@email.com', 25.50, 'active'),
('Ama Osei', '0545678901', 'ama@email.com', 15.00, 'active'),
('Kofi Mensah', '0501234567', 'kofi@email.com', 50.75, 'active'),
('Abena Akosua', '0261234567', 'abena@email.com', 30.25, 'suspended'),
('Yaw Boateng', '0551234567', 'yaw@email.com', 100.00, 'active'),
('Cynthia Owusu', '0241111111', 'cynthia@email.com', 45.30, 'active'),
('Samuel Arhin', '0502222222', 'samuel@email.com', 12.75, 'active'),
('Ekua Mensah', '0701234567', 'ekua@email.com', 5.00, 'inactive'),
('Bernard Ansah', '0771234567', 'bernard@email.com', 87.60, 'active'),
('Grace Boakye', '0801234567', 'grace@email.com', 22.40, 'active');

-- =====================================================
-- SAMPLE DATA - TRANSACTIONS
-- =====================================================
INSERT INTO transactions (customer_id, amount, transaction_type, description, reference_number) VALUES
(1, 10.00, 'recharge', 'Airtime Recharge GHS 10', 'TXN20240101001'),
(1, 2.50, 'call', 'Local Call Deduction', 'TXN20240101002'),
(2, 5.00, 'recharge', 'Airtime Recharge GHS 5', 'TXN20240102001'),
(3, 20.00, 'data', 'Data Bundle 1GB', 'TXN20240103001'),
(4, 1.50, 'sms', 'SMS Package - 50 SMS', 'TXN20240104001'),
(5, 15.00, 'recharge', 'Airtime Recharge GHS 15', 'TXN20240105001'),
(6, 10.00, 'recharge', 'Airtime Recharge GHS 10', 'TXN20240106001'),
(7, 5.00, 'recharge', 'Airtime Recharge GHS 5', 'TXN20240107001'),
(8, 3.00, 'call', 'International Call Deduction', 'TXN20240108001'),
(9, 25.00, 'data', 'Data Bundle 5GB', 'TXN20240109001'),
(10, 20.00, 'recharge', 'Airtime Recharge GHS 20', 'TXN20240110001'),
(1, 5.00, 'data', 'Data Bundle 500MB', 'TXN20240111001'),
(2, 15.00, 'recharge', 'Airtime Recharge GHS 15', 'TXN20240112001'),
(3, 3.50, 'sms', 'SMS Package - 25 SMS', 'TXN20240113001'),
(5, 10.00, 'recharge', 'Airtime Recharge GHS 10', 'TXN20240114001');

-- =====================================================
-- DATABASE INFORMATION
-- =====================================================
-- Total Tables: 2 (customers, transactions)
-- Sample Records: 10 customers, 15 transactions
-- =====================================================
