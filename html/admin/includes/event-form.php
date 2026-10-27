<?php
$eventData = $eventData ?? [];
$isEdit = $isEdit ?? false;
?>
<form class="admin-card admin-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= adminEscape(csrfToken()) ?>">
    <?php if ($isEdit): ?><input type="hidden" name="event_id" value="<?= (int) ($eventData["event_id"] ?? 0) ?>"><?php endif; ?>
    <label>Title
        <input name="title" maxlength="200" required value="<?= adminEscape($eventData["title"] ?? "") ?>">
    </label>
    <label>Category
        <input name="category" maxlength="100" value="<?= adminEscape($eventData["category"] ?? "") ?>">
    </label>
    <label>Organizer
        <input name="organizer" maxlength="150" value="<?= adminEscape($eventData["organizer"] ?? "") ?>">
    </label>
    <label>Event date
        <input type="date" name="event_date" required value="<?= adminEscape($eventData["event_date"] ?? "") ?>">
    </label>
    <label>Event time
        <input name="event_time" maxlength="20" required value="<?= adminEscape($eventData["event_time"] ?? "") ?>">
    </label>
    <label>Venue
        <input name="venue" maxlength="150" required value="<?= adminEscape($eventData["venue"] ?? "") ?>">
    </label>
    <label class="admin-full">Description
        <textarea name="description" maxlength="10000" required><?= adminEscape($eventData["description"] ?? "") ?></textarea>
    </label>
    <label>Poster image (JPEG, PNG, or WebP; max 5 MB)
        <input type="file" name="poster" accept="image/jpeg,image/png,image/webp">
    </label>
    <?php if (!empty($eventData["image"])): ?><p class="admin-full">Current poster: <img src="../<?= adminEscape($eventData["image"]) ?>" alt="" width="120"></p><?php endif; ?>
    <div class="admin-actions admin-full">
        <button class="admin-button" type="submit"><?= $isEdit ? "Save event" : "Create event" ?></button>
        <a class="admin-button admin-button--muted" href="events.php">Cancel</a>
    </div>
</form>
