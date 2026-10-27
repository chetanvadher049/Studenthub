<?php
require_once __DIR__ . "/includes/bootstrap.php";
adminRequirePost();
adminRequireCsrf();
$eventId = filter_var($_POST["event_id"] ?? null, FILTER_VALIDATE_INT);
if (!$eventId) { http_response_code(400); exit("Choose a valid event."); }

$posterQuery = $connection->prepare(
    "SELECT image FROM events WHERE event_id = ? LIMIT 1"
);
$posterQuery->bind_param("i", $eventId);
$posterQuery->execute();
$poster = $posterQuery->get_result()->fetch_column();
$posterQuery->close();

try {
    $statement = $connection->prepare("DELETE FROM events WHERE event_id = ?");
    $statement->bind_param("i", $eventId);
    $statement->execute();
    $deleted = $statement->affected_rows > 0;
    $statement->close();
    if ($deleted) {
        if (is_string($poster) && str_starts_with($poster, "uploads/events/")) {
            $localPoster = dirname(__DIR__) . "/" . $poster;
            if (is_file($localPoster)) { unlink($localPoster); }
        }
        adminFlash("Event deleted successfully.");
    } else {
        adminFlash("Event not found.", "error");
    }
} catch (mysqli_sql_exception $exception) {
    error_log("Event deletion failed: " . $exception->getMessage());
    adminFlash(
        "Event could not be deleted. Cancel it instead if registrations exist.",
        "error"
    );
}
adminRedirect("events.php");
