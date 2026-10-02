CREATE TABLE IF NOT EXISTS event_registrations (
    registration_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id VARCHAR(100) NOT NULL,
    event_id INT UNSIGNED NOT NULL,
    event_title VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (registration_id),
    UNIQUE KEY uq_event_registrations_student_event (student_id, event_id),
    KEY idx_event_registrations_event (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
