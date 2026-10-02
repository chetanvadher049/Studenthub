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
    $path = __DIR__ . "/../html/json/events.json";
    $contents = file_get_contents($path);
    if ($contents === false) {
        error_log("StudentHub event data file could not be read.");
        eventResponse(503, ["error" => "Event registration is unavailable."]);
    }

    try {
        $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        error_log("StudentHub event data JSON is invalid: " . $exception->getMessage());
        eventResponse(503, ["error" => "Event registration is unavailable."]);
    }

    foreach ($data["events"] ?? [] as $event) {
        if ((int) ($event["id"] ?? 0) === $eventId) {
            return $event;
        }
    }

    return null;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["action"] ?? "") === "token") {
    if (empty($_SESSION["studenthub_csrf"])) {
        $_SESSION["studenthub_csrf"] = bin2hex(random_bytes(32));
    }

    eventResponse(200, ["csrfToken" => $_SESSION["studenthub_csrf"]]);
}

if (empty($_SESSION["logged_in"]) || !is_string($_SESSION["student_id"] ?? null)) {
    eventResponse(401, ["error" => "Sign in to register for an event."]);
}

$studentId = $_SESSION["student_id"];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $eventId = filter_input(INPUT_GET, "event_id", FILTER_VALIDATE_INT);
    if (!$eventId || !findEvent($eventId)) {
        eventResponse(404, ["error" => "This event could not be found."]);
    }

    try {
        $connection = studenthub_db();
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
        $connection->close();
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
if (stripos($contentType, "application/json") !== 0) {
    eventResponse(415, ["error" => "Send event registrations as JSON."]);
}

$token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
if (
    !is_string($token) ||
    !isset($_SESSION["studenthub_csrf"]) ||
    !hash_equals($_SESSION["studenthub_csrf"], $token)
) {
    eventResponse(403, ["error" => "Refresh the events page and try again."]);
}

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

$eventId = filter_var(
    is_array($payload) ? ($payload["eventId"] ?? null) : null,
    FILTER_VALIDATE_INT
);
$event = $eventId ? findEvent($eventId) : null;
if (!$event) {
    eventResponse(404, ["error" => "This event could not be found."]);
}

$eventDate = $event["date"] ?? "";
$title = $event["title"] ?? "";
if (
    !is_string($eventDate) ||
    !preg_match("/^\\d{4}-\\d{2}-\\d{2}$/", $eventDate) ||
    $eventDate < date("Y-m-d") ||
    !is_string($title) ||
    strlen($title) > 150
) {
    eventResponse(410, ["error" => "Registration for this event is closed."]);
}

try {
    $connection = studenthub_db();
    $statement = $connection->prepare(
        "INSERT INTO event_registrations
            (student_id, event_id, event_title, event_date)
         VALUES (?, ?, ?, ?)"
    );
    $statement->bind_param("siss", $studentId, $eventId, $title, $eventDate);
    $statement->execute();
    $statement->close();
    $connection->close();
    eventResponse(201, ["registered" => true]);
} catch (mysqli_sql_exception $exception) {
    if ((int) $exception->getCode() === 1062) {
        eventResponse(409, ["registered" => true, "alreadyRegistered" => true]);
    }

    error_log("StudentHub event registration failed: " . $exception->getMessage());
    eventResponse(500, ["error" => "Could not register for this event."]);
}
