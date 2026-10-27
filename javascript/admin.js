// ==========================================
// STUDENTHUB - ADMIN PAGE JAVASCRIPT
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
                const isOpen = sidebar.classList.contains("active");
                menuToggle.setAttribute("aria-expanded", String(isOpen));
                menuToggle.setAttribute(
                    "aria-label",
                    isOpen ? "Close navigation menu" : "Open navigation menu"
                );
                const backdrop = document.getElementById("sidebarBackdrop");
                if (backdrop) {
                    backdrop.classList.toggle("active", isOpen);
                }

            }
        );

        const backdrop = document.getElementById("sidebarBackdrop");
        if (backdrop) {
            backdrop.addEventListener("click", closeSidebar);
        }

        // ==========================================
        // CLOSE MENU WHEN CLICKING OUTSIDE
        // ==========================================

        document.addEventListener(
            "click",
            function (event) {

                if (
                    sidebar.classList.contains("active") &&
                    !event.target.closest(".menu-container") &&
                    !event.target.closest("#sidebar")
                ) {

                    closeSidebar();

                }

            }
        );


        // ==========================================
        // CLOSE MENU AFTER CLICKING A LINK
        // ==========================================

        const menuLinks =
            sidebar.querySelectorAll("a");


        menuLinks.forEach(function (link) {

            link.addEventListener(
                "click",
                function () {

                    closeSidebar();

                }
            );

        });

    }


    // ==========================================
    // RESTORE HAMBURGER ICON
    // ==========================================

    function showHamburger() {

        if (!menuToggle) {
            return;
        }

        menuToggle.innerHTML = '<i class="bi bi-list fs-4" aria-hidden="true"></i>';

        menuToggle.setAttribute(
            "aria-expanded",
            "false"
        );

        menuToggle.setAttribute(
            "aria-label",
            "Open navigation menu"
        );

    }

    function closeSidebar() {
        if (!sidebar || !menuToggle) {
            return;
        }
        sidebar.classList.remove("active");
        menuToggle.setAttribute("aria-expanded", "false");
        menuToggle.setAttribute("aria-label", "Open navigation menu");
        const backdrop = document.getElementById("sidebarBackdrop");
        if (backdrop) {
            backdrop.classList.remove("active");
        }
        showHamburger();
    }


    // ==========================================
    // 2. LIGHT / DARK THEME TOGGLE
    // ==========================================

    const themeToggle =
        document.getElementById("themeToggle");


    // Get previously saved theme

    const savedTheme =
        localStorage.getItem(
            "studenthub-theme"
        );


    // ==========================================
    // APPLY SAVED THEME
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


                // Save selected theme

                if (isDark) {

                    localStorage.setItem(
                        "studenthub-theme",
                        "dark"
                    );

                } else {

                    localStorage.setItem(
                        "studenthub-theme",
                        "light"
                    );

                }


                // Update theme button

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