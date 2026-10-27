document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector(".async-event-manager");
    if (!root) return;
    const apiUrl = root.dataset.apiUrl;
    const csrfToken = root.dataset.csrfToken;
    const form = document.getElementById("async-event-form");
    const list = document.getElementById("async-event-list");
    const message = document.getElementById("async-event-message");

    const showMessage = (text, isError = false) => {
        message.textContent = text;
        message.hidden = !text;
        message.classList.toggle("admin-flash--error", isError);
    };

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            ...options,
            headers: { Accept: "application/json", ...(options.headers || {}) }
        });
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.errors?.join(" ") || data.error || "Request failed.");
        }
        return data;
    };

    const render = (events) => {
        list.replaceChildren();
        if (!events.length) {
            list.innerHTML = "<tr><td colspan=\"6\">No events found.</td></tr>";
            return;
        }
        events.forEach((event) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${escapeHtml(event.title)}</td>
                <td>${escapeHtml(event.event_date)} ${escapeHtml(event.event_time)}</td>
                <td>${escapeHtml(event.venue)}</td>
                <td>${escapeHtml(event.category)}</td>
                <td>${event.image ? "Uploaded" : "None"}</td>
                <td class="admin-actions">
                    <button type="button" data-edit="${event.id}">Edit</button>
                    <button type="button" class="admin-button--danger" data-delete="${event.id}">Delete</button>
                </td>`;
            list.append(row);
        });
    };

    const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (char) => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    }[char]));

    const load = async () => {
        try {
            render((await request(apiUrl)).events);
        } catch (error) {
            showMessage(error.message, true);
        }
    };

    const resetForm = () => {
        form.reset();
        form.elements.event_id.value = "";
        form.hidden = true;
    };

    document.getElementById("async-event-new").addEventListener("click", () => {
        resetForm();
        form.hidden = false;
        form.elements.title.focus();
    });
    document.getElementById("async-event-cancel").addEventListener("click", resetForm);
    document.getElementById("async-event-refresh").addEventListener("click", load);

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const id = form.elements.event_id.value;
        const body = new FormData(form);
        body.append("csrf_token", csrfToken);
        if (id) body.append("_method", "PUT");
        try {
            const result = await request(id ? `${apiUrl}?id=${encodeURIComponent(id)}` : apiUrl, {
                method: "POST",
                body
            });
            showMessage(result.message);
            resetForm();
            await load();
        } catch (error) {
            showMessage(error.message, true);
        }
    });

    list.addEventListener("click", async (event) => {
        const editId = event.target.dataset.edit;
        const deleteId = event.target.dataset.delete;
        if (editId) {
            try {
                const data = await request(`${apiUrl}?id=${editId}`);
                const record = data.events[0];
                Object.entries(record).forEach(([key, value]) => {
                    if (form.elements[key]) form.elements[key].value = value ?? "";
                });
                form.elements.event_id.value = record.id;
                form.hidden = false;
                form.elements.title.focus();
            } catch (error) {
                showMessage(error.message, true);
            }
        }
        if (deleteId && confirm("Delete this event?")) {
            try {
                const result = await request(`${apiUrl}?id=${deleteId}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-Token": csrfToken }
                });
                showMessage(result.message);
                await load();
            } catch (error) {
                showMessage(error.message, true);
            }
        }
    });

    load();
});
