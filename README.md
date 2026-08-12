# StudentHub Portal

> **Web Design and Frontend (WDF) Project**

## 📌 Project Overview

StudentHub is a **frontend-only academic portal** designed to provide students with a simple and organized interface for accessing academic and campus-related information from one place.

The project demonstrates the design and development of a multi-page student portal using **HTML5 and CSS3**. It focuses on page structure, navigation, cards, tables, forms, responsive layouts, and a consistent visual design across the application.

The current version is a **static frontend prototype**. It does not require a backend server, database, or JavaScript-based application logic.

---

## 🎯 Project Objectives

The main objectives of StudentHub are:

- To design a user-friendly student portal.
- To create a structured multi-page website using HTML5.
- To use CSS3 for professional page styling and layout.
- To implement responsive layouts suitable for different screen sizes.
- To provide consistent navigation between related pages.
- To demonstrate practical frontend web-development concepts.
- To create a foundation that can later be connected to a backend and database.

---

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| **HTML5** | Page structure, semantic content, forms, and navigation |
| **CSS3** | Styling, layouts, colors, spacing, cards, tables, and forms |
| **CSS Flexbox / Grid** | Responsive component and page layouts |
| **Static File Navigation** | Linking pages using relative HTML paths |
| **Image Assets** | Visual content used by event and portal sections |

### Current Implementation

- **Frontend:** HTML5 + CSS3
- **JavaScript:** Not required for the current prototype
- **Backend:** Not implemented
- **Database:** Not implemented
- **Authentication:** UI prototype only
- **Data Storage:** Static content

---

## 📄 Main Pages

The StudentHub project contains the following main frontend pages:

| No. | Page | File | Purpose |
|---:|---|---|---|
| 1 | Landing / Home | `html/index.html` | Introduction, hero section, and main navigation |
| 2 | Login | `html/login.html` | Login and authentication interface |
| 3 | Dashboard | `html/dashboard.html` | Student overview and quick-access information |
| 4 | Courses | `html/courses.html` | Course listing and course information |
| 5 | Course Workspace | `html/course_workspace.html` | Course modules, syllabus, and learning content |
| 6 | Assignments | `html/assignments.html` | Assignment list, status, and tracking |
| 7 | Assignment Details | `html/assignment-details.html` | Assignment instructions and submission interface |
| 8 | Grades | `html/grades.html` | Gradebook, GPA, and course-wise grades |
| 9 | Discussions | `html/discussions.html` | Discussion forum and thread interface |
| 10 | Profile | `html/profile.html` | User profile, preferences, and account settings |

> **Note:** Keep the filenames in this README synchronized with the actual files in the project folder.

---

## 📖 Page Purpose

### 1. Home / Landing Page

**File:** `html/index.html`

The landing page introduces StudentHub and provides access to the main features of the portal. It contains the primary navigation and feature highlights.

### 2. Login

**File:** `html/login.html`

Provides the frontend layout for student authentication, including login and sign-up related interface elements.

### 3. Dashboard

**File:** `html/dashboard.html`

Provides an overview of student activities such as course progress, upcoming work, deadlines, and announcements.

### 4. Courses

**File:** `html/courses.html`

Displays course information using a structured course catalog/card layout.

### 5. Course Workspace

**File:** `html/course_workspace.html`

Provides a detailed course area containing modules, syllabus information, materials, and course-related sections.

### 6. Assignments

**File:** `html/assignments.html`

Displays assignments using structured layouts such as tables, filters, status indicators, and assignment information.

### 7. Assignment Details

**File:** `html/assignment-details.html`

Provides detailed assignment information, instructions, upload/submission interface, and related content.

### 8. Grades

**File:** `html/grades.html`

Displays student academic performance using GPA summaries, course-wise grades, and gradebook information.

### 9. Discussions

**File:** `html/discussions.html`

Provides a discussion/forum interface containing discussion threads, categories, and forum actions.

### 10. Profile

**File:** `html/profile.html`

Provides the user profile and account-settings interface, including personal information and preferences.

---

## 🎨 Stylesheets

The project uses separate CSS files to keep page structure and presentation organized.

| Page | Stylesheet |
|---|---|
| Home | `html/css/index.css` |
| Login | `html/css/login.css` |
| Dashboard | `html/css/dashboard.css` |
| Courses | `html/css/courses.css` |
| Course Workspace | `html/css/course_workspace.css` |
| Assignments | `html/css/assignments.css` |
| Assignment Details | `html/css/assignments-details.css` |
| Grades | `html/css/grades.css` |
| Discussions | `html/css/discussions.css` |
| Profile | `html/css/profile.css` |

### CSS Responsibilities

The stylesheets are responsible for:

- Page layouts
- Responsive design
- Navigation
- Cards and panels
- Tables
- Forms and input fields
- Buttons
- Progress indicators
- Typography
- Spacing and alignment
- Mobile-friendly layouts

---

## ✨ Key Features

### Student Features

- Landing page with project introduction
- Student login interface
- Student dashboard
- Course catalog
- Course workspace
- Assignment tracking
- Assignment details and submission UI
- Gradebook and GPA display
- Discussion forum
- User profile and settings

### UI / Design Features

- Consistent page navigation
- Card-based content sections
- Structured tables
- Forms and buttons
- Progress indicators
- Sidebar-style layouts where applicable
- Responsive CSS layouts
- Reusable visual patterns
- Low-fidelity prototype approach

---

## 📱 Responsive Design

The project is designed with responsive web-development principles so that layouts can adapt to different screen sizes.

