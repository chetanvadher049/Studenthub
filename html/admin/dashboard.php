<?php
require_once __DIR__ . "/includes/bootstrap.php";

try {
    $result = $connection->query(
        "SELECT
            (SELECT COUNT(*) FROM student) AS student_total,
            (SELECT COUNT(*) FROM admins WHERE status = 'active') AS admin_total,
            (SELECT COUNT(*) FROM events) AS event_total,
            (SELECT COUNT(*) FROM event_registrations) AS registration_total"
    );
    $stats = $result->fetch_assoc();
    $result->free();

    $monthlyRows = $connection->query(
        "SELECT DATE_FORMAT(time_stamp, '%Y-%m') AS month_key, COUNT(*) AS registration_count
         FROM event_registrations
         WHERE time_stamp >= DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 5 MONTH), '%Y-%m-01')
         GROUP BY DATE_FORMAT(time_stamp, '%Y-%m')
         ORDER BY month_key"
    )->fetch_all(MYSQLI_ASSOC);
    $monthlyCounts = [];
    foreach ($monthlyRows as $row) {
        $monthlyCounts[$row["month_key"]] = (int) $row["registration_count"];
    }

    $monthlyStudentRows = $connection->query(
        "SELECT DATE_FORMAT(time_stamp, '%Y-%m') AS month_key, COUNT(*) AS student_count
         FROM student
         WHERE time_stamp >= DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 5 MONTH), '%Y-%m-01')
         GROUP BY DATE_FORMAT(time_stamp, '%Y-%m')
         ORDER BY month_key"
    )->fetch_all(MYSQLI_ASSOC);
    $monthlyStudentCounts = [];
    foreach ($monthlyStudentRows as $row) {
        $monthlyStudentCounts[$row["month_key"]] = (int) $row["student_count"];
    }

    $registrationActivity = [];
    $monthStart = new DateTimeImmutable("first day of this month");
    for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
        $month = $monthStart->modify("-" . $monthOffset . " months");
        $monthKey = $month->format("Y-m");
        $registrationActivity[] = [
            "label" => $month->format("M Y"),
            "count" => $monthlyCounts[$monthKey] ?? 0,
            "students" => $monthlyStudentCounts[$monthKey] ?? 0,
        ];
    }
    $maxMonthlyCount = max(
        1,
        ...array_column($registrationActivity, "count"),
        ...array_column($registrationActivity, "students")
    );
    $chartWidth = 900;
    $chartHeight = 250;
    $chartLeft = 34;
    $chartRight = 14;
    $chartTop = 18;
    $chartBottom = 34;
    $chartInnerWidth = $chartWidth - $chartLeft - $chartRight;
    $chartInnerHeight = $chartHeight - $chartTop - $chartBottom;
    $chartPoints = static function (string $key) use (
        $registrationActivity,
        $maxMonthlyCount,
        $chartLeft,
        $chartTop,
        $chartInnerWidth,
        $chartInnerHeight
    ): string {
        $lastIndex = max(1, count($registrationActivity) - 1);
        $points = [];
        foreach ($registrationActivity as $index => $month) {
            $x = $chartLeft + ($index / $lastIndex) * $chartInnerWidth;
            $y = $chartTop + $chartInnerHeight - (((int) $month[$key] / $maxMonthlyCount) * $chartInnerHeight);
            $points[] = round($x, 2) . "," . round($y, 2);
        }
        return implode(" ", $points);
    };

    $recentRegistrations = $connection->query(
        "SELECT r.student_id, s.student_name, s.email, r.event_title, r.event_date,
                r.time_stamp AS registration_date
         FROM event_registrations r
         LEFT JOIN student s ON s.student_id = r.student_id
         ORDER BY r.time_stamp DESC, r.registration_id DESC
         LIMIT 6"
    )->fetch_all(MYSQLI_ASSOC);

    $popularEvents = $connection->query(
        "SELECT e.event_id AS id, e.title, e.category, COUNT(r.registration_id) AS registration_count
         FROM events e
         LEFT JOIN event_registrations r
           ON r.event_id = e.event_id AND r.registration_status = 'registered'
         GROUP BY e.event_id, e.title, e.category
         ORDER BY registration_count DESC, e.event_date DESC
         LIMIT 5"
    )->fetch_all(MYSQLI_ASSOC);
    $maxEventRegistrations = max(
        1,
        ...array_map(static fn (array $event): int => (int) $event["registration_count"], $popularEvents)
    );

    $upcomingEvents = $connection->query(
        "SELECT event_id AS id, title, event_date, venue AS location
         FROM events
         WHERE event_date >= CURRENT_DATE
         ORDER BY event_date, event_id
         LIMIT 5"
    )->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $exception) {
    error_log("StudentHub admin dashboard query failed: " . $exception->getMessage());
    http_response_code(503);
    exit("Dashboard data is temporarily unavailable. Check the database connection and try again.");
}

