# StudentHub Sitemap

This sitemap reflects the pages and navigation links implemented in the current StudentHub frontend prototype.

## Page Structure

```text
StudentHub Portal
│
├── Landing Page (html/index.html)
│   └── Links to Login, Dashboard, Courses, Assignments, Grades, Discussions, and Profile
│
├── Authentication (html/login.html)
│   ├── Login Form
│   └── Sign Up Form
│
├── Dashboard (html/dashboard.html)
│   ├── Upcoming Deadlines
│   ├── Course Progress
│   ├── Recent Announcements
│   └── Quick Navigation to other sections
│
├── Courses (html/courses.html)
│   ├── Enrolled Courses
│   │   └── View Course
│   └── Available Courses
│       └── Enroll Now
│
├── Course Workspace (html/course_workspace.html)
│   ├── Syllabus
│   ├── Materials
│   ├── Announcements
│   └── Module Actions (Watch / View / Start)
│
├── Assignments (html/assignments.html)
│   ├── All
│   ├── Pending
│   ├── Submitted
│   └── Graded
│
├── Assignment Details (html/assignment-details.html)
│   ├── Instructions
│   ├── Upload Submission
│   ├── Comments
│   └── Submit Assignment
│
├── Grades (html/grades.html)
│   ├── GPA Summary
│   └── Course Grade Table
│
├── Discussions (html/discussions.html)
│   ├── Categories
│   ├── Threads
│   ├── Search Action
│   └── Replies / Discussion Entries
│
└── Profile & Settings (html/profile.html)
    ├── Profile Information
    ├── Notification Preferences
    └── Privacy & Security Preferences
```

## Main Navigation Flow

```text
Landing Page
    │
    ▼
Login / Sign Up
    │
    ▼
Dashboard
    │
    ├── Courses ─────────────► Course Catalog ─────► Course Workspace
    │
    ├── Assignments ─────────► Assignments Hub ────► Assignment Details
    │
    ├── Grades ─────────────► Gradebook
    │
    ├── Discussions ─────────► Discussion Forum
    │
    └── Profile ─────────────► Profile & Settings
```

## Navigation Notes

- The landing page links to the main functional sections.
- The sidebar on most pages provides direct access to Dashboard, Courses, Assignments, Grades, Discussions, and Profile.
- The browser-style top bar on each page links back to earlier sections and refreshes the current page.
- The current prototype is static and uses direct HTML links rather than dynamic routing.
