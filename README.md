# StudentHub Portal

> **Web Design and Frontend (WDF) Project**

---

## 📌 Project Overview

StudentHub is a multi-page student portal prototype designed to provide students with a simple and organized interface for accessing academic and campus-related information from one place.

The project demonstrates a multi-page student portal using **HTML5, CSS3, and JavaScript**, with PHP endpoints for login and registration. It focuses on page structure, navigation, forms, cards, responsive layouts, and a consistent visual design across the application.

The project includes interactive JavaScript page behaviors and PHP login/registration endpoints. PHP features require a local PHP server and the database settings configured for the environment; the site is not production authentication software.

---

## 🎯 Project Objectives

The main objectives of StudentHub are:

- To design a user-friendly student portal.
- To create a structured multi-page website using HTML5.
- To use CSS3 for page styling and layout.
- To create responsive layouts suitable for different screen sizes.
- To provide navigation between related pages.
- To demonstrate practical frontend web-development concepts.
- To provide a prototype that can be extended with securely configured backend and database services.

---

## 🛠️ Technology Stack

| **Technology** | **Purpose** |
|---|---|
| **HTML5** | Page structure, semantic content, forms, and navigation |
| **CSS3** | Styling, layouts, colors, spacing, cards, and forms |
| **CSS Flexbox / Grid** | Responsive page and component layouts |
| **Static File Navigation** | Linking pages using relative HTML paths |
| **JavaScript** | Page interactions, validation, menus, themes, and dashboard behaviors |
| **PHP** | Login and registration endpoints |
| **Image Assets** | Visual content used throughout the portal |

### 🔹 Current Implementation

- **Frontend:** HTML5 + CSS3
- **JavaScript:** Page-specific interactions and form validation
- **Backend:** PHP login, registration, contact, event catalog, and event registration endpoints are included
- **Database:** MySQLi connection is centralized in `php/dbconnect.php`; the running WAMP project uses the `studenthub` schema for student registration, login, events, and legacy event registrations
- **Session navigation:** Shared page script shows account links based on the active student or administrator session
- **Authentication:** Student and administrator login modes use prepared statements, hashed passwords, session regeneration, and CSRF-protected forms
- **Data Storage:** MySQL for student and event data; JSON for FAQ content
- **Shared footer:** All site pages use the same responsive footer with navigation and contact links

---

## 📄 Main Pages

The StudentHub project contains ten pages (static HTML pages plus PHP-backed student pages):

| **No.** | **Page** | **File** | **Purpose** |
|---:|---|---|---|
| 1 | 🏠 Home / Landing | `html/index.html` | Introduction and main navigation |
| 2 | 🔐 Login | `html/login.html` | Login interface |
| 3 | 📊 Dashboard | `html/dashboard.php` | Authenticated student overview |
| 4 | ℹ️ About | `html/about.html` | Project and portal information |
| 5 | 🛠️ Admin | `html/admin/dashboard.php` | Administrator-only management dashboard |
| 6 | 📞 Contact | `html/contact.html` | Contact interface |
| 7 | 📅 Events | `html/events.html` | MySQL-backed events and campus activities |
| 8 | ❓ FAQ | `html/faq.html` | Frequently asked questions |
| 9 | 👤 Profile | `html/profile.php` | Authenticated, database-backed student profile |
| 10 | 📝 Register | `html/register.html` | Student registration interface |

---

## 🎨 Stylesheets

Each HTML page has a matching CSS file with the same base name.
All pages also load `css/shared.css` after their page stylesheet for common colors, navigation, focus states, and responsive behavior.

| **HTML Page** | **CSS File** |
|---|---|
| `about.html` | `css/about.css` |
| `admin.html` | `css/admin.css` |
| `contact.html` | `css/contact.css` |
| `dashboard.php` | `css/dashboard.css` |
| `events.html` | `css/events.css` |
| `faq.html` | `css/faq.css` |
| `index.html` | `css/index.css` |
| `login.html` | `css/login.css` |
| `profile.php` | `css/profile.css` |
| `register.html` | `css/register.css` |

### 🎨 CSS Responsibilities

The CSS files are responsible for:

- 📐 Page layouts
- 🧭 Navigation
- 🃏 Cards and content sections
- 📝 Forms and input fields
- 🔘 Buttons
- 🔤 Typography
- ↔️ Spacing and alignment
- 📱 Responsive layouts
- 🎨 Visual styling

---

## 🖼️ Static Assets

The project currently contains the following image assets:

- `html/hackathon.jpg`
- `html/img.png`
- `html/sport.webp`
- `html/workshop.jpg`

These assets can be used for event sections, portal content, banners, cards, and other visual elements.

