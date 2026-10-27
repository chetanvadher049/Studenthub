<?php
require_once __DIR__ . "/auth.php";
studenthub_start_session();

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

    if (!studenthub_verify_csrf_token($_POST["csrf_token"] ?? null)) {
        registrationError(
            "Registration form expired",
            "Reload the registration page and submit the form again.",
            403
        );
    }

    $studentName = trim(postedString("student_name"));
    $username = trim(postedString("username"));
    $email = trim(postedString("email"));
    $mobileNumber = trim(postedString("mobile_no"));
    $gender = trim(postedString("gender"));
    $course = trim(postedString("course"));
    $academicYear = trim(postedString("academic_year"));
    $password = postedString("password");
    $confirmPassword = postedString("confirm_password");
    $termsAccepted = isset($_POST["terms"]);

    if (
        !preg_match(
            "/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]{1,99}$/u",
            $studentName
        )
    ) {
        registrationError(
            "Check your name",
            "Enter a name between 2 and 100 characters using letters, spaces, apostrophes or hyphens."
        );
    }

    if (!preg_match("/^[A-Za-z0-9_]{3,30}$/", $username)) {
        registrationError(
            "Check your username",
            "Use 3 to 30 letters, numbers or underscores for your username."
        );
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        registrationError(
            "Check your email",
            "Enter a valid email address of no more than 100 characters."
        );
    }

    if (!preg_match("/^[0-9]{10}$/", $mobileNumber)) {
        registrationError(
            "Check your mobile number",
            "Enter exactly 10 digits."
        );
    }

    $allowed_gender = ["Male", "Female", "Other"];

    if (!in_array($gender, $allowed_gender, true)) {
        registrationError("Choose a gender", "Select one of the available options.");
    }

    if ($course === "" || strlen($course) > 100) {
        registrationError(
            "Check your course",
            "Enter a course name of no more than 100 characters."
        );
    }

    if ($academicYear === "" || strlen($academicYear) > 30) {
        registrationError(
            "Check your academic year",
            "Enter an academic year of no more than 30 characters."
        );
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


    if ($password !== $confirmPassword) {
        registrationError(
            "Passwords do not match",
            "Enter the same password in both password fields."
        );
    }

    if (!$termsAccepted) {
        registrationError(
            "Terms required",
            "Accept the Terms & Conditions before creating your account."
        );
    }

    /*
     * The registration practical uses the legacy StudentHub database:
     * studenthub.student. Keep the form-to-column mapping explicit because
     * that table uses student_name, mobile_no and password column names.
     */
    $conn = studenthub_db("studenthub");

    $existing_account = $conn->prepare(
        "SELECT email, username
         FROM student
         WHERE email = ? OR username = ?
         LIMIT 1"
    );

    $existing_account->bind_param("ss", $email, $username);
    $existing_account->execute();
    $existing_account->store_result();

    if ($existing_account->num_rows > 0) {
        $existing_account->close();
        $conn->close();

        registrationError(
            "Account details already in use",
            "An account already uses this email address or username. Choose different details or sign in.",
            409
        );
    }

    $existing_account->close();

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $studentId = "STU_" . bin2hex(random_bytes(12));

    $stmt = $conn->prepare(
        "INSERT INTO student
        (
            student_id,
            student_name,
            username,
            email,
            mobile_no,
            gender,
            course,
            academic_year,
            password
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssssssss",
        $studentId,
        $studentName,
        $username,
        $email,
        $mobileNumber,
        $gender,
        $course,
        $academicYear,
        $passwordHash
    );

    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: ../html/login.html?registered=1");
    exit;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Successful</title>

    <link rel="stylesheet" href="../html/css/shared.css">
    <style>

        * {
            box-sizing: border-box;
        }

        body {
            align-items: stretch;
            justify-content: flex-start;
            font-family: Arial, sans-serif;
            background: #f5f8ff;
        }

        .success-box {

            width: 90%;
            max-width: 500px;
            margin: auto;

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

<?php
} catch (mysqli_sql_exception $e) {

    if ((int) $e->getCode() === 1062) {
        http_response_code(409);
        registrationError(
            "Account details already in use",
            "An account already uses this email address or username. Choose different details or sign in.",
            409
        );
    }

    error_log("StudentHub registration database error: " . $e->getMessage());
    http_response_code(500);
    registrationError(
        "Registration unavailable",
        "We could not create your account right now. Please try again later.",
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
                align-items: stretch;
                justify-content: flex-start;
                padding: 0;
            }

            .registration-error {
                width: min(100%, 520px);
                margin: auto;
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