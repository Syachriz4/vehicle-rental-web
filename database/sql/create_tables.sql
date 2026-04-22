-- =====================================================
-- VEHICLE BOOKING SYSTEM - DATABASE SCHEMA
-- =====================================================
-- Database: vehicle_booking
-- Created: 2026-04-16
-- Type: Generic/Template Schema (SQL Documentation)
-- Note: This file documents the database structure.
--       The actual schema is managed via Laravel Migrations.
-- =====================================================

-- 1. REGIONS TABLE - Locations (Headquarters, Branch, Mining Sites)
-- =====================================================
CREATE TABLE regions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,                    -- Location name (e.g., REGION-01, REGION-02)
    code VARCHAR(20) UNIQUE NOT NULL,              -- Location code (e.g., RGN-001, RGN-002)
    description TEXT,                              -- Location description
    address VARCHAR(255),                          -- Full address
    type ENUM('kantor_pusat', 'kantor_cabang', 'tambang') NOT NULL, -- Location type
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. DEPARTMENTS TABLE - Departments/Work Units
-- =====================================================
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,                    -- Department name (e.g., DEPT-01, DEPT-02)
    code VARCHAR(20) UNIQUE NOT NULL,              -- Department code (e.g., DPT-001, DPT-002)
    description TEXT,                              -- Department description
    region_id INT,                                 -- FK to regions (department location)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL
);

-- 3. USERS TABLE - System Users (Admin, Approver, Staff)
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,                    -- Full name
    email VARCHAR(100) UNIQUE NOT NULL,            -- Email (for login)
    email_verified_at TIMESTAMP NULL,              -- Email verification timestamp
    password VARCHAR(255) NOT NULL,                -- Hashed password
    role ENUM('admin', 'approver', 'user') DEFAULT 'user', -- User role
    department_id BIGINT UNSIGNED NULL,            -- FK to departments
    supervisor_id BIGINT UNSIGNED NULL,            -- FK to users (direct supervisor for hierarchy)
    phone VARCHAR(20) NULL,                        -- Phone number
    is_active BOOLEAN DEFAULT TRUE,                -- Active/inactive status
    remember_token VARCHAR(100) NULL,              -- Remember me token
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 4. VEHICLES TABLE - Vehicle Data
-- =====================================================
CREATE TABLE vehicles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    plate_number VARCHAR(20) UNIQUE NOT NULL,      -- License plate (e.g., VEH-001, VEH-002)
    vehicle_name VARCHAR(100) NOT NULL,            -- Vehicle name/description
    vehicle_type ENUM('passenger', 'cargo') NOT NULL, -- Type: passenger or cargo vehicle
    region_id INT NOT NULL,                        -- FK to regions (vehicle location)
    brand VARCHAR(50) NULL,                        -- Brand (e.g., Brand A, Brand B)
    model VARCHAR(50) NULL,                        -- Model (e.g., Model A, Model B)
    year INT NULL,                                 -- Manufacturing year
    purchase_date DATE NULL,                       -- Purchase date
    current_km INT DEFAULT 0,                      -- Current km
    last_service_date DATE NULL,                   -- Last service date
    status ENUM('available', 'in_use', 'maintenance') DEFAULT 'available', -- Vehicle status
    is_rental BOOLEAN DEFAULT FALSE,               -- Is rental vehicle?
    rental_company_name VARCHAR(100) NULL,         -- Rental company name (if rental)
    notes TEXT NULL,                               -- Additional notes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE RESTRICT
);

-- 5. BOOKINGS TABLE - Vehicle Bookings/Reservations
-- =====================================================
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_number VARCHAR(50) UNIQUE NOT NULL,    -- Booking number (e.g., BK202401001)
    user_id INT NOT NULL,                          -- FK to users (booking requester)
    vehicle_id INT NOT NULL,                       -- FK to vehicles (booked vehicle)
    driver_id INT NULL,                            -- FK to users (assigned driver)
    approver1_id INT NULL,                         -- FK to users (level 1 approver)
    approver2_id INT NULL,                         -- FK to users (level 2 approver)
    start_date DATETIME NOT NULL,                  -- Booking start date/time
    end_date DATETIME NOT NULL,                    -- Planned return date/time
    actual_return_date DATETIME NULL,              -- Actual return date/time
    purpose VARCHAR(255) NOT NULL,                 -- Purpose of booking
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending', -- Booking status
    fuel_used INT NULL,                            -- Fuel used (liters)
    start_km INT NULL,                             -- Starting km
    end_km INT NULL,                               -- Ending km
    notes TEXT NULL,                               -- Special notes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approver1_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approver2_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 6. APPROVALS TABLE - Approval History