---

## ✨ Key Features

### 👨‍🎓 Student Portal Features

- 🏠 Landing page
- 🔐 Login interface
- 📝 Registration interface
- 📊 Student dashboard
- 👤 Profile page
- 📅 Events page
- ❓ FAQ page
- 📞 Contact page
- ℹ️ About page
- 🛠️ Administrator dashboard with student, event, and registration management

### 🎨 UI / Design Features

- 🧭 Multi-page navigation
- 🃏 Card-based sections
- 📝 Forms and buttons
- 📋 Structured content layouts
- 📱 Responsive CSS layouts
- 🎨 Consistent page styling
- 🖼️ Static image assets
- 📐 Low-fidelity wireframe support

---

## 📱 Responsive Design

The project follows responsive web-development principles so that layouts can adapt to different screen sizes.

The CSS structure can use modern techniques such as:

- **Flexbox**
- **CSS Grid**
- Relative sizing
- Flexible containers
- Media queries
- Mobile-friendly spacing and typography

---

## 🎨 Design Approach

StudentHub follows a simple and clear frontend-prototype approach.

The interface uses common UI components such as:

- 🧭 Navigation sections
- 🃏 Cards
- ▫️ Boxes
- ➖ Borders
- 🏷️ Headings
- 📝 Forms
- 🔘 Buttons
- 📄 Content sections
- 📱 Responsive layouts

The purpose is to establish the website structure and user flow before adding advanced application logic.

---

## 📁 Project Structure

```text
StudentHub/
│
├── README.md
├── FRONTEND_FOLDER_STRUCTURE.txt
├── SITEMAP.md
├── LOW_FIDELITY_WIREFRAME.md
│
├── html/
│   ├── css/
│   │   ├── shared.css
│   │   ├── about.css
│   │   ├── admin.css
│   │   ├── contact.css
│   │   ├── dashboard.css
│   │   ├── dashboard-dynamic.css
│   │   ├── events.css
│   │   ├── faq.css
│   │   ├── index.css
│   │   ├── login.css
│   │   ├── profile.css
│   │   └── register.css
│   │
│   ├── about.html
│   ├── admin.html
│   ├── admin.php
│   ├── contact.html
│   ├── dashboard.php
│   ├── events.html
│   ├── faq.html
│   ├── hackathon.jpg
│   ├── img.png
│   ├── index.html
│   ├── login.html
│   ├── profile.php
│   ├── event-register.php
│   ├── register.html
│   ├── sport.webp
│   ├── workshop.jpg
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── students.php
│   │   ├── events.php
│   │   └── registrations.php
│   └── json/
│       ├── events.json
│       └── faq.json
│
├── javascript/
│   ├── about.js
│   ├── admin.js
│   ├── contact.js
│   ├── dashboard.js
│   ├── events.js
│   ├── faq.js
│   ├── index.js
│   ├── login.js
│   ├── profile.js
│   └── register.js
│
├── database/
│   ├── studenthub_db.sql
│   └── migrations/
│       ├── 000_create_student.sql
│       ├── 001_profile_and_contact.sql
│       ├── 002_event_registrations.sql
│       ├── 003_events_catalog.sql
│       ├── 004_event_registration_details.sql
│       ├── 005_student_username.sql
│       └── 006_student_auth_fields.sql
│
├── php/
│   ├── auth.php
│   ├── contact.php
│   ├── dbconnect.php
│   ├── event-registration.php
│   ├── events.php
│   ├── footer.php
│   ├── login.php
│   ├── logout.php
│   ├── session-status.php
│   └── register.php
│
└── wireframe/
```

---

## 🚀 How to Run the Project

### 1️⃣ Option 1: Open the Frontend Directly

1. Open the `StudentHub` project folder.
2. Navigate to the `html` directory.
3. Open `index.html`.
4. Use the navigation links to move between pages.

> Static pages and JavaScript interactions can be viewed this way. PHP form endpoints require a PHP-enabled server.

### 2️⃣ Option 2: Use Visual Studio Code / WAMP

1. Open the `StudentHub` folder in **Visual Studio Code**.
2. Start the PHP-enabled local web server (for example, WAMP).
3. Open `http://localhost/<project-folder>/html/index.html`.
4. Configure and start the PHP/database services before testing login and registration.

### Current MySQL database setup

The current WAMP setup uses the shared MySQLi connection in `php/dbconnect.php` with MySQL on port 3306 and the `studenthub` database.

