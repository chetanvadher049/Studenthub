ALTER TABLE event_registrations
    ADD COLUMN student_name VARCHAR(100) NULL AFTER student_id,
    ADD COLUMN student_email VARCHAR(100) NULL AFTER student_name,
    ADD COLUMN mobile_no VARCHAR(10) NULL AFTER student_email,
    ADD COLUMN registration_status ENUM('registered', 'cancelled', 'attended')
        NOT NULL DEFAULT 'registered' AFTER event_date,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL
        ON UPDATE CURRENT_TIMESTAMP AFTER time_stamp;

UPDATE event_registrations AS registration
JOIN student
    ON student.student_id = registration.student_id
SET registration.student_name = student.student_name,
    registration.student_email = student.email,
    registration.mobile_no = student.mobile_no
WHERE registration.student_name IS NULL
   OR registration.student_email IS NULL
   OR registration.mobile_no IS NULL;

ALTER TABLE event_registrations
    ADD KEY idx_event_registrations_status (registration_status);
