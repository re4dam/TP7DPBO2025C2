-- Buat database (jika belum ada)
CREATE DATABASE IF NOT EXISTS maid_agency;
USE maid_agency;

-- Tabel maids
CREATE TABLE IF NOT EXISTS maids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    availability_status ENUM('available', 'booked', 'on_leave') NOT NULL DEFAULT 'available',
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_salary CHECK (salary >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uc_phone UNIQUE (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_maid INT NOT NULL,
    id_user INT NOT NULL,
    job_type VARCHAR(100) NOT NULL,
    address_of_job TEXT NOT NULL,
    description TEXT,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    date DATETIME NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in hours',
    total_cost DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('unpaid', 'paid', 'partially_paid', 'refunded') NOT NULL DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_maid) REFERENCES maids(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT chk_duration CHECK (duration > 0),
    CONSTRAINT chk_total_cost CHECK (total_cost >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Buat index untuk pencarian yang lebih cepat
CREATE INDEX idx_maid_availability ON maids(availability_status);
CREATE INDEX idx_transaction_status ON transactions(status);
CREATE INDEX idx_transaction_user ON transactions(id_user);
CREATE INDEX idx_transaction_maid ON transactions(id_maid);