1. Back up any existing database before changing it. For example: `mysqldump -u root -p --single-transaction studenthub > studenthub_backup.sql`.
2. Import `database/studenthub_db.sql` for the canonical schema, or follow the legacy setup below when using the practical `studenthub.student` table.
3. For a database created from an earlier version of this canonical schema, apply `database/migrations/006_student_auth_fields.sql` only if its `students.gender` and `students.academic_year` columns are missing. Do not run it against a fresh import because those columns are already in the schema.
4. The legacy `studenthub` tables are not automatically renamed or copied. Back up legacy data and plan/test any data conversion separately before using the canonical tables.

The canonical schema includes `students`, `admins`, `events`, `registrations`, `remember_tokens`, `audit_logs`, `login_attempts`, and `contact_messages`. The running practical schema uses `student`, `events`, `event_registrations`, `admins`, and `login_attempts`.

The connection supports WAMP/XAMPP defaults (`127.0.0.1`, `root`, empty local password, port `3306`, database `studenthub`). Override them with `STUDENTHUB_DB_HOST`, `STUDENTHUB_DB_USER`, `STUDENTHUB_DB_PASSWORD`, `STUDENTHUB_DB_NAME`, and `STUDENTHUB_DB_PORT`. Keep credentials in the local environment, never in source control.

There is no default administrator. Use the secure CLI process described below to create an admin; never insert or store a plaintext password. Student self-registration is available from `html/register.html`.

Students sign in through Student Login and are sent to `html/dashboard.php`; administrators use Admin Login and are sent to `html/admin/dashboard.php`. The admin folder implements student search/filter/pagination, create/view/edit/deactivate/delete, event management and poster uploads, and participant listings. Every management page checks the administrator session on the server. Student dashboards/profile and event registration independently require an active student account; public event listings read from MySQL.

Sign-out is a CSRF-protected POST operation. State-changing forms use session-bound CSRF tokens, sessions expire after 20 minutes of inactivity, and login attempts are throttled. Client-side session navigation only changes displayed links; it is not an authorization boundary. Event posters are stored under `html/uploads/events/`.

Run the site through Apache/PHP (for example WAMP) at `http://localhost/<project-folder>/html/index.html`. phpMyAdmin or MySQL must be running for database-backed routes. The FAQ content remains in JSON.

### First admin account

There is no public administrator-registration form or default admin password. Run this from the project root in a trusted terminal:

```text
D:\wamp\bin\php\php8.4.15\php.exe scripts\create-admin.php
```

The script collects a name, username, email, and terminal-hidden password, validates the values, and stores only a `password_hash()` in `admins`. The first admin becomes `super_admin`; subsequent CLI-created accounts are regular admins. Run the same script to add admins safely. Never expose it through Apache or allow students to insert into `admins` or change roles.

### Optional one-time JSON import

The supplied `html/json/students.json` and `html/json/events.json` contain 15 demo records each. They are not runtime data sources; FAQ content remains in `faq.json`. Preview before importing:

```text
php scripts\import-json-data.php --students --events --dry-run
php scripts\import-json-data.php --students --events --yes
```

Select only `--students` or `--events` to import one table. Each selected MySQL table must be empty or the importer stops without writing anything. The JSON source files are never modified. Imported demo students are inactive, get generated usernames, and receive unrecoverable random password hashes, so they cannot log in until an admin reviews and activates/resets them. Event organizer values and original image filenames are retained. Back up the database first.

For an older canonical database, run `database/migrations/007_event_organizer.sql` once if `events.organizer` is missing. Fresh schema imports already include it.

### Admin dashboard and styling

The dashboard generates all statistics, charts, event lists, and registration tables from MySQL, with explicit empty states. It reuses `html/css/admin.css` and `javascript/admin.js`, with Bootstrap 5.3.3 and Bootstrap Icons loaded once from CDN. No Metis ZIP/template was present in the workspace; the existing StudentHub branding and CSS were adapted into a responsive admin layout rather than using static Metis records.

`.env.example` documents the supported connection variables. PHP does not parse `.env` files, so configure these values in Apache/PHP or the WAMP/XAMPP environment. `.env` is ignored by Git.

### Legacy database setup (registration practical)

The registration practical stores new account records in the `studenthub` database and its `student` table. For a fresh legacy database, run these migrations in numeric order:

1. `database/migrations/000_create_student.sql` creates the required student account fields: `student_id`, `student_name`, `email`, `mobile_no`, `gender`, `password`, and `time_stamp`.
2. `database/migrations/001_profile_and_contact.sql` adds `course` and `academic_year`, and creates `contact_messages`.
3. `database/migrations/002_event_registrations.sql` creates `event_registrations`.
4. `database/migrations/003_events_catalog.sql` creates/seeds the `studenthub.events` catalog used by the current event pages.
5. `database/migrations/004_event_registration_details.sql` adds the registrant name, email, mobile number, registration status, and update timestamp to `event_registrations`.
6. `database/migrations/005_student_username.sql` adds a unique username for student accounts and assigns one to existing students.
7. `database/migrations/008_student_registration_schema.sql` creates the complete registration table for a fresh `studenthub` database.
8. `database/migrations/009_admin_management_support.sql` creates the `admins` and `login_attempts` tables required for administrator login and student management.

