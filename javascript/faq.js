// =====================================================
// STUDENTHUB FAQ JAVASCRIPT
// Fetch API
// Dynamic Rendering
// Search
// Filter
// Sorting
// Pagination
// Accordion
// Dark Mode
// Feedback Form
// =====================================================


document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // 1. VARIABLES
        // =================================================

        const faqSearch =
            document.getElementById(
                "faqSearch"
            );


        const faqContainer =
            document.getElementById(
                "faqContainer"
            );


        const categoryFilter =
            document.getElementById(
                "faqCategoryFilter"
            );


        const sortSelect =
            document.getElementById(
                "faqSort"
            );


        const perPageSelect =
            document.getElementById(
                "faqPerPage"
            );


        const resultCount =
            document.getElementById(
                "faqResultCount"
            );


        const pagination =
            document.getElementById(
                "faqPagination"
            );


        const noResults =
            document.getElementById(
                "noResults"
            );


        const loading =
            document.getElementById(
                "faqLoading"
            );


        const errorMessage =
            document.getElementById(
                "faqError"
            );


        // =================================================
        // 2. DATA VARIABLES
        // =================================================

        let allFAQs = [];

        let filteredFAQs = [];

        let currentPage = 1;

        let itemsPerPage = 5;


        // =================================================
        // 3. DARK / LIGHT MODE
        // =================================================

        const themeToggle =
            document.getElementById(
                "themeToggle"
            );


        function setTheme(isDark) {

            if (isDark) {

                document.body.classList.add(
                    "dark-theme"
                );

                if (themeToggle) {

                    themeToggle.textContent =
                        "☀️";

                    themeToggle.setAttribute(
                        "aria-label",
                        "Switch to light mode"
                    );
                }

            } else {

                document.body.classList.remove(
                    "dark-theme"
                );

                if (themeToggle) {

                    themeToggle.textContent =
                        "🌙";

                    themeToggle.setAttribute(
                        "aria-label",
                        "Switch to dark mode"
                    );
                }
            }


            localStorage.setItem(
                "studenthub-theme",
                isDark
                    ? "dark"
                    : "light"
            );
        }


        const savedTheme =
            localStorage.getItem(
                "studenthub-theme"
            );


        setTheme(
            savedTheme === "dark"
        );


        if (themeToggle) {

            themeToggle.addEventListener(
                "click",
                function () {

                    const isDark =
                        document.body.classList.contains(
                            "dark-theme"
                        );

                    setTheme(!isDark);

                }
            );
        }


        // =================================================
        // 4. HAMBURGER MENU
        // =================================================

        const menu =
            document.querySelector(
                ".menu-details"
            );


        document.addEventListener(
            "click",
            function (event) {

                if (
                    menu &&
                    !menu.contains(
                        event.target
                    )
                ) {

                    menu.removeAttribute(
                        "open"
                    );
                }

            }
        );


        // =================================================
        // 5. CLOSE NOTIFICATION
        // =================================================

        const notification =
            document.getElementById(
                "notificationBanner"
            );


        const closeNotification =
            document.getElementById(
                "closeNotification"
            );


        if (
            notification &&
            closeNotification
        ) {

            closeNotification.addEventListener(
                "click",
                function () {

                    notification.style.display =
                        "none";

                }
            );
        }


        // =================================================
        // 6. FETCH FAQ JSON
        // =================================================

        async function loadFAQs() {

            try {

                loading.style.display =
                    "block";


                errorMessage.style.display =
                    "none";


                /*
                    Your structure:

                    html/
                    |
                    |-- faq.html
                    |
                    |-- json/
                    |    |
                    |    |-- faq.json
                    |
                    |-- javascript/
                         |
                         |-- faq.js

                    Therefore:

                    json/faq.json
                */


                const response =
                    await fetch(
                        "json/faq.json"
                    );


                if (!response.ok) {

                    throw new Error(
                        "FAQ JSON file could not be loaded."
                    );
                }


                const data =
                    await response.json();


                // JSON structure:
                //
                // {
                //     "faqs": [...]
                // }


                if (
                    Array.isArray(
                        data.faqs
                    )
                ) {

                    allFAQs =
                        data.faqs;

                } else {

                    throw new Error(
                        "Invalid FAQ JSON format."
                    );
                }


                if (
                    allFAQs.length === 0
                ) {

                    throw new Error(
                        "No FAQ records found."
                    );
                }


                // Create category list

                createCategories();


                // Initial display

                applyFilters();


            } catch (error) {

                console.error(
                    error
                );


                errorMessage.textContent =
                    "Unable to load FAQs. Please check json/faq.json.";


                errorMessage.style.display =
                    "block";


            } finally {

                loading.style.display =
                    "none";
            }
        }


        // =================================================
        // 7. CREATE CATEGORY OPTIONS
        // =================================================

        function createCategories() {

            const categories =
                [
                    ...new Set(
                        allFAQs.map(
                            function (faq) {

                                return faq.category;

                            }
                        )
                    )
                ];


            categories.sort(
                function (a, b) {

                    return a.localeCompare(
                        b
                    );

                }
            );


            categoryFilter.innerHTML = `

                <option value="all">
                    All Categories
                </option>

            `;


            categories.forEach(
                function (category) {

                    const option =
                        document.createElement(
                            "option"
                        );


                    option.value =
                        category;


                    option.textContent =
                        category;


                    categoryFilter.appendChild(
                        option
                    );
                }
            );
        }


        // =================================================
        // 8. APPLY SEARCH + FILTER + SORT
        // =================================================

        function applyFilters() {

            const searchText =
                faqSearch.value
                    .toLowerCase()
                    .trim();


            const category =
                categoryFilter.value;


            filteredFAQs =
                allFAQs.filter(
                    function (faq) {

                        const question =
                            faq.question
                                .toLowerCase();


                        const answer =
                            faq.answer
                                .toLowerCase();


                        const faqCategory =
                            faq.category
                                .toLowerCase();


                        const searchMatch =

                            question.includes(
                                searchText
                            )

                            ||

                            answer.includes(
                                searchText
                            )

                            ||

                            faqCategory.includes(
                                searchText
                            );


                        const categoryMatch =

                            category ===
                                "all"

                            ||

                            faq.category ===
                                category;


                        return (
                            searchMatch &&
                            categoryMatch
                        );

                    }
                );


            // =================================================
            // SORT
            // =================================================

            const sortType =
                sortSelect.value;


            filteredFAQs.sort(
                function (a, b) {

                    switch (
                        sortType
                    ) {

                        case "question-asc":

                            return a.question.localeCompare(
                                b.question
                            );


                        case "question-desc":

                            return b.question.localeCompare(
                                a.question
                            );


                        case "category-asc":

                            return a.category.localeCompare(
                                b.category
                            );


                        case "category-desc":

                            return b.category.localeCompare(
                                a.category
                            );


                        default:

                            return (
                                Number(a.id) -
                                Number(b.id)
                            );
                    }

                }
            );


            currentPage = 1;


            renderFAQs();
        }


        // =================================================
        // 9. RENDER FAQS
        // =================================================

        function renderFAQs() {

            // IMPORTANT:
            // Clear old dynamically generated FAQs.
            // This prevents duplicate FAQ lists.

            faqContainer.innerHTML = "";


            const total =
                filteredFAQs.length;


            const totalPages =
                Math.ceil(
                    total /
                    itemsPerPage
                );


            // =================================================
            // NO RESULTS
            // =================================================

            if (total === 0) {

                noResults.style.display =
                    "block";


                resultCount.textContent =
                    "0 FAQs found";


                pagination.innerHTML =
                    "";


                return;
            }


            noResults.style.display =
                "none";


            // =================================================
            // PAGE CALCULATION
            // =================================================

            const start =
                (currentPage - 1) *
                itemsPerPage;


            const end =
                start +
                itemsPerPage;


            const pageFAQs =
                filteredFAQs.slice(
                    start,
                    end
                );


            // =================================================
            // RESULT COUNT
            // =================================================

            resultCount.textContent =
                `Showing ${
                    start + 1
                }-${
                    Math.min(
                        end,
                        total
                    )
                } of ${
                    total
                } FAQs`;


            // =================================================
            // CREATE FAQ ITEMS
            // =================================================

            pageFAQs.forEach(
                function (faq) {

                    const item =
                        document.createElement(
                            "article"
                        );


                    item.className =
                        "faq-item";

                    const answerId = `faq-answer-${faq.id}`;

                    item.innerHTML = `

                        <button
                            type="button"
                            class="faq-question"
                            aria-controls="${answerId}"
                            aria-expanded="false">

                            <span
                                class="faq-question-text">

                                ${escapeHTML(
                                    faq.question
                                )}

                            </span>


                            <span
                                class="faq-icon">

                                +

                            </span>

                        </button>


                        <div
                            class="faq-answer"
                            id="${answerId}"
                            style="display:none;">

                            <p>

                                ${escapeHTML(
                                    faq.answer
                                )}

                            </p>


                            <small
                                class="faq-category">

                                Category:
                                ${escapeHTML(
                                    faq.category
                                )}

                            </small>

                        </div>

                    `;


                    faqContainer.appendChild(
                        item
                    );
                }
            );


            // Add accordion

            addAccordion();


            // Pagination

            createPagination(
                totalPages
            );
        }


        // =================================================
        // 10. FAQ ACCORDION
        // =================================================

        function addAccordion() {

            const questions =
                faqContainer.querySelectorAll(
                    ".faq-question"
                );


            questions.forEach(
                function (question) {

                    question.addEventListener(
                        "click",
                        function () {

                            const item =
                                question.closest(
                                    ".faq-item"
                                );


                            const answer =
                                item.querySelector(
                                    ".faq-answer"
                                );


                            const icon =
                                item.querySelector(
                                    ".faq-icon"
                                );


                            const currentlyOpen =
                                answer.style.display ===
                                "block";


                            // Close all

                            faqContainer
                                .querySelectorAll(
                                    ".faq-answer"
                                )
                                .forEach(
                                    function (
                                        otherAnswer
                                    ) {

                                        otherAnswer.style.display =
                                            "none";

                                    }
                                );


                            faqContainer
                                .querySelectorAll(
                                    ".faq-icon"
                                )
                                .forEach(
                                    function (
                                        otherIcon
                                    ) {

                                        otherIcon.textContent =
                                            "+";

                                    }
                                );


                            faqContainer
                                .querySelectorAll(
                                    ".faq-question"
                                )
                                .forEach(
                                    function (
                                        otherQuestion
                                    ) {

                                        otherQuestion.setAttribute(
                                            "aria-expanded",
                                            "false"
                                        );

                                    }
                                );


                            // Open clicked item

                            if (
                                !currentlyOpen
                            ) {

                                answer.style.display =
                                    "block";


                                icon.textContent =
                                    "−";


                                question.setAttribute(
                                    "aria-expanded",
                                    "true"
                                );
                            }

                        }
                    );
                }
            );
        }


        // =================================================
        // 11. PAGINATION
        // =================================================

        function createPagination(
            totalPages
        ) {

            pagination.innerHTML =
                "";


            if (
                totalPages <= 1
            ) {

                return;
            }


            // Previous

            const previous =
                document.createElement(
                    "button"
                );


            previous.textContent =
                "« Previous";


            previous.disabled =
                currentPage === 1;


            previous.addEventListener(
                "click",
                function () {

                    if (
                        currentPage > 1
                    ) {

                        currentPage--;

                        renderFAQs();

                        scrollToFAQs();
                    }

                }
            );


            pagination.appendChild(
                previous
            );


            // Page numbers

            for (
                let page = 1;
                page <= totalPages;
                page++
            ) {

                const button =
                    document.createElement(
                        "button"
                    );


                button.textContent =
                    page;


                if (
                    page === currentPage
                ) {

                    button.classList.add(
                        "active"
                    );
                }


                button.addEventListener(
                    "click",
                    function () {

                        currentPage =
                            page;


                        renderFAQs();

                        scrollToFAQs();

                    }
                );


                pagination.appendChild(
                    button
                );
            }


            // Next

            const next =
                document.createElement(
                    "button"
                );


            next.textContent =
                "Next »";


            next.disabled =
                currentPage ===
                totalPages;


            next.addEventListener(
                "click",
                function () {

                    if (
                        currentPage <
                        totalPages
                    ) {

                        currentPage++;

                        renderFAQs();

                        scrollToFAQs();
                    }

                }
            );


            pagination.appendChild(
                next
            );
        }


        // =================================================
        // 12. SCROLL TO FAQ
        // =================================================

        function scrollToFAQs() {

            faqContainer.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }


        // =================================================
        // 13. HTML ESCAPE
        // =================================================

        function escapeHTML(
            value
        ) {

            return String(value)

                .replace(
                    /&/g,
                    "&amp;"
                )

                .replace(
                    /</g,
                    "&lt;"
                )

                .replace(
                    />/g,
                    "&gt;"
                )

                .replace(
                    /"/g,
                    "&quot;"
                )

                .replace(
                    /'/g,
                    "&#039;"
                );
        }


        // =================================================
        // 14. SEARCH
        // =================================================

        faqSearch.addEventListener(
            "input",
            function () {

                applyFilters();

            }
        );


        // =================================================
        // 15. CATEGORY FILTER
        // =================================================

        categoryFilter.addEventListener(
            "change",
            function () {

                applyFilters();

            }
        );


        // =================================================
        // 16. SORT
        // =================================================

        sortSelect.addEventListener(
            "change",
            function () {

                applyFilters();

            }
        );


        // =================================================
        // 17. ITEMS PER PAGE
        // =================================================

        perPageSelect.addEventListener(
            "change",
            function () {

                itemsPerPage =
                    Number(
                        perPageSelect.value
                    );


                currentPage = 1;


                renderFAQs();

            }
        );


        // =================================================
        // 18. FEEDBACK FORM
        // =================================================

        const feedbackForm =
            document.getElementById(
                "feedbackForm"
            );


        const feedbackModal =
            document.getElementById(
                "feedbackModal"
            );


        const modalClose =
            document.getElementById(
                "modalClose"
            );


        const modalOK =
            document.getElementById(
                "modalOk"
            );


        const nameError =
            document.getElementById(
                "nameError"
            );


        const emailError =
            document.getElementById(
                "emailError"
            );


        const feedbackError =
            document.getElementById(
                "feedbackError"
            );


        // =================================================
        // FEEDBACK SUBMIT
        // =================================================

        feedbackForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();


                nameError.textContent =
                    "";

                emailError.textContent =
                    "";

                feedbackError.textContent =
                    "";


                const name =
                    document
                        .getElementById(
                            "feedback-name"
                        )
                        .value
                        .trim();


                const email =
                    document
                        .getElementById(
                            "feedback-email"
                        )
                        .value
                        .trim();


                const message =
                    document
                        .getElementById(
                            "feedback-message"
                        )
                        .value
                        .trim();


                // NAME

                const namePattern =
                    /^[A-Za-z ]{2,50}$/;


                if (
                    !namePattern.test(
                        name
                    )
                ) {

                    nameError.textContent =
                        "Please enter a valid name.";

                    return;
                }


                // EMAIL

                const emailPattern =
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                if (
                    !emailPattern.test(
                        email
                    )
                ) {

                    emailError.textContent =
                        "Please enter a valid email.";

                    return;
                }


                // MESSAGE

                if (
                    message.length < 10
                ) {

                    feedbackError.textContent =
                        "Feedback must contain at least 10 characters.";

                    return;
                }


                // SAVE

                const feedbackData = {

                    name: name,

                    email: email,

                    message: message,

                    date:
                        new Date()
                            .toLocaleString()

                };


                localStorage.setItem(
                    "studenthub-feedback",
                    JSON.stringify(
                        feedbackData
                    )
                );


                // RESET

                feedbackForm.reset();


                // SHOW MODAL

                feedbackModal.style.display =
                    "flex";

            }
        );


        // =================================================
        // 19. MODAL CLOSE
        // =================================================

        modalClose.addEventListener(
            "click",
            function () {

                feedbackModal.style.display =
                    "none";

            }
        );


        modalOK.addEventListener(
            "click",
            function () {

                feedbackModal.style.display =
                    "none";

            }
        );


        // Close outside

        feedbackModal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target ===
                    feedbackModal
                ) {

                    feedbackModal.style.display =
                        "none";
                }

            }
        );


        // Escape

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape"
                ) {

                    feedbackModal.style.display =
                        "none";
                }

            }
        );


        // =================================================
        // 20. START APPLICATION
        // =================================================

        loadFAQs();

    }
);