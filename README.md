StudentHub Portal

Web Design and Frontend (WDF) Project

1. Project Overview

StudentHub is a frontend-only academic portal designed to provide students with a simple and organized interface for accessing academic and campus-related information from one place.

The project demonstrates the design and development of a multi-page student portal using HTML5 and CSS3. It focuses on page structure, navigation, cards, tables, forms, responsive layouts, and a consistent visual design across the application.

The current version is a static frontend prototype. It does not require a backend server, database, or JavaScript-based application logic.

2. Project Objectives

The main objectives of StudentHub are:

To design a user-friendly student portal.

To create a structured multi-page website using HTML5.

To use CSS3 for professional page styling and layout.

To implement responsive layouts suitable for different screen sizes.

To provide consistent navigation between related pages.

To demonstrate practical frontend web-development concepts.

To create a foundation that can later be connected to a backend and database.

3. Technology Stack

Technology

Purpose

HTML5

Page structure, semantic content, forms and navigation

CSS3

Styling, layouts, colors, spacing, cards, tables and forms

CSS Flexbox / Grid

Responsive component and page layouts

Static File Navigation

Linking pages using relative HTML paths

Image Assets

Visual content used by event and portal sections

Current implementation

Frontend: HTML5 + CSS3

JavaScript: Not required for the current prototype

Backend: Not implemented

Database: Not implemented

Authentication: UI prototype only

Data storage: Static content

4. Main Pages

The StudentHub project contains the following main frontend pages:

No.

Page

File

Purpose

1

Landing / Home

html/index.html

Introduction, hero section and main navigation

2

Login

html/login.html

Login and authentication interface

3

Dashboard

html/dashboard.html

Student overview and quick-access information

4

Courses

html/courses.html

Course listing and course information

5

Course Workspace

html/course_workspace.html

Course modules, syllabus and learning content

6

Assignments

html/assignments.html

Assignment list, status and tracking

7

Assignment Details

html/assignment-details.html

Assignment instructions and submission interface

8

Grades

html/grades.html

Gradebook, GPA and course-wise grades

9

Discussions

html/discussions.html

Discussion forum and thread interface

10

Profile

html/profile.html

User profile, preferences and account settings

Note: The page list above represents the intended StudentHub academic-portal structure. Always keep filenames synchronized with the actual files in the project folder.

5. Page Purpose

Home / Landing Page

File: html/index.html

The landing page introduces StudentHub and provides access to the main features of the portal. It contains the primary navigation and feature highlights.

Login

File: html/login.html

Provides the frontend layout for student authentication, including login and sign-up related interface elements.

Dashboard

File: html/dashboard.html

Provides an overview of student activities such as course progress, upcoming work, deadlines and announcements.

Courses

File: html/courses.html

Displays course information using a structured course catalog/card layout.

Course Workspace

File: html/course_workspace.html

Provides a detailed course area containing modules, syllabus information, materials and course-related sections.

Assignments

File: html/assignments.html

Displays assignments using structured layouts such as tables, filters, status indicators and assignment information.

Assignment Details

File: html/assignment-details.html

Provides detailed assignment information, instructions, upload/submission interface and related content.

Grades

File: html/grades.html

Displays student academic performance using GPA summaries, course-wise grades and gradebook information.

Discussions

File: html/discussions.html

Provides a discussion/forum interface containing discussion threads, categories and forum actions.

Profile

File: html/profile.html

Provides the user profile and account-settings interface, including personal information and preferences.

6. Stylesheets

The project uses separate CSS files to keep page structure and presentation organized.

Page

Stylesheet

Home

html/css/index.css

Login

html/css/login.css

Dashboard

html/css/dashboard.css

Courses

html/css/courses.css

Course Workspace

html/css/course_workspace.css

Assignments

html/css/assignments.css

Assignment Details

html/css/assignments-details.css

Grades

html/css/grades.css

Discussions

html/css/discussions.css

Profile

html/css/profile.css

CSS Responsibilities

Page layouts

Responsive design

Navigation

Cards and panels

Tables

Forms and input fields

Buttons

Progress indicators

Typography

Spacing and alignment

Mobile-friendly layouts

7. Key Features

Student Features

Landing page with project introduction

Student login interface

Student dashboard

Course catalog

Course workspace

Assignment tracking

Assignment details and submission UI

Gradebook and GPA display