-- =====================================================
CREATE TABLE approvals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,                       -- FK to bookings (approval target)
    approver_id INT NOT NULL,                      -- FK to users (approver)
    level INT NOT NULL,                            -- Approval level (1 or 2)
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending', -- Approval status
    comments TEXT NULL,                            -- Approver comments
    approved_at TIMESTAMP NULL,                    -- Approval timestamp
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (approver_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- 7. ACTIVITY_LOGS TABLE - Application Activity Log
-- =====================================================
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,                          -- FK to users (action performer)
    action VARCHAR(50) NOT NULL,                   -- Action type (create, update, delete, approve, reject, etc)
    module VARCHAR(50) NOT NULL,                   -- Module accessed (booking, vehicle, approval, etc)
    description TEXT NOT NULL,                     -- Action description
    ip_address VARCHAR(45) NULL,                   -- IP address
    user_agent VARCHAR(255) NULL,                  -- Browser user agent
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- 8. FUEL_CONSUMPTION TABLE - Fuel Usage Tracking
-- =====================================================
CREATE TABLE fuel_consumption (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_id INT NOT NULL,                       -- FK to vehicles
    booking_id INT NULL,                           -- FK to bookings (optional)
    amount INT NOT NULL,                           -- Fuel amount (liters)
    price DECIMAL(10, 2) NOT NULL,                 -- Price per liter
    fuel_date DATE NOT NULL,                       -- Refuel date
    km_at_fuel INT NULL,                           -- Km at refuel
    notes TEXT NULL,                               -- Notes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
);

-- =====================================================
-- INDEXES for query performance
-- =====================================================
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_department_id ON users(department_id);
CREATE INDEX idx_users_supervisor_id ON users(supervisor_id);
CREATE INDEX idx_vehicles_plate_number ON vehicles(plate_number);
CREATE INDEX idx_vehicles_status ON vehicles(status);
CREATE INDEX idx_bookings_booking_number ON bookings(booking_number);
CREATE INDEX idx_bookings_user_id ON bookings(user_id);
CREATE INDEX idx_bookings_vehicle_id ON bookings(vehicle_id);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_driver_id ON bookings(driver_id);
CREATE INDEX idx_approvals_booking_id ON approvals(booking_id);
CREATE INDEX idx_approvals_approver_id ON approvals(approver_id);
CREATE INDEX idx_approvals_status ON approvals(status);
CREATE INDEX idx_approvals_level ON approvals(level);
CREATE INDEX idx_activity_logs_user_id ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_action ON activity_logs(action);
CREATE INDEX idx_fuel_consumption_vehicle_id ON fuel_consumption(vehicle_id);
CREATE INDEX idx_fuel_consumption_booking_id ON fuel_consumption(booking_id);

-- =====================================================
-- ADDITIONAL LARAVEL SYSTEM TABLES
-- =====================================================
-- The following tables are automatically created by Laravel migrations:
-- - migrations: Tracks database migrations
-- - password_reset_tokens: Password reset tokens
-- - sessions: Session management
-- - cache: Cache system
-- - cache_locks: Cache lock system
-- - jobs: Job queue system
-- - job_batches: Job batch management
-- - failed_jobs: Failed job tracking

-- =====================================================
-- SAMPLE DATA (Via DatabaseSeeder)
-- =====================================================
-- 8 REGIONS: REGION-01 to REGION-08 (1 Headquarters, 1 Branch, 6 Mining Locations)
-- 8 DEPARTMENTS: DEPT-01 to DEPT-08 across regions
-- 7 USERS: 1 Admin, 2 Approvers (2-level hierarchy), 4 Regular Staff
-- 6 VEHICLES: 4 Company-owned (2 passenger + 2 cargo), 2 Rental

-- =====================================================
-- USER CREDENTIALS FOR TESTING
-- =====================================================
-- Admin User: admin@example.com / password123
-- Manager (Approver L2): manager@example.com / password123  
-- Supervisor (Approver L1): supervisor@example.com / password123
-- Staff Users: usera@example.com, userb@example.com, userc@example.com, userd@example.com / password123
