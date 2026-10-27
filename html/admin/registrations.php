<?php
require_once __DIR__ . "/includes/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    adminRequireCsrf();
    $registrationId = filter_var($_POST["registration_id"] ?? null, FILTER_VALIDATE_INT);
    $newStatus = $_POST["status"] ?? "";
    if (!$registrationId || $registrationId < 1 || !in_array($newStatus, ["registered", "cancelled"], true)) {
        adminFlash("Choose a valid registration and status.", "error");
        adminRedirect("registrations.php");
    }

    try {
        $update = $connection->prepare(
            "UPDATE registrations SET status = ? WHERE id = ?"
        );
        $update->bind_param("si", $newStatus, $registrationId);
        $update->execute();
        $exists = $update->affected_rows > 0;
        $update->close();
        if (!$exists) {
            $check = $connection->prepare("SELECT id FROM registrations WHERE id = ? LIMIT 1");
            $check->bind_param("i", $registrationId);
            $check->execute();
            $check->store_result();
            $exists = $check->num_rows > 0;
            $check->close();
        }
        if ($exists) {
            adminAuditSafe($connection, $admin, "registration_status_updated", "registration", $registrationId);
            adminFlash("Registration status updated.");
        } else {
            adminFlash("Registration not found.", "error");
        }
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub registration update failed: " . $exception->getMessage());
        adminFlash("Registration status could not be updated.", "error");
    }
    $eventRedirect = filter_var($_POST["event_filter"] ?? null, FILTER_VALIDATE_INT);
    adminRedirect($eventRedirect ? "registrations.php?event_id=" . $eventRedirect : "registrations.php");
}

$eventId = filter_var($_GET["event_id"] ?? null, FILTER_VALIDATE_INT);
$search = trim(is_string($_GET["q"] ?? null) ? $_GET["q"] : "");
$status = $_GET["status"] ?? "";
$conditions = [];
$values = [];
$types = "";
if ($eventId && $eventId > 0) {
    $conditions[] = "r.event_id = ?";
    $values[] = $eventId;
    $types .= "i";
} else {
    $eventId = null;
}
if (in_array($status, ["registered", "cancelled"], true)) {
    $conditions[] = "r.status = ?";
    $values[] = $status;
    $types .= "s";
} else {
    $status = "";
}
if ($search !== "") {
    $conditions[] = "(s.full_name LIKE ? OR s.username LIKE ? OR s.email LIKE ? OR e.title LIKE ?)";
    $term = "%" . $search . "%";
    array_push($values, $term, $term, $term, $term);
    $types .= "ssss";
}
$where = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";
$from = " FROM registrations r
        JOIN students s ON s.id = r.student_id
        JOIN events e ON e.id = r.event_id";
$count = $connection->prepare("SELECT COUNT(*) AS total" . $from . $where);
if ($types !== "") {
    $count->bind_param($types, ...$values);
}
$count->execute();
$totalRows = (int) $count->get_result()->fetch_assoc()["total"];
$count->close();
$perPage = 25;
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$page = max(1, min($totalPages, (int) ($_GET["page"] ?? 1)));
$offset = ($page - 1) * $perPage;
$sql = "SELECT r.id, r.registration_date, r.status, s.id AS student_id,
               s.full_name, s.username, s.email, e.id AS event_id,
               e.title AS event_title, e.event_date
        " . $from . $where . "
        ORDER BY r.registration_date DESC, r.id DESC LIMIT ? OFFSET ?";
$statement = $connection->prepare($sql);
$rowTypes = $types . "ii";
$rowValues = array_merge($values, [$perPage, $offset]);
$statement->bind_param($rowTypes, ...$rowValues);
$statement->execute();
$registrations = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$statement->close();
$events = $connection->query("SELECT id, title FROM events ORDER BY event_date")->fetch_all(MYSQLI_ASSOC);

$pageTitle = "Event Registrations";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-card">
    <form class="admin-filters" method="get">
        <label>Search
            <input type="search" name="q" value="<?= adminEscape($search) ?>" placeholder="Student, email, username, event">
        </label>
        <label>Event
            <select name="event_id"><option value="">All events</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= (int) $event["id"] ?>"<?= $eventId === (int) $event["id"] ? " selected" : "" ?>><?= adminEscape($event["title"]) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Status
            <select name="status"><option value="">All</option>
                <?php foreach (["registered", "cancelled"] as $value): ?>
                    <option value="<?= $value ?>"<?= $status === $value ? " selected" : "" ?>><?= ucfirst($value) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="admin-button" type="submit">Filter</button>
    </form>
    <div class="admin-table-wrap"><table class="admin-table">
        <thead><tr><th>Student</th><th>Username</th><th>Email</th><th>Event</th><th>Event date</th><th>Registered</th><th>Status</th><th>Update status</th></tr></thead>
        <tbody>
        <?php foreach ($registrations as $registration): ?>
            <tr>
                <td><a href="student-view.php?id=<?= (int) $registration["student_id"] ?>"><?= adminEscape($registration["full_name"]) ?></a></td>
                <td><?= adminEscape($registration["username"]) ?></td>
                <td><?= adminEscape($registration["email"]) ?></td>
                <td><a href="event-view.php?id=<?= (int) $registration["event_id"] ?>"><?= adminEscape($registration["event_title"]) ?></a></td>
                <td><?= adminEscape($registration["event_date"]) ?></td>
                <td><?= adminEscape($registration["registration_date"]) ?></td>
                <td><?= adminEscape($registration["status"]) ?></td>
                <td>
                    <form method="post" class="admin-actions">
                        <input type="hidden" name="csrf_token" value="<?= adminEscape(csrfToken()) ?>">
                        <input type="hidden" name="registration_id" value="<?= (int) $registration["id"] ?>">
                        <input type="hidden" name="event_filter" value="<?= (int) ($eventId ?? 0) ?>">
                        <select name="status" aria-label="Registration status for <?= adminEscape($registration["full_name"]) ?>">
                            <?php foreach (["registered", "cancelled"] as $value): ?>
                                <option value="<?= $value ?>"<?= $registration["status"] === $value ? " selected" : "" ?>><?= ucfirst($value) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$registrations): ?><tr><td colspan="8">No registrations found. Try adjusting the filters, or wait for students to register.</td></tr><?php endif; ?>
        </tbody>
    </table></div>
    <p class="admin-muted"><?= $totalRows ?> registrations</p>
    <nav class="admin-pagination" aria-label="Registration pages">
        <?php if ($page > 1): ?><a href="?<?= adminEscape(http_build_query(array_merge($_GET, ["page" => $page - 1]))) ?>">Previous</a><?php endif; ?>
        <span>Page <?= $page ?> of <?= $totalPages ?></span>
        <?php if ($page < $totalPages): ?><a href="?<?= adminEscape(http_build_query(array_merge($_GET, ["page" => $page + 1]))) ?>">Next</a><?php endif; ?>
    </nav>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
