-- Create database
CREATE DATABASE IF NOT EXISTS reservation_system;
USE reservation_system;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    user_id_number VARCHAR(10) PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL,
    sex VARCHAR(10) NOT NULL,
    phone VARCHAR(11) NOT NULL
);

-- Create reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id_number VARCHAR(10) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    venue VARCHAR(100) NOT NULL,
    equipment TEXT,
    status VARCHAR(10) DEFAULT 'pending',
    FOREIGN KEY (user_id_number) REFERENCES users(user_id_number)
);

