<?php
require_once __DIR__ . "/includes/bootstrap.php";
$studentId = trim((string) ($_GET["id"] ?? ""));
if ($studentId === "") { http_response_code(400); exit("Choose a valid student."); }
$statement = $connection->prepare(
    "SELECT student_id, student_name, username, email, mobile_no, gender,
            course, academic_year, time_stamp
     FROM student WHERE student_id = ? LIMIT 1"
);
$statement->bind_param("s", $studentId);
$statement->execute();
$student = $statement->get_result()->fetch_assoc();
$statement->close();
if (!$student) { http_response_code(404); exit("Student not found."); }
$pageTitle = "Student Details";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-card">
    <div class="admin-actions">
        <a class="admin-button" href="student-edit.php?id=<?= urlencode($student["student_id"]) ?>">Edit student</a>
        <a class="admin-button admin-button--muted" href="students.php">Back to students</a>
    </div>
    <dl class="admin-details"><?php foreach ([
        "Student ID" => "student_id", "Full name" => "student_name",
        "Username" => "username", "Email" => "email", "Mobile" => "mobile_no",
        "Gender" => "gender", "Course" => "course",
        "Academic year" => "academic_year", "Registered" => "time_stamp",
    ] as $label => $key): ?><dt><strong><?= adminEscape($label) ?></strong></dt><dd><?= adminEscape($student[$key] ?: "—") ?></dd><?php endforeach; ?></dl>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
