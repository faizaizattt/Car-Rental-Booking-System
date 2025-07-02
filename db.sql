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
  img_link VARCHAR(200) NOT NULL,
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
INSERT INTO users (name, email, password_hash, role)
VALUES ('Admin User', 'admin@example.com', MD5('admin123'), 'admin');


INSERT INTO cars (brand, model, img_link, price_per_day, seats, fuel_type, status) VALUES
('Perodua', 'Myvi', 'https://firebasestorage.googleapis.com/v0/b/fir-app-2c3a2.appspot.com/o/car_type%2F1682563435077-perodua%20myvi.png?alt=media&token=00a4c988-dadf-408a-a466-4ddf41e6c555', 120.00, 5, 'Petrol', 'available'),
('Perodua', 'Axia', 'https://firebasestorage.googleapis.com/v0/b/fir-app-2c3a2.appspot.com/o/car_type%2F1682563181414-axia.png?alt=media&token=cd38fd1a-6b21-452f-a52a-c127b97f79bba', 100.00, 5, 'Petrol', 'available'),
('Toyota', 'Vios', 'https://firebasestorage.googleapis.com/v0/b/fir-app-2c3a2.appspot.com/o/car_type%2F1682572137688-vios.png?alt=media&token=7f574425-51fb-4807-b889-903d9d92f385', 150.00, 5 ,'Petrol', 'available'),
('Renault', 'Capture', 'https://firebasestorage.googleapis.com/v0/b/fir-app-2c3a2.appspot.com/o/car_type%2F1682562399723-captur.png?alt=media&token=8c161fd1-8d90-4348-9b02-974510d36d3c', 320.00, 5, 'Diesel', 'available');