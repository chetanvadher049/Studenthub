-- Back up studenthub_db before applying this migration.
-- Adds fields required by the existing student registration/profile forms and
-- login throttling. Run this migration once after importing studenthub_db.sql
-- into a database created from an earlier version of that schema.

ALTER TABLE students
    ADD COLUMN gender VARCHAR(10) NULL AFTER phone,
    ADD COLUMN academic_year VARCHAR(30) NULL AFTER course;

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
