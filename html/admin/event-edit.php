<?php
require_once __DIR__ . "/includes/bootstrap.php";
$eventId = filter_var($_GET["id"] ?? $_POST["event_id"] ?? null, FILTER_VALIDATE_INT);
if (!$eventId) { http_response_code(400); exit("Choose a valid event."); }

$statement = $connection->prepare(
    "SELECT event_id, title, event_date, event_time, venue, category,
            organizer, description, image
     FROM events WHERE event_id = ? LIMIT 1"
);
$statement->bind_param("i", $eventId);
$statement->execute();
$eventData = $statement->get_result()->fetch_assoc();
$statement->close();
if (!$eventData) { http_response_code(404); exit("Event not found."); }

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    adminRequireCsrf();
    $eventData = array_merge($eventData, adminEventInput());
    $errors = adminEventErrors($eventData);
    $newPoster = null;
    if (!$errors) {
        try {
            $newPoster = adminStoreEventPoster($_FILES["poster"] ?? []);
        } catch (RuntimeException $exception) {
            $errors[] = $exception->getMessage();
        }
    }
    if (!$errors) {
        try {
            $image = $newPoster ?? $eventData["image"];
            $update = $connection->prepare(
                "UPDATE events
                 SET title = ?, event_date = ?, event_time = ?, venue = ?,
                     category = ?, organizer = ?, description = ?, image = ?
                 WHERE event_id = ?"
            );
            $update->bind_param(
                "ssssssssi",
                $eventData["title"], $eventData["event_date"],
                $eventData["event_time"], $eventData["venue"],
                $eventData["category"], $eventData["organizer"],
                $eventData["description"], $image, $eventId
            );
            $update->execute();
            $update->close();
            adminFlash("Event updated successfully.");
            adminRedirect("event-view.php?id=" . $eventId);
        } catch (mysqli_sql_exception $exception) {
            error_log("Event update failed: " . $exception->getMessage());
            $errors[] = "Event could not be updated.";
        }
    }
}

$isEdit = true;
$pageTitle = "Edit Event";
require __DIR__ . "/includes/header.php";
if ($errors): ?><ul class="admin-errors"><?php foreach ($errors as $error): ?>
    <li><?= adminEscape($error) ?></li>
<?php endforeach; ?></ul><?php endif;
require __DIR__ . "/includes/event-form.php";
require __DIR__ . "/includes/footer.php";
