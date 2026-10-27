<?php
require_once __DIR__ . "/includes/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    adminRequireCsrf();
    $studentId = trim((string) ($_POST["student_id"] ?? ""));
    if ($studentId === "") {
        adminFlash("Choose a valid student.", "error");
        adminRedirect("students.php");
    }

    try {
        $statement = $connection->prepare(
            "DELETE FROM student WHERE student_id = ?"
        );
        $statement->bind_param("s", $studentId);
        $statement->execute();
        adminFlash($statement->affected_rows ? "Student deleted successfully." : "Student not found.", $statement->affected_rows ? "success" : "error");
        $statement->close();
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub student delete failed: " . $exception->getMessage());
        adminFlash("Student could not be deleted.", "error");
    }
    adminRedirect("students.php");
}

$search = trim(is_string($_GET["q"] ?? null) ? $_GET["q"] : "");
$courseFilter = trim(is_string($_GET["course"] ?? null) ? $_GET["course"] : "");
$conditions = [];
$parameters = [];
$types = "";
if ($search !== "") {
    $conditions[] = "(student_id LIKE ? OR student_name LIKE ? OR username LIKE ? OR email LIKE ? OR mobile_no LIKE ?)";
    $term = "%{$search}%";
    $parameters = [$term, $term, $term, $term, $term];
    $types = "sssss";
}
if ($courseFilter !== "") {
    $conditions[] = "course = ?";
    $parameters[] = $courseFilter;
    $types .= "s";
}
$where = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";

$count = $connection->prepare("SELECT COUNT(*) AS total FROM student" . $where);
if ($types !== "") {
    $count->bind_param($types, ...$parameters);
}
$count->execute();
$totalRows = (int) $count->get_result()->fetch_assoc()["total"];
$count->close();

$statement = $connection->prepare(
    "SELECT student_id, student_name, username, email, mobile_no,
            gender, course, academic_year, time_stamp
     FROM student" . $where . " ORDER BY time_stamp DESC"
);
if ($types !== "") {
    $statement->bind_param($types, ...$parameters);
}
$statement->execute();
$students = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$statement->close();
$courseResult = $connection->query(
    "SELECT DISTINCT course FROM student WHERE course <> '' ORDER BY course"
);
$courses = array_column($courseResult->fetch_all(MYSQLI_NUM), 0);
$courseResult->free();

$pageTitle = "Students";
require __DIR__ . "/includes/header.php";
?>
<section class="admin-card">
    <div class="admin-actions"><a class="admin-button" href="student-create.php">Add student</a></div>
    <form class="admin-filters" method="get">
        <label>Search <input type="search" name="q" value="<?= adminEscape($search) ?>" placeholder="ID, name, username, email"></label>
        <label>Course <select name="course"><option value="">All courses</option>
            <?php foreach ($courses as $course): ?><option value="<?= adminEscape($course) ?>"<?= $courseFilter === $course ? " selected" : "" ?>><?= adminEscape($course) ?></option><?php endforeach; ?>
        </select></label>
        <button class="admin-button" type="submit">Search / Filter</button>
    </form>
    <div class="admin-table-wrap"><table class="admin-table">
        <thead><tr><th>Student ID</th><th>Name</th><th>Username</th><th>Email</th><th>Mobile</th><th>Course</th><th>Registered</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= adminEscape($student["student_id"]) ?></td>
                <td><?= adminEscape($student["student_name"]) ?></td>
                <td><?= adminEscape($student["username"]) ?></td>
                <td><?= adminEscape($student["email"]) ?></td>
                <td><?= adminEscape($student["mobile_no"]) ?></td>
                <td><?= adminEscape($student["course"] ?: "—") ?></td>
                <td><?= adminEscape($student["time_stamp"]) ?></td>
                <td class="admin-actions">
                    <a href="student-view.php?id=<?= urlencode($student["student_id"]) ?>">View</a>
                    <a href="student-edit.php?id=<?= urlencode($student["student_id"]) ?>">Edit</a>
                    <form method="post" onsubmit="return confirm('Delete this student?');">
                        <input type="hidden" name="csrf_token" value="<?= adminEscape(csrfToken()) ?>">
                        <input type="hidden" name="student_id" value="<?= adminEscape($student["student_id"]) ?>">
                        <button class="admin-button--danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$students): ?><tr><td colspan="8">No students match your search.</td></tr><?php endif; ?>
        </tbody>
    </table></div>
    <p class="admin-muted"><?= $totalRows ?> student(s) found.</p>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
