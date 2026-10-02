document.addEventListener("DOMContentLoaded", function () {

    // 1. HAMBURGER MENU
    let menu = document.querySelector(".menu-details");

    document.addEventListener("click", function (e) {
        if (menu && !menu.contains(e.target)) {
            menu.removeAttribute("open");
        }
    });


    // 2. LIGHT / DARK MODE
    let themeBtn = document.getElementById("themeToggle");

    if (themeBtn) {
        let isDark = localStorage.getItem("studenthub-theme") === "dark";
        document.body.classList.toggle("dark-theme", isDark);
        themeBtn.textContent = isDark ? "☀️" : "🌙";
        themeBtn.setAttribute(
            "aria-label",
            isDark ? "Switch to light theme" : "Switch to dark theme"
        );

        themeBtn.addEventListener("click", function () {

            isDark = document.body.classList.toggle("dark-theme");
            localStorage.setItem("studenthub-theme", isDark ? "dark" : "light");
            themeBtn.textContent = isDark ? "☀️" : "🌙";
            themeBtn.setAttribute(
                "aria-label",
                isDark ? "Switch to light theme" : "Switch to dark theme"
            );

        });

    }


    // 3. NOTIFICATION BANNER
    let notification = document.getElementById("notificationBanner");
    let closeBtn = document.getElementById("closeNotification");

    if (closeBtn && notification) {

        closeBtn.addEventListener("click", function () {
            notification.style.display = "none";
        });

    }


    // 4. IMAGE / CONTENT SLIDER
    let slides = document.querySelectorAll(".hero-slide");
    let dots = document.querySelectorAll(".slider-dot");

    let next = document.querySelector(".slider-next");
    let prev = document.querySelector(".slider-prev");

    let current = 0;


    function showSlide(n) {

        slides.forEach(function (slide) {
            slide.classList.remove("active");
        });

        dots.forEach(function (dot) {
            dot.classList.remove("active");
        });

        current = (n + slides.length) % slides.length;

        slides[current].classList.add("active");

        if (dots[current]) {
            dots[current].classList.add("active");
        }

    }


    if (slides.length && next && prev) {
        next.addEventListener("click", function () {
            showSlide(current + 1);
        });

        prev.addEventListener("click", function () {
            showSlide(current - 1);
        });

        dots.forEach(function (dot) {
            dot.addEventListener("click", function () {
                showSlide(Number(dot.dataset.slide));
            });
        });

        setInterval(function () {
            showSlide(current + 1);
        }, 5000);
    }



    // 5. COLLAPSIBLE FAQ
    let questions = document.querySelectorAll(".faq-question");

    questions.forEach(function (question) {

        question.addEventListener("click", function () {

            let answer = question.nextElementSibling;

            if (answer.style.display === "block") {

                answer.style.display = "none";

            }
            else {

                answer.style.display = "block";

            }

        });

    });



    // 6. MODAL POPUP
    let modal = document.getElementById("studentModal");

    let openBtn = document.querySelector(".open-modal");

    let closeModal = document.querySelector(".modal-close");
    let confirmModal = document.querySelector(".modal-ok");

    function hideModal() {
        if (modal) {
            modal.classList.remove("open");
        }
    }


    // Open modal
    if (openBtn && modal) {

        openBtn.addEventListener("click", function () {

            modal.classList.add("open");

        });

    }


    // Close modal
    if (closeModal && modal) {

        closeModal.addEventListener("click", function () {

            hideModal();

        });

    }

    if (confirmModal) {
        confirmModal.addEventListener("click", hideModal);
    }

    if (modal) {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                hideModal();
            }
        });
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            hideModal();
        }
    });

});