$pageTitle = "Dashboard";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-stat-grid" aria-label="StudentHub statistics">
    <?php foreach ([
        ["Users", (int) $stats["student_total"] + (int) $stats["admin_total"], "bi-people-fill", "Students and active administrators", "users"],
        ["Students", (int) $stats["student_total"], "bi-mortarboard-fill", "Registered student accounts", "students"],
        ["Events", (int) $stats["event_total"], "bi-calendar-event-fill", "Events in the event catalogue", "events"],
        ["Registrations", (int) $stats["registration_total"], "bi-card-checklist", "Event registrations", "registrations"],
    ] as [$label, $value, $icon, $description, $tone]): ?>
        <article class="admin-card admin-stat-card admin-stat-card--<?= adminEscape($tone) ?>">
            <div class="admin-stat-card-heading"><span><?= adminEscape($label) ?></span><i class="bi bi-three-dots-vertical" aria-hidden="true"></i></div>
            <strong class="admin-stat-value"><?= number_format($value) ?></strong>
            <p class="admin-stat-label"><?= adminEscape($description) ?></p>
            <span class="admin-stat-icon" aria-hidden="true"><i class="bi <?= adminEscape($icon) ?>"></i></span>
        </article>
    <?php endforeach; ?>
</section>

<div class="admin-dashboard-grid">
    <section class="admin-card admin-traffic-card" aria-labelledby="activity-title">
        <div class="admin-dashboard-section-heading">
            <div><h2 class="admin-section-title mb-1" id="activity-title">Traffic</h2><p class="admin-muted mb-0">Event registrations · Last 6 months</p></div>
            <span class="admin-period-switch"><button type="button" class="active">Month</button><button type="button" disabled>Year</button></span>
        </div>
        <div class="admin-line-chart" role="img" aria-label="Monthly registrations and student signups for the last six months">
            <svg viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>" preserveAspectRatio="none" aria-hidden="true">
                <?php for ($line = 0; $line <= 4; $line++): ?>
                    <?php $lineY = $chartTop + ($line / 4) * $chartInnerHeight; ?>
                    <line x1="<?= $chartLeft ?>" y1="<?= round($lineY, 2) ?>" x2="<?= $chartWidth - $chartRight ?>" y2="<?= round($lineY, 2) ?>" class="admin-chart-grid-line"></line>
                <?php endfor; ?>
                <polyline points="<?= adminEscape($chartPoints("students")) ?>" class="admin-chart-line admin-chart-line--students"></polyline>
                <polyline points="<?= adminEscape($chartPoints("count")) ?>" class="admin-chart-line admin-chart-line--registrations"></polyline>
                <?php foreach ($registrationActivity as $index => $month): ?>
                    <?php $x = $chartLeft + ($index / max(1, count($registrationActivity) - 1)) * $chartInnerWidth; ?>
                    <text x="<?= round($x, 2) ?>" y="<?= $chartHeight - 8 ?>" class="admin-chart-axis-label" text-anchor="middle"><?= adminEscape($month["label"]) ?></text>
                <?php endforeach; ?>
            </svg>
        </div>
        <div class="admin-chart-legend">
            <span><i class="admin-chart-legend-dot admin-chart-legend-dot--registrations"></i>Registrations</span>
            <span><i class="admin-chart-legend-dot admin-chart-legend-dot--students"></i>New students</span>
        </div>
    </section>

    <section class="admin-card" aria-labelledby="popular-events-title">
        <h2 class="admin-section-title" id="popular-events-title"><span><i class="bi bi-trophy me-2" aria-hidden="true"></i>Event registrations</span><a href="events.php" class="small">Manage</a></h2>
        <?php if (!$popularEvents): ?>
            <p class="admin-empty mb-0">No events have been added yet.</p>
        <?php else: ?>
            <div class="admin-chart" aria-label="Registrations per event">
                <?php foreach ($popularEvents as $event): ?>
                    <div class="admin-chart-row" title="<?= adminEscape($event["title"]) ?>">
                        <span class="text-truncate"><?= adminEscape($event["category"] ?: "Other") ?></span>
                        <div class="admin-chart-track" aria-hidden="true"><div class="admin-chart-bar" style="width:<?= round(((int) $event["registration_count"] / $maxEventRegistrations) * 100, 2) ?>%"></div></div>
                        <span class="admin-chart-value"><?= (int) $event["registration_count"] ?></span>
                    </div>
                    <a class="small text-decoration-none" href="event-view.php?id=<?= (int) $event["id"] ?>"><?= adminEscape($event["title"]) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="admin-card" aria-labelledby="recent-registrations-title">
        <h2 class="admin-section-title" id="recent-registrations-title"><span><i class="bi bi-clock-history me-2" aria-hidden="true"></i>Recent registrations</span><a href="registrations.php" class="small">View all</a></h2>
        <?php if (!$recentRegistrations): ?>
            <p class="admin-empty mb-0">There are no registrations to show.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table table align-middle mb-0">
                    <thead><tr><th scope="col">Student</th><th scope="col">Event</th><th scope="col">Date</th><th scope="col">Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentRegistrations as $registration): ?>
                        <tr>
                            <td><span class="d-block fw-semibold"><?= adminEscape($registration["student_name"] ?: $registration["student_id"]) ?></span><small class="admin-muted"><?= adminEscape($registration["email"] ?: "Student account") ?></small></td>
                            <td><?= adminEscape($registration["event_title"]) ?></td>
                            <td><?= adminEscape(date("M j, Y", strtotime($registration["registration_date"]))) ?></td>
                            <td><span class="badge text-bg-success">Registered</span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="admin-card" aria-labelledby="upcoming-events-title">
        <h2 class="admin-section-title" id="upcoming-events-title"><span><i class="bi bi-calendar2-week me-2" aria-hidden="true"></i>Upcoming events</span><a href="events.php" class="small">View all</a></h2>
        <?php if (!$upcomingEvents): ?>
            <p class="admin-empty mb-0">No future upcoming events. Add an event to populate this list.</p>
        <?php else: ?>
            <div class="admin-event-list">
                <?php foreach ($upcomingEvents as $event): ?>
                    <a class="admin-event-row text-decoration-none" href="event-view.php?id=<?= (int) $event["id"] ?>">
                        <span><strong class="d-block"><?= adminEscape($event["title"]) ?></strong><small class="admin-muted"><?= adminEscape($event["location"]) ?></small></span>
                        <time class="admin-muted text-end" datetime="<?= adminEscape(date(DATE_ATOM, strtotime($event["event_date"]))) ?>"><?= adminEscape(date("M j", strtotime($event["event_date"]))) ?></time>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php require __DIR__ . "/includes/footer.php"; ?>
