// ==========================================
// PROFILE PAGE JAVASCRIPT
// ==========================================

document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // 1. HAMBURGER MENU
    // ==========================================

    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");

    if (menuToggle && sidebar) {
        const menuContainer = menuToggle.closest(".menu-container");

        function closeMenu() {
            sidebar.classList.remove("show");
            menuToggle.classList.remove("active");
            menuToggle.setAttribute("aria-expanded", "false");
            menuToggle.setAttribute("aria-label", "Open navigation menu");
        }

        menuToggle.addEventListener("click", function () {
            const isOpen = sidebar.classList.toggle("show");
            menuToggle.classList.toggle("active", isOpen);
            menuToggle.setAttribute("aria-expanded", String(isOpen));
            menuToggle.setAttribute(
                "aria-label",
                isOpen ? "Close navigation menu" : "Open navigation menu"
            );
        });

        document.addEventListener("click", function (event) {
            if (
                sidebar.classList.contains("show") &&
                menuContainer &&
                !menuContainer.contains(event.target)
            ) {
                closeMenu();
            }
        });

        sidebar.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", closeMenu);
        });
    }


    // ==========================================
    // 2. LIGHT / DARK THEME SWITCHER
    // ==========================================

    const themeToggle = document.getElementById("themeToggle");

    // Load previously selected theme
    const savedTheme = localStorage.getItem("studenthub-theme");

    if (savedTheme === "dark") {
        document.body.classList.add("dark-theme");

        if (themeToggle) {
            themeToggle.innerHTML = "☀️";
        }
    }


    if (themeToggle) {

        themeToggle.addEventListener("click", function () {

            document.body.classList.toggle("dark-theme");

            // Check current theme
            if (document.body.classList.contains("dark-theme")) {

                localStorage.setItem("studenthub-theme", "dark");

                themeToggle.innerHTML = "☀️";

            } else {

                localStorage.setItem("studenthub-theme", "light");

                themeToggle.innerHTML = "🌙";

            }

        });

    }


    // ==========================================
    // 3. EDIT PROFILE MODAL
    // ==========================================

    const editProfileBtn = document.getElementById("editProfileBtn");
    const profileModal = document.getElementById("profileModal");
    const closeProfileModal = document.getElementById("closeProfileModal");
    const cancelProfileBtn = document.getElementById("cancelProfileBtn");


    // Open modal
    if (editProfileBtn && profileModal) {

        editProfileBtn.addEventListener("click", function () {

            profileModal.classList.add("active");

        });

    }


    // Close modal using X button
    if (closeProfileModal && profileModal) {

        closeProfileModal.addEventListener("click", function () {

            profileModal.classList.remove("active");

        });

    }


    // Close modal using Cancel button
    if (cancelProfileBtn && profileModal) {

        cancelProfileBtn.addEventListener("click", function () {

            profileModal.classList.remove("active");

        });

    }


    // Close modal when clicking outside modal content
    if (profileModal) {

        profileModal.addEventListener("click", function (event) {

            if (event.target === profileModal) {

                profileModal.classList.remove("active");

            }

        });

    }


});