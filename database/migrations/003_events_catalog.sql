CREATE TABLE IF NOT EXISTS events (
    event_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    event_time VARCHAR(20) NOT NULL,
    venue VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    organizer VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (event_id),
    KEY idx_events_date (event_date),
    KEY idx_events_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO events
    (event_id, title, event_date, event_time, venue, category, organizer, description, image)
VALUES
    (1, 'Web Development Workshop', '2026-09-20', '10:00 AM', 'Computer Lab 1', 'Workshop', 'Computer Science Department', 'Learn HTML, CSS and JavaScript fundamentals by building a responsive website.', 'workshop.jpg'),
    (2, 'Coding Competition', '2026-09-22', '11:00 AM', 'Programming Lab', 'Competition', 'Coding Club', 'Test your programming skills by solving challenging coding problems.', NULL),
    (3, 'Cyber Security Awareness Seminar', '2026-09-24', '02:00 PM', 'Seminar Hall', 'Seminar', 'Cyber Security Club', 'Understand common cyber threats and learn how to protect personal information online.', NULL),
    (4, 'Annual Sports Meet', '2026-09-27', '09:00 AM', 'College Ground', 'Sports', 'Sports Committee', 'Participate in exciting indoor and outdoor sports competitions.', 'sport.webp'),
    (5, 'AI and Machine Learning Workshop', '2026-09-29', '10:30 AM', 'AI Lab', 'Workshop', 'AI Club', 'Explore the fundamentals of artificial intelligence and machine learning.', NULL),
    (6, 'Photography Contest', '2026-10-01', '01:00 PM', 'Activity Center', 'Competition', 'Photography Club', 'Showcase your creativity and photography skills in this campus-wide contest.', NULL),
    (7, 'Career Guidance Session', '2026-10-03', '11:30 AM', 'Auditorium', 'Career', 'Training and Placement Cell', 'Get useful career guidance and learn how to prepare for campus placements.', NULL),
    (8, 'Hackathon 2026', '2026-10-05', '09:00 AM', 'Innovation Center', 'Competition', 'Innovation Club', 'Build innovative technology solutions within a limited time during this exciting hackathon.', 'hackathon.jpg'),
    (9, 'Cloud Computing Seminar', '2026-10-08', '02:30 PM', 'Seminar Hall 2', 'Seminar', 'Cloud Computing Club', 'Learn about cloud computing, virtualization and modern cloud platforms.', NULL),
    (10, 'Cultural Festival', '2026-10-10', '05:00 PM', 'Main Auditorium', 'Cultural', 'Cultural Committee', 'Enjoy music, dance, drama and other cultural performances by students.', NULL),
    (11, 'Resume Building Workshop', '2026-10-13', '10:00 AM', 'Placement Room', 'Career', 'Placement Cell', 'Learn how to create an effective resume that attracts recruiters.', NULL),
    (12, 'Robotics Exhibition', '2026-10-15', '11:00 AM', 'Engineering Block', 'Exhibition', 'Robotics Club', 'Explore innovative robotics projects developed by students.', NULL),
    (13, 'Entrepreneurship Talk', '2026-10-18', '03:00 PM', 'Conference Hall', 'Seminar', 'Entrepreneurship Cell', 'Successful entrepreneurs share their startup experiences and business knowledge.', NULL),
    (14, 'Quiz Competition', '2026-10-20', '12:00 PM', 'Lecture Hall 5', 'Competition', 'Student Council', 'Participate in an exciting general knowledge and technology quiz competition.', NULL),
    (15, 'Freshers Welcome Event', '2026-10-22', '04:00 PM', 'College Auditorium', 'Cultural', 'Student Council', 'Welcome new students with fun activities, performances and interactive sessions.', NULL)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    event_date = VALUES(event_date),
    event_time = VALUES(event_time),
    venue = VALUES(venue),
    category = VALUES(category),
    organizer = VALUES(organizer),
    description = VALUES(description),
    image = VALUES(image);
