<?php
require_once __DIR__ . "/../php/auth.php";

$student = requireStudent();

try {
    $dashboardConnection = studenthub_db("studenthub");

    $upcomingEventResult = $dashboardConnection->query(
        "SELECT COUNT(*) AS event_count
         FROM events
         WHERE event_date >= CURRENT_DATE"
    );
    $upcomingEventCount = (int) $upcomingEventResult->fetch_assoc()["event_count"];
    $upcomingEventResult->free();

    $registrationCountQuery = $dashboardConnection->prepare(
        "SELECT COUNT(*) AS registration_count
         FROM event_registrations AS registration
         WHERE registration.student_id = ?
           AND registration.registration_status = 'registered'
           AND registration.event_date >= CURRENT_DATE"
    );
    $studentId = (string) $student["student_id"];
    $registrationCountQuery->bind_param("s", $studentId);
    $registrationCountQuery->execute();
    $registrationCount = (int) $registrationCountQuery
        ->get_result()
        ->fetch_assoc()["registration_count"];
    $registrationCountQuery->close();

    $upcomingEventsResult = $dashboardConnection->query(
        "SELECT event_id, title, event_date, event_time,
                venue, category, description
         FROM events
         WHERE event_date >= CURRENT_DATE
         ORDER BY event_date, event_id
         LIMIT 3"
    );
    $upcomingEvents = $upcomingEventsResult->fetch_all(MYSQLI_ASSOC);
    $upcomingEventsResult->free();
    $dashboardConnection->close();
} catch (mysqli_sql_exception $exception) {
    error_log("StudentHub dashboard data lookup failed: " . $exception->getMessage());
    http_response_code(503);
    exit("Dashboard data is temporarily unavailable. Please try again shortly.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>StudentHub - Dashboard</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/dashboard-dynamic.css">
    <link rel="stylesheet" href="css/shared.css?v=5">
    <script src="../javascript/dashboard.js?v=3" defer></script>

</head>

<body>


<!-- =====================================================
     HEADER
     ===================================================== -->

<header class="site-header">


    <!-- =================================================
         3-LINE DROPDOWN MENU
         ================================================= -->

    <div class="menu-container">

        <details class="menu-details">


            <!-- 3 LINE MENU BUTTON -->

            <summary class="menu-button"
                     aria-label="Open navigation menu">

                <span></span>
                <span></span>
                <span></span>

            </summary>


            <!-- =================================================
                 DROPDOWN NAVIGATION
                 ================================================= -->

            <nav class="dropdown-menu"
                 aria-label="Main navigation">


                <a href="index.html">

                    <span class="menu-icon">🏠</span>

                    <span>Home</span>

                </a>


                <a href="about.html">

                    <span class="menu-icon">ⓘ</span>

                    <span>About</span>

                </a>


                <a href="dashboard.php"
                   aria-current="page">

                    <span class="menu-icon">▣</span>

                    <span>Dashboard</span>

                </a>


                <a href="events.html">

                    <span class="menu-icon">▦</span>

                    <span>Events</span>

                </a>


                <a href="profile.php">

                    <span class="menu-icon">👤</span>

                    <span>Profile</span>

                </a>


                <a href="contact.html">

                    <span class="menu-icon">✉</span>

                    <span>Contact</span>

                </a>


                <a href="admin.html">

                    <span class="menu-icon">◉</span>

                    <span>Admin</span>

                </a>


                <a href="faq.html">

                    <span class="menu-icon">?</span>

                    <span>FAQ</span>

                </a>

            </nav>


        </details>

    </div>



    <!-- =================================================
         STUDENTHUB LOGO
         ================================================= -->

    <a href="index.html"
       class="logo"
       aria-label="StudentHub Home">


        <span class="logo-icon">
            🎓
        </span>


        <span class="logo-text">
            StudentHub
        </span>


    </a>



    <!-- =================================================
         LOGIN / REGISTER
         ================================================= -->

    <div class="header-actions">
        <button id="themeToggle" class="theme-toggle" type="button"
                aria-label="Switch to dark mode">🌙</button>

        <div class="auth-buttons">
        <a href="profile.php">Profile</a>
        <a href="../php/logout.php">Log out</a>
        </div>
    </div>


</header>

<!-- =====================================================
     MAIN CONTENT
     ================================================= -->

<main class="main-content">


    <!-- =================================================
         DASHBOARD HEADER
         ================================================= -->

    <section class="dashboard-header"
             aria-labelledby="dashboard-title">


        <div>

            <p class="page-label">
                Student Portal
            </p>

            <h1 id="dashboard-title">
                Dashboard
            </h1>

            <p class="dashboard-subtitle">
                Welcome back, <?= studenthub_escape($student["student_name"]) ?>! Here's an overview of your
                student activities.
            </p>

        </div>


    </section>



    <!-- =================================================
         SUMMARY CARDS
         ================================================= -->

    <section class="dashboard-summary"
             aria-labelledby="summary-title">


        <h2 id="summary-title"
            class="visually-hidden">

            Dashboard Summary

        </h2>



        <!-- EVENTS -->

        <article class="summary-card">


            <div class="summary-top">


                <div class="summary-icon events-icon"
                     aria-hidden="true">

                    📅

                </div>


            </div>


            <strong><?= $upcomingEventCount ?></strong>


            <span>
                Upcoming Events
            </span>


            <p>
                Available in the catalog
            </p>


        </article>



        <!-- NOTIFICATIONS -->

        <article class="summary-card">


            <div class="summary-top">


                <div class="summary-icon notification-icon"
                     aria-hidden="true">

                    ✅

                </div>


            </div>


            <strong><?= $registrationCount ?></strong>


            <span>
                Your Registrations
            </span>


            <p>
                Active event registrations
            </p>


        </article>



        <!-- MESSAGES -->

        <article class="summary-card">


            <div class="summary-top">


                <div class="summary-icon message-icon"
                     aria-hidden="true">

                    🎓

                </div>


            </div>


            <strong>Ready</strong>


            <span>
                Student Profile
            </span>


            <p>
                Connected to your account
            </p>


        </article>


    </section>



    <!-- =================================================
         DASHBOARD PANELS
         ================================================= -->

    <section class="dashboard-panels">


        <!-- =================================================
             UPCOMING EVENTS
             ================================================= -->

        <article class="dashboard-panel">


            <div class="panel-header">


                <div>

                    <p class="panel-label">
                        Schedule
                    </p>

                    <h2>
                        Upcoming Events
                    </h2>

                </div>


                <a href="events.html"
                   class="panel-view-link">

                    View All

                </a>


            </div>



            <ul class="dashboard-list">
                <?php if ($upcomingEvents === []): ?>
                    <li class="dashboard-empty">There are no upcoming events.</li>
                <?php else: ?>
                    <?php foreach ($upcomingEvents as $event): ?>
                        <?php
                        $eventDetails = sprintf(
                            "%s at %s, %s. %s",
                            (new DateTimeImmutable($event["event_date"]))->format("F j, Y"),
                            $event["event_time"],
                            $event["venue"],
                            $event["description"]
                        );
                        ?>
                        <li class="event-item"
                            data-event="<?= studenthub_escape($event["title"]) ?>"
                            data-text="<?= studenthub_escape($eventDetails) ?>">
                            <div class="list-thumbnail" aria-hidden="true">📅</div>
                            <div class="list-content">
                                <strong><?= studenthub_escape($event["title"]) ?></strong>
                                <span>
                                    <?= studenthub_escape($event["category"]) ?> ·
                                    <?= studenthub_escape((new DateTimeImmutable($event["event_date"]))->format("M j, Y")) ?>
                                </span>
                            </div>
                            <span class="list-arrow">›</span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>


            <a href="events.html"
               class="panel-link">

                View All Events

            </a>


        </article>



        <!-- =================================================
             ANNOUNCEMENTS
             ================================================= -->

        <article class="dashboard-panel">


            <div class="panel-header">


                <div>

                    <p class="panel-label">
                        Latest Updates
                    </p>

                    <h2>
                        Announcements
                    </h2>

                </div>


            </div>



            <ul class="announcement-list">


                <!-- ANNOUNCEMENT 1 -->

                <li class="announcement-item" data-title="New academic schedule" data-text="The latest academic schedule is now available for students.">
<span class="announcement-dot"
                          aria-hidden="true">
                    </span>


                    <div>

                        <strong>
                            New academic schedule
                        </strong>

                        <span>
                            The latest schedule is now available.
                        </span>

                    </div>


                </li>



                <!-- ANNOUNCEMENT 2 -->

                <li class="announcement-item" data-title="Event registration is open" data-text="Registration for upcoming college events is now open.">
<span class="announcement-dot"
                          aria-hidden="true">
                    </span>


                    <div>

                        <strong>
                            Event registration is open
                        </strong>

                        <span>
                            Register for upcoming college events.
                        </span>

                    </div>


                </li>



                <!-- ANNOUNCEMENT 3 -->

                <li class="announcement-item" data-title="Important student notice" data-text="Please check the latest student updates and notices.">
<span class="announcement-dot"
                          aria-hidden="true">
                    </span>


                    <div>

                        <strong>
                            Important student notice
                        </strong>

                        <span>
                            Please check the latest student updates.
                        </span>

                    </div>


                </li>


            </ul>


        </article>


    </section>


</main>




<!-- MODAL POPUP -->
<div id="dashboardModal" class="dashboard-modal">
    <div class="modal-box">
        <button id="modalClose" class="modal-close" type="button"
                aria-label="Close popup">&times;</button>

        <h2 id="modalTitle">StudentHub</h2>
        <p id="modalText">
            Select an event or announcement to see more information.
        </p>

        <button id="modalOk" class="modal-ok" type="button">OK</button>
    </div>
</div>

<!-- =====================================================
     FOOTER
     ===================================================== -->

<?php
$footerPagePrefix = "";
$footerVariant = "compact";
require __DIR__ . "/../php/footer.php";
?>


<script src="../javascript/session-nav.js" defer></script>
</body>

</html>