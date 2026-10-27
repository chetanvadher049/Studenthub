<?php
$pageTitle = $pageTitle ?? "Admin Dashboard";
$flash = $_SESSION["admin_flash"] ?? null;
unset($_SESSION["admin_flash"]);
$currentPage = basename($_SERVER["SCRIPT_NAME"] ?? "");
$activeSection = match ($currentPage) {
    "students.php", "student-create.php", "student-edit.php", "student-view.php" => "students",
    "events.php", "events-api.php", "event-create.php", "event-edit.php", "event-view.php" => "events",
    "registrations.php" => "registrations",
    default => "dashboard",
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= adminEscape($pageTitle) ?> - StudentHub Admin</title>
    <meta name="theme-color" content="#f4f7fb">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/shared.css">
    <link rel="stylesheet" href="../css/admin.css?v=7">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar" id="sidebar" aria-label="Admin navigation">
        <a class="admin-brand" href="dashboard.php"><span class="admin-brand-mark" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></span>StudentHub</a>
        <p class="admin-nav-label">Workspace</p>
        <nav class="nav flex-column" aria-label="Administration">
            <a class="admin-nav-link<?= $activeSection === "dashboard" ? " active" : "" ?>" href="dashboard.php"<?= $activeSection === "dashboard" ? ' aria-current="page"' : "" ?>><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i>Dashboard</a>
            <a class="admin-nav-link<?= $activeSection === "students" ? " active" : "" ?>" href="students.php"<?= $activeSection === "students" ? ' aria-current="page"' : "" ?>><i class="bi bi-people-fill" aria-hidden="true"></i>Students</a>
            <a class="admin-nav-link<?= $activeSection === "events" ? " active" : "" ?>" href="events.php"<?= $activeSection === "events" ? ' aria-current="page"' : "" ?>><i class="bi bi-calendar-event-fill" aria-hidden="true"></i>Events</a>
            <a class="admin-nav-link<?= $activeSection === "registrations" ? " active" : "" ?>" href="registrations.php"<?= $activeSection === "registrations" ? ' aria-current="page"' : "" ?>><i class="bi bi-card-checklist" aria-hidden="true"></i>Registrations</a>
        </nav>
        <p class="admin-nav-label">Public site</p>
        <nav class="nav flex-column" aria-label="Public links">
            <a class="admin-nav-link" href="../events.html"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>Public events</a>
        </nav>
    </aside>
    <button class="admin-sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Close navigation menu"></button>
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <div class="menu-container">
                    <button class="admin-sidebar-toggle" id="menuToggle" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Open navigation menu"><i class="bi bi-list fs-4" aria-hidden="true"></i></button>
                </div>
                <p class="admin-topbar-title mb-0"><?= adminEscape($pageTitle) ?></p>
            </div>
            <div class="admin-topbar-actions">
                <button id="themeToggle" class="admin-theme-toggle" type="button" aria-label="Switch to dark mode" title="Switch theme"><i class="bi bi-moon-stars" aria-hidden="true"></i></button>
                <span class="admin-muted d-none d-sm-inline"><?= adminEscape($admin["full_name"]) ?></span>
                <a class="btn btn-outline-secondary btn-sm" href="../../php/logout.php">Sign out</a>
            </div>
        </header>
        <main class="admin-content">
            <div class="admin-page-heading">
                <div><p class="admin-muted mb-1">StudentHub administration</p><h1><?= adminEscape($pageTitle) ?></h1></div>
                <span class="badge text-bg-light border"><?= adminEscape($admin["role"]) ?></span>
            </div>
    <?php if (is_array($flash)): ?>
        <p class="admin-flash<?= ($flash["type"] ?? "") === "error" ? " admin-flash--error" : "" ?>" role="status">
            <?= adminEscape($flash["message"] ?? "") ?>
        </p>
    <?php endif; ?>
