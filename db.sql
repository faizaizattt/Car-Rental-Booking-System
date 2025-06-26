-- Create database (run once)
CREATE DATABASE IF NOT EXISTS car_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_rental;

-- Users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cars
CREATE TABLE cars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(80) NOT NULL,
  model VARCHAR(80) NOT NULL,
  price_per_day DECIMAL(10,2) NOT NULL,
  seats TINYINT NOT NULL,
  fuel_type VARCHAR(20) NOT NULL,
  status ENUM('available','maintenance','unavailable') DEFAULT 'available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  car_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  total_cost DECIMAL(10,2) NOT NULL,
  status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (car_id)  REFERENCES cars(id)  ON DELETE CASCADE
);

-- Sample admin user (password = admin123)
INSERT INTO users (name,email,password_hash,role) VALUES
('Administrator','admin@carrental.com',
  '$2y$10$XkFtgZalEV2/6gnFQd7ZVe2mV2/kvB0y4gixxk2rUOeJSk3CIfCqa',
  'admin');