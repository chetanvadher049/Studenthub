<?php

require_once __DIR__ . "/dbconnect.php";

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Allow: GET");
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method."]);
    exit;
}

try {
    $connection = studenthub_db("studenthub");
    $result = $connection->query(
        "SELECT event_id AS id,
                title,
                DATE_FORMAT(event_date, '%Y-%m-%d') AS date,
                event_time AS time,
                venue,
                category,
                organizer,
                description,
                image,
                'upcoming' AS status
         FROM events
         WHERE event_date >= CURRENT_DATE
         ORDER BY event_date, event_id"
    );
    $events = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($events as &$event) {
        $event["id"] = (int) $event["id"];
    }
    unset($event);
    $result->free();
    $connection->close();

    echo json_encode(
        ["events" => $events],
        JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
    );
} catch (mysqli_sql_exception | JsonException $exception) {
    error_log("StudentHub event catalog request failed: " . $exception->getMessage());
    http_response_code(503);
    echo json_encode(["error" => "Events could not be loaded right now."]);
}
