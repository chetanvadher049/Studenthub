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
- **Backend:** PHP login and registration endpoints are included
- **Database:** MySQL connection is centralized in `php/dbconnect.php`; student profile and contact messages use the `studenthub` database
- **Authentication:** Prototype only; review and harden before deployment
- **Data Storage:** Static JSON content and PHP registration data files

---

## 📄 Main Pages

The StudentHub project contains ten pages (static HTML pages plus PHP-backed student pages):

| **No.** | **Page** | **File** | **Purpose** |
|---:|---|---|---|
| 1 | 🏠 Home / Landing | `html/index.html` | Introduction and main navigation |
| 2 | 🔐 Login | `html/login.html` | Login interface |
| 3 | 📊 Dashboard | `html/dashboard.php` | Authenticated student overview |
| 4 | ℹ️ About | `html/about.html` | Project and portal information |
| 5 | 🛠️ Admin | `html/admin.html` | Admin interface prototype |
| 6 | 📞 Contact | `html/contact.html` | Contact interface |
| 7 | 📅 Events | `html/events.html` | Events and campus activities |
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
- 🛠️ Admin interface prototype

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
│   ├── contact.html
│   ├── dashboard.php
│   ├── events.html
│   ├── faq.html
│   ├── hackathon.jpg
│   ├── img.png
│   ├── index.html
│   ├── login.html
│   ├── profile.php
│   ├── register.html
│   ├── sport.webp
│   ├── workshop.jpg
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
│   └── migrations/
│       ├── 001_profile_and_contact.sql
│       └── 002_event_registrations.sql
│
├── php/
│   ├── auth.php
│   ├── contact.php
│   ├── dbconnect.php
│   ├── logout.php
│   ├── login.php
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

### Registration database

Registration and login use the `studenthub` database and its `student` table:

`student_id`, `student_name`, `email`, `mobile_no`, `gender`, `password`, `time_stamp`

The current table uses a string `student_id`; registration generates a 32-character hexadecimal ID. `mobile_no` is `VARCHAR(10)` so all 10 digits, including a leading zero, are retained. The local database column was updated from `INT` to `VARCHAR(10)`. The password column must be long enough for a `password_hash()` result; `VARCHAR(255)` is recommended.

The additive migration in `database/migrations/001_profile_and_contact.sql` adds the course/year fields used by profile editing and a `contact_messages` table for submitted contact forms. It has been applied to the local `studenthub` database. Run `database/migrations/002_event_registrations.sql` to add the table used by authenticated event registrations (it is also applied to the local database).

The connection defaults in `php/dbconnect.php` are for a local WAMP setup (`localhost`, user `root`, an empty password, and database `studenthub`). Override them with the `STUDENTHUB_DB_HOST`, `STUDENTHUB_DB_USER`, `STUDENTHUB_DB_PASSWORD`, `STUDENTHUB_DB_NAME`, and `STUDENTHUB_DB_PORT` environment variables when needed. Do not expose production database credentials in source control.

Dashboard and profile routes require an authenticated student session. Use the **Log out** link to end the session. The admin page remains an accessible prototype; the database does not define administrator roles or permissions.

---

## ⚠️ Prototype Limitations

Registration, login, student profile updates, contact form submissions, and event registrations are connected to the local MySQL database. They require Apache/PHP, the `studenthub` database, and the migrations above. The events catalog remains a JSON file, and the admin page is still a public prototype because the student schema has no roles or permissions. Production deployment still requires environment-specific database credentials, HTTPS, and appropriate operational/security hardening.

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
