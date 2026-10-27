# 🎓 StudentHub Portal — Sitemap
<img width="1536" height="1024" alt="ChatGPT Image Aug 15, 2026, 03_24_25 PM" src="https://github.com/user-attachments/assets/9c51dc3d-7acd-4fb3-b996-2dfcac67f20d" />

## 📌 Project Overview

StudentHub Portal is a multi-page student portal prototype developed as a Web Design and Frontend (WDF) project.

- **Project Type:** Frontend and PHP prototype / WDF Project
- **Primary Technologies:** HTML5 + CSS3 + JavaScript + PHP
- **Implemented Pages:** 10
- **JavaScript:** Implemented for page interactions
- **Backend:** PHP login, registration, contact, and event endpoints included
- **Database:** Local setup/configuration required
- **Shared footer:** Available across site pages with navigation and contact links
- **Authentication:** Prototype; not production-hardened

---

## 🗺️ 1. Main Sitemap

```text
StudentHub Portal
│
├── 🏠 1. Home / Landing
│   └── index.html
│
├── 🔐 2. Login
│   └── login.html
│
├── 📊 3. Dashboard
│   └── dashboard.php
│
├── ℹ️ 4. About
│   └── about.html
│
├── 🛠️ 5. Admin
│   └── admin.html
│
├── 📞 6. Contact
│   └── contact.html
│
├── 📅 7. Events
│   └── events.html
│
├── ❓ 8. FAQ
│   └── faq.html
│
├── 👤 9. Profile
│   └── profile.php
│
└── 📝 10. Register
    └── register.html
```

---

## 🎨 2. Sitemap Legend

| Symbol | Category | Pages |
|---|---|---|
| 🔵 | Main Page / Entry | Home / Landing |
| 🟢 | User Pages | Dashboard, Events, Profile |
| 🟣 | Information Pages | About, Contact, FAQ |
| 🟠 | Admin Page | Admin |
| 🟡 | Authentication Pages | Login, Register |

---

## 📄 3. Current Implemented Pages

| No. | Page | File | Purpose |
|---:|---|---|---|
| 1 | 🏠 Home / Landing | `html/index.html` | Main entry point |
| 2 | 🔐 Login | `html/login.html` | Login interface |
| 3 | 📊 Dashboard | `html/dashboard.php` | Authenticated student overview |
| 4 | ℹ️ About | `html/about.html` | Information about StudentHub |
| 5 | 🛠️ Admin | `html/admin.html` | Administration interface prototype |
| 6 | 📞 Contact | `html/contact.html` | Contact interface |
| 7 | 📅 Events | `html/events.html` | Campus events and activities |
| 8 | ❓ FAQ | `html/faq.html` | Frequently asked questions |
| 9 | 👤 Profile | `html/profile.php` | Authenticated student profile |
| 10 | 📝 Register | `html/register.html` | Student registration interface |

**Total implemented pages: 10**

---

## 📋 4. Page Details

### 🏠 Home / Landing — `index.html`

**Purpose:** Main entry point of the StudentHub Portal.

**Main content:**
- StudentHub introduction
- Main navigation
- Portal overview
- Access to important sections
- Student-oriented information

**CSS:** `html/css/index.css`

---

### 🔐 Login — `login.html`

**Purpose:** Provides the user login interface.

**Main content:**
- Username/email field
- Password field
- Login button
- Registration navigation
- Authentication UI

**CSS:** `html/css/login.css`

**Limitation:** A PHP login endpoint is included; configure the local PHP/database environment and harden authentication before deployment.

---

### 📊 Dashboard — `dashboard.php`

**Purpose:** Provides the main student dashboard interface.

**Main content:**
- Student overview
- Dashboard cards
- Quick-access information
- Portal information
- Announcements/information

**CSS:** `html/css/dashboard.css`

**Limitation:** Dashboard information is static.

---

### ℹ️ About — `about.html`

**Purpose:** Provides information about the StudentHub project and portal.

**Main content:**
- Project introduction
- Project objectives
- Portal information
- Academic platform concept

**CSS:** `html/css/about.css`

---

### 🛠️ Admin — `admin.html`

**Purpose:** Provides an administrative interface prototype.

**Main content:**
- Portal management UI
- Content management UI
- Event-related management UI
- Administrative controls

**CSS:** `html/css/admin.css`

**Limitations:**
- No real admin authentication
- No database operations
- No user-management backend
- No dynamic event management
- No role-based access control

---

### 📞 Contact — `contact.html`

