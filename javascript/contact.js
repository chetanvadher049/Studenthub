// ==========================================
// STUDENTHUB - CONTACT PAGE JAVASCRIPT
// ==========================================

document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // 1. HAMBURGER MENU
    // ==========================================

    const menuToggle =
        document.getElementById("menuToggle");

    const sidebar =
        document.getElementById("sidebar");

    if (menuToggle && sidebar) {

        menuToggle.addEventListener("click", function () {

            sidebar.classList.toggle("active");

            if (sidebar.classList.contains("active")) {

                menuToggle.innerHTML = "✕";

                menuToggle.setAttribute(
                    "aria-expanded",
                    "true"
                );

                menuToggle.setAttribute(
                    "aria-label",
                    "Close navigation menu"
                );

            } else {

                menuToggle.innerHTML = `
                    <span></span>
                    <span></span>
                    <span></span>
                `;

                menuToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

                menuToggle.setAttribute(
                    "aria-label",
                    "Open navigation menu"
                );

            }

        });


        // Close menu when clicking outside

        document.addEventListener("click", function (event) {

            if (
                !event.target.closest(".menu-container") &&
                sidebar.classList.contains("active")
            ) {

                sidebar.classList.remove("active");

                menuToggle.innerHTML = `
                    <span></span>
                    <span></span>
                    <span></span>
                `;

                menuToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

        });

    }


    // ==========================================
    // 2. LIGHT / DARK THEME
    // ==========================================

    const themeToggle =
        document.getElementById("themeToggle");

    const savedTheme =
        localStorage.getItem(
            "studenthub-theme"
        );


    // Apply saved dark theme

    if (savedTheme === "dark") {

        document.body.classList.add(
            "dark-theme"
        );

        if (themeToggle) {

            themeToggle.innerHTML = "☀️";

            themeToggle.setAttribute(
                "aria-label",
                "Switch to light mode"
            );

        }

    }


    // Theme button click

    if (themeToggle) {

        themeToggle.addEventListener(
            "click",
            function () {

                document.body.classList.toggle(
                    "dark-theme"
                );


                // DARK MODE

                if (
                    document.body.classList.contains(
                        "dark-theme"
                    )
                ) {

                    localStorage.setItem(
                        "studenthub-theme",
                        "dark"
                    );

                    themeToggle.innerHTML = "☀️";

                    themeToggle.setAttribute(
                        "aria-label",
                        "Switch to light mode"
                    );

                }


                // LIGHT MODE

                else {

                    localStorage.setItem(
                        "studenthub-theme",
                        "light"
                    );

                    themeToggle.innerHTML = "🌙";

                    themeToggle.setAttribute(
                        "aria-label",
                        "Switch to dark mode"
                    );

                }

            }
        );

    }


});