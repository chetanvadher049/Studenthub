<?php
require_once __DIR__ . "/../php/auth.php";
$student = studenthub_require_login();
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
    <link rel="stylesheet" href="css/shared.css">
    <script src="../javascript/dashboard.js?v=2" defer></script>

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

<!-- NOTIFICATION BANNER -->
<div id="notificationBanner" class="notification-banner">
    <span>📢 You have 5 new notifications and upcoming events.</span>
    <button id="closeNotification" type="button" aria-label="Close notification">×</button>
</div>



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


            <strong>
                12
            </strong>


            <span>
                Events
            </span>


            <p>
                Upcoming events
            </p>


        </article>



        <!-- NOTIFICATIONS -->

        <article class="summary-card">


            <div class="summary-top">


                <div class="summary-icon notification-icon"
                     aria-hidden="true">

                    🔔

                </div>


            </div>


            <strong>
                5
            </strong>


            <span>
                Notifications
            </span>


            <p>
                New notifications
            </p>


        </article>



        <!-- MESSAGES -->

        <article class="summary-card">


            <div class="summary-top">


                <div class="summary-icon message-icon"
                     aria-hidden="true">

                    👤

                </div>


            </div>


            <strong>
                3
            </strong>


            <span>
                Messages
            </span>


            <p>
                Unread messages
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


                <!-- EVENT 1 -->
                <li class="event-item" data-event="Technical Workshop">
                    <div class="list-thumbnail" aria-hidden="true">
                        📚
                    </div>

                    <div class="list-content">
                        <strong>Technical Workshop</strong>
                        <span>Technology & Development</span>
                    </div>

                    <span class="list-arrow">›</span>
                </li>


                <!-- EVENT 2 -->
                <li class="event-item" data-event="College Hackathon">
                    <div class="list-thumbnail" aria-hidden="true">
                        💻
                    </div>

                    <div class="list-content">
                        <strong>College Hackathon</strong>
                        <span>Coding Competition</span>
                    </div>

                    <span class="list-arrow">›</span>
                </li>


                <!-- EVENT 3 -->
                <li class="event-item" data-event="Sports Day">
                    <div class="list-thumbnail" aria-hidden="true">
                        🏆
                    </div>

                    <div class="list-content">
                        <strong>Sports Day</strong>
                        <span>Annual Sports Activities</span>
                    </div>

                    <span class="list-arrow">›</span>
                </li>


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

<footer class="site-footer">


    <p>

        &copy; 2026 StudentHub.
        All rights reserved.

    </p>


</footer>


</body>

</html>