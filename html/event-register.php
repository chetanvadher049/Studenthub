<?php
require_once __DIR__ . "/../php/auth.php";

$student = requireStudent();
$eventId = filter_var($_GET["event_id"] ?? null, FILTER_VALIDATE_INT);
if (!$eventId || $eventId < 1) {
    http_response_code(400);
    exit("Select a valid event to register.");
}

try {
    $connection = studenthub_db();
    $eventQuery = $connection->prepare(
        "SELECT id AS event_id, title, event_date,
                DATE_FORMAT(event_date, '%h:%i %p') AS event_time,
                location AS venue, category, organizer, description,
                poster_path AS image, status
         FROM events
         WHERE id = ?
         LIMIT 1"
    );
    $eventQuery->bind_param("i", $eventId);
    $eventQuery->execute();
    $event = $eventQuery->get_result()->fetch_assoc();
    $eventQuery->close();

    if (!$event) {
        $connection->close();
        http_response_code(404);
        exit("This event could not be found.");
    }

    $registrationQuery = $connection->prepare(
        "SELECT status
         FROM registrations
         WHERE student_id = ? AND event_id = ?
         LIMIT 1"
    );
    $studentId = (int) $student["student_id"];
    $registrationQuery->bind_param("ii", $studentId, $eventId);
    $registrationQuery->execute();
    $registration = $registrationQuery->get_result()->fetch_assoc();
    $registrationQuery->close();
    $connection->close();
} catch (mysqli_sql_exception $exception) {
    error_log("StudentHub registration form lookup failed: " . $exception->getMessage());
    http_response_code(503);
    exit("Event registration is temporarily unavailable. Please try again shortly.");
}

$escape = static fn ($value): string => studenthub_escape((string) $value);
$eventDate = new DateTimeImmutable($event["event_date"]);
$isPastEvent = $eventDate <= new DateTimeImmutable()
    || $event["status"] !== "upcoming";
$alreadyRegistered = $registration
    && $registration["status"] === "registered";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for <?= $escape($event["title"]) ?> - StudentHub</title>
    <link rel="stylesheet" href="css/events.css">
    <link rel="stylesheet" href="css/shared.css?v=5">
</head>
<body>
    <header class="site-header">
        <a href="events.html" class="logo" aria-label="Back to StudentHub events">
            <span class="logo-icon">🎓</span>
            <span class="logo-text">StudentHub</span>
        </a>
        <a href="../php/logout.php" class="registration-account-link">Sign out</a>
    </header>

    <main class="registration-page">
        <a class="registration-back-link" href="events.html">← Back to events</a>
        <section class="registration-card" aria-labelledby="registration-title">
            <p class="page-label">Event registration</p>
            <h1 id="registration-title">Register for <?= $escape($event["title"]) ?></h1>
            <p class="registration-intro">Confirm your place using your active student account.</p>

            <section class="registration-event-summary" aria-label="Selected event">
                <span class="event-category"><?= $escape($event["category"]) ?></span>
                <h2><?= $escape($event["title"]) ?></h2>
                <p><?= $escape($event["description"]) ?></p>
                <p class="registration-event-meta">
                    <?= $escape($eventDate->format("F j, Y")) ?>
                    · <?= $escape($event["event_time"]) ?>
                    · <?= $escape($event["venue"]) ?>
                </p>
                <p class="registration-event-meta">Organized by <?= $escape($event["organizer"]) ?></p>
            </section>

            <?php if ($alreadyRegistered): ?>
                <p class="registration-notice" role="status">
                    You are already registered for this event.
                </p>
            <?php elseif ($registration): ?>
                <p class="registration-notice" role="status">
                    Your previous registration is <?= $escape($registration["status"]) ?>.
                    Contact the event organizer if you need to change it.
                </p>
            <?php elseif ($isPastEvent): ?>
                <p class="registration-notice" role="status">
                    Registration for this event is closed because it has already taken place.
                </p>
            <?php else: ?>
                <form class="event-registration-form"
                      action="../php/event-registration.php"
                      method="post">
                    <input type="hidden" name="csrf_token"
                           value="<?= $escape(csrfToken()) ?>">
                    <input type="hidden" name="event_id"
                           value="<?= $escape($event["event_id"]) ?>">

                    <button class="register-event" type="submit">Submit Registration</button>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <footer class="studenthub-footer studenthub-footer--compact">
        <p class="studenthub-footer__legal">&copy; 2026 StudentHub.</p>
    </footer>
</body>
</html>
