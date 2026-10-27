<?php

require_once __DIR__ . "/dbconnect.php";

function studenthub_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        $lastActivity = $_SESSION["last_activity"] ?? null;
        if (
            is_int($lastActivity) &&
            time() - $lastActivity > 1200
        ) {
            $_SESSION = [];
            session_regenerate_id(true);
        }

        $_SESSION["last_activity"] = time();
        return;
    }

    ini_set("session.use_strict_mode", "1");
    ini_set("session.use_only_cookies", "1");
    ini_set("session.cookie_httponly", "1");
    ini_set("session.cookie_samesite", "Lax");
    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "secure" => isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
        "httponly" => true,
        "samesite" => "Lax",
    ]);

    session_start();

    $lastActivity = $_SESSION["last_activity"] ?? null;
    if (is_int($lastActivity) && time() - $lastActivity > 1200) {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    $_SESSION["last_activity"] = time();
}

function studenthub_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function studenthub_csrf_token(): string
{
    studenthub_start_session();

    if (!is_string($_SESSION["studenthub_csrf"] ?? null)) {
        $_SESSION["studenthub_csrf"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["studenthub_csrf"];
}

function csrfToken(): string
{
    return studenthub_csrf_token();
}

function verifyCsrfToken($token): bool
{
    return is_string($token)
        && isset($_SESSION["studenthub_csrf"])
        && is_string($_SESSION["studenthub_csrf"])
        && hash_equals($_SESSION["studenthub_csrf"], $token);
}

function studenthub_verify_csrf_token($token): bool
{
    return verifyCsrfToken($token);
}

function isAuthenticated(): bool
{
    studenthub_start_session();

    return ($_SESSION["authenticated"] ?? false) === true
        && in_array($_SESSION["user_type"] ?? null, ["student", "admin"], true)
        && (is_int($_SESSION["user_id"] ?? null)
            || is_string($_SESSION["user_id"] ?? null))
        && is_string($_SESSION["role"] ?? null)
        && is_string($_SESSION["user_name"] ?? null);
}

function currentUser(): ?array
{
    return isAuthenticated()
        ? [
            "id" => $_SESSION["user_id"],
            "type" => $_SESSION["user_type"],
            "role" => $_SESSION["role"],
            "name" => $_SESSION["user_name"],
        ]
        : null;
}

function requireStudent(): array
{
    if (!isAuthenticated()) {
        studenthub_require_authentication("student", 401);
    }
    if ($_SESSION["user_type"] !== "student") {
        studenthub_require_authentication("student", 403);
    }

    try {
        $connection = studenthub_db("studenthub");
        $statement = $connection->prepare(
            "SELECT student_id, student_name, username, email, mobile_no,
                    gender, course, academic_year, time_stamp AS created_at
             FROM student
             WHERE student_id = ?
             LIMIT 1"
        );
        $studentId = (string) $_SESSION["user_id"];
        $statement->bind_param("s", $studentId);
        $statement->execute();
        $student = $statement->get_result()->fetch_assoc();
        if ($student) {
            $student["status"] = "active";
        }
        $statement->close();
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub student authorization failed: " . $exception->getMessage());
        http_response_code(503);
        exit("Your account is temporarily unavailable. Please try again shortly.");
    }

    if (!$student) {
        expireCurrentSession();
        studenthub_require_authentication("student", 401);
    }

    return $student;
}

function requireAdmin(): array
{
    if (!isAuthenticated()) {
        studenthub_require_authentication("admin", 401);
    }
    if ($_SESSION["user_type"] !== "admin") {
        studenthub_require_authentication("admin", 403);
    }

    try {
        $adminDatabase = getenv("STUDENTHUB_ADMIN_DB_NAME") ?: "studenthub";
        $connection = studenthub_db($adminDatabase);
        $statement = $connection->prepare(
            "SELECT id, full_name, username, email, role
             FROM admins
             WHERE id = ? AND status = 'active' AND role IN ('admin', 'super_admin')
             LIMIT 1"
        );
        $statement->bind_param("i", $_SESSION["user_id"]);
        $statement->execute();
        $admin = $statement->get_result()->fetch_assoc();
        $statement->close();
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub admin session lookup failed: " . $exception->getMessage());
        http_response_code(503);
        exit("Your account is temporarily unavailable. Please try again shortly.");
    }

    if (!$admin || $admin["role"] !== $_SESSION["role"]) {
        expireCurrentSession();
        studenthub_require_authentication("admin", 401);
    }

    return $admin;
}

function requireSuperAdmin(): array
{
    $admin = requireAdmin();
    if ($admin["role"] !== "super_admin") {
        http_response_code(403);
        exit("This action requires a super administrator.");
    }

    return $admin;
}

function studenthub_require_login(): array
{
    return requireStudent();
}

function studenthub_require_admin(): array
{
    return requireAdmin();
}

function studenthub_require_authentication(string $userType, int $status): void
{
    $scriptPath = str_replace("\\", "/", $_SERVER["SCRIPT_NAME"] ?? "");
    if (str_contains($scriptPath, "/php/")) {
        http_response_code($status);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            "error" => $status === 401
                ? "Sign in to continue."
                : "You are not authorized to perform this action.",
        ]);
        exit;
    }

    $loginPath = str_contains($scriptPath, "/html/admin/")
        ? "../login.html"
        : (str_contains($scriptPath, "/php/") ? "../html/login.html" : "login.html");
    if ($userType === "admin") {
        $loginPath .= "?mode=admin";
    }
    header("Location: " . $loginPath);
    exit;
}

function expireCurrentSession(): void
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), "", [
            "expires" => time() - 42000,
            "path" => $cookie["path"],
            "domain" => $cookie["domain"],
            "secure" => $cookie["secure"],
            "httponly" => $cookie["httponly"],
            "samesite" => $cookie["samesite"] ?? "Lax",
        ]);
    }

    session_destroy();
}
