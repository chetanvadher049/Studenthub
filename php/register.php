<?php
require_once __DIR__ . "/dbconnect.php";

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        header("Allow: POST");
        registrationError(
            "Invalid request",
            "Submit the registration form to create your account.",
            405
        );
    }

    $student_name = trim(postedString("student_name"));
    $email        = trim(postedString("email"));
    $mobile_no    = trim(postedString("mobile_no"));
    $gender       = trim(postedString("gender"));
    $password     = postedString("password");
    $confirm      = postedString("confirm_password");
    $terms        = isset($_POST["terms"]);

    if (
        !preg_match(
            "/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]{1,99}$/u",
            $student_name
        )
    ) {
        registrationError(
            "Check your name",
            "Enter a name between 2 and 100 characters using letters, spaces, apostrophes or hyphens."
        );
    }

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 100
    ) {
        registrationError(
            "Check your email",
            "Enter a valid email address of no more than 100 characters."
        );
    }

    if (!preg_match("/^[0-9]{10}$/", $mobile_no)) {
        registrationError(
            "Check your mobile number",
            "Enter exactly 10 digits."
        );
    }

    $allowed_gender = ["Male", "Female", "Other"];

    if (!in_array($gender, $allowed_gender, true)) {
        registrationError("Choose a gender", "Select one of the available options.");
    }


    if (
        strlen($password) < 8 ||
        strlen($password) > 100 ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[@$!%*?&]/", $password)
    ) {
        registrationError(
            "Choose a stronger password",
            "Use 8 to 100 characters, including uppercase and lowercase letters, a number, and one of @$!%*?&."
        );
    }


    if ($password !== $confirm) {
        registrationError(
            "Passwords do not match",
            "Enter the same password in both password fields."
        );
    }

    if (!$terms) {
        registrationError(
            "Terms required",
            "Accept the Terms & Conditions before creating your account."
        );
    }

    $conn = studenthub_db();

    $check = $conn->prepare(
        "SELECT student_id
         FROM student
         WHERE email = ?
         LIMIT 1"
    );

    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {

        $check->close();
        $conn->close();

        registrationError(
            "Email already registered",
            "An account already uses this email address. Try signing in instead.",
            409
        );
    }

    $check->close();

    $student_id = bin2hex(random_bytes(16));
    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    $stmt = $conn->prepare(
        "INSERT INTO student
        (
            student_id,
            student_name,
            email,
            mobile_no,
            gender,
            password,
            time_stamp
        )
        VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)"
    );

    $stmt->bind_param(
        "ssssss",
        $student_id,
        $student_name,
        $email,
        $mobile_no,
        $gender,
        $hashed_password
    );

    $stmt->execute();

    $stmt->close();
    $conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Successful</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;

            font-family: Arial, sans-serif;

            background: #f5f8ff;

            display: flex;
            justify-content: center;
            align-items: center;

            min-height: 100vh;
        }

        .success-box {

            width: 90%;
            max-width: 500px;

            background: #ffffff;

            padding: 40px 30px;

            border-radius: 15px;

            text-align: center;

            box-shadow: 0 12px 32px rgba(23, 43, 77, 0.08);
        }

        .success-icon {

            width: 70px;
            height: 70px;

            margin: 0 auto 20px;

            background: #dcfce7;

            color: #16a34a;

            border-radius: 50%;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 38px;
        }

        h1 {

            color: #16a34a;

            margin-bottom: 15px;
        }

        p {

            color: #555;

            line-height: 1.6;
        }

        .student-id {

            background: #f3f4f6;

            padding: 12px;

            border-radius: 8px;

            margin: 20px 0;

            word-break: break-all;

            font-size: 14px;
        }

        .btn {

            display: inline-block;

            margin-top: 15px;

            padding: 12px 25px;

            background: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            transition: 0.3s;
        }

        .btn:hover {

            background: #1d4ed8;
        }

    </style>

</head>

<body>

    <div class="success-box">

        <div class="success-icon">
            ✓
        </div>

        <h1>
            Registration Successful!
        </h1>

        <p>
            Your StudentHub account has been
            created successfully.
        </p>

        <div class="student-id">

            <strong>Student ID:</strong><br>

            <?php
            echo htmlspecialchars((string) $student_id, ENT_QUOTES, "UTF-8");
            ?>

        </div>

        <a
            href="../html/login.html"
            class="btn"
        >
            Go to Login
        </a>

    </div>

</body>

</html>

<?php

} catch (mysqli_sql_exception $e) {

    if ((int) $e->getCode() === 1062) {
        http_response_code(409);
        registrationError(
            "Email already registered",
            "An account already uses this email address. Try signing in instead.",
            409
        );
    }

    error_log("StudentHub registration database error: " . $e->getMessage());
    http_response_code(500);
    registrationError(
        "Registration unavailable",
        "We could not create your account right now. Check the database configuration and try again.",
        500
    );
}

function postedString(string $key): string
{
    $value = $_POST[$key] ?? "";

    return is_string($value) ? $value : "";
}

function registrationError(
    string $title,
    string $message,
    int $statusCode = 400
): void
{
    http_response_code($statusCode);
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, "UTF-8");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $safeTitle ?> - StudentHub</title>
        <link rel="stylesheet" href="../html/css/shared.css">
        <style>
            body {
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .registration-error {
                width: min(100%, 520px);
                padding: clamp(24px, 6vw, 40px);
                border: 1px solid var(--border);
                border-radius: 16px;
                background: var(--surface);
                box-shadow: var(--shadow);
            }

            .registration-error h1 {
                margin-top: 0;
                color: #b91c1c;
            }

            .registration-error p {
                color: var(--muted);
            }

            .registration-error a {
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
        <main class="registration-error">
            <h1 role="alert"><?= $safeTitle ?></h1>
            <p><?= $safeMessage ?></p>
            <a href="../html/register.html">Back to registration</a>
        </main>
    </body>
    </html>
    <?php
    exit;
}