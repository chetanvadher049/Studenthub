<?php
require_once __DIR__ . "/../php/auth.php";
$student = studenthub_require_login();
$profileForm = $student;
$profileError = "";

if (empty($_SESSION["studenthub_csrf"])) {
    $_SESSION["studenthub_csrf"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (
        !is_string($token) ||
        !hash_equals($_SESSION["studenthub_csrf"], $token)
    ) {
        http_response_code(400);
        $profileError = "Your form expired. Reload the page and try again.";
    } else {
        $profileInput = static function (string $key): string {
            $value = $_POST[$key] ?? "";
            return is_string($value) ? trim($value) : "";
        };

        $name = $profileInput("student_name");
        $email = $profileInput("email");
        $mobile = $profileInput("mobile_no");
        $gender = $profileInput("gender");
        $course = $profileInput("course");
        $academicYear = $profileInput("academic_year");
        $profileForm = array_merge($student, [
            "student_name" => $name,
            "email" => $email,
            "mobile_no" => $mobile,
            "gender" => $gender,
            "course" => $course,
            "academic_year" => $academicYear,
        ]);

        if (
            !preg_match(
                "/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]{1,99}$/u",
                $name
            )
        ) {
            $profileError = "Enter a valid name between 2 and 100 characters.";
        } elseif (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($email) > 100
        ) {
            $profileError = "Enter a valid email address.";
        } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
            $profileError = "Enter a 10-digit mobile number.";
        } elseif (!in_array($gender, ["Male", "Female", "Other"], true)) {
            $profileError = "Select a valid gender.";
        } elseif (strlen($course) > 100 || strlen($academicYear) > 30) {
            $profileError = "Course or academic year is too long.";
        } else {
            try {
                $connection = studenthub_db();
                $check = $connection->prepare(
                    "SELECT student_id
                     FROM student
                     WHERE email = ? AND student_id <> ?
                     LIMIT 1"
                );
                $check->bind_param("ss", $email, $student["student_id"]);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $profileError = "That email address is already in use.";
                } else {
                    $update = $connection->prepare(
                        "UPDATE student
                         SET student_name = ?, email = ?, mobile_no = ?,
                             gender = ?, course = ?, academic_year = ?
                         WHERE student_id = ?"
                    );
                    $update->bind_param(
                        "sssssss",
                        $name,
                        $email,
                        $mobile,
                        $gender,
                        $course,
                        $academicYear,
                        $student["student_id"]
                    );
                    $update->execute();
                    $update->close();

                    $_SESSION["student_name"] = $name;
                    $_SESSION["student_email"] = $email;
                    $_SESSION["student_mobile"] = $mobile;
                    $_SESSION["student_gender"] = $gender;
                    $check->close();
                    $connection->close();

                    header("Location: profile.php?updated=1");
                    exit;
                }

                $check->close();
                $connection->close();
            } catch (mysqli_sql_exception $exception) {
                error_log("StudentHub profile update failed: " . $exception->getMessage());
                http_response_code(500);
                $profileError = "Your profile could not be updated. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>StudentHub - Profile</title>

    <link rel="stylesheet"
          href="css/profile.css">
    <link rel="stylesheet" href="css/shared.css">


</head>


<body>


<!-- =====================================================
     HEADER
     ===================================================== -->

<header class="site-header">


    <!-- HAMBURGER MENU -->

    <div class="menu-container">

        <button id="menuToggle"
                class="menu-button"
                type="button"
                aria-label="Open navigation menu"
                aria-expanded="false">

            <span></span>
            <span></span>
            <span></span>

        </button>


        <!-- NAVIGATION -->

        <nav id="sidebar"
             class="dropdown-menu"
             aria-label="Main navigation">

            <a href="index.html">
                🏠
                <span>Home</span>
            </a>

            <a href="about.html">
                ⓘ
                <span>About</span>
            </a>

            <a href="dashboard.php">
                ▣
                <span>Dashboard</span>
            </a>

            <a href="events.html">
                ▦
                <span>Events</span>
            </a>

            <a href="profile.php"
               aria-current="page">
                👤
                <span>Profile</span>
            </a>

            <a href="contact.html">
                ✉
                <span>Contact</span>
            </a>

            <a href="admin.html">
                ◉
                <span>Admin</span>
            </a>

            <a href="faq.html">
                ?
                <span>FAQ</span>
            </a>

        </nav>

    </div>


    <!-- LOGO -->

    <a href="index.html"
       class="logo"
       aria-label="StudentHub Home">

        <span class="logo-icon">
            🎓
        </span>

        <span class="logo-text">
            StudentHub
        </span>

    </a>


    <!-- HEADER RIGHT -->

    <div class="header-right">


        <!-- THEME BUTTON -->

        <button id="themeToggle"
                class="theme-button"
                type="button"
                aria-label="Switch theme">

            🌙

        </button>


        <!-- LOGIN / REGISTER -->

        <div class="auth-buttons">

            <a href="profile.php">Profile</a>
            <a href="../php/logout.php">Log out</a>

        </div>

    </div>

</header>



<!-- =====================================================
     MAIN PROFILE CONTENT
     ===================================================== -->

<main class="profile-main">

    <?php if ($profileError !== ""): ?>
        <p class="profile-feedback" role="alert">
            <?= studenthub_escape($profileError) ?>
        </p>
    <?php elseif (isset($_GET["updated"])): ?>
        <p class="profile-feedback profile-feedback-success" role="status">
            Your profile changes have been saved.
        </p>
    <?php endif; ?>

    <!-- =================================================
         PROFILE HEADER
         ================================================= -->

    <section class="profile-header"
             aria-labelledby="profileName">


        <!-- COVER -->

        <div class="profile-cover">
        </div>


        <!-- PROFILE IMAGE -->

        <div class="profile-picture">

            <img src="img.png"
                 alt="Profile picture">

        </div>


        <!-- BASIC INFORMATION -->

        <div class="profile-basic-info">

            <h1 id="profileName">
                <?= studenthub_escape($student["student_name"]) ?>
            </h1>

            <p id="profileEmail">
                <?= studenthub_escape($student["email"]) ?>
            </p>

        </div>

    </section>



    <!-- =================================================
         PERSONAL INFORMATION
         ================================================= -->

    <section class="personal-information"
             aria-labelledby="personal-info-title">


        <h2 id="personal-info-title">
            Personal Information
        </h2>


        <dl class="profile-details">


            <!-- FULL NAME -->

            <div class="profile-row">

                <dt>
                    Full Name
                </dt>

                <dd>
                    <?= studenthub_escape($student["student_name"]) ?>
                </dd>

            </div>


            <!-- USERNAME -->

            <div class="profile-row">

                <dt>
                    Student ID
                </dt>

                <dd>
                    <?= studenthub_escape($student["student_id"]) ?>
                </dd>

            </div>


            <!-- EMAIL -->

            <div class="profile-row">

                <dt>
                    Email
                </dt>

                <dd>
                    <?= studenthub_escape($student["email"]) ?>
                </dd>

            </div>


            <!-- PHONE -->

            <div class="profile-row">

                <dt>
                    Phone
                </dt>

                <dd>
                    <?= studenthub_escape($student["mobile_no"]) ?>
                </dd>

            </div>


            <!-- COURSE -->

            <div class="profile-row">

                <dt>
                    Course
                </dt>

                <dd>
                    <?= studenthub_escape($student["course"]) ?>
                </dd>

            </div>


            <!-- YEAR -->

            <div class="profile-row">

                <dt>
                    Year
                </dt>

                <dd>
                    <?= studenthub_escape($student["academic_year"]) ?>
                </dd>

            </div>


            <!-- GENDER -->

            <div class="profile-row">

                <dt>
                    Gender
                </dt>

                <dd>
                    <?= studenthub_escape($student["gender"]) ?>
                </dd>

            </div>


        </dl>



        <!-- =================================================
             EDIT PROFILE BUTTON
             ================================================= -->

        <div class="edit-profile">

            <button id="editProfileBtn"
                    class="edit-button"
                    type="button">

                ✏️ Edit Profile

            </button>

        </div>

    </section>

</main>



<!-- =====================================================
     EDIT PROFILE MODAL POPUP
     ===================================================== -->

<div id="profileModal"
     class="modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modalTitle">


    <div class="modal-content">


        <!-- CLOSE BUTTON -->

        <button id="closeProfileModal"
                class="modal-close"
                type="button"
                aria-label="Close popup">

            ×

        </button>


        <!-- TITLE -->

        <h2 id="modalTitle">
            Edit Profile
        </h2>



        <!-- =================================================
             EDIT PROFILE FORM
             ================================================= -->

        <form id="profileForm" method="post" action="profile.php">
            <input type="hidden" name="csrf_token"
                   value="<?= studenthub_escape($_SESSION["studenthub_csrf"]) ?>">


            <!-- FULL NAME -->

            <label for="editName">
                Full Name
            </label>

            <input type="text"
                   id="editName"
                   name="student_name"
                   minlength="2"
                   maxlength="100"
                   value="<?= studenthub_escape($profileForm["student_name"]) ?>"
                   required>



            <!-- EMAIL -->

            <label for="editEmail">
                Email
            </label>

            <input type="email"
                   id="editEmail"
                   name="email"
                   maxlength="100"
                   value="<?= studenthub_escape($profileForm["email"]) ?>"
                   required>



            <!-- PHONE -->

            <label for="editPhone">
                Phone
            </label>

            <input type="tel"
                   id="editPhone"
                   name="mobile_no"
                   maxlength="10"
                   pattern="[0-9]{10}"
                   value="<?= studenthub_escape($profileForm["mobile_no"]) ?>"
                   required>



            <!-- COURSE -->

            <label for="editCourse">
                Course
            </label>

            <input type="text"
                   id="editCourse"
                   name="course"
                   maxlength="100"
                   value="<?= studenthub_escape($profileForm["course"]) ?>"
                   >



            <!-- YEAR -->

            <label for="editYear">
                Year
            </label>

            <input type="text"
                   id="editYear"
                   name="academic_year"
                   maxlength="30"
                   value="<?= studenthub_escape($profileForm["academic_year"]) ?>"
                   >



            <!-- GENDER -->

            <label for="editGender">
                Gender
            </label>

            <select id="editGender"
                    name="gender"
                    required>

                <option value="Male" <?= $profileForm["gender"] === "Male" ? "selected" : "" ?>>
                    Male
                </option>

                <option value="Female" <?= $profileForm["gender"] === "Female" ? "selected" : "" ?>>
                    Female
                </option>

                <option value="Other" <?= $profileForm["gender"] === "Other" ? "selected" : "" ?>>
                    Other
                </option>

            </select>



            <!-- BUTTONS -->

            <div class="modal-actions">


                <button type="button"
                        id="cancelProfileBtn"
                        class="cancel-button">

                    Cancel

                </button>


                <button type="submit"
                        class="save-button">

                    Save Changes

                </button>


            </div>

        </form>

    </div>

</div>



<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer class="site-footer">

    <p>
        &copy; 2026 StudentHub.
        All rights reserved.
    </p>

</footer>



<!-- =====================================================
     JAVASCRIPT
     ===================================================== -->

<script src="../javascript/profile.js?v=2"
        defer></script>


</body>

</html>