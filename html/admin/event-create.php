<?php
require_once __DIR__ . "/includes/bootstrap.php";
$eventData = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    adminRequireCsrf();
    $eventData = adminEventInput();
    $errors = adminEventErrors($eventData);
    $posterPath = null;
    if (!$errors) {
        try {
            $posterPath = adminStoreEventPoster($_FILES["poster"] ?? []);
        } catch (RuntimeException $exception) {
            $errors[] = $exception->getMessage();
        }
    }
    if (!$errors) {
        try {
            $statement = $connection->prepare(
                "INSERT INTO events
                 (title, event_date, event_time, venue, category,
                  organizer, description, image)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $statement->bind_param(
                "ssssssss",
                $eventData["title"], $eventData["event_date"],
                $eventData["event_time"], $eventData["venue"],
                $eventData["category"], $eventData["organizer"],
                $eventData["description"], $posterPath
            );
            $statement->execute();
            $eventId = (int) $connection->insert_id;
            $statement->close();
            adminFlash("Event created successfully.");
            adminRedirect("event-view.php?id=" . $eventId);
        } catch (mysqli_sql_exception $exception) {
            error_log("Event creation failed: " . $exception->getMessage());
            $errors[] = "Event could not be created.";
        }
    }
}

$pageTitle = "Add Event";
require __DIR__ . "/includes/header.php";
if ($errors): ?><ul class="admin-errors"><?php foreach ($errors as $error): ?>
    <li><?= adminEscape($error) ?></li>
<?php endforeach; ?></ul><?php endif;
require __DIR__ . "/includes/event-form.php";
require __DIR__ . "/includes/footer.php";
