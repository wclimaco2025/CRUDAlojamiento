-- Database Schema for Alojamientos CRUD Application
-- Create database
CREATE DATABASE IF NOT EXISTS alojamientos_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alojamientos_db;

-- Table: users
-- Stores user accounts with authentication credentials and role information
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('regular', 'admin') DEFAULT 'regular' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: alojamientos
-- Stores accommodation listings with details
CREATE TABLE IF NOT EXISTS alojamientos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    ubicacion VARCHAR(200),
    precio DECIMAL(10, 2) NOT NULL,
    imagen_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre),
    INDEX idx_ubicacion (ubicacion),
    CHECK (precio > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: user_alojamientos
-- Junction table for many-to-many relationship between users and alojamientos
CREATE TABLE IF NOT EXISTS user_alojamientos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    alojamiento_id INT NOT NULL,
    selected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (alojamiento_id) REFERENCES alojamientos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_alojamiento (user_id, alojamiento_id),
    INDEX idx_user_id (user_id),
    INDEX idx_alojamiento_id (alojamiento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
