document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // GET FORM ELEMENTS
    // =====================================================

    const form = document.getElementById("registerForm");

    const name = document.getElementById("student_name");
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const mobile = document.getElementById("mobile_no");
    const gender = document.getElementById("gender");
    const course = document.getElementById("course");
    const academicYear = document.getElementById("academic_year");
    const password = document.getElementById("password");
    const confirmPassword =
        document.getElementById("confirm_password");
    const terms = document.getElementById("terms");
    const csrfInput = document.getElementById("csrf_token");
    const feedback = document.getElementById("formFeedback");
    const submitButton = form.querySelector('[type="submit"]');
    const themeToggle = document.getElementById("themeToggle");
    let isSubmitting = false;
    let csrfReady = false;

    if (themeToggle) {
        const isDark = localStorage.getItem("studenthub-theme") === "dark";
        document.body.classList.toggle("dark-theme", isDark);
        themeToggle.textContent = isDark ? "☀️" : "🌙";
        themeToggle.setAttribute(
            "aria-label",
            isDark ? "Switch to light mode" : "Switch to dark mode"
        );
        themeToggle.addEventListener("click", function () {
            const dark = document.body.classList.toggle("dark-theme");
            localStorage.setItem("studenthub-theme", dark ? "dark" : "light");
            themeToggle.textContent = dark ? "☀️" : "🌙";
            themeToggle.setAttribute(
                "aria-label",
                dark ? "Switch to light mode" : "Switch to dark mode"
            );
        });
    }

    fetch("../php/session-status.php", {
        credentials: "same-origin",
        cache: "no-store",
        headers: { Accept: "application/json" },
    })
        .then(async (response) => {
            const contentType = response.headers.get("content-type") || "";
            if (!response.ok || !contentType.includes("application/json")) {
                throw new Error(`Session token request failed (${response.status}).`);
            }

            const status = await response.json();
            if (typeof status.csrfToken !== "string" || status.csrfToken === "") {
                throw new Error("Session token response did not contain a CSRF token.");
            }

            csrfInput.value = status.csrfToken;
            csrfReady = true;
        })
        .catch((error) => {
            console.error("Unable to prepare the StudentHub registration form.", error);
        });


    // =====================================================
    // FORM VALIDATION
    // =====================================================

    const namePattern = /^[\p{L}\p{M}][\p{L}\p{M}\s.'’-]{1,99}$/u;
    const usernamePattern = /^[A-Za-z0-9_]{3,30}$/;
    const passwordPattern =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,100}$/;
    const phonePattern = /^[0-9]{10}$/;

    function updateCustomValidity() {
        name.setCustomValidity(
            namePattern.test(name.value.trim())
                ? ""
                : "Enter a name between 2 and 100 characters using letters, spaces, apostrophes or hyphens."
        );
        username.setCustomValidity(
            usernamePattern.test(username.value.trim())
                ? ""
                : "Use 3 to 30 letters, numbers or underscores for your username."
        );
        email.setCustomValidity(
            email.value === "" || !email.validity.typeMismatch
                ? ""
                : "Enter a valid email address."
        );
        mobile.setCustomValidity(
            phonePattern.test(mobile.value)
                ? ""
                : "Enter exactly 10 digits for your mobile number."
        );
        gender.setCustomValidity(
            gender.value ? "" : "Choose a gender."
        );
        course.setCustomValidity(
            course.value.trim() ? "" : "Enter your course."
        );
        academicYear.setCustomValidity(
            academicYear.value.trim() ? "" : "Enter your academic year."
        );
        password.setCustomValidity(
            passwordPattern.test(password.value)
                ? ""
                : "Use 8 to 100 characters, including uppercase and lowercase letters, a number, and one of @$!%*?&."
        );
        confirmPassword.setCustomValidity(
            confirmPassword.value === password.value
                ? ""
                : "Passwords do not match."
        );
        terms.setCustomValidity(
            terms.checked ? "" : "Accept the Terms & Conditions to continue."
        );
    }

    [
        name,
        username,
        email,
        mobile,
        gender,
        course,
        academicYear,
        password,
        confirmPassword,
        terms,
    ].forEach((field) => {
        field.addEventListener("input", () => {
            updateCustomValidity();
            feedback.hidden = true;
        });
        field.addEventListener("change", () => {
            updateCustomValidity();
            feedback.hidden = true;
        });
    });

    form.addEventListener("submit", (event) => {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        if (!csrfReady) {
            event.preventDefault();
            feedback.textContent = "We could not prepare a secure registration. Reload the page and try again.";
            feedback.hidden = false;
            return;
        }

        updateCustomValidity();

        const invalidField = form.querySelector(":invalid");
        if (invalidField) {
            event.preventDefault();
            feedback.textContent = invalidField.validationMessage;
            feedback.hidden = false;
            invalidField.focus();
            return;
        }

        isSubmitting = true;
        submitButton.disabled = true;
        submitButton.textContent = "Creating Account...";
        form.setAttribute("aria-busy", "true");
        feedback.hidden = true;
    });

});