/* =====================================================
   STUDENTHUB EVENTS JAVASCRIPT

   FEATURES:
   1. Hamburger menu
   2. Light / Dark theme
   3. Event search
   4. Category filter
   5. Pagination
   6. Event details modal

   REMOVED:
   - Notification banner
   - Content slider
   - Collapsible FAQ
   ===================================================== */


document.addEventListener("DOMContentLoaded", async function () {

    const eventList = document.getElementById("eventList");
    const eventLoadError = document.getElementById("eventLoadError");
    const categoryFilter = document.getElementById("category-filter");

    async function loadEvents() {
        try {
            const response = await fetch("json/events.json", {
                headers: { Accept: "application/json" }
            });
            if (!response.ok) {
                throw new Error(`Event data request failed (${response.status}).`);
            }

            const data = await response.json();
            if (!Array.isArray(data.events)) {
                throw new Error("Event data has an invalid format.");
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const events = data.events.filter((event) => {
                if (
                    !Number.isInteger(event.id) ||
                    typeof event.title !== "string" ||
                    typeof event.description !== "string" ||
                    typeof event.category !== "string" ||
                    typeof event.date !== "string" ||
                    !/^\d{4}-\d{2}-\d{2}$/.test(event.date)
                ) {
                    throw new Error("An event record is missing required fields.");
                }

                const [year, month, day] = event.date.split("-").map(Number);
                return new Date(year, month - 1, day) >= today;
            });

            eventList.replaceChildren();
            if (categoryFilter) {
                categoryFilter.replaceChildren(new Option("All Categories", "all"));
                [...new Set(events.map((event) => event.category))]
                    .sort()
                    .forEach((category) => {
                        categoryFilter.add(new Option(category, category));
                    });
            }

            events.forEach((event) => {
                const card = document.createElement("article");
                card.className = "event-item";
                card.dataset.eventId = String(event.id);
                card.dataset.category = event.category;
                card.dataset.title = event.title;
                card.dataset.description = event.description;
                card.dataset.date = event.date;

                const icon = document.createElement("div");
                icon.className = "event-image";
                icon.setAttribute("aria-hidden", "true");
                icon.textContent = ({
                    Workshop: "📚",
                    Competition: "💻",
                    Sports: "🏆",
                    Cultural: "🎭",
                    Career: "🎓",
                    Seminar: "🗣️",
                    Exhibition: "🧪"
                })[event.category] || "📅";

                const details = document.createElement("div");
                details.className = "event-details";
                const category = document.createElement("div");
                category.className = "event-category";
                category.textContent = event.category;
                const title = document.createElement("h2");
                title.textContent = event.title;
                const description = document.createElement("p");
                description.textContent = event.description;
                const meta = document.createElement("div");
                meta.className = "event-meta";
                const date = document.createElement("time");
                const [year, month, day] = event.date.split("-").map(Number);
                date.dateTime = event.date;
                date.textContent = `📅 ${new Date(year, month - 1, day).toLocaleDateString(undefined, {
                    day: "numeric",
                    month: "long",
                    year: "numeric"
                })}`;
                meta.append(date);

                if (event.time || event.venue) {
                    const place = document.createElement("span");
                    place.textContent = [event.time, event.venue]
                        .filter(Boolean)
                        .join(" · ");
                    meta.append(place);
                }

                details.append(category, title, description, meta);
                const viewButton = document.createElement("button");
                viewButton.className = "view-details";
                viewButton.type = "button";
                viewButton.textContent = "View Details";
                card.append(icon, details, viewButton);
                eventList.append(card);
            });

            if (events.length === 0) {
                eventList.textContent = "There are no upcoming events.";
            }
        } catch (error) {
            console.error("Unable to load StudentHub events.", error);
            eventList.replaceChildren();
            eventLoadError.textContent = "Events could not be loaded. Please try again later.";
            eventLoadError.hidden = false;
        }
    }

    await loadEvents();


    /* =====================================================
       1. HAMBURGER MENU
       ===================================================== */

    const menuButton =
        document.getElementById("menuButton");

    const dropdownMenu =
        document.getElementById("dropdownMenu");


    if (menuButton && dropdownMenu) {

        menuButton.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

                dropdownMenu.classList.toggle("show");

                menuButton.classList.toggle("active");


                const isOpen =
                    dropdownMenu.classList.contains("show");


                menuButton.setAttribute(
                    "aria-expanded",
                    String(isOpen)
                );

            }
        );


        // Close menu when clicking outside

        document.addEventListener(
            "click",
            function (event) {

                if (
                    !dropdownMenu.contains(event.target) &&
                    !menuButton.contains(event.target)
                ) {

                    dropdownMenu.classList.remove("show");

                    menuButton.classList.remove("active");

                    menuButton.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                }

            }
        );


        // Close menu after selecting a link

        dropdownMenu
            .querySelectorAll("a")
            .forEach(function (link) {

                link.addEventListener(
                    "click",
                    async function () {

                        dropdownMenu.classList.remove("show");

                        menuButton.classList.remove("active");

                        menuButton.setAttribute(
                            "aria-expanded",
                            "false"
                        );

                    }
                );

            });

    }


    /* =====================================================
       2. LIGHT / DARK THEME
       ===================================================== */

    const themeToggle =
        document.getElementById("themeToggle");


    function applyTheme(theme) {

        const isDark =
            theme === "dark";


        document.body.classList.toggle(
            "dark-theme",
            isDark
        );


        if (themeToggle) {

            themeToggle.textContent =
                isDark ? "☀️" : "🌙";


            themeToggle.setAttribute(
                "aria-label",
                isDark
                    ? "Switch to light mode"
                    : "Switch to dark mode"
            );


            themeToggle.setAttribute(
                "title",
                isDark
                    ? "Switch to light mode"
                    : "Switch to dark mode"
            );

        }

    }


    // Load saved theme

    const savedTheme =
        localStorage.getItem(
            "studenthub-theme"
        ) || "light";


    applyTheme(savedTheme);


    // Change theme

    if (themeToggle) {

        themeToggle.addEventListener(
            "click",
            function () {

                const darkMode =
                    !document.body.classList.contains(
                        "dark-theme"
                    );


                const newTheme =
                    darkMode
                        ? "dark"
                        : "light";


                applyTheme(newTheme);


                localStorage.setItem(
                    "studenthub-theme",
                    newTheme
                );

            }
        );

    }


    /* =====================================================
       3. SEARCH + CATEGORY FILTER
       ===================================================== */

    const searchInput =
        document.getElementById(
            "event-search"
        );


    const filterButton =
        document.getElementById(
            "filterButton"
        );


    const allEvents =
        Array.from(
            document.querySelectorAll(
                ".event-item"
            )
        );


    const resultCount =
        document.getElementById(
            "resultCount"
        );


    let filteredEvents =
        [...allEvents];


    function filterEvents() {

        const searchText =
            searchInput
                ? searchInput.value
                    .toLowerCase()
                    .trim()
                : "";


        const selectedCategory =
            categoryFilter
                ? categoryFilter.value
                : "all";


        filteredEvents =
            allEvents.filter(
                function (event) {

                    const title =
                        (
                            event.dataset.title ||
                            ""
                        ).toLowerCase();


                    const description =
                        (
                            event.dataset.description ||
                            ""
                        ).toLowerCase();


                    const category =
                        event.dataset.category ||
                        "";


                    const matchesSearch =
                        title.includes(searchText) ||
                        description.includes(searchText);


                    const matchesCategory =
                        selectedCategory === "all" ||
                        category === selectedCategory;


                    return (
                        matchesSearch &&
                        matchesCategory
                    );

                }
            );


        // Start from page 1 after filtering

        currentPage = 1;


        renderPagination();

        displayPage();

    }


    // Search button

    if (filterButton) {

        filterButton.addEventListener(
            "click",
            filterEvents
        );

    }


    // Live search

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            filterEvents
        );

    }


    // Category filter

    if (categoryFilter) {

        categoryFilter.addEventListener(
            "change",
            filterEvents
        );

    }


    /* =====================================================
       4. PAGINATION
       ===================================================== */

    const previousPage =
        document.getElementById(
            "previousPage"
        );


    const nextPage =
        document.getElementById(
            "nextPage"
        );


    const pageNumbers =
        document.getElementById(
            "pageNumbers"
        );


    const eventsPerPage = 3;

    let currentPage = 1;


    function displayPage() {

        // Hide all events first

        allEvents.forEach(
            function (event) {

                event.style.display =
                    "none";

            }
        );


        const start =
            (currentPage - 1) *
            eventsPerPage;


        const end =
            start +
            eventsPerPage;


        // Show events for current page

        filteredEvents
            .slice(start, end)
            .forEach(
                function (event) {

                    event.style.display =
                        "grid";

                }
            );


        // Update result count

        const total =
            filteredEvents.length;


        if (resultCount) {

            if (total === 0) {

                resultCount.textContent =
                    "No events found.";

            }

            else {

                const first =
                    start + 1;


                const last =
                    Math.min(
                        end,
                        total
                    );


                resultCount.textContent =
                    `Showing ${first}-${last} of ${total} events`;

            }

        }


        const totalPages =
            Math.ceil(
                total /
                eventsPerPage
            );


        // Previous button

        if (previousPage) {

            previousPage.disabled =
                currentPage === 1;

        }


        // Next button

        if (nextPage) {

            nextPage.disabled =
                totalPages === 0 ||
                currentPage >= totalPages;

        }

    }


    function renderPagination() {

        if (!pageNumbers) {
            return;
        }


        pageNumbers.innerHTML = "";


        const totalPages =
            Math.ceil(
                filteredEvents.length /
                eventsPerPage
            );


        for (
            let i = 1;
            i <= totalPages;
            i++
        ) {

            const button =
                document.createElement(
                    "button"
                );


            button.type = "button";

            button.textContent = i;


            button.setAttribute(
                "aria-label",
                `Go to page ${i}`
            );


            if (i === currentPage) {

                button.classList.add(
                    "active"
                );


                button.setAttribute(
                    "aria-current",
                    "page"
                );

            }


            button.addEventListener(
                "click",
                function () {

                    currentPage = i;


                    renderPagination();

                    displayPage();


                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });

                }
            );


            pageNumbers.appendChild(
                button
            );

        }

    }


    // Previous page

    if (previousPage) {

        previousPage.addEventListener(
            "click",
            function () {

                if (currentPage > 1) {

                    currentPage--;

                    renderPagination();

                    displayPage();

                }

            }
        );

    }


    // Next page

    if (nextPage) {

        nextPage.addEventListener(
            "click",
            function () {

                const totalPages =
                    Math.ceil(
                        filteredEvents.length /
                        eventsPerPage
                    );


                if (
                    currentPage <
                    totalPages
                ) {

                    currentPage++;

                    renderPagination();

                    displayPage();

                }

            }
        );

    }


    // Initial pagination

    renderPagination();

    displayPage();


    /* =====================================================
       5. EVENT DETAILS MODAL
       ===================================================== */

    const eventModal =
        document.getElementById(
            "eventModal"
        );


    const closeModal =
        document.getElementById(
            "closeModal"
        );


    const modalIcon =
        document.getElementById(
            "modalIcon"
        );


    const modalCategory =
        document.getElementById(
            "modalCategory"
        );


    const modalTitle =
        document.getElementById(
            "modalTitle"
        );


    const modalDescription =
        document.getElementById(
            "modalDescription"
        );


    const modalDate =
        document.getElementById(
            "modalDate"
        );


    const registerEvent =
        document.getElementById(
            "registerEvent"
        );

    const registrationMessage =
        document.getElementById(
            "eventRegistrationMessage"
        );


    /* -----------------------------------------------------
       Open modal
       ----------------------------------------------------- */

    document
        .querySelectorAll(".view-details")
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const event =
                            button.closest(
                                ".event-item"
                            );


                        if (
                            !event ||
                            !eventModal
                        ) {
                            return;
                        }


                        const iconElement =
                            event.querySelector(
                                ".event-image"
                            );


                        const icon =
                            iconElement
                                ? iconElement
                                    .textContent
                                    .trim()
                                : "📅";


                        modalIcon.textContent =
                            icon;


                        modalCategory.textContent =
                            event.dataset.category ||
                            "";


                        modalTitle.textContent =
                            event.dataset.title ||
                            "Event";


                        modalDescription.textContent =
                            event.dataset.description ||
                            "";


                        modalDate.textContent =
                            "📅 " +
                            (
                                event.dataset.date ||
                                ""
                            );

                        eventModal.dataset.eventId =
                            event.dataset.eventId || "";
                        registerEvent.disabled = false;
                        registerEvent.textContent = "Register for Event";
                        registrationMessage.hidden = true;
                        registrationMessage.textContent = "";

                        eventModal.classList.add(
                            "show"
                        );


                        document.body.style.overflow =
                            "hidden";

                    }
                );

            }
        );


    /* -----------------------------------------------------
       Close modal
       ----------------------------------------------------- */

    function closeEventModal() {

        if (!eventModal) {
            return;
        }


        eventModal.classList.remove(
            "show"
        );


        document.body.style.overflow =
            "";

    }


    if (closeModal) {

        closeModal.addEventListener(
            "click",
            closeEventModal
        );

    }


    /* -----------------------------------------------------
       Close modal by clicking outside
       ----------------------------------------------------- */

    if (eventModal) {

        eventModal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target ===
                    eventModal
                ) {

                    closeEventModal();

                }

            }
        );

    }


    /* -----------------------------------------------------
       Close modal with ESC
       ----------------------------------------------------- */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape" &&
                eventModal &&
                eventModal.classList.contains(
                    "show"
                )
            ) {

                closeEventModal();

            }

        }
    );


    /* -----------------------------------------------------
       Register for event
       ----------------------------------------------------- */

    if (registerEvent) {

        registerEvent.addEventListener(
            "click",
            async function () {
                const eventId = Number(eventModal?.dataset.eventId);
                if (!Number.isInteger(eventId) || eventId < 1) {
                    registrationMessage.textContent =
                        "Event details are unavailable. Close this window and try again.";
                    registrationMessage.hidden = false;
                    return;
                }

                registerEvent.disabled = true;
                registerEvent.textContent = "Registering...";
                registrationMessage.hidden = true;

                try {
                    const tokenResponse = await fetch(
                        "../php/event-registration.php?action=token",
                        { headers: { Accept: "application/json" } }
                    );
                    const tokenData = await tokenResponse.json();
                    if (!tokenResponse.ok || !tokenData.csrfToken) {
                        throw new Error(
                            tokenData.error || "Could not prepare the registration."
                        );
                    }

                    const response = await fetch(
                        "../php/event-registration.php",
                        {
                            method: "POST",
                            headers: {
                                Accept: "application/json",
                                "Content-Type": "application/json",
                                "X-CSRF-Token": tokenData.csrfToken
                            },
                            body: JSON.stringify({ eventId })
                        }
                    );
                    const result = await response.json();

                    if (response.status === 401) {
                        window.location.href =
                            "login.html?return_to=events.html";
                        return;
                    }

                    if (response.ok || (response.status === 409 && result.registered)) {
                        registerEvent.textContent = "Registered";
                        registrationMessage.textContent = result.alreadyRegistered
                            ? "You are already registered for this event."
                            : "Your event registration has been saved.";
                        registrationMessage.hidden = false;
                        return;
                    }

                    throw new Error(
                        result.error || "Could not register for this event."
                    );
                } catch (error) {
                    console.error("Event registration failed.", error);
                    registerEvent.disabled = false;
                    registerEvent.textContent = "Register for Event";
                    registrationMessage.textContent =
                        error.message || "Could not register for this event.";
                    registrationMessage.hidden = false;
                }
            }
        );

    }

});