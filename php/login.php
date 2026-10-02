<?php

// =====================================================
// STUDENTHUB LOGIN
// =====================================================
//
// Database : studenthub
// Table    : student
//
// Columns:
// student_id
// student_name
// email
// mobile_no
// gender
// password
//
// =====================================================


// =====================================================
// START SESSION
// =====================================================

require_once __DIR__ . "/auth.php";
studenthub_start_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../html/login.html");
    exit;
}

try {
    $conn = studenthub_db();

    // =================================================
    // GET LOGIN DATA
    // =================================================

    $emailInput = $_POST["email"] ?? "";
    $passwordInput = $_POST["password"] ?? "";
    if (!is_string($emailInput) || !is_string($passwordInput)) {
        showError("Please enter a valid email address and password.");
    }

    $email = trim($emailInput);
    $password = $passwordInput;


    // =================================================
    // VALIDATE EMPTY VALUES
    // =================================================

    if (
        $email === "" ||
        $password === ""
    ) {

        showError(
            "Please enter email and password."
        );
    }


    // =================================================
    // VALIDATE EMAIL
    // =================================================

    if (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        showError(
            "Please enter a valid email address."
        );
    }


    // =================================================
    // FIND STUDENT
    // =================================================

    $stmt = $conn->prepare(
        "SELECT
            student_id,
            student_name,
            email,
            mobile_no,
            gender,
            course,
            academic_year,
            password
         FROM student
         WHERE email = ?
         LIMIT 1"
    );


    $stmt->bind_param(
        "s",
        $email
    );


    $stmt->execute();


    $result =
        $stmt->get_result();


    // =================================================
    // CHECK USER EXISTS
    // =================================================

    if (
        $result->num_rows !== 1
    ) {

        $stmt->close();

        showError(
            "Invalid email or password."
        );
    }


    // =================================================
    // GET STUDENT DATA
    // =================================================

    $student =
        $result->fetch_assoc();


    // =================================================
    // CHECK PASSWORD
    // =================================================

    if (
        !password_verify(
            $password,
            $student["password"]
        )
    ) {

        $stmt->close();

        showError(
            "Invalid email or password."
        );
    }


    // =================================================
    // LOGIN SUCCESS
    // =================================================

    session_regenerate_id(true);


    $_SESSION["logged_in"] =
        true;


    $_SESSION["student_id"] =
        $student["student_id"];


    $_SESSION["student_name"] =
        $student["student_name"];


    $_SESSION["student_email"] =
        $student["email"];


    $_SESSION["student_mobile"] =
        $student["mobile_no"];


    $_SESSION["student_gender"] =
        $student["gender"];


    // =================================================
    // CLOSE CONNECTION
    // =================================================

    $stmt->close();

    $conn->close();


    // =================================================
    // REDIRECT AFTER LOGIN
    // =================================================

    $returnTo = $_POST["return_to"] ?? "";
    $destination = $returnTo === "events.html"
        ? "../html/events.html"
        : "../html/dashboard.php";
    header("Location: " . $destination);

    exit;


}


// =====================================================
// DATABASE ERROR
// =====================================================

catch (
    mysqli_sql_exception $e
) {

    error_log("StudentHub login database error: " . $e->getMessage());
    http_response_code(500);
    showError(
        "Sign-in is temporarily unavailable. Please try again shortly.",
        500
    );
}


// =====================================================
// ERROR FUNCTION
// =====================================================

function showError(string $message, int $statusCode = 400): void
{
    http_response_code($statusCode);
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


    <style>

        * {
            box-sizing: border-box;
        }

        body {

            margin: 0;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f4f7fb;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .error-box {

            width: 90%;

            max-width: 500px;

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

        <a href="../html/login.html">
            Try Again
        </a>

        <a href="../html/register.html">
            Register
        </a>

    </div>

</body>

</html>

<?php
    exit;
}