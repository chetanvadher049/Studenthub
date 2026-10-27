<?php
require_once __DIR__ . "/includes/bootstrap.php";
$eventId = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$eventId) { http_response_code(400); exit("Choose a valid event."); }

$statement = $connection->prepare(
    "SELECT event_id, title, event_date, event_time, venue, category,
            organizer, description, image
     FROM events WHERE event_id = ? LIMIT 1"
);
$statement->bind_param("i", $eventId);
$statement->execute();
$event = $statement->get_result()->fetch_assoc();
$statement->close();
if (!$event) { http_response_code(404); exit("Event not found."); }

$pageTitle = "Event Details";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-card">
    <div class="admin-actions">
        <a class="admin-button" href="event-edit.php?id=<?= (int) $event["event_id"] ?>">Edit event</a>
        <a class="admin-button admin-button--muted" href="events.php">Back to events</a>
    </div>
    <?php if ($event["image"]): ?>
        <p><img src="../<?= adminEscape($event["image"]) ?>"
                alt="<?= adminEscape($event["title"]) ?>"
                style="max-width:100%;max-height:320px"></p>
    <?php endif; ?>
    <h2><?= adminEscape($event["title"]) ?></h2>
    <p><?= nl2br(adminEscape($event["description"])) ?></p>
    <dl class="admin-details">
        <dt>Date</dt><dd><?= adminEscape($event["event_date"]) ?></dd>
        <dt>Time</dt><dd><?= adminEscape($event["event_time"]) ?></dd>
        <dt>Venue</dt><dd><?= adminEscape($event["venue"]) ?></dd>
        <dt>Category</dt><dd><?= adminEscape($event["category"]) ?></dd>
        <dt>Organizer</dt><dd><?= adminEscape($event["organizer"]) ?></dd>
    </dl>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
