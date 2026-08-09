# StudentHub Low-Fidelity Wireframe

This document presents a structured low-fidelity wireframe for the StudentHub frontend prototype. It describes the expected layout and content for each page in a clean, readable format.

## Overview

The StudentHub interface is organized as a static multi-page web application with:
- a top browser-style navigation bar,
- a left sidebar for main sections,
- a central content area for page-specific information,
- consistent styling across all screens.

## 1. Landing Page

Path: / or html/index.html

```text
+----------------------------------------------------------------+
| StudentHub                       About | Features | Login      |
+----------------------------------------------------------------+
|                                                                |
| Welcome to your All-in-One                                    |
| Academic Portal                                               |
|                                                                |
|                     [ Get Started ]                           |
|                                                                |
+----------------------------------------------------------------+
| FEATURES OVERVIEW                                              |
|                                                                |
| +----------------+ +----------------+ +----------------+       |
| | Course Hub     | | Grade Tracker | | Discussions     |       |
| | Track syllabus | | Calculate GPA | | Engage with     |       |
| | and materials  | | and progress  | | peers and staff |       |
| +----------------+ +----------------+ +----------------+       |
+----------------------------------------------------------------+
```

## 2. Authentication / Login Page

Path: /login.html or html/login.html

```text
+--------------------------------------------------------------+
| StudentHub                                                   |
+--------------------------------------------------------------+
|                                                              |
|                +-------------------------+                  |
|                | Login | Sign Up         |                  |
|                +-------------------------+                  |
|                | Email                   |                  |
|                | [______________]        |                  |
|                | Password                |                  |
|                | [______________]        |                  |
|                | [ ] Remember Me         |                  |
|                | Forgot Password?        |                  |
|                | [ Sign In ]             |                  |
|                +-------------------------+                  |
|                                                              |
+--------------------------------------------------------------+
```

## 3. Student Dashboard

Path: /dashboard.html or html/dashboard.html

```text
+----------------------------------------------------------------+
| StudentHub                                      User Profile   |
+----------------+-------------------------------------------------+
| Dashboard      | Welcome, Alex!                                 |
| Courses        |                                                 |
| Assignments    | +----------------+ +--------------------------+   |
| Grades         | | Upcoming       | | Course Progress         |   |
| Forum          | | Deadlines      | | Overall: 72%           |   |
| Events         | +----------------+ +--------------------------+   |
| Settings       |                                                 |
|                | +----------------+ +--------------------------+   |
|                | | Course         | | Recent Announcements    |   |
|                | | Progress       | |                         |   |
|                | +----------------+ +--------------------------+   |
+----------------+-------------------------------------------------+
```

## 4. Course Catalog

Path: /courses.html or html/courses.html

```text
+----------------------------------------------------------------+
| Sidebar       | Course Catalog                 Dept | Term      |
+---------------+------------------------------------------------+
| Dashboard     | Enrolled Courses                                |
| Courses       | +-----------+ +-----------+ +-----------+        |
| Assignments   | | CS101     | | MATH201   | | ENG102    |        |
| Grades        | | Course    | | Course    | | Course    |        |
| Forum         | | [View]    | | [View]    | | [View]    |        |
| Events        | +-----------+ +-----------+ +-----------+        |
| Settings      |                                                |
|               | Available for Registration                     |
|               | +-----------+ +-----------+                     |
|               | | PHY151    | | HIST101   |                     |
|               | | [Enroll]  | | [Enroll]  |                     |
|               | +-----------+ +-----------+                     |
+---------------+------------------------------------------------+
```

## 5. Course Workspace

Path: /course_workspace.html or html/course_workspace.html

```text
+----------------------------------------------------------------+
| Sidebar       | Course Title: CS101                           |
+---------------+------------------------------------------------+
| Dashboard     | Instructor: Prof. Alan                         |
| Courses       | Syllabus | Material | Announcements | Portal    |
| Assignments   |                                                |
| Grades        | +--------------------------------------------+ |
| Forum         | | Module 1: Basics                          | |
| Events        | | [Lecture 1]                    [Watch]   | |
| Settings      | | [Reading]                      [View]    | |
|               | | [Quiz 1]                       [Start]   | |
|               | +--------------------------------------------+ |
+---------------+------------------------------------------------+
```

