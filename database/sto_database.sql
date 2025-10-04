-- Создание базы данных
CREATE DATABASE IF NOT EXISTS sto_database;
USE sto_database;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица записей на услуги
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_phone VARCHAR(20) NOT NULL,
    car_model VARCHAR(100) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    booking_date DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица отзывов
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    approved BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Вставка тестовых данных
INSERT INTO reviews (client_name, rating, review_text) VALUES
('Иван Петров', 5, 'Отличный сервис! Быстро и качественно починили двигатель. Рекомендую!'),
('Анна Сидорова', 4, 'Хорошее обслуживание, вежливый персонал. Цены адекватные.'),
('Сергей Козлов', 5, 'Лучший автосервис в городе! Всегда доволен результатом.');

-- Создание пользователя для базы данных (опционально)
CREATE USER IF NOT EXISTS 'sto_user'@'localhost' IDENTIFIED BY 'sto_password';
GRANT ALL PRIVILEGES ON sto_database.* TO 'sto_user'@'localhost';
FLUSH PRIVILEGES;