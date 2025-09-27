-- Master database for the website
-- Engine: MySQL 8+

-- Create database
CREATE DATABASE IF NOT EXISTS care_group
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE care_group;

-- Shared helper: on update current timestamp
-- All tables use InnoDB, utf8mb4

-- Departments (for public site and doctor specialization linkage)
DROP TABLE IF EXISTS departments;
CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Patients
DROP TABLE IF EXISTS patients;
CREATE TABLE patients (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  contact_phone VARCHAR(40) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctors
DROP TABLE IF EXISTS doctors;
CREATE TABLE doctors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  contact_phone VARCHAR(40) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  department_id INT UNSIGNED NULL,
  specialty VARCHAR(120) NULL,
  clinic VARCHAR(120) NULL,
  degree VARCHAR(120) NULL,
  bio TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_doctor_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins
DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  role ENUM('super','staff') NOT NULL DEFAULT 'staff',
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctor availability
DROP TABLE IF EXISTS doctor_availability;
CREATE TABLE doctor_availability (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT UNSIGNED NOT NULL,
  -- Exact slot or recurrence description
  slot_start DATETIME NULL,
  slot_end DATETIME NULL,
  recurrence_type ENUM('none','weekly','monthly') NOT NULL DEFAULT 'none',
  weekday TINYINT NULL COMMENT '0=Sun .. 6=Sat for weekly',
  month_day TINYINT NULL COMMENT '1..31 for monthly',
  notes VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_availability_doctor (doctor_id),
  CONSTRAINT fk_availability_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Appointments
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NOT NULL,
  scheduled_at DATETIME NOT NULL,
  status ENUM('scheduled','checked_in','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  reason VARCHAR(255) NULL,
  notes TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_appt_doctor (doctor_id, scheduled_at),
  KEY idx_appt_patient (patient_id, scheduled_at),
  CONSTRAINT fk_appt_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_appt_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages between parties
DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_type ENUM('patient','doctor','admin') NOT NULL,
  sender_id INT UNSIGNED NOT NULL,
  receiver_type ENUM('patient','doctor','admin') NOT NULL,
  receiver_id INT UNSIGNED NOT NULL,
  subject VARCHAR(200) NULL,
  body TEXT NOT NULL,
  read_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_msg_receiver (receiver_type, receiver_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medical records (simple placeholder)
DROP TABLE IF EXISTS medical_records;
CREATE TABLE medical_records (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NULL,
  title VARCHAR(200) NOT NULL,
  details TEXT NULL,
  recorded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_records_patient (patient_id, recorded_at),
  CONSTRAINT fk_rec_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_rec_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed initial departments (matching your site)
INSERT INTO departments (name, slug, description) VALUES
 ('General Medicine','general-medicine','Primary care and general medical services'),
 ('Neurology','neurology','Brain and nervous system'),
 ('Diabetes & Endocrinology','diabetes-endocrinology','Hormonal and metabolic conditions'),
 ('GI & Liver Diseases','gi-liver-diseases','Gastroenterology and hepatology'),
 ('Nephrology','nephrology','Kidney care and dialysis'),
 ('Surgical Oncology','surgical-oncology','Cancer surgery care'),
 ('Cardiology','cardiology','Heart and vascular care'),
 ('Medical Oncology','medical-oncology','Chemotherapy and systemic cancer care'),
 ('Dentistry','dentistry','Oral health and dentistry'),
 ('Rheumatology','rheumatology','Autoimmune and joint disorders');

-- Example admin (password: admin123) - change after import
-- UPDATE THIS IN PRODUCTION!
INSERT INTO admins (name, username, email, role, password_hash)
VALUES ('Site Admin','admin','admin@example.com','super',
        PASSWORD('admin123'))
ON DUPLICATE KEY UPDATE email = VALUES(email);

-- Note: For modern MySQL, prefer password_hash() in PHP instead of MySQL PASSWORD().
-- The above admin seed is only illustrative. In application code, create admins using password_hash().
