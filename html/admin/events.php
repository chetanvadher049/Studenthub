<?php
require_once __DIR__ . "/includes/bootstrap.php";

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

$count = $connection->prepare("SELECT COUNT(*) AS total FROM events" . $where);
if ($types !== "") {
    $count->bind_param($types, ...$values);
}
$count->execute();
$total = (int) $count->get_result()->fetch_assoc()["total"];
$count->close();

$statement = $connection->prepare(
    "SELECT event_id, title, event_date, event_time, venue, category,
            organizer, image
     FROM events" . $where . " ORDER BY event_date DESC, event_id DESC"
);
if ($types !== "") {
    $statement->bind_param($types, ...$values);
}
$statement->execute();
$events = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$statement->close();

$categoryResult = $connection->query(
    "SELECT DISTINCT category FROM events
     WHERE category <> '' ORDER BY category"
);
$categories = array_column($categoryResult->fetch_all(MYSQLI_NUM), 0);
$categoryResult->free();

$pageTitle = "Events";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-card">
    <div class="admin-actions">
        <a class="admin-button" href="event-create.php">Add event</a>
        <a class="admin-button admin-button--muted" href="events-api.php">Async API manager</a>
    </div>
    <form class="admin-filters" method="get">
        <label>Search
            <input type="search" name="q" value="<?= adminEscape($search) ?>"
                   placeholder="Title, description, venue">
        </label>
        <label>Category
            <select name="category">
                <option value="">All categories</option>
                <?php foreach ($categories as $item): ?>
                    <option value="<?= adminEscape($item) ?>"
                        <?= $category === $item ? "selected" : "" ?>>
                        <?= adminEscape($item) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="admin-button" type="submit">Search / Filter</button>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Title</th><th>Date</th><th>Time</th><th>Venue</th>
                    <th>Category</th><th>Poster</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= adminEscape($event["title"]) ?></td>
                    <td><?= adminEscape($event["event_date"]) ?></td>
                    <td><?= adminEscape($event["event_time"]) ?></td>
                    <td><?= adminEscape($event["venue"]) ?></td>
                    <td><?= adminEscape($event["category"]) ?></td>
                    <td><?= $event["image"] ? "Uploaded" : "None" ?></td>
                    <td class="admin-actions">
                        <a href="event-view.php?id=<?= (int) $event["event_id"] ?>">View</a>
                        <a href="event-edit.php?id=<?= (int) $event["event_id"] ?>">Edit</a>
                        <form method="post" action="event-delete.php"
                              onsubmit="return confirm('Delete this event?');">
                            <input type="hidden" name="csrf_token"
                                   value="<?= adminEscape(csrfToken()) ?>">
                            <input type="hidden" name="event_id"
                                   value="<?= (int) $event["event_id"] ?>">
                            <button class="admin-button--danger"
                                    type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$events): ?>
                <tr><td colspan="7">No events found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="admin-muted"><?= $total ?> event(s) found.</p>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