**Purpose:** Provides a contact interface for users.

**Main content:**
- Contact information
- Contact form/interface
- User enquiry interface
- Communication-related UI

**CSS:** `html/css/contact.css`

Contact form submissions are saved through the PHP backend. The shared site footer provides site navigation and contact links.

---

### 📅 Events — `events.html`

**Purpose:** Displays student and campus event information.

**Example categories:**
- Workshops
- Hackathons
- Sports
- Campus activities

**Related image assets:**
- `hackathon.jpg`
- `sport.webp`
- `workshop.jpg`

**CSS:** `html/css/events.css`

---

### ❓ FAQ — `faq.html`

**Purpose:** Provides frequently asked questions and answers.

**Main content:**
- Common student questions
- Answers/help information
- Organized FAQ sections

**CSS:** `html/css/faq.css`

---

### 👤 Profile — `profile.php`

**Purpose:** Provides a student profile interface.

**Main content:**
- Student information
- Profile details
- User account information
- Profile UI components

**CSS:** `html/css/profile.css`

**Limitation:** Profile information is not persistently stored in a database.

---

### 📝 Register — `register.html`

**Purpose:** Provides the student registration interface.

**Main content:**
- Registration form
- Student information fields
- Registration button
- Navigation to Login

**CSS:** `html/css/register.css`

**Limitation:** A PHP registration endpoint is included; configure and verify the local database/storage setup before use.

---

## 🎨 5. Stylesheets / CSS

Each HTML page has a corresponding CSS file.

```text
html/
│
└── 📁 css/
    ├── 🎨 about.css
    ├── 🎨 admin.css
    ├── 🎨 contact.css
    ├── 🎨 dashboard.css
    ├── 🎨 dashboard-dynamic.css
    ├── 🎨 dashboard-dynamic.css
    ├── 🎨 events.css
    ├── 🎨 faq.css
    ├── 🎨 index.css
    ├── 🎨 login.css
    ├── 🎨 profile.css
    ├── 🎨 register.css
    └── 🎨 shared.css
    └── 🎨 shared.css
```

### CSS Responsibilities

- 📐 Page layouts
- 🧭 Navigation styling
- 🃏 Cards
- 📝 Forms
- 🔘 Buttons
- 🔤 Typography
- 🎨 Colors
- ↔️ Spacing
- ▫️ Borders
- 📱 Responsive layouts
- 📏 Alignment
- 🖥️ Page-specific styling

---

## 🖼️ 6. Image Assets

```text
html/
│
├── 🖼️ hackathon.jpg
├── 🖼️ img.png
├── 🖼️ sport.webp
└── 🖼️ workshop.jpg
```

### Uses

- Event cards
- Workshops
- Sports activities
- Hackathons
- Portal banners
- Other visual content

---

## 📚 7. Project Documentation

```text
Documentation
│
├── 📄 README.md
├── 📄 SITEMAP.md
├── 📄 LOW_FIDELITY_WIREFRAME.md
└── 📄 FRONTEND_FOLDER_STRUCTURE.txt
```

| File | Purpose |
|---|---|
| `README.md` | Complete project documentation |
| `SITEMAP.md` | Website hierarchy and navigation structure |
| `LOW_FIDELITY_WIREFRAME.md` | Initial layout and wireframe planning |
| `FRONTEND_FOLDER_STRUCTURE.txt` | Frontend file/folder reference |

---

## 📁 8. Complete Project Structure

```text
StudentHub/
│
├── 📄 README.md
├── 📄 FRONTEND_FOLDER_STRUCTURE.txt
├── 📄 SITEMAP.md
├── 📄 LOW_FIDELITY_WIREFRAME.md
│
├── 📁 html/
│   ├── 📁 json/
│   │   ├── events.json
│   │   └── faq.json
│   │
│   ├── 📁 css/
│   │   ├── 🎨 about.css
│   │   ├── 🎨 admin.css
│   │   ├── 🎨 contact.css
│   │   ├── 🎨 dashboard.css
│   │   ├── 🎨 dashboard-dynamic.css
│   │   ├── 🎨 events.css
│   │   ├── 🎨 faq.css
│   │   ├── 🎨 index.css
│   │   ├── 🎨 login.css
│   │   ├── 🎨 profile.css
│   │   ├── 🎨 register.css
│   │   └── 🎨 shared.css
│   │
│   ├── 🏠 index.html
│   ├── 🔐 login.html
│   ├── 📊 dashboard.php
│   ├── ℹ️ about.html
│   ├── 🛠️ admin.html
│   ├── 📞 contact.html
│   ├── 📅 events.html
│   ├── ❓ faq.html
│   ├── 👤 profile.php
│   ├── 📝 register.html
│   │
│   ├── 🖼️ hackathon.jpg
│   ├── 🖼️ img.png
│   ├── 🖼️ sport.webp
│   └── 🖼️ workshop.jpg
│
├── 📁 javascript/
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
├── 📁 php/
│   ├── footer.php
│   ├── dbconnect.php
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── session-status.php
└── 📁 wireframe/
```

