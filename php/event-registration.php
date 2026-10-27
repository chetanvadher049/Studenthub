<?php

require_once __DIR__ . "/auth.php";

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");
studenthub_start_session();

function eventResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function findEvent(int $eventId): ?array
{
    try {
        $connection = studenthub_db("studenthub");
        $statement = $connection->prepare(
            "SELECT event_id AS id, title, event_date AS date,
                    event_time AS time, venue, category, organizer,
                    description, image,
                    'upcoming' AS status
             FROM events
             WHERE event_id = ? AND event_date >= CURRENT_DATE
             LIMIT 1"
        );
        $statement->bind_param("i", $eventId);
        $statement->execute();
        $event = $statement->get_result()->fetch_assoc();
        $statement->close();

        return $event ?: null;
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub event catalog lookup failed: " . $exception->getMessage());
        eventResponse(503, ["error" => "Event registration is unavailable."]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["action"] ?? "") === "token") {
    eventResponse(200, ["csrfToken" => csrfToken()]);
}

if (!isAuthenticated()) {
    eventResponse(401, ["error" => "Sign in to register for an event."]);
}
if (currentUser()["type"] !== "student") {
    eventResponse(403, ["error" => "Only students can register for events."]);
}

$student = requireStudent();
$studentId = (int) $student["student_id"];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $eventId = filter_input(INPUT_GET, "event_id", FILTER_VALIDATE_INT);
    if (!$eventId || !findEvent($eventId)) {
        eventResponse(404, ["error" => "This event could not be found."]);
    }

    try {
        $connection = studenthub_db("studenthub");
        $statement = $connection->prepare(
            "SELECT registration_id
             FROM event_registrations
             WHERE student_id = ? AND event_id = ?
             LIMIT 1"
        );
        $statement->bind_param("si", $studentId, $eventId);
        $statement->execute();
        $statement->store_result();
        $registered = $statement->num_rows > 0;
        $statement->close();
        eventResponse(200, ["registered" => $registered]);
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub event registration lookup failed: " . $exception->getMessage());
        eventResponse(500, ["error" => "Could not check event registration."]);
    }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Allow: GET, POST");
    eventResponse(405, ["error" => "Unsupported request method."]);
}

$contentType = $_SERVER["CONTENT_TYPE"] ?? "";
if (
    stripos($contentType, "application/json") !== 0 &&
    stripos($contentType, "application/x-www-form-urlencoded") !== 0
) {
    eventResponse(415, ["error" => "Submit the event registration form."]);
}

$isFormSubmission =
    stripos($contentType, "application/x-www-form-urlencoded") === 0;
$token = $isFormSubmission
    ? ($_POST["csrf_token"] ?? "")
    : ($_SERVER["HTTP_X_CSRF_TOKEN"] ?? "");
if (!verifyCsrfToken($token)) {
    if ($isFormSubmission) {
        eventFormResponse(
            403,
            "Registration form expired",
            "Return to the event and open a fresh registration form."
        );
    }
    eventResponse(403, ["error" => "Refresh the events page and try again."]);
}

if ($isFormSubmission) {
    $payload = $_POST;
} else {
    try {
        $payload = json_decode(
            file_get_contents("php://input"),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    } catch (JsonException $exception) {
        eventResponse(400, ["error" => "The registration request was not valid JSON."]);
    }
}

$eventId = filter_var(
    is_array($payload)
        ? ($payload[$isFormSubmission ? "event_id" : "eventId"] ?? null)
        : null,
    FILTER_VALIDATE_INT
);
$event = $eventId ? findEvent($eventId) : null;
if (!$event) {
    if ($isFormSubmission) {
        eventFormResponse(404, "Event not found", "This event could not be found.");
    }
    eventResponse(404, ["error" => "This event could not be found."]);
}

$eventDate = $event["date"] ?? "";
if (
    !is_string($eventDate) ||
    !preg_match("/^\\d{4}-\\d{2}-\\d{2}$/", $eventDate) ||
    $eventDate < date("Y-m-d") ||
    ($event["status"] ?? "") !== "upcoming"
) {
    if ($isFormSubmission) {
        eventFormResponse(410, "Registration closed", "Registration for this event is closed.");
    }
    eventResponse(410, ["error" => "Registration for this event is closed."]);
}

try {
    $connection = studenthub_db("studenthub");
    $statement = $connection->prepare(
        "INSERT INTO event_registrations
            (student_id, student_name, student_email, mobile_no,
             event_id, event_title, event_date, registration_status)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'registered')"
    );
    $studentName = (string) $student["student_name"];
    $studentEmail = (string) $student["email"];
    $studentMobile = (string) $student["mobile_no"];
    $eventTitle = (string) $event["title"];
    $eventDate = (string) $event["date"];
    $statement->bind_param(
        "ssssiss",
        $studentId,
        $studentName,
        $studentEmail,
        $studentMobile,
        $eventId,
        $eventTitle,
        $eventDate
    );
    $statement->execute();
    $statement->close();
    if ($isFormSubmission) {
        eventFormResponse(
            201,
            "Registration complete",
            "You are registered for " . $event["title"] . ". A confirmation has been saved to your account."
        );
    }
    eventResponse(201, ["registered" => true]);
} catch (mysqli_sql_exception $exception) {
    if ((int) $exception->getCode() === 1062) {
        if ($isFormSubmission) {
            eventFormResponse(
                409,
                "Already registered",
                "Your account is already registered for this event."
            );
        }
        eventResponse(409, ["registered" => true, "alreadyRegistered" => true]);
    }

    error_log("StudentHub event registration failed: " . $exception->getMessage());
    if ($isFormSubmission) {
        eventFormResponse(
            500,
            "Registration could not be saved",
            "We could not save your registration right now. Please try again later."
        );
    }
    eventResponse(500, ["error" => "Could not register for this event."]);
}

function eventFormResponse(int $status, string $title, string $message): void
{
    header("Content-Type: text/html; charset=utf-8");
    http_response_code($status);
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, "UTF-8");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $safeTitle ?> - StudentHub</title>
        <link rel="stylesheet" href="../html/css/events.css">
        <link rel="stylesheet" href="../html/css/shared.css">
    </head>
    <body>
        <main class="registration-page">
            <section class="registration-card" role="<?= $status >= 400 ? "alert" : "status" ?>">
                <h1><?= $safeTitle ?></h1>
                <p><?= $safeMessage ?></p>
                <a class="registration-back-link" href="../html/events.html">Back to events</a>
            </section>
        </main>
    </body>
    </html>
    <?php
    exit;
}
