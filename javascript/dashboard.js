// =====================================================
// dashboard.js
// =====================================================
//
// Dashboard Features:
// 1. Hamburger Menu
// 2. Light / Dark Theme
// 3. Notification Banner
// 4. Modal Popup
//
// Removed Features:
// - Content Slider
// - Collapsible FAQ
// =====================================================


document.addEventListener("DOMContentLoaded", function () {


    // =====================================================
    // 1. HAMBURGER MENU
    // =====================================================

    const menu = document.querySelector(".menu-details");


    // Close menu when clicking outside

    document.addEventListener("click", function (event) {

        if (menu && !menu.contains(event.target)) {

            menu.removeAttribute("open");

        }

    });



    // =====================================================
    // 2. LIGHT / DARK MODE
    // =====================================================

    const themeButton =
        document.getElementById("themeToggle");


    // Function to apply theme

    function setTheme(dark) {

        if (dark) {

            // Enable dark mode

            document.body.classList.add(
                "dark-theme"
            );


            // Change button icon

            if (themeButton) {

                themeButton.textContent = "☀️";


                themeButton.setAttribute(
                    "aria-label",
                    "Switch to light mode"
                );


                themeButton.setAttribute(
                    "title",
                    "Switch to light mode"
                );

            }

        }

        else {

            // Enable light mode

            document.body.classList.remove(
                "dark-theme"
            );


            // Change button icon

            if (themeButton) {

                themeButton.textContent = "🌙";


                themeButton.setAttribute(
                    "aria-label",
                    "Switch to dark mode"
                );


                themeButton.setAttribute(
                    "title",
                    "Switch to dark mode"
                );

            }

        }


        // Save theme preference

        localStorage.setItem(
            "studenthub-theme",
            dark ? "dark" : "light"
        );

    }



    // Check whether theme button exists

    if (themeButton) {


        // Get previously saved theme

        const savedTheme =
            localStorage.getItem(
                "studenthub-theme"
            );


        // Apply saved theme

        if (savedTheme === "dark") {

            setTheme(true);

        }

        else {

            setTheme(false);

        }



        // Change theme when button is clicked

        themeButton.addEventListener(
            "click",
            function () {

                const dark =
                    !document.body.classList.contains(
                        "dark-theme"
                    );


                setTheme(dark);

            }
        );

    }



    // =====================================================
    // 3. NOTIFICATION BANNER
    // =====================================================

    const notification =
        document.getElementById(
            "notificationBanner"
        );


    const closeNotification =
        document.getElementById(
            "closeNotification"
        );


    // Check whether notification elements exist

    if (
        notification &&
        closeNotification
    ) {


        // Close notification button

        closeNotification.addEventListener(
            "click",
            function () {

                notification.style.display =
                    "none";

            }
        );

    }



    // =====================================================
    // 4. MODAL POPUP
    // =====================================================

    const modal =
        document.getElementById(
            "dashboardModal"
        );


    const modalTitle =
        document.getElementById(
            "modalTitle"
        );


    const modalText =
        document.getElementById(
            "modalText"
        );


    const modalClose =
        document.getElementById(
            "modalClose"
        );


    const modalOK =
        document.getElementById(
            "modalOk"
        );



    // =====================================================
    // OPEN MODAL FUNCTION
    // =====================================================

    function openModal(title, message) {

        // Make sure modal elements exist

        if (
            !modal ||
            !modalTitle ||
            !modalText
        ) {

            return;

        }


        // Set modal title

        modalTitle.textContent =
            title || "StudentHub";


        // Set modal message

        modalText.textContent =
            message ||
            "No additional information available.";


        // Show modal

        modal.style.display = "flex";

    }



    // =====================================================
    // CLOSE MODAL FUNCTION
    // =====================================================

    function closeModal() {

        if (modal) {

            modal.style.display =
                "none";

        }

    }



    // =====================================================
    // 5. EVENT POPUP
    // =====================================================

    const events =
        document.querySelectorAll(
            ".event-item"
        );


    events.forEach(function (event) {

        event.addEventListener(
            "click",
            function () {


                // Get event name

                const eventName =
                    event.getAttribute(
                        "data-event"
                    );


                // Open modal

                openModal(

                    eventName,

                    "This is an upcoming StudentHub event. " +
                    "Click View All Events for complete details."

                );

            }
        );

    });



    // =====================================================
    // 6. ANNOUNCEMENT POPUP
    // =====================================================

    const announcements =
        document.querySelectorAll(
            ".announcement-item"
        );


    announcements.forEach(
        function (announcement) {

            announcement.addEventListener(
                "click",
                function () {


                    // Get announcement title

                    const title =
                        announcement.getAttribute(
                            "data-title"
                        );


                    // Get announcement message

                    const message =
                        announcement.getAttribute(
                            "data-text"
                        );


                    // Open modal

                    openModal(
                        title,
                        message
                    );

                }
            );

        }
    );



    // =====================================================
    // 7. MODAL CLOSE BUTTON
    // =====================================================

    if (modalClose) {

        modalClose.addEventListener(
            "click",
            closeModal
        );

    }



    // =====================================================
    // 8. MODAL OK BUTTON
    // =====================================================

    if (modalOK) {

        modalOK.addEventListener(
            "click",
            closeModal
        );

    }



    // =====================================================
    // 9. CLOSE MODAL WHEN CLICKING OUTSIDE
    // =====================================================

    if (modal) {

        modal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target === modal
                ) {

                    closeModal();

                }

            }
        );

    }



    // =====================================================
    // 10. CLOSE MODAL USING ESCAPE KEY
    // =====================================================

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape"
            ) {

                closeModal();

            }

        }
    );


});