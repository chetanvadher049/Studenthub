<?php
require_once __DIR__ . "/includes/bootstrap.php";
$studentId = trim((string) ($_GET["id"] ?? $_POST["student_id"] ?? ""));
if ($studentId === "") { http_response_code(400); exit("Choose a valid student."); }
$statement = $connection->prepare(
    "SELECT student_id, student_name, username, email, mobile_no, gender,
            course, academic_year, time_stamp
     FROM student WHERE student_id = ? LIMIT 1"
);
$statement->bind_param("s", $studentId);
$statement->execute();
$studentData = $statement->get_result()->fetch_assoc();
$statement->close();
if (!$studentData) { http_response_code(404); exit("Student not found."); }
$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    adminRequireCsrf();
    $input = adminStudentInput();
    $studentData = array_merge($studentData, [
        "full_name" => $input["full_name"],
        "username" => $input["username"],
        "email" => $input["email"],
        "phone" => $input["phone"],
        "course" => $input["course"],
        "gender" => $input["gender"],
        "academic_year" => $input["academic_year"],
        "password" => $input["password"],
    ]);
    $errors = adminStudentErrors($studentData, false);
    if (!$errors) {
        try {
            $check = $connection->prepare(
                "SELECT student_id FROM student
                 WHERE (email = ? OR username = ?) AND student_id <> ?
                 LIMIT 1"
            );
            $check->bind_param("sss", $input["email"], $input["username"], $studentId);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $errors[] = "Username or email is already in use.";
            }
            $check->close();
            if (!$errors) {
                if ($input["password"] === "") {
                    $update = $connection->prepare(
                        "UPDATE student
                         SET student_name = ?, username = ?, email = ?,
                             mobile_no = ?, gender = ?, course = ?, academic_year = ?
                         WHERE student_id = ?"
                    );
                    $update->bind_param("ssssssss", $input["full_name"], $input["username"], $input["email"], $input["phone"], $input["gender"], $input["course"], $input["academic_year"], $studentId);
                } else {
                    $hash = password_hash($input["password"], PASSWORD_DEFAULT);
                    $update = $connection->prepare(
                        "UPDATE student
                         SET student_name = ?, username = ?, email = ?,
                             mobile_no = ?, gender = ?, course = ?,
                             academic_year = ?, password = ?
                         WHERE student_id = ?"
                    );
                    $update->bind_param("sssssssss", $input["full_name"], $input["username"], $input["email"], $input["phone"], $input["gender"], $input["course"], $input["academic_year"], $hash, $studentId);
                }
                $update->execute();
                $update->close();
                adminFlash("Student updated successfully.");
                adminRedirect("student-view.php?id=" . urlencode($studentId));
            }
        } catch (mysqli_sql_exception $exception) {
            $errors[] = (int) $exception->getCode() === 1062
                ? "Username or email is already in use."
                : "Student could not be updated.";
            error_log("StudentHub admin student update failed: " . $exception->getMessage());
        }
    }
}
$pageTitle = "Edit Student";
require __DIR__ . "/includes/header.php";
?>
<?php if ($errors): ?><ul class="admin-errors"><?php foreach ($errors as $error): ?><li><?= adminEscape($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
<?php require __DIR__ . "/includes/student-form.php"; ?>
<?php require __DIR__ . "/includes/footer.php"; ?>
