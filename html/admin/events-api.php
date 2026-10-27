<?php
require_once __DIR__ . "/includes/bootstrap.php";
$pageTitle = "Async Event Manager";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-card async-event-manager"
         data-api-url="../../php/events-api.php"
         data-csrf-token="<?= adminEscape(csrfToken()) ?>">
    <div class="admin-actions">
        <button class="admin-button" id="async-event-new" type="button">New event</button>
        <button class="admin-button admin-button--muted" id="async-event-refresh" type="button">Refresh</button>
    </div>
    <p id="async-event-message" class="admin-flash" role="status" hidden></p>
    <form id="async-event-form" class="admin-form" enctype="multipart/form-data" hidden>
        <input type="hidden" name="event_id">
        <label>Title <input name="title" maxlength="150" required></label>
        <label>Event date <input type="date" name="event_date" required></label>
        <label>Event time <input name="event_time" maxlength="20" required></label>
        <label>Venue <input name="venue" maxlength="150" required></label>
        <label>Category <input name="category" maxlength="50" required></label>
        <label>Organizer <input name="organizer" maxlength="150"></label>
        <label class="admin-full">Description
            <textarea name="description" required></textarea>
        </label>
        <label class="admin-full">Poster (JPEG, PNG, WebP; maximum 5 MB)
            <input type="file" name="poster" accept="image/jpeg,image/png,image/webp">
        </label>
        <div class="admin-actions admin-full">
            <button class="admin-button" type="submit">Save event</button>
            <button class="admin-button admin-button--muted" id="async-event-cancel" type="button">Cancel</button>
        </div>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Date</th><th>Venue</th><th>Category</th><th>Poster</th><th>Actions</th></tr></thead>
            <tbody id="async-event-list"><tr><td colspan="6">Loading events...</td></tr></tbody>
        </table>
    </div>
</section>
<script src="../../javascript/admin-events-api.js" defer></script>
<?php require __DIR__ . "/includes/footer.php"; ?>
