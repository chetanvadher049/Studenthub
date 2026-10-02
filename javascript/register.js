document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // GET FORM ELEMENTS
    // =====================================================

    const form = document.getElementById("registerForm");

    const name = document.getElementById("student_name");
    const mobile = document.getElementById("mobile_no");
    const password = document.getElementById("password");
    const confirmPassword =
        document.getElementById("confirm_password");
    const feedback = document.getElementById("formFeedback");
    const themeToggle = document.getElementById("themeToggle");

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


    // =====================================================
    // FORM VALIDATION
    // =====================================================

    const namePattern = /^[\p{L}\p{M}][\p{L}\p{M}\s.'’-]{1,99}$/u;
    const passwordPattern =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,100}$/;

    function updateCustomValidity() {
        name.setCustomValidity(
            namePattern.test(name.value.trim())
                ? ""
                : "Enter a name between 2 and 100 characters using letters, spaces, apostrophes or hyphens."
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
    }

    [name, password, confirmPassword].forEach((field) => {
        field.addEventListener("input", () => {
            updateCustomValidity();
            feedback.hidden = true;
        });
    });

    form.addEventListener("invalid", (event) => {
        feedback.textContent = event.target.validationMessage;
        feedback.hidden = false;
    }, true);

    form.addEventListener("submit", (event) => {
        updateCustomValidity();

        if (!form.checkValidity()) {
            event.preventDefault();
            const invalidField = form.querySelector(":invalid");

            feedback.textContent = invalidField
                ? invalidField.validationMessage
                : "Please check the registration details and try again.";
            feedback.hidden = false;
            invalidField?.focus();
            invalidField?.reportValidity();
            return;
        }

        feedback.hidden = true;
    });

});