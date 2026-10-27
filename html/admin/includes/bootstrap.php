<?php

require_once __DIR__ . "/../../../php/auth.php";

$admin = requireAdmin();
$connection = studenthub_db("studenthub");

function adminEscape($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function adminRequirePost(): void
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        header("Allow: POST");
        exit("This action requires a POST request.");
    }
}

function adminRequireCsrf(): void
{
    if (!verifyCsrfToken($_POST["csrf_token"] ?? null)) {
        http_response_code(403);
        exit("Your form expired. Reload the page and try again.");
    }
}

function adminAudit(mysqli $connection, array $admin, string $action, string $entity, ?int $entityId): void
{
    $details = json_encode(["action" => $action], JSON_THROW_ON_ERROR);
    $statement = $connection->prepare(
        "INSERT INTO audit_logs (actor_type, actor_id, action, entity_type, entity_id, details)
         VALUES ('admin', ?, ?, ?, ?, ?)"
    );
    $statement->bind_param("issis", $admin["id"], $action, $entity, $entityId, $details);
    $statement->execute();
    $statement->close();
}

function adminFlash(string $message, string $type = "success"): void
{
    $_SESSION["admin_flash"] = ["message" => $message, "type" => $type];
}

function adminRedirect(string $path): void
{
    header("Location: " . $path);
    exit;
}

function adminStudentInput(): array
{
    $read = static function (string $key): string {
        $value = $_POST[$key] ?? "";
        return is_string($value) ? trim($value) : "";
    };

    return [
        "full_name" => $read("full_name"),
        "username" => $read("username"),
        "email" => $read("email"),
        "phone" => $read("phone"),
        "course" => $read("course"),
        "enrollment_number" => $read("enrollment_number"),
        "gender" => $read("gender"),
        "academic_year" => $read("academic_year"),
        "status" => $read("status"),
        "password" => is_string($_POST["password"] ?? null) ? $_POST["password"] : "",
    ];
}

function adminStudentErrors(array $student, bool $passwordRequired): array
{
    $errors = [];
    if (!preg_match("/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]{1,99}$/u", $student["full_name"])) {
        $errors[] = "Enter a valid full name between 2 and 100 characters.";
    }

    if (!preg_match("/^[A-Za-z0-9_]{3,50}$/", $student["username"])) {
        $errors[] = "Use 3 to 50 letters, numbers, or underscores for the username.";
    }
    if (!filter_var($student["email"], FILTER_VALIDATE_EMAIL) || strlen($student["email"]) > 255) {
        $errors[] = "Enter a valid email address up to 255 characters.";
    }
    if ($student["phone"] !== "" && !preg_match("/^[0-9]{10}$/", $student["phone"])) {
        $errors[] = "Enter a valid 10-digit mobile number.";
    }
    if (strlen($student["course"]) > 100 || strlen($student["enrollment_number"]) > 50) {
        $errors[] = "Course or enrollment number is too long.";
    }
    if (!in_array($student["status"], ["active", "inactive"], true)) {
        $errors[] = "Choose a valid student status.";
    }
    if ($passwordRequired || $student["password"] !== "") {
        if (
            strlen($student["password"]) < 8 ||
            strlen($student["password"]) > 100 ||
            !preg_match("/[a-z]/", $student["password"]) ||
            !preg_match("/[A-Z]/", $student["password"]) ||
            !preg_match("/[0-9]/", $student["password"]) ||
            !preg_match("/[@$!%*?&]/", $student["password"])
        ) {
            $errors[] = "Use a password of 8–100 characters with uppercase, lowercase, a number, and one of @$!%*?&.";
        }
    }

    return $errors;
}

function adminEventInput(): array
{
    $read = static function (string $key): string {
        $value = $_POST[$key] ?? "";
        return is_string($value) ? trim($value) : "";
    };

    return [
        "title" => $read("title"),
        "event_date" => $read("event_date"),
        "event_time" => $read("event_time"),
        "venue" => $read("venue"),
        "category" => $read("category"),
        "organizer" => $read("organizer"),
        "description" => $read("description"),
    ];
}

function adminEventErrors(array $event): array
{
    $errors = [];
    if ($event["title"] === "" || strlen($event["title"]) > 150) {
        $errors[] = "Title is required and must be 150 characters or fewer.";
    }
    $date = DateTime::createFromFormat("Y-m-d", $event["event_date"]);
    $dateErrors = DateTime::getLastErrors();
    if (
        !$date
        || ($dateErrors !== false && (
            $dateErrors["warning_count"] > 0
            || $dateErrors["error_count"] > 0
        ))
        || ($date && $date->format("Y-m-d") !== $event["event_date"])
    ) {
        $errors[] = "Enter a valid event date.";
    }
    if ($event["event_time"] === "" || strlen($event["event_time"]) > 20) {
        $errors[] = "Enter a valid event time.";
    }
    if ($event["venue"] === "" || strlen($event["venue"]) > 150) {
        $errors[] = "Venue is required and must be 150 characters or fewer.";
    }
    if ($event["category"] === "" || strlen($event["category"]) > 50) {
        $errors[] = "Category is required and must be 50 characters or fewer.";
    }
    if (strlen($event["organizer"]) > 150) {
        $errors[] = "Organizer must be 150 characters or fewer.";
    }
    if ($event["description"] === "") {
        $errors[] = "Description is required.";
    }
    return $errors;
}

function adminAuditSafe(mysqli $connection, array $admin, string $action, string $entity, ?int $entityId): void
{
    try {
        adminAudit($connection, $admin, $action, $entity, $entityId);
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub admin audit write failed: " . $exception->getMessage());
    }
}

function adminStoreEventPoster(array $upload): ?string
{
    if (($upload["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($upload["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($upload["size"] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException("Choose an image smaller than 5 MB.");
    }

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($upload["tmp_name"]);
    $extensions = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp",
    ];
    if (!isset($extensions[$mimeType])) {
        throw new RuntimeException("Poster must be a JPEG, PNG, or WebP image.");
    }

    $fileName = bin2hex(random_bytes(16)) . "." . $extensions[$mimeType];
    $directory = dirname(__DIR__, 2) . "/uploads/events";
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException("Poster storage is unavailable.");
    }
    if (!move_uploaded_file($upload["tmp_name"], $directory . "/" . $fileName)) {
        throw new RuntimeException("Poster could not be saved.");
    }

    return "uploads/events/" . $fileName;
}
