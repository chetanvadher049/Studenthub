<?php

require_once __DIR__ . "/dbconnect.php";

function studenthub_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "secure" => isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
        "httponly" => true,
        "samesite" => "Lax",
    ]);

    session_start();
}

function studenthub_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function studenthub_require_login(): array
{
    studenthub_start_session();

    $studentId = $_SESSION["student_id"] ?? null;
    if (
        empty($_SESSION["logged_in"]) ||
        !is_string($studentId) ||
        $studentId === ""
    ) {
        header("Location: login.html");
        exit;
    }

    try {
        $connection = studenthub_db();
        $statement = $connection->prepare(
            "SELECT student_id, student_name, email, mobile_no, gender,
                    course, academic_year
             FROM student
             WHERE student_id = ?
             LIMIT 1"
        );
        $statement->bind_param("s", $studentId);
        $statement->execute();
        $student = $statement->get_result()->fetch_assoc();
        $statement->close();
        $connection->close();
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub session lookup failed: " . $exception->getMessage());
        http_response_code(503);
        exit("Your account is temporarily unavailable. Please try again shortly.");
    }

    if (!$student) {
        $_SESSION = [];
        session_destroy();
        header("Location: login.html");
        exit;
    }

    $_SESSION["student_name"] = $student["student_name"];
    $_SESSION["student_email"] = $student["email"];
    $_SESSION["student_mobile"] = $student["mobile_no"];
    $_SESSION["student_gender"] = $student["gender"];

    return $student;
}
