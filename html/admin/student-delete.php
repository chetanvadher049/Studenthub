<?php
require_once __DIR__ . "/includes/bootstrap.php";
adminRequirePost();
adminRequireCsrf();
$studentId = trim((string) ($_POST["student_id"] ?? ""));
if ($studentId === "") { http_response_code(400); exit("Choose a valid student."); }
try {
    $statement = $connection->prepare("DELETE FROM student WHERE student_id = ?");
    $statement->bind_param("s", $studentId);
    $statement->execute();
    adminFlash($statement->affected_rows ? "Student deleted successfully." : "Student not found.", $statement->affected_rows ? "success" : "error");
    $statement->close();
} catch (mysqli_sql_exception $exception) {
    error_log("StudentHub student deletion failed: " . $exception->getMessage());
    adminFlash("Student could not be deleted.", "error");
}
adminRedirect("students.php");
