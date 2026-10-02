document.addEventListener("DOMContentLoaded", function () {

    // =====================================================
    // GET FORM ELEMENTS
    // =====================================================

    const form =
        document.getElementById("loginForm");

    const email =
        document.getElementById("login-email");

    const password =
        document.getElementById("login-password");

    const togglePassword =
        document.getElementById("togglePassword");

    const themeToggle = document.getElementById("themeToggle");
    const returnTo = document.getElementById("return_to");
    const requestedReturn = new URLSearchParams(window.location.search)
        .get("return_to");

    if (returnTo && requestedReturn === "events.html") {
        returnTo.value = requestedReturn;
    }

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
    // SHOW / HIDE PASSWORD
    // =====================================================

    if (togglePassword && password) togglePassword.addEventListener(
        "click",
        function () {

            if (password.type === "password") {

                password.type = "text";

                togglePassword.textContent = "Hide";

            } else {

                password.type = "password";

                togglePassword.textContent = "Show";

            }

        }
    );


    // =====================================================
    // LOGIN VALIDATION
    // =====================================================

    form.addEventListener(
        "submit",
        function (event) {

            const emailPattern =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


            // Email empty

            if (email.value.trim() === "") {

                event.preventDefault();

                alert(
                    "Email address is required."
                );

                email.focus();

                return;
            }


            // Email format

            if (
                !emailPattern.test(
                    email.value.trim()
                )
            ) {

                event.preventDefault();

                alert(
                    "Please enter a valid email address."
                );

                email.focus();

                return;
            }


            // Password empty

            if (password.value === "") {

                event.preventDefault();

                alert(
                    "Password is required."
                );

                password.focus();

                return;
            }


            // Password length

            if (password.value.length < 8) {

                event.preventDefault();

                alert(
                    "Password must be at least 8 characters."
                );

                password.focus();

                return;
            }


            /*
             * IMPORTANT
             *
             * Do NOT use event.preventDefault()
             * when validation succeeds.
             *
             * The form will now be submitted to:
             *
             * php/login.php
             */

        }
    );

});