Back up before applying any migration because older migrations may already have been applied. `php/register.php` connects to `studenthub` and inserts each valid submission into `student`; the generated `student_id`, email and username constraints allow multiple accounts while preventing duplicate account identifiers.

The current registration, student login, profile, dashboard, event listing, event registration, and admin student-management handlers target this legacy practical schema. The student-management module supports add, view, edit, delete, search, and course filtering through prepared MySQLi statements with success/error messages. The WAMP/XAMPP environment variables are documented in `.env.example`.

### Admin event management

The admin event module uses the legacy `studenthub.events` table and supports:

- Add, view, edit, and delete events
- Event title, date, time, venue, category, organizer, and description
- Search and category filtering
- Poster uploads saved under `html/uploads/events/`
- JPEG, PNG, and WebP MIME validation
- Maximum poster size of 5 MB
- Prepared MySQLi statements and CSRF-protected admin forms
- Clear success and failure messages

Open `html/admin/events.php` after administrator login to manage events.

The asynchronous event API is available at `php/events-api.php`, and the
Fetch API admin interface is `html/admin/events-api.php`. It supports GET
list/detail requests and CSRF-protected POST, PUT (multipart POST with
`_method=PUT`), and DELETE operations without full page reloads.

---

## ⚠️ Prototype Limitations

Start Apache and MySQL before verifying the live workflows. Student registration and login were verified through the running WAMP server after aligning the handlers with the live schema.

Registration, login, student profile updates, contact form submissions, event listings, dashboard event summaries, and event registrations require Apache/PHP and the `studenthub` schema. Administrator login additionally requires an administrator table and active administrator record. The FAQ content remains a JSON file. Production deployment still requires environment-specific database credentials, HTTPS, and operational/security hardening.

---

## 🔮 Future Scope

StudentHub can be extended into a complete student-management platform by adding:

### 💻 Frontend Enhancements

- Dynamic content
- Interactive dashboards
- Search and filtering
- Animations and transitions
- Advanced responsive behavior
- Accessibility improvements

### ⚙️ Backend Enhancements

- User authentication
- Student registration
- Database integration
- Course management
- Assignment management
- Grade management
- Event management
- Profile management
- Admin controls

### 🚀 Advanced Features

- 🔔 Student notifications
- 🗓️ Timetable management
- 📅 Calendar
- 📊 Attendance tracking
- ⏰ Assignment deadlines
- 📤 Online submissions
- 👨‍🏫 Faculty portal
- 🛠️ Admin dashboard
- 🔑 Role-based access control

---

## 📚 Additional Documentation

| **File** | **Purpose** |
|---|---|
| `README.md` | Complete project documentation |
| `SITEMAP.md` | Website hierarchy and navigation flow |
| `LOW_FIDELITY_WIREFRAME.md` | Initial layout and wireframe planning |
| `FRONTEND_FOLDER_STRUCTURE.txt` | Frontend folder and file reference |

---

## 📊 Project Status

**Current Status:** `Frontend Prototype / WDF Practical Project`

The project demonstrates:

- 🌐 Multi-page website development
- 🧱 HTML5 structure
- 🎨 CSS3 styling
- 📱 Responsive layout concepts
- 🧭 Navigation between pages
- 🎓 Academic portal UI design
- 📝 Forms
- 🃏 Cards
- 🖼️ Static image assets
- 📊 Dashboard-style layouts

The project can be further enhanced with production-ready backend services, security controls, and database functionality.

---

## 📝 Project Information

| **Item** | **Details** |
|---|---|
| **Project Name** | StudentHub Portal |
| **Project Type** | Web Design and Frontend Project |
| **Development Type** | Static Frontend Prototype |
| **Primary Technologies** | HTML5 and CSS3 |
| **Documentation** | README + Sitemap + Wireframe Documentation |

---

## ✅ Conclusion

StudentHub provides a structured frontend for an academic student portal. The project demonstrates practical knowledge of **HTML5, CSS3, responsive layouts, navigation, forms, cards, and UI organization**.

The current prototype establishes the major portal screens and provides a foundation for future development into a fully functional academic management system.

---

> **StudentHub Portal — Web Design and Frontend (WDF) Project**
