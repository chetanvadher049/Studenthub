<?php
require_once __DIR__ . "/includes/bootstrap.php";
$studentData = ["status" => "active"];
$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    adminRequireCsrf();
    $studentData = adminStudentInput();
    $errors = adminStudentErrors($studentData, true);
    if (!$errors) {
        try {
            $studentId = "STU_" . bin2hex(random_bytes(12));
            $hash = password_hash($studentData["password"], PASSWORD_DEFAULT);
            $statement = $connection->prepare(
                "INSERT INTO student
                 (student_id, student_name, username, email, mobile_no, gender, course, academic_year, password)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $statement->bind_param("sssssssss", $studentId, $studentData["full_name"], $studentData["username"], $studentData["email"], $studentData["phone"], $studentData["gender"], $studentData["course"], $studentData["academic_year"], $hash);
            $statement->execute();
            $statement->close();
            adminFlash("Student created successfully.");
            adminRedirect("student-view.php?id=" . urlencode($studentId));
        } catch (mysqli_sql_exception $exception) {
            $errors[] = (int) $exception->getCode() === 1062
                ? "Username or email is already in use."
                : "Student could not be created.";
            error_log("StudentHub admin student creation failed: " . $exception->getMessage());
        }
    }
}
$pageTitle = "Add Student";
require __DIR__ . "/includes/header.php";
?>
<?php if ($errors): ?><ul class="admin-errors"><?php foreach ($errors as $error): ?><li><?= adminEscape($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
<?php require __DIR__ . "/includes/student-form.php"; ?>
<?php require __DIR__ . "/includes/footer.php"; ?>
