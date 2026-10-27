document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    const identifier = document.getElementById("login-email");
    const password = document.getElementById("login-password");
    const togglePassword = document.getElementById("togglePassword");
    const themeToggle = document.getElementById("themeToggle");
    const returnTo = document.getElementById("return_to");
    const loginMode = document.getElementById("user_type");
    const csrfInput = document.getElementById("csrf_token");
    const feedback = document.getElementById("loginFeedback");
    const submitButton = form.querySelector('[type="submit"]');
    const identifierLabel = document.getElementById("loginIdentifierLabel");
    const modeLabel = document.getElementById("loginModeLabel");
    const description = document.getElementById("loginDescription");
    const tabs = document.querySelectorAll("[data-login-mode]");
    let isSubmitting = false;

    const requestedReturn = new URLSearchParams(window.location.search)
        .get("return_to");
    if (returnTo && requestedReturn === "events.html") {
        returnTo.value = requestedReturn;
    }

    function showMessage(message) {
        feedback.textContent = message;
        feedback.hidden = false;
    }

    function selectLoginMode(mode) {
        loginMode.value = mode;
        identifierLabel.textContent = mode === "admin"
            ? "Admin Email or Username"
            : "Student Email or Username";
        identifier.placeholder = mode === "admin"
            ? "Enter your admin email or username"
            : "Enter your student email or username";
        modeLabel.textContent = mode === "admin" ? "Administrator Account" : "Student Account";
        description.textContent = mode === "admin"
            ? "Sign in with your StudentHub administrator account."
            : "Sign in to access your StudentHub student account.";
        tabs.forEach((tab) => {
            const selected = tab.dataset.loginMode === mode;
            tab.classList.toggle("active", selected);
            tab.setAttribute("aria-selected", String(selected));
        });
        feedback.hidden = true;
    }

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            selectLoginMode(tab.dataset.loginMode);
        });
    });

    const requestedMode = new URLSearchParams(window.location.search).get("mode");
    selectLoginMode(requestedMode === "admin" ? "admin" : "student");

    if (themeToggle) {
        const isDark = localStorage.getItem("studenthub-theme") === "dark";
        document.body.classList.toggle("dark-theme", isDark);
        themeToggle.textContent = isDark ? "☀️" : "🌙";
        themeToggle.setAttribute(
            "aria-label",
            isDark ? "Switch to light mode" : "Switch to dark mode"
        );
        themeToggle.addEventListener("click", () => {
            const dark = document.body.classList.toggle("dark-theme");
            localStorage.setItem("studenthub-theme", dark ? "dark" : "light");
            themeToggle.textContent = dark ? "☀️" : "🌙";
            themeToggle.setAttribute(
                "aria-label",
                dark ? "Switch to light mode" : "Switch to dark mode"
            );
        });
    }

    if (togglePassword) {
        togglePassword.addEventListener("click", () => {
            const showPassword = password.type === "password";
            password.type = showPassword ? "text" : "password";
            togglePassword.textContent = showPassword ? "Hide" : "Show";
        });
    }

    const query = new URLSearchParams(window.location.search);
    if (query.get("registered") === "1") {
        feedback.textContent = "Registration successful. You can now sign in.";
        feedback.classList.add("login-feedback--success");
        feedback.hidden = false;
    } else if (query.get("logged_out") === "1") {
        feedback.textContent = "You have been signed out.";
        feedback.classList.add("login-feedback--success");
        feedback.hidden = false;
    }

    const csrfPromise = fetch("../php/session-status.php", {
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
        })
        .catch((error) => {
            console.error("Unable to prepare the StudentHub login form.", error);
            return false;
        });

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        if (isSubmitting) {
            return;
        }

        feedback.classList.remove("login-feedback--success");
        feedback.hidden = true;

        if (identifier.value.trim() === "") {
            showMessage("Enter your username or email address.");
            identifier.focus();
            return;
        }

        if (password.value === "") {
            showMessage("Enter your password.");
            password.focus();
            return;
        }

        if (password.value.length > 255) {
            showMessage("Your password is not valid.");
            password.focus();
            return;
        }

        const tokenReady = await csrfPromise;
        if (tokenReady === false || csrfInput.value === "") {
            showMessage("We could not prepare a secure login. Reload the page and try again.");
            return;
        }

        isSubmitting = true;
        submitButton.disabled = true;
        submitButton.textContent = "Signing in...";
        form.setAttribute("aria-busy", "true");
        HTMLFormElement.prototype.submit.call(form);
    });
});