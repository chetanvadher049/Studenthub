<?php

require_once __DIR__ . "/auth.php";
studenthub_start_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../html/login.html");
    exit;
}

$loginMode = $_POST["user_type"] ?? "student";
if (!is_string($loginMode) || !in_array($loginMode, ["student", "admin"], true)) {
    showError("Invalid username or password.", 401);
}

if (!verifyCsrfToken($_POST["csrf_token"] ?? null)) {
    showError("Your login form expired. Reload the page and try again.", 403, $loginMode);
}

$identifier = $_POST["identifier"] ?? "";
$password = $_POST["password"] ?? "";
if (
    !is_string($identifier) ||
    !is_string($password) ||
    trim($identifier) === "" ||
    $password === "" ||
    strlen($identifier) > 255 ||
    strlen($password) > 255
) {
    showError("Invalid username or password.", 401, $loginMode);
}
$identifier = trim($identifier);

$throttleKey = hash(
    "sha256",
    $loginMode . "|" . ($_SERVER["REMOTE_ADDR"] ?? "unknown") . "|" . strtolower($identifier)
);

try {
    $database = $loginMode === "student"
        ? "studenthub"
        : (getenv("STUDENTHUB_ADMIN_DB_NAME") ?: "studenthub");
    $connection = studenthub_db($database);

    if ($loginMode === "admin" && loginIsThrottled($connection, $throttleKey)) {
        $connection->close();
        showError("Invalid username or password. Please try again later.", 429, $loginMode);
    }

    if ($loginMode === "admin") {
        $statement = $connection->prepare(
            "SELECT id, full_name, email, password_hash, role
             FROM admins
             WHERE (email = ? OR username = ?) AND status = 'active'
             LIMIT 1"
        );
        $statement->bind_param("ss", $identifier, $identifier);
        $statement->execute();
        $account = $statement->get_result()->fetch_assoc();
    } else {
        $statement = $connection->prepare(
            "SELECT student_id AS id, student_name AS full_name,
                    email, password
             FROM student
             WHERE email = ? OR username = ?
             LIMIT 1"
        );
        $statement->bind_param("ss", $identifier, $identifier);
        $statement->execute();
        $account = $statement->get_result()->fetch_assoc();
    }

    $storedPassword = $account["password_hash"] ?? $account["password"] ?? "";
    if (!$account || !password_verify($password, $storedPassword)) {
        $statement->close();
        if ($loginMode === "admin") {
            recordFailedLogin($connection, $throttleKey);
        }
        $connection->close();
        showError("Invalid username or password.", 401, $loginMode);
    }

    if ($loginMode === "admin") {
        $adminId = (int) $account["id"];
        $lastLogin = $connection->prepare(
            "UPDATE admins SET last_login_at = CURRENT_TIMESTAMP WHERE id = ?"
        );
        $lastLogin->bind_param("i", $adminId);
        $lastLogin->execute();
        $lastLogin->close();
        $role = $account["role"];
    } else {
        $role = "student";
    }

    if ($loginMode === "admin") {
        clearLoginFailures($connection, $throttleKey);
    }
    $statement->close();
    $connection->close();

    session_regenerate_id(true);
    $_SESSION = [
        "authenticated" => true,
        "user_id" => $loginMode === "student"
            ? (string) $account["id"]
            : (int) $account["id"],
        "user_type" => $loginMode,
        "role" => $role,
        "user_name" => $account["full_name"],
        "last_activity" => time(),
        "studenthub_csrf" => bin2hex(random_bytes(32)),
    ];

    if ($loginMode === "admin") {
        header("Location: ../html/admin/dashboard.php");
        exit;
    }

    $returnTo = $_POST["return_to"] ?? "";
    header("Location: " . ($returnTo === "events.html"
        ? "../html/events.html"
        : "../html/dashboard.php"));
    exit;
} catch (mysqli_sql_exception $exception) {
    error_log("StudentHub login database error: " . $exception->getMessage());
    http_response_code(503);
    showError("Sign-in is temporarily unavailable. Please try again shortly.", 503, $loginMode);
}

