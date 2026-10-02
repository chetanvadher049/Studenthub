ALTER TABLE student
    ADD COLUMN course VARCHAR(100) NOT NULL DEFAULT '' AFTER gender,
    ADD COLUMN academic_year VARCHAR(30) NOT NULL DEFAULT '' AFTER course;

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
