<?php

if (PHP_SAPI !== "cli") {
    http_response_code(404);
    exit;
}

require_once __DIR__ . "/../php/dbconnect.php";

$options = array_slice($argv, 1);
$importStudents = in_array("--students", $options, true);
$importEvents = in_array("--events", $options, true);
$dryRun = in_array("--dry-run", $options, true);
$confirmed = in_array("--yes", $options, true);

if (!$importStudents && !$importEvents) {
    fwrite(STDERR, "Select --students and/or --events. Use --dry-run first; imports require --yes." . PHP_EOL);
    exit(2);
}

try {
    $studentData = null;
    $eventData = null;
    if ($importStudents) {
        $decoded = json_decode(
            file_get_contents(__DIR__ . "/../html/json/students.json"),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        if (!is_array($decoded["students"] ?? null)) {
            throw new RuntimeException("students.json must contain a students array.");
        }
        $studentData = $decoded["students"];
    }
    if ($importEvents) {
        $decoded = json_decode(
            file_get_contents(__DIR__ . "/../html/json/events.json"),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        if (!is_array($decoded["events"] ?? null)) {
            throw new RuntimeException("events.json must contain an events array.");
        }
        $eventData = $decoded["events"];
    }

    $connection = studenthub_db();
    $targets = [];
    if ($importStudents) {
        $targets[] = "students";
    }
    if ($importEvents) {
        $targets[] = "events";
    }
    $existingCounts = [];
    foreach ($targets as $table) {
        $existingCounts[$table] = (int) $connection->query("SELECT COUNT(*) FROM `" . $table . "`")->fetch_row()[0];
    }

    fwrite(STDOUT, "Import preview:" . PHP_EOL);
    if ($importStudents) {
        fwrite(STDOUT, "  JSON student records: " . count($studentData) . " (will be inactive and cannot sign in until an admin activates/resets them)" . PHP_EOL);
    }
    if ($importEvents) {
        fwrite(STDOUT, "  JSON event records: " . count($eventData) . PHP_EOL);
    }
    foreach ($existingCounts as $table => $count) {
        fwrite(STDOUT, "  Existing MySQL " . $table . ": " . $count . PHP_EOL);
        if ($count !== 0) {
            throw new RuntimeException(
                "Import stopped: the " . $table . " table is not empty. Existing database records will not be changed."
            );
        }
    }

    if ($dryRun) {
        fwrite(STDOUT, "Dry run only; no records were written." . PHP_EOL);
        exit(0);
    }
    if (!$confirmed) {
        throw new RuntimeException("No data was written. Re-run with --yes after reviewing this preview.");
    }

    $connection->begin_transaction();

    if ($importStudents) {
        $insertStudent = $connection->prepare(
            "INSERT INTO students
                (full_name, username, email, password_hash, phone, gender, course, academic_year, profile_image, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'inactive')"
        );
        foreach ($studentData as $index => $student) {
            if (!is_array($student)) {
                throw new RuntimeException("Invalid student record at JSON row " . ($index + 1) . ".");
            }
            $id = filter_var($student["id"] ?? ($index + 1), FILTER_VALIDATE_INT);
            $fullName = trim(is_string($student["name"] ?? null) ? $student["name"] : "");
            $email = trim(is_string($student["email"] ?? null) ? $student["email"] : "");
            if (!$id || $id < 1 || $fullName === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException("Invalid student record at JSON row " . ($index + 1) . ".");
            }
            $username = "json_student_" . $id;
            $phone = is_string($student["mobile"] ?? null) ? $student["mobile"] : null;
            $gender = is_string($student["gender"] ?? null) ? $student["gender"] : null;
            $course = is_string($student["course"] ?? null) ? $student["course"] : null;
            $academicYear = is_string($student["year"] ?? null) ? $student["year"] : null;
            $profileImage = is_string($student["profileImage"] ?? null)
                && preg_match('#^(?!/)(?!.*(?:^|/)\.\.(?:/|$))[A-Za-z0-9_./-]{1,255}$#', $student["profileImage"])
                ? $student["profileImage"]
                : null;
            $unusableRandomPassword = bin2hex(random_bytes(32));
            $passwordHash = password_hash($unusableRandomPassword, PASSWORD_DEFAULT);
            unset($unusableRandomPassword);
            $insertStudent->bind_param(
                "sssssssss",
                $fullName,
                $username,
                $email,
                $passwordHash,
                $phone,
                $gender,
                $course,
                $academicYear,
                $profileImage
            );
            $insertStudent->execute();
        }
        $insertStudent->close();
    }

    if ($importEvents) {
        $insertEvent = $connection->prepare(
            "INSERT INTO events
                (title, description, event_date, location, category, organizer, poster_path, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        foreach ($eventData as $index => $event) {
            if (!is_array($event)) {
                throw new RuntimeException("Invalid event record at JSON row " . ($index + 1) . ".");
            }
            $title = trim(is_string($event["title"] ?? null) ? $event["title"] : "");
            $description = trim(is_string($event["description"] ?? null) ? $event["description"] : "");
            $location = trim(is_string($event["venue"] ?? null) ? $event["venue"] : "");
            $category = is_string($event["category"] ?? null) ? $event["category"] : null;
            $organizer = is_string($event["organizer"] ?? null) ? $event["organizer"] : null;
            $date = is_string($event["date"] ?? null) ? $event["date"] : "";
            $time = is_string($event["time"] ?? null) ? $event["time"] : "12:00 AM";
            $eventDate = DateTimeImmutable::createFromFormat("!Y-m-d h:i A", $date . " " . $time);
            if (!$eventDate || $eventDate->format("Y-m-d") !== $date) {
                throw new RuntimeException("Invalid event date/time at JSON row " . ($index + 1) . ".");
            }
            if ($title === "" || $description === "" || $location === "") {
                throw new RuntimeException("Required event fields are missing at JSON row " . ($index + 1) . ".");
            }
            $image = is_string($event["image"] ?? null) ? $event["image"] : "";
            $posterPath = $image !== "" && preg_match('#^[A-Za-z0-9_.-]{1,255}$#', $image)
                ? $image
                : null;
            $status = $eventDate < new DateTimeImmutable() ? "completed" : "upcoming";
            $eventDateString = $eventDate->format("Y-m-d H:i:s");
            $insertEvent->bind_param(
                "ssssssss",
                $title,
                $description,
                $eventDateString,
                $location,
                $category,
                $organizer,
                $posterPath,
                $status
            );
            $insertEvent->execute();
        }
        $insertEvent->close();
    }

    $connection->commit();
    $connection->close();
    fwrite(STDOUT, "Import completed successfully. The JSON source files were not modified." . PHP_EOL);
} catch (JsonException | RuntimeException | mysqli_sql_exception $exception) {
    if (isset($connection) && $connection instanceof mysqli) {
        try {
            $connection->rollback();
        } catch (Throwable $rollbackException) {
            error_log("StudentHub JSON import rollback failed: " . $rollbackException->getMessage());
        }
        $connection->close();
    }
    if ($exception instanceof mysqli_sql_exception) {
        error_log("StudentHub JSON import failed: " . $exception->getMessage());
        fwrite(STDERR, (int) $exception->getCode() === 1062
            ? "Import stopped because database uniqueness constraints found duplicate records."
            : "Import failed. Check the database connection and source data." . PHP_EOL);
        exit(1);
    }
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
