-- StudentHub registration schema.
-- Run this file against the studenthub database before using html/register.html.

CREATE DATABASE IF NOT EXISTS studenthub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE studenthub;

CREATE TABLE IF NOT EXISTS student (
    student_id VARCHAR(100) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    username VARCHAR(110) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile_no VARCHAR(10) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    course VARCHAR(100) NOT NULL DEFAULT '',
    academic_year VARCHAR(30) NOT NULL DEFAULT '',
    password VARCHAR(255) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id),
    UNIQUE KEY uq_student_email (email),
    UNIQUE KEY uq_student_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- For an existing legacy database, apply 001_profile_and_contact.sql and
-- 005_student_username.sql before this migration. Those migrations add the
-- same columns and username constraint to the original student table.