Discussion forum

User profile and settings

UI / Design Features

Consistent page navigation

Card-based content sections

Structured tables

Forms and buttons

Progress indicators

Sidebar-style layouts where applicable

Responsive CSS layouts

Reusable visual patterns

Low-fidelity prototype approach

8. Responsive Design

The project is designed with responsive web-development principles so that layouts can adapt to different screen sizes.

The CSS can use modern layout techniques such as:

Flexbox

CSS Grid

Relative sizing

Flexible containers

Media queries

Mobile-friendly spacing and typography

The recommended design approach is mobile-first, followed by enhancements for tablet and desktop screens.

9. Design Approach

StudentHub follows a low-fidelity frontend prototype approach.

The interface uses simple and clear visual components such as:

Boxes

Borders

Cards

Headings

Tables

Forms

Buttons

Navigation sections

Progress indicators

The purpose is to establish the website's structure and user flow before adding advanced visual effects or application logic.

10. Project Structure

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

11. How to Run the Project

Option 1: Open Directly in a Browser

Extract the project ZIP file.

Open the project folder.

Navigate to the html directory.

Open index.html.

Use the navigation links to move between pages.

Because this is a static frontend project, a backend server is not required.

Option 2: Use Visual Studio Code

Open the project folder in Visual Studio Code.

Open html/index.html.

Install the Live Server extension if required.

Right-click index.html.

Select Open with Live Server.

Test the website in the browser.

Using Live Server is recommended during development because it makes it easier to test page navigation and responsive layouts.

12. Navigation Flow

The primary StudentHub user flow is:

                     ┌───────────────┐
                     │     HOME      │
                     │  index.html   │
                     └───────┬───────┘
                             │
       ┌─────────┬───────────┼───────────┬───────────┐
       ▼         ▼           ▼           ▼           ▼
     Login    Dashboard    Courses    Discussions  Profile
       │         │           │
       │         │           ▼
       │         │      Course Workspace
       │         │           │
       │         │           ▼
       │         │      Assignments
       │         │           │
       │         │           ▼
       │         │    Assignment Details
       │         │
       │         ▼
       │       Grades
       │
       └──────────────► Student Portal

See SITEMAP.md for the complete sitemap and page hierarchy.

13. Static Prototype Limitation

The current project is a frontend prototype, so the following are visual interfaces rather than fully functional systems:

Login authentication

Sign-up/account creation

Assignment submission

Grade storage

Course enrollment

Discussion posting

Profile data updates

Notification management

These features can be connected to a backend/database in a future version.

14. Future Scope

StudentHub can be extended into a complete student-management platform by adding:

Frontend Enhancements

JavaScript interactions

Dynamic navigation

Form validation

Interactive dashboards

Search and filtering

Animations and transitions

Advanced responsive behavior

Accessibility improvements

Backend Enhancements

User authentication

Student registration

Database integration

Course management

Assignment management

Grade management

Discussion management

Profile management

Admin controls

Advanced Features

Student notifications

Timetable management

Calendar

Attendance tracking

Assignment deadlines

Online submissions

Faculty portal

Admin dashboard

Role-based access control

15. Documentation Files

File

Purpose

README.md

Complete project documentation

SITEMAP.md

Website hierarchy and navigation flow

LOW_FIDELITY_WIREFRAME.md

Initial layout/wireframe planning

FRONTEND_FOLDER_STRUCTURE.txt

Frontend folder and file reference

16. Project Status

Current Status: Frontend prototype / WDF practical project

The project successfully demonstrates:

Multi-page website development

HTML5 structure

CSS3 styling

Responsive layout concepts

Navigation between pages

Academic portal UI design

Forms, cards, tables and dashboard layouts

The project is ready to be further enhanced with JavaScript, backend services and database functionality.

17. Conclusion

StudentHub provides a structured and user-friendly frontend for an academic student portal. The project demonstrates practical knowledge of HTML5, CSS3, responsive layouts, navigation, forms, tables, cards and UI organization.

The current prototype establishes the major student-facing screens and provides a strong foundation for future development into a fully functional academic management system.

18. Project Information

Project Name: StudentHub PortalProject Type: Web Design and Frontend ProjectDevelopment Type: Static Frontend PrototypePrimary Technologies: HTML5 and CSS3Documentation: README + Sitemap + Wireframe Documentation

