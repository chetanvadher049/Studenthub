<?php
require_once __DIR__ . "/../html/admin/includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");

function apiResponse(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function apiInput(): array
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        return adminEventInput();
    }

    $raw = file_get_contents("php://input");
    $data = json_decode($raw ?: "{}", true);
    return is_array($data) ? array_map(
        static fn ($value): string => is_string($value) ? trim($value) : "",
        $data
    ) : [];
}

function apiEvent(array $row): array
{
    return [
        "id" => (int) $row["event_id"],
        "title" => $row["title"],
        "event_date" => $row["event_date"],
        "event_time" => $row["event_time"],
        "venue" => $row["venue"],
        "category" => $row["category"],
        "organizer" => $row["organizer"],
        "description" => $row["description"],
        "image" => $row["image"],
    ];
}

try {
    $admin = requireAdmin();
    $method = $_SERVER["REQUEST_METHOD"];
    $input = [];
    $override = $_POST["_method"] ?? "";
    if ($method === "POST" && is_string($override) && $override !== "") {
        $method = strtoupper($override);
    }

    if ($method === "GET") {
        $search = trim((string) ($_GET["q"] ?? ""));
        $category = trim((string) ($_GET["category"] ?? ""));
        $conditions = [];
        $values = [];
        $types = "";

        if ($search !== "") {
            $conditions[] = "(title LIKE ? OR description LIKE ? OR venue LIKE ?)";
            $term = "%{$search}%";
            $values = [$term, $term, $term];
            $types = "sss";
        }
        if ($category !== "") {
            $conditions[] = "category = ?";
            $values[] = $category;
            $types .= "s";
        }
        $where = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";
        $eventId = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
        if ($eventId) {
            $conditions[] = "event_id = ?";
            $values[] = $eventId;
            $types .= "i";
            $where = " WHERE " . implode(" AND ", $conditions);
        }
        $statement = $connection->prepare(
            "SELECT event_id, title, event_date, event_time, venue, category,
                    organizer, description, image
             FROM events" . $where . " ORDER BY event_date DESC, event_id DESC"
        );
        if ($types !== "") {
            $statement->bind_param($types, ...$values);
        }
        $statement->execute();
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        apiResponse(["events" => array_map("apiEvent", $rows)]);
    }

    $csrf = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? ($_POST["csrf_token"] ?? null);
    if (!verifyCsrfToken($csrf)) {
        apiResponse(["error" => "Your request token expired. Reload the page."], 403);
    }

    if ($method === "POST") {
        $input = apiInput();
        $errors = adminEventErrors($input);
        $image = null;
        if (!$errors) {
            try {
                $image = adminStoreEventPoster($_FILES["poster"] ?? []);
            } catch (RuntimeException $exception) {
                $errors[] = $exception->getMessage();
            }
        }
        if ($errors) {
            apiResponse(["errors" => $errors], 422);
        }
        $nextIdResult = $connection->query(
            "SELECT COALESCE(MAX(event_id), 0) + 1 AS next_id FROM events"
        );
        $eventId = (int) $nextIdResult->fetch_assoc()["next_id"];
        $nextIdResult->free();
        $statement = $connection->prepare(
            "INSERT INTO events
             (event_id, title, event_date, event_time, venue, category,
              organizer, description, image)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $statement->bind_param(
            "issssssss",
            $eventId,
            $input["title"], $input["event_date"], $input["event_time"],
            $input["venue"], $input["category"], $input["organizer"],
            $input["description"], $image
        );
        $statement->execute();
        $statement->close();
        apiResponse(["message" => "Event created successfully.", "id" => $eventId], 201);
    }

    $eventId = filter_var(
        $_GET["id"] ?? $_POST["event_id"] ?? ($input["id"] ?? null),
        FILTER_VALIDATE_INT
    );
    if (!$eventId) {
        apiResponse(["error" => "A valid event ID is required."], 400);
    }

    if ($method === "PUT") {
        $input = apiInput();
        $statement = $connection->prepare(
            "SELECT event_id, title, event_date, event_time, venue, category,
                    organizer, description, image
             FROM events WHERE event_id = ? LIMIT 1"
        );
        $statement->bind_param("i", $eventId);
        $statement->execute();
        $current = $statement->get_result()->fetch_assoc();
        $statement->close();
        if (!$current) {
            apiResponse(["error" => "Event not found."], 404);
        }
        $input = array_merge($current, $input);
        $errors = adminEventErrors($input);
        $image = $current["image"];
        if (!$errors) {
            try {
                $image = adminStoreEventPoster($_FILES["poster"] ?? []) ?? $image;
            } catch (RuntimeException $exception) {
                $errors[] = $exception->getMessage();
            }
        }
        if ($errors) {
            apiResponse(["errors" => $errors], 422);
        }
        $statement = $connection->prepare(
            "UPDATE events
             SET title = ?, event_date = ?, event_time = ?, venue = ?,
                 category = ?, organizer = ?, description = ?, image = ?
             WHERE event_id = ?"
        );
        $statement->bind_param(
            "ssssssssi",
            $input["title"], $input["event_date"], $input["event_time"],
            $input["venue"], $input["category"], $input["organizer"],
            $input["description"], $image, $eventId
        );
        $statement->execute();
        $statement->close();
        apiResponse(["message" => "Event updated successfully."]);
    }

    if ($method === "DELETE") {
        $statement = $connection->prepare("DELETE FROM events WHERE event_id = ?");
        $statement->bind_param("i", $eventId);
        $statement->execute();
        $deleted = $statement->affected_rows > 0;
        $statement->close();
        apiResponse(
            ["message" => $deleted ? "Event deleted successfully." : "Event not found."],
            $deleted ? 200 : 404
        );
    }

    apiResponse(["error" => "Unsupported request method."], 405);
} catch (mysqli_sql_exception | JsonException $exception) {
    error_log("StudentHub event API failed: " . $exception->getMessage());
    apiResponse(["error" => "The event operation could not be completed."], 500);
}