The CSS uses or is intended to use modern layout techniques such as:

- **Flexbox**
- **CSS Grid**
- Relative sizing
- Flexible containers
- Media queries
- Mobile-friendly spacing and typography

The recommended design approach is **mobile-first**, followed by enhancements for tablet and desktop screens.

---

## 🎨 Design Approach

StudentHub follows a **low-fidelity frontend prototype** approach.

The interface uses simple and clear visual components such as:

- Boxes
- Borders
- Cards
- Headings
- Tables
- Forms
- Buttons
- Navigation sections
- Progress indicators

The purpose is to establish the website's structure and user flow before adding advanced visual effects or application logic.

---

## 📁 Project Structure

```text
StudentHub/
│
├── README.md
├── SITEMAP.md
├── LOW_FIDELITY_WIREFRAME.md
├── FRONTEND_FOLDER_STRUCTURE.txt
│
├── html/
│   ├── index.html
│   ├── login.html
│   ├── dashboard.html
│   ├── courses.html
│   ├── course_workspace.html
│   ├── assignments.html
│   ├── assignment-details.html
│   ├── grades.html
│   ├── discussions.html
│   ├── profile.html
│   │
│   ├── css/
│   │   ├── index.css
│   │   ├── login.css
│   │   ├── dashboard.css
│   │   ├── courses.css
│   │   ├── course_workspace.css
│   │   ├── assignments.css
│   │   ├── assignments-details.css
│   │   ├── grades.css
│   │   ├── discussions.css
│   │   └── profile.css
│   │
│   └── [static image/assets]
│
└── wireframe/
    └── [wireframe files]
```

---

## 🚀 How to Run the Project

### Option 1: Open Directly in a Browser

1. Extract the project ZIP file.
2. Open the project folder.
3. Navigate to the `html` directory.
4. Open `index.html`.
5. Use the navigation links to move between pages.

Because this is a static frontend project, a backend server is not required.

### Option 2: Use Visual Studio Code

1. Open the project folder in **Visual Studio Code**.
2. Open `html/index.html`.
3. Install the **Live Server** extension if required.
4. Right-click `index.html`.
5. Select **Open with Live Server**.
6. Test the website in the browser.

Using Live Server is recommended during development because it makes it easier to test page navigation and responsive layouts.

---

## 🧭 Navigation Flow

The primary StudentHub user flow is:

```text
                         ┌───────────────┐
                         │     HOME      │
                         │  index.html   │
                         └───────┬───────┘
                                 │
       ┌─────────┬───────────────┼──────────────┬────────────┐
       ▼         ▼               ▼              ▼            ▼
     Login    Dashboard        Courses      Discussions   Profile
       │         │               │
       │         │               ▼
       │         │        Course Workspace
       │         │               │
       │         │               ▼
       │         │          Assignments
       │         │               │
       │         │               ▼
       │         │        Assignment Details
       │         │
       │         ▼
       │       Grades
       │
       └────────────────► Student Portal
```

See **`SITEMAP.md`** for the complete sitemap and page hierarchy.

---

## ⚠️ Static Prototype Limitations

The current project is a **frontend prototype**, so the following are visual interfaces rather than fully functional systems:

- Login authentication
- Sign-up/account creation
- Assignment submission
- Grade storage
- Course enrollment
- Discussion posting
- Profile data updates
- Notification management

These features can be connected to a backend and database in a future version.

---

## 🔮 Future Scope

StudentHub can be extended into a complete student-management platform by adding:

### Frontend Enhancements

- JavaScript interactions
- Dynamic navigation
- Form validation
- Interactive dashboards
- Search and filtering
- Animations and transitions
- Advanced responsive behavior
- Accessibility improvements

### Backend Enhancements

- User authentication
- Student registration
- Database integration
- Course management
- Assignment management
- Grade management
- Discussion management
- Profile management
- Admin controls

### Advanced Features

- Student notifications
- Timetable management
- Calendar
- Attendance tracking
- Assignment deadlines
- Online submissions
- Faculty portal
- Admin dashboard
- Role-based access control

---

## 📚 Additional Documentation

| File | Purpose |
|---|---|
| `README.md` | Complete project documentation |
| `SITEMAP.md` | Website hierarchy and navigation flow |
| `LOW_FIDELITY_WIREFRAME.md` | Initial layout/wireframe planning |
| `FRONTEND_FOLDER_STRUCTURE.txt` | Frontend folder and file reference |

---

## 📊 Project Status

**Current Status:** Frontend Prototype / WDF Practical Project

The project demonstrates:

- Multi-page website development
- HTML5 structure
- CSS3 styling
- Responsive layout concepts
- Navigation between pages
- Academic portal UI design
- Forms
- Cards
- Tables
- Dashboard layouts

The project can be further enhanced with JavaScript, backend services, and database functionality.

---

## ✅ Conclusion

StudentHub provides a structured and user-friendly frontend for an academic student portal. The project demonstrates practical knowledge of **HTML5, CSS3, responsive layouts, navigation, forms, tables, cards, and UI organization**.

The current prototype establishes the major student-facing screens and provides a strong foundation for future development into a fully functional academic management system.

---

## 📝 Project Information

| Item | Details |
|---|---|
| **Project Name** | StudentHub Portal |
| **Project Type** | Web Design and Frontend Project |
| **Development Type** | Static Frontend Prototype |
| **Primary Technologies** | HTML5 and CSS3 |
| **Documentation** | README + Sitemap + Wireframe Documentation |

---

> **StudentHub Portal — Web Design and Frontend (WDF) Project**
