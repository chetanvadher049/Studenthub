<?php

require_once __DIR__ . "/auth.php";
studenthub_start_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    contactResult(
        "Invalid request",
        "Send your message through the contact form.",
        false,
        405
    );
}

if (!verifyCsrfToken($_POST["csrf_token"] ?? null)) {
    contactResult(
        "Form expired",
        "Reload the contact page and send your message again.",
        false,
        403
    );
}

$name = contactInput("name");
$email = trim(contactInput("email"));
$subject = contactInput("subject");
$message = contactInput("message");
$honeypot = contactInput("company");

if ($honeypot !== "") {
    contactResult(
        "Message not accepted",
        "Please submit the contact form directly.",
        false,
        400
    );
}

if (
    !preg_match("/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]{1,99}$/u", $name) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 100 ||
    $subject === "" ||
    strlen($subject) > 150 ||
    trim($message) === "" ||
    strlen($message) > 20000
) {
    contactResult(
        "Check your message",
        "Enter a valid name and email, a subject of up to 150 characters, and a message of up to 5,000 characters.",
        false,
        400
    );
}

try {
    $connection = studenthub_db();
    $statement = $connection->prepare(
        "INSERT INTO contact_messages (name, email, subject, message)
         VALUES (?, ?, ?, ?)"
    );
    $statement->bind_param("ssss", $name, $email, $subject, $message);
    $statement->execute();
    $statement->close();
    $connection->close();
} catch (mysqli_sql_exception $exception) {
    error_log("StudentHub contact submission failed: " . $exception->getMessage());
    contactResult(
        "Message could not be sent",
        "We could not save your message right now. Please try again later.",
        false,
        500
    );
}

contactResult(
    "Message received",
    "Thank you for contacting StudentHub. Your message has been saved and our team will get back to you.",
    true
);

function contactInput(string $key): string
{
    $value = $_POST[$key] ?? "";

    return is_string($value) ? trim($value) : "";
}

function contactResult(
    string $title,
    string $message,
    bool $success,
    int $statusCode = 200
): void {
    http_response_code($statusCode);
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, "UTF-8");
    $headingColor = $success ? "#166534" : "#b91c1c";
    $statusRole = $success ? "status" : "alert";
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $safeTitle ?> - StudentHub</title>
        <link rel="stylesheet" href="../html/css/contact.css">
        <link rel="stylesheet" href="../html/css/shared.css">
        <style>
            body {
                align-items: stretch;
                justify-content: flex-start;
                padding: 0;
            }

            .contact-result {
                width: min(100%, 520px);
                margin: auto;
                padding: clamp(24px, 6vw, 40px);
                border: 1px solid var(--border);
                border-radius: 16px;
                background: var(--surface);
                box-shadow: var(--shadow);
            }

            .contact-result h1 {
                margin-top: 0;
                color: <?= $headingColor ?>;
            }

            .contact-result p {
                color: var(--muted);
            }

            .contact-result a {
                display: inline-block;
                margin-top: 12px;
                padding: 11px 20px;
                border-radius: 9px;
                background: var(--brand);
                color: #fff;
                font-weight: 700;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <main class="contact-result">
            <h1 role="<?= $statusRole ?>"><?= $safeTitle ?></h1>
            <p><?= $safeMessage ?></p>
            <a href="../html/contact.html">Back to contact</a>
        </main>
        <?php
        $footerVariant = "minimal";
        require __DIR__ . "/footer.php";
        ?>
    </body>
    </html>
    <?php
    exit;
}
