<?php

require_once __DIR__ . "/auth.php";

studenthub_start_session();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!studenthub_verify_csrf_token($_POST["csrf_token"] ?? null)) {
        http_response_code(403);
        exit("Your sign-out request expired. Reload this page and try again.");
    }

    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $cookie = session_get_cookie_params();
        setcookie(
            session_name(),
            "",
            [
                "expires" => time() - 42000,
                "path" => $cookie["path"],
                "domain" => $cookie["domain"],
                "secure" => $cookie["secure"],
                "httponly" => $cookie["httponly"],
                "samesite" => $cookie["samesite"] ?? "Lax",
            ]
        );
    }

    session_destroy();
    header("Location: ../html/login.html?logged_out=1");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    header("Allow: GET, POST");
    exit("Unsupported request method.");
}

$safeToken = studenthub_escape(studenthub_csrf_token());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign out - StudentHub</title>
    <link rel="stylesheet" href="../html/css/shared.css">
    <style>
        .sign-out-card {
            width: min(100% - 32px, 480px);
            margin: 12vh auto;
            padding: 32px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: var(--shadow);
            text-align: center;
        }

        .sign-out-card h1 {
            margin-top: 0;
        }

        .sign-out-card button {
            margin-top: 12px;
            padding: 11px 20px;
            border: 0;
            border-radius: 9px;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .sign-out-card a {
            display: inline-block;
            margin-left: 10px;
            color: var(--brand);
        }
    </style>
</head>
<body>
    <main class="sign-out-card">
        <h1>Sign out of StudentHub?</h1>
        <p>Confirm to end your current session.</p>
        <form action="../php/logout.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $safeToken ?>">
            <button type="submit">Sign out</button>
            <a href="../html/index.html">Cancel</a>
        </form>
    </main>
</body>
</html>