## 6. Assignments Hub

Path: /assignments.html or html/assignments.html

```text
+----------------------------------------------------------------+
| Sidebar       | Assignments                                    |
+---------------+------------------------------------------------+
| Dashboard     | [All] [Pending] [Submitted] [Graded]            |
| Courses       |                                                |
| Assignments   | +--------------------------------------------+ |
| Grades        | | Title | Course | Due Date | Status | Points | |
| Forum         | | Lab 2 | CS101  | Today    | Pending| -/100  | |
| Events        | | Lab 3 | CS101  | Today    | Pending| -/100  | |
| Settings      | | Lab 4 | CS201  | Tomorrow | Pending| -/100  | |
|               | +--------------------------------------------+ |
+---------------+------------------------------------------------+
```

## 7. Assignment Details

Path: /assignment-details.html or html/assignment-details.html

```text
+----------------------------------------------------------------+
| Sidebar       | < Back to Assignments                         |
+---------------+------------------------------------------------+
| Dashboard     | LAB501: Lab 3 (Bus, 8 Points)                 |
| Courses       |                                                |
| Assignments   | Instructions                                   |
| Grades        | Complete the assigned task and submit on time. |
| Forum         |                                                |
| Events        | +--------------------------------------------+ |
| Settings      | | Drag and drop files or Browse            | |
|               | +--------------------------------------------+ |
|               | File: [________________]                     |
|               | Comments: [____________] [Submit]          |
+---------------+------------------------------------------------+
```

## 8. Gradebook

Path: /grades.html or html/grades.html

```text
+----------------------------------------------------------------+
| Sidebar       | Gradebook                     GPA: 8.65 / 10    |
+---------------+------------------------------------------------+
| Dashboard     | Current / Cumulative GPA                        |
| Courses       |                                                |
| Assignments   | +--------------------------------------------+ |
| Grades        | | Course | Credits | Grade | Earned | Points | |
| Forum         | | CS101  | 3       | A     | 87%    | 261    | |
| Events        | | Math201| 3       | B+    | 78%    | 234    | |
| Settings      | | Lab 1  | 1       | A     | 95%    | 95     | |
|               | +--------------------------------------------+ |
+---------------+------------------------------------------------+
```

## 9. Discussion Forum

Path: /discussions.html or html/discussions.html

```text
+----------------------------------------------------------------+
| Sidebar       | Class Discussion Board                        |
+---------------+------------------------------------------------+
| Dashboard     | [All Threads] [New] [General] [Q&A] [Resources] |
| Courses       |                                                |
| Assignments   | +--------------------------------------------+ |
| Grades        | | Thread                  | Author | Replies | |
| Forum         | | Clarification on Lab 2 | Prof. A| 12      | |
| Events        | | Clarification on Lab 3 | Prof. K| 10      | |
| Settings      | | Doubt on Assignment    | Prof. A| 7       | |
|               | +--------------------------------------------+ |
+---------------+------------------------------------------------+
```

## 10. Profile and Settings

Path: /profile.html or html/profile.html

```text
+----------------------------------------------------------------+
| Sidebar       | User Profile & Account Settings               |
+---------------+------------------------------------------------+
| Dashboard     | +----------------------+ +------------------+     |
| Courses       | | Profile Information  | | Notifications    |     |
| Assignments   | | Full Name            | | [ ] Email        |     |
| Grades        | | [___________]        | | [ ] Reminders   |     |
| Forum         | | Roll No.             | | [ ] Announcements|    |
| Events        | | [___________]        | +------------------+     |
| Settings      | | Email                |                            |
|               | | [___________]        | +------------------+     |
|               | | [ Save Changes ]     | | Privacy/Security|     |
|               | +----------------------+ +------------------+     |
+---------------+------------------------------------------------+
```

## Notes

- The wireframes are intentionally low-fidelity and focus on layout structure rather than visual polish.
- The design is intended to be expanded later with richer styling, interactions, and responsive behavior.
- The overall layout remains consistent across all pages for easier navigation and future development.