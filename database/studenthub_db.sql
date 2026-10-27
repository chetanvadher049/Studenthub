-- StudentHub database schema
-- Safe setup for MySQL / MariaDB with MySQLi usage in the PHP project.
--
-- Backup before applying this file to an existing environment:
--   mysqldump -u root -p --single-transaction --routines --events studenthub_db > studenthub_db_backup.sql
-- 
-- Then import this file in phpMyAdmin or from the MySQL CLI:
--   mysql -u root -p studenthub_db < database/studenthub_db.sql
--
-- Important: the legacy project currently uses different table names and several
-- legacy UUID-based values. This schema intentionally creates the canonical
-- project tables for the new StudentHub database and avoids hard-deleting old data.
-- The safe approach is to back up the database first and then apply this file to a
-- fresh studenthub_db database or a clean staging instance.

CREATE DATABASE IF NOT EXISTS studenthub_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE studenthub_db;

-- ------------------------------------------------------------------------------------------------------------------
-- students
-- ------------------------------------------------------------------------------------------------------------------
-- Safe deletion policy:
--   - Do not hard-delete records when historical registrations or audit records may exist.
--   - Prefer setting status = 'inactive' for students, and keep registrations intact.
-- ------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    gender VARCHAR(10) NULL,
    course VARCHAR(100) NULL,
    academic_year VARCHAR(30) NULL,
    enrollment_number VARCHAR(50) NULL,
    profile_image VARCHAR(255) NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_students_username (username),
    UNIQUE KEY uq_students_email (email),
    UNIQUE KEY uq_students_enrollment_number (enrollment_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------------------------------------------
-- admins
-- ------------------------------------------------------------------------------------------------------------------
-- Safe deletion policy:
--   - Prefer setting status = 'inactive' instead of deleting admins.
--   - Maintain audit trail for admin actions and keep historical records intact.
-- ------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') NOT NULL DEFAULT 'admin',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_admins_username (username),
    UNIQUE KEY uq_admins_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------------------------------------------
-- events
-- ------------------------------------------------------------------------------------------------------------------
-- Safe deletion policy:
--   - Events should not be physically deleted while registrations exist.
--   - Preferred action: set status = 'cancelled' and keep historical registration records.
--   - `created_by` references admins(id) and is nullable to avoid accidental breakage.
-- ------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS events (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    category VARCHAR(100) NULL,
    organizer VARCHAR(150) NULL,
    poster_path VARCHAR(255) NULL,
    created_by INT UNSIGNED NULL,
    status ENUM('upcoming', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_events_event_date (event_date),
    KEY idx_events_status (status),
    KEY idx_events_category (category),
    CONSTRAINT fk_events_created_by
        FOREIGN KEY (created_by) REFERENCES admins (id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------------------------------------------
-- registrations
-- ------------------------------------------------------------------------------------------------------------------
-- Safe deletion policy:
--   - Do not silently delete registrations. Keep historical row data and use status = 'cancelled' instead.
--   - Foreign keys use RESTRICT to block accidental child-data loss.
-- ------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS registrations (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    event_id INT UNSIGNED NOT NULL,
    registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'cancelled') NOT NULL DEFAULT 'registered',
    PRIMARY KEY (id),
    UNIQUE KEY uq_registrations_student_event (student_id, event_id),
    KEY idx_registrations_student (student_id),
    KEY idx_registrations_event (event_id),
    KEY idx_registrations_status (status),
    CONSTRAINT fk_registrations_student
        FOREIGN KEY (student_id) REFERENCES students (id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_registrations_event
        FOREIGN KEY (event_id) REFERENCES events (id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------------------------------------------
-- remember_tokens
-- ------------------------------------------------------------------------------------------------------------------
-- Optional but recommended for secure “remember me” login flows.
-- Tokens are stored as selector + hash pair and never as raw login tokens.
-- ------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS remember_tokens (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_type ENUM('student', 'admin') NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    selector VARCHAR(32) NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_remember_tokens_selector (selector),
    KEY idx_remember_tokens_user (user_type, user_id),
    KEY idx_remember_tokens_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------------------------------------------
-- audit_logs
-- ------------------------------------------------------------------------------------------------------------------
-- Recommended to track important admin activity without storing plain-text credentials,
-- raw tokens, or session IDs. Keep this table explicit and easy to query.
-- ------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    actor_type ENUM('student', 'admin', 'system') NOT NULL,
    actor_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NOT NULL,
    entity_id INT UNSIGNED NULL,
    details JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_audit_logs_actor (actor_type, actor_id),
    KEY idx_audit_logs_entity (entity_type, entity_id),
    KEY idx_audit_logs_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
    attempt_key CHAR(64) NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    window_started_at DATETIME NOT NULL,
    locked_until DATETIME NULL,
    PRIMARY KEY (attempt_key),
    KEY idx_login_attempts_locked_until (locked_until)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
    message_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (message_id),
    KEY idx_contact_messages_time_stamp (time_stamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------------------------------------------
-- Example safe migration notes for older environments
-- ------------------------------------------------------------------------------------------------------------------
-- If the project already contains legacy tables (student, event_registrations, etc.), do not run destructive ALTER
-- statements automatically. Instead:
--   1. Back up the database with mysqldump.
--   2. Create the new studenthub_db database.
--   3. Import this file into the clean database.
--   4. Move any required legacy data only with explicit, tested INSERT/UPDATE statements.
--   5. Keep original records intact until the new schema is validated.
--
-- This avoids duplicate table creation and protects historical registration data.