function loginIsThrottled(mysqli $connection, string $key): bool
{
    $statement = $connection->prepare(
        "SELECT locked_until FROM login_attempts WHERE attempt_key = ? LIMIT 1"
    );
    $statement->bind_param("s", $key);
    $statement->execute();
    $attempt = $statement->get_result()->fetch_assoc();
    $statement->close();

    return $attempt
        && $attempt["locked_until"] !== null
        && strtotime($attempt["locked_until"]) > time();
}

function recordFailedLogin(mysqli $connection, string $key): void
{
    $statement = $connection->prepare(
        "INSERT INTO login_attempts (attempt_key, attempts, window_started_at)
         VALUES (?, 1, CURRENT_TIMESTAMP)
         ON DUPLICATE KEY UPDATE
            attempts = IF(window_started_at < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 15 MINUTE), 1, attempts + 1),
            locked_until = IF(window_started_at < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 15 MINUTE), NULL, locked_until),
            window_started_at = IF(window_started_at < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 15 MINUTE), CURRENT_TIMESTAMP, window_started_at)"
    );
    $statement->bind_param("s", $key);
    $statement->execute();
    $statement->close();

    $statement = $connection->prepare(
        "UPDATE login_attempts
         SET locked_until = IF(attempts >= 5, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 15 MINUTE), locked_until)
         WHERE attempt_key = ?"
    );
    $statement->bind_param("s", $key);
    $statement->execute();
    $statement->close();
}

function clearLoginFailures(mysqli $connection, string $key): void
{
    $statement = $connection->prepare("DELETE FROM login_attempts WHERE attempt_key = ?");
    $statement->bind_param("s", $key);
    $statement->execute();
    $statement->close();
}


// =====================================================
// ERROR FUNCTION
// =====================================================

function showError(
    string $message,
    int $statusCode = 400,
    string $loginMode = "student"
): void
{
    http_response_code($statusCode);
    $loginUrl = "../html/login.html"
        . ($loginMode === "admin" ? "?mode=admin" : "");
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width,
                 initial-scale=1.0"
    >

    <title>
        StudentHub - Login Error
    </title>

    <link rel="stylesheet" href="../html/css/shared.css">
    <style>

        * {
            box-sizing: border-box;
        }

        body {

            margin: 0;

            min-height: 100vh;

            align-items: stretch;

            justify-content: flex-start;

            padding: 0;

            background: #f4f7fb;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .error-box {

            width: 90%;

            max-width: 500px;

            margin: auto;

            background: white;

            padding: 35px;

            text-align: center;

            border-radius: 15px;

            box-shadow:
                0 8px 30px
                rgba(0, 0, 0, 0.10);
        }

        h1 {

            color: #dc2626;

            margin-bottom: 12px;
        }

        p {

            color: #4b5563;

            line-height: 1.6;
        }

        a {

            display: inline-block;

            margin-top: 15px;

            margin-right: 5px;

            padding: 12px 20px;

            background: #4f46e5;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-weight: 700;
        }

        a:hover {

            background: #3730a3;
        }

        .studenthub-footer a {
            margin: 0;
            padding: 0;
            background: none;
            border-radius: 0;
        }

    </style>

</head>


<body>

    <div class="error-box">

        <h1>
            Login Failed
        </h1>

        <p>
            <?php
                echo htmlspecialchars(
                    $message,
                    ENT_QUOTES,
                    "UTF-8"
                );
            ?>
        </p>

        <a href="<?= htmlspecialchars($loginUrl, ENT_QUOTES, "UTF-8") ?>">
            Try Again
        </a>

        <a href="../html/register.html">
            Register
        </a>

    </div>
    <?php
    $footerPagePrefix = "../html/";
    $footerVariant = "minimal";
    require __DIR__ . "/footer.php";
    ?>

</body>

</html>

<?php
    exit;
}