ALTER TABLE student
    ADD COLUMN username VARCHAR(110) NULL AFTER student_name;

UPDATE student
SET username = CONCAT('student_', student_id)
WHERE username IS NULL;

ALTER TABLE student
    MODIFY username VARCHAR(110) NOT NULL,
    ADD UNIQUE KEY uq_student_username (username);
