(() => {
    const authContainers = document.querySelectorAll(".auth-buttons");
    if (authContainers.length === 0) {
        return;
    }

    // The pages are typically served through a local PHP server, but opening the HTML
    // directly from the filesystem should not break the page. In that case, the fetch
    // may return the PHP source or an unavailable route instead of JSON.
    fetch("../php/session-status.php", {
        credentials: "same-origin",
        cache: "no-store",
        headers: { Accept: "application/json" },
    })
        .then(async (response) => {
            const contentType = response.headers.get("content-type") || "";

            if (!response.ok || !contentType.includes("application/json")) {
                return null;
            }

            return response.json();
        })
        .then((status) => {
            if (status && typeof status.csrfToken === "string") {
                document.querySelectorAll('input[name="csrf_token"]').forEach((input) => {
                    if (!input.value) {
                        input.value = status.csrfToken;
                    }
                });
            }

            if (
                !status ||
                status.authenticated !== true ||
                !["student", "admin"].includes(status.userType)
            ) {
                return;
            }

            authContainers.forEach((container) => {
                const existingLinks = container.querySelectorAll("a");
                if (existingLinks.length < 2) {
                    return;
                }

                const profileLink = document.createElement("a");
                profileLink.href = status.userType === "admin" ? "admin.php" : "profile.php";
                profileLink.textContent = status.userType === "admin" ? "Admin Panel" : "Profile";
                profileLink.className = existingLinks[0].className;
                profileLink.classList.remove("active");

                const logoutLink = document.createElement("a");
                logoutLink.href = "../php/logout.php";
                logoutLink.textContent = "Log out";
                logoutLink.className = existingLinks[1].className;
                logoutLink.classList.remove("active");

                existingLinks[0].replaceWith(profileLink);
                existingLinks[1].replaceWith(logoutLink);
            });
        })
        .catch((error) => {
            // Ignore network errors if the PHP endpoint is unavailable while browsing
            // static files or before the local server is started.
            console.debug("StudentHub session check unavailable.", error);
        });
})();