The shared `php/footer.php` partial is rendered on static and PHP-generated pages.

When a student signs in, the shared session navigation displays Profile and Log out in the page header instead of Login and Register.

Public information pages use the full footer. Login and registration pages and signed-in student pages use a compact footer, while generated result pages show only the copyright line.

---

## 🧭 9. Navigation Structure

The sitemap's logical navigation structure is:

```text
                         🎓 STUDENTHUB PORTAL
                                  │
                                  ▼
                         🏠 HOME / LANDING
                                  │
        ┌──────────┬──────────────┼──────────────┬───────────┐
        │          │              │              │           │
        ▼          ▼              ▼              ▼           ▼
     🔐 LOGIN   📊 DASHBOARD   ℹ️ ABOUT       🛠️ ADMIN   📞 CONTACT
        │          │              │              │           │
        ▼          │              │              │           ▼
   📝 REGISTER     │              │              │          ❓ FAQ
                   │              │              │
                   ├──────────────┼──────────────┐
                   ▼              ▼              ▼
                📅 EVENTS       👤 PROFILE    Other UI
```

> **Note:** This diagram represents the intended sitemap/navigation structure. Exact clickable relationships should be verified from the actual HTML `<a href>` links.

---

## ⚠️ 10. Static Prototype Limitations

The current StudentHub project is an interactive frontend and PHP prototype. The following application features are still prototypes or require environment-specific setup:

```text
⚠️ PHP server and database configuration
⚠️ Production-ready authentication and authorization
⚠️ Persistent, database-backed profile and dashboard data
⚠️ Live admin, user, and event management
⚠️ Production contact and feedback delivery
```

---

## 🚀 11. Future Scope

The portal can later be extended with:

```text
Future StudentHub
│
├── 📚 Academic
│
├── 📦 Resources
│   ├── 📝 Notes
│   ├── 📄 Papers
│   ├── 🎥 Videos
│   └── 📚 E-books
│
├── 🔔 Notifications
├── 🕒 Timetable
├── 📅 Calendar
├── 📊 Attendance
├── ⏰ Assignment Deadlines
├── 📤 Online Submissions
├── 💬 Feedback
├── 👨‍🏫 Faculty Portal
└── 🛠️ Advanced Admin Dashboard
```

These are **future features** and are not part of the current 10-page implementation.

---

## 📊 12. Current Project Status

| Component | Status |
|---|---|
| 🏠 Home | ✅ Implemented |
| 🔐 Login UI | ✅ Implemented |
| 📊 Dashboard | ✅ Implemented |
| ℹ️ About | ✅ Implemented |
| 🛠️ Admin UI | ✅ Implemented |
| 📞 Contact | ✅ Implemented |
| 📅 Events | ✅ Implemented |
| ❓ FAQ | ✅ Implemented |
| 👤 Profile | ✅ Implemented |
| 📝 Register | ✅ Implemented |
| 🎨 CSS | ✅ Implemented |
| 🖼️ Image Assets | ✅ Available |
| 📚 Documentation | ✅ Available |
| 📱 Responsive CSS | ✅ Implemented |
| JavaScript interactions | ✅ Implemented |
| PHP login and registration endpoints | ✅ Included; environment setup required |
| Database configuration | ⚠️ Local setup required |
| Production authentication | ⚠️ Not production-hardened |

---

## ✅ 13. Final Summary

The StudentHub sitemap contains:

- 🏠 **1 Main Landing Page**
- 🔐 **2 Authentication Pages**
- 👨‍🎓 **3 Student/User Pages**
- 🟣 **3 Information/Support Pages**
- 🛠️ **1 Administration Page**
- 🎨 **10 Corresponding CSS Files**
- 🖼️ **4 Image Assets**
- 📚 **4 Documentation Files**

**Total current HTML pages: 10**

> 🎓 **StudentHub Portal — Web Design and Frontend (WDF) Project**
>
> Static Frontend Academic Portal Prototype
