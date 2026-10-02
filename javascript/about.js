// ==========================================
// STUDENTHUB - ABOUT PAGE JAVASCRIPT
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

        menuToggle.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

                sidebar.classList.toggle("active");


                if (
                    sidebar.classList.contains("active")
                ) {

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

                    showHamburger();

                }

            }
        );


        // ==========================================
        // CLOSE MENU WHEN CLICKING OUTSIDE
        // ==========================================

        document.addEventListener(
            "click",
            function (event) {

                if (
                    sidebar.classList.contains("active") &&
                    !event.target.closest(".menu-container")
                ) {

                    sidebar.classList.remove("active");

                    showHamburger();

                }

            }
        );


        // ==========================================
        // CLOSE MENU AFTER SELECTING A LINK
        // ==========================================

        const menuLinks =
            sidebar.querySelectorAll("a");


        menuLinks.forEach(function (link) {

            link.addEventListener(
                "click",
                function () {

                    sidebar.classList.remove("active");

                    showHamburger();

                }
            );

        });

    }


    // ==========================================
    // RESTORE HAMBURGER ICON
    // ==========================================

    function showHamburger() {

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


    // ==========================================
    // 2. LIGHT / DARK THEME
    // ==========================================

    const themeToggle =
        document.getElementById("themeToggle");


    // Get saved theme

    const savedTheme =
        localStorage.getItem(
            "studenthub-theme"
        );


    // ==========================================
    // APPLY SAVED DARK MODE
    // ==========================================

    if (savedTheme === "dark") {

        document.body.classList.add(
            "dark-theme"
        );

        updateThemeButton(true);

    } else {

        updateThemeButton(false);

    }


    // ==========================================
    // THEME BUTTON CLICK
    // ==========================================

    if (themeToggle) {

        themeToggle.addEventListener(
            "click",
            function () {

                const isDark =
                    document.body.classList.toggle(
                        "dark-theme"
                    );


                // ==================================
                // SAVE DARK MODE
                // ==================================

                if (isDark) {

                    localStorage.setItem(
                        "studenthub-theme",
                        "dark"
                    );

                }


                // ==================================
                // SAVE LIGHT MODE
                // ==================================

                else {

                    localStorage.setItem(
                        "studenthub-theme",
                        "light"
                    );

                }


                // Update button

                updateThemeButton(isDark);

            }
        );

    }


    // ==========================================
    // UPDATE THEME BUTTON
    // ==========================================

    function updateThemeButton(isDark) {

        if (!themeToggle) {
            return;
        }


        // DARK MODE

        if (isDark) {

            themeToggle.innerHTML = "☀️";

            themeToggle.setAttribute(
                "aria-label",
                "Switch to light mode"
            );

            themeToggle.setAttribute(
                "title",
                "Switch to light mode"
            );

        }


        // LIGHT MODE

        else {

            themeToggle.innerHTML = "🌙";

            themeToggle.setAttribute(
                "aria-label",
                "Switch to dark mode"
            );

            themeToggle.setAttribute(
                "title",
                "Switch to dark mode"
            );

        }

    }

});