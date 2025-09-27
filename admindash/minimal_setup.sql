-- Minimal setup for CareGroup AdminDash
-- Schema + helpful indexes + optional seed data matching current code

CREATE DATABASE IF NOT EXISTS care_group CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE care_group;

-- Cities
CREATE TABLE IF NOT EXISTS cities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctors
CREATE TABLE IF NOT EXISTS doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  specialization VARCHAR(100) NOT NULL,
  email VARCHAR(150) NULL,
  phone VARCHAR(20) NULL,
  status ENUM('Active','Inactive','On Leave') DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Patients
CREATE TABLE IF NOT EXISTS patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NULL,
  phone VARCHAR(20) NULL,
  city_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_patients_city FOREIGN KEY (city_id)
    REFERENCES cities(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users (login accounts) - email is optional but supported by api/login.php
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('Administrator','Doctor','Patient') NOT NULL DEFAULT 'Administrator',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Website content
CREATE TABLE IF NOT EXISTS website_content (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('Disease','Prevention','Cure','News','Invention') NOT NULL,
  title VARCHAR(200) NOT NULL,
  summary VARCHAR(255) NOT NULL,
  details TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Helpful indexes
CREATE INDEX IF NOT EXISTS idx_doctors_specialization ON doctors(specialization);
CREATE INDEX IF NOT EXISTS idx_doctors_status ON doctors(status);
CREATE INDEX IF NOT EXISTS idx_patients_city ON patients(city_id);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_content_type ON website_content(type);

-- Seed data (optional)
INSERT INTO cities (name) VALUES
('New York'), ('Los Angeles'), ('Chicago')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Admin user (username: admin, password: admin123)
-- bcrypt hash for 'admin123'
INSERT INTO users (name, username, email, password_hash, role)
VALUES ('Admin User', 'admin', 'admin@example.com', '$2y$10$0mXJf7mZC7Zb7oMt8LZbQOKj6k3w4Yc6o1o4wL1zT9f2f1rJ4XK1a', 'Administrator')
ON DUPLICATE KEY UPDATE name = VALUES(name), email = VALUES(email), role = VALUES(role);

-- Example website content (optional)
INSERT INTO website_content (type, title, summary, details) VALUES
('Disease', 'Hypertension', 'Common condition managed with lifestyle and medication', 'Hypertension can be managed by diet, exercise, and prescribed medicines.'),
('Prevention', 'Hand Hygiene', 'Wash hands for 20 seconds', 'Proper handwashing reduces transmission of infections.'),
('News', 'New Vaccine Update', 'Latest immunization guidelines released', 'Authorities published updated vaccination guidelines.');
