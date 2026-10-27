CREATE TABLE IF NOT EXISTS student (
    student_id VARCHAR(100) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile_no VARCHAR(10) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id),
    UNIQUE KEY uq_student_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
