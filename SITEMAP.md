The current StudentHub project is a static frontend academic portal prototype developed using HTML5 and CSS3.

The current sitemap contains 10 implemented HTML pages, organized into logical sections.

StudentHub Portal
│
├── 🏠 Home / Landing
│   └── index.html
│
├── ℹ️ Information
│   └── About
│       └── about.html
│
├── 🔐 Authentication
│   ├── Login
│   │   └── login.html
│   │
│   └── Register
│       └── register.html
│
├── 👨‍🎓 Student Portal
│   ├── Dashboard
│   │   └── dashboard.html
│   │
│   ├── Events
│   │   └── events.html
│   │
│   └── Profile
│       └── profile.html
│
├── 🆘 Support
│   ├── Contact
│   │   └── contact.html
│   │
│   └── FAQ
│       └── faq.html
│
└── 🛠️ Administration
    └── Admin Panel
        └── admin.html
2. Visual Navigation Structure

The recommended logical sitemap is:

                         ┌─────────────────────┐
                         │   STUDENTHUB PORTAL │
                         └──────────┬──────────┘
                                    │
                                    ▼
                         ┌─────────────────────┐
                         │   HOME / LANDING    │
                         │     index.html      │
                         └──────────┬──────────┘
                                    │
        ┌──────────────┬────────────┼────────────┬──────────────┐
        │              │            │            │              │
        ▼              ▼            ▼            ▼              ▼
   INFORMATION   AUTHENTICATION  STUDENT      SUPPORT     ADMINISTRATION
        │              │         PORTAL           │              │
        ▼         ┌────┴────┐      │         ┌────┴────┐        ▼
      ABOUT       │         │      │         │         │       ADMIN
        │       LOGIN    REGISTER  │      CONTACT     FAQ
        │         │         │      │
        │         │         │   ┌──┼──────┐
        │         │         │   │  │      │
        │         │         │   ▼  ▼      ▼
        │         │         │ DASHBOARD EVENTS PROFILE
        │         │         │
        └─────────┴─────────┴───────────────┴───────────────

This structure is better than showing all pages as direct children of Home because it represents the logical grouping of the portal.

3. Current Implemented Pages
No.	Section	Page	File	Purpose
1	Main	Home / Landing	html/index.html	Main entry point
2	Information	About	html/about.html	Information about StudentHub
3	Authentication	Login	html/login.html	Login interface
4	Authentication	Register	html/register.html	Registration interface
5	Student Portal	Dashboard	html/dashboard.html	Student overview
6	Student Portal	Events	html/events.html	Events and campus activities
7	Student Portal	Profile	html/profile.html	Student profile interface
8	Support	Contact	html/contact.html	Contact interface
9	Support	FAQ	html/faq.html	Frequently asked questions
10	Administration	Admin	html/admin.html	Administration interface prototype

Total implemented HTML pages: 10

4. Detailed Page Information
🏠 1. Home / Landing Page

File:

html/index.html
Purpose

The Home page acts as the main entry point to the StudentHub portal.

Main responsibilities
Introduce the StudentHub portal
Provide primary navigation
Display portal-related information
Provide access to other major sections
Present the overall purpose of the project
Associated stylesheet
html/css/index.css
ℹ️ 2. About Page

File:

html/about.html
Purpose

The About page provides information about the StudentHub project and its purpose.

Main responsibilities
Explain StudentHub
Describe project objectives
Provide information about the portal
Explain the concept of the academic platform
Associated stylesheet
html/css/about.css
5. Authentication Section

The authentication section contains the interface pages for user access.

Authentication
│
├── Login
│   └── login.html
│
└── Register
    └── register.html
🔐 3. Login Page

File:

html/login.html
Purpose

Provides the user login interface.

Main responsibilities
Provide username/email input
Provide password input
Provide login button
Provide navigation toward registration
Demonstrate authentication UI
Important limitation

The current project does not implement real authentication.

Associated stylesheet
html/css/login.css
📝 4. Register Page

File:

html/register.html
Purpose

Provides the student registration interface.

Main responsibilities
Collect registration information
Provide form fields
Provide registration button
Provide navigation toward login
Important limitation

Registration data is not stored in a database in the current version.

Associated stylesheet
html/css/register.css
6. Student Portal Section
Student Portal
│
├── Dashboard
├── Events
└── Profile
📊 5. Dashboard

File:

html/dashboard.html
Purpose

The Dashboard acts as the main student portal interface after entering the student area.

Possible/current interface elements
Student overview
Quick-access sections
Portal information
Dashboard cards
Announcements/information
Navigation to other student sections
Important limitation

Dashboard information is currently static.

Associated stylesheet
html/css/dashboard.css
📅 6. Events

File:

html/events.html
Purpose

Displays student/campus event information.

Example event categories
Workshops
Hackathons
Sports
Campus activities
Static assets used by the project can support event presentation, including:
hackathon.jpg
sport.webp
workshop.jpg
Associated stylesheet
html/css/events.css
👤 7. Profile

File:

html/profile.html
Purpose

Provides a student profile interface.

Main responsibilities
Display student information
Provide profile-style UI
Demonstrate student account information
Important limitation

The current profile is a frontend UI prototype and does not provide persistent database-backed profile information.

Associated stylesheet
html/css/profile.css
7. Support Section
Support
│
├── Contact
└── FAQ
📞 8. Contact

File:

html/contact.html
Purpose

Provides a contact interface for users.

Main responsibilities
Contact information
Contact form/interface
User enquiry interface
Communication-related UI
Important limitation

Form submission processing is not connected to a backend.

Associated stylesheet
html/css/contact.css
❓ 9. FAQ

File:

html/faq.html
Purpose

Provides frequently asked questions and answers.

Main responsibilities
Answer common student questions
Provide help/information
Organize frequently requested information
Associated stylesheet
html/css/faq.css
8. Administration Section
Administration
│
└── Admin Panel
    └── admin.html
🛠️ 10. Admin Panel

File:

html/admin.html
Purpose

Provides an administrative interface prototype.

Possible administrative functions represented by the UI
Portal management
Content management
Event-related management
Administrative controls
Important limitation

The Admin page is currently a UI prototype only.

It does not implement:

Real admin authentication
Database operations
User management backend
Dynamic event management
Role-based access control
Associated stylesheet
html/css/admin.css
9. HTML → CSS Relationship

Each implemented HTML page has its own corresponding stylesheet.

html/
│
├── index.html       ─────► css/index.css
├── about.html       ─────► css/about.css
├── login.html       ─────► css/login.css
├── register.html    ─────► css/register.css
├── dashboard.html   ─────► css/dashboard.css
├── events.html      ─────► css/events.css
├── profile.html     ─────► css/profile.css
├── contact.html     ─────► css/contact.css
├── faq.html         ─────► css/faq.css
└── admin.html       ─────► css/admin.css
CSS responsibilities

The CSS files handle:

Layout
Navigation styling
Cards
Forms
Buttons
Typography
Colors
Spacing
Borders
Responsive layouts
Page-specific styling
10. Static Image Assets

The current project contains these image assets:

html/
│
├── hackathon.jpg
├── img.png
├── sport.webp
└── workshop.jpg
Purpose

These assets can be used for:

Event cards
Workshops
Sports activities
Hackathons
Portal banners
Other visual content
11. Current Project Structure

The corrected project structure should be represented as:

StudentHub/
│
├── README.md
├── FRONTEND_FOLDER_STRUCTURE.txt
├── SITEMAP.md
├── LOW_FIDELITY_WIREFRAME.md
│
├── html/
│   │
│   ├── css/
│   │   ├── about.css
│   │   ├── admin.css
│   │   ├── contact.css
│   │   ├── dashboard.css
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
│   ├── dashboard.html
│   ├── events.html
│   ├── faq.html
│   ├── index.html
│   ├── login.html
│   ├── profile.html
│   ├── register.html
│   │
│   ├── hackathon.jpg
│   ├── img.png
│   ├── sport.webp
│   └── workshop.jpg
│
└── wireframe/
12. Documentation Files

The project also contains supporting documentation.

File	Purpose
README.md	Complete project documentation
SITEMAP.md	Website hierarchy and navigation structure
LOW_FIDELITY_WIREFRAME.md	Initial wireframe/layout planning
FRONTEND_FOLDER_STRUCTURE.txt	Frontend file/folder reference
13. Current User Flow

Because this is currently a static frontend prototype, the safest representation is:

                         Visitor
                            │
                            ▼
                     Home / Landing
                            │
       ┌────────────┬───────┼────────┬────────────┐
       │            │       │        │            │
       ▼            ▼       ▼        ▼            ▼
     About        Login   Events   Contact       FAQ
                    │
                    ▼
                 Register
                    │
                    ▼
             Student Interface
              ┌─────┼─────┐
              ▼     ▼     ▼
         Dashboard Events Profile


                            │
                            ▼
                       Admin Panel
Important

This represents page navigation/UI structure, not a real authentication process.

14. Future / Proposed Sitemap

The following pages should NOT be shown as currently implemented. They belong under future development.

Future StudentHub
│
├── Academic
│
├── Resources
│   ├── Notes
│   ├── Papers
│   ├── Videos
│   └── E-books
│
├── Notifications
│
├── Timetable
│
├── Calendar
│
├── Feedback
│
├── Edit Profile
│
└── Legal
    ├── Privacy Policy
    └── Terms & Conditions

This distinction is important because these pages are part of the future scope, not the current 10-page implementation.

15. Future Student User Flow

After implementing JavaScript, backend, authentication and database functionality, the proposed user flow could become:

                    Student
                       │
                       ▼
                    Login
                       │
                Authentication
                       │
                       ▼
                  Dashboard
                       │
       ┌───────────────┼────────────────┐
       │               │                │
       ▼               ▼                ▼
   Academic        Resources          Events
       │               │
       │        ┌──────┼──────┐
       │        ▼      ▼      ▼
       │      Notes  Papers Videos
       │
       ├──────────► Notifications
       │
       ├──────────► Timetable
       │
       ├──────────► Calendar
       │
       └──────────► Profile
                       │
                       ▼
                  Edit Profile

This is a proposed future architecture, not the current implementation.

16. Future Administration Flow

A future backend-powered version could use:

                  Admin Login
                       │
                       ▼
                 Admin Dashboard
                       │
       ┌───────────────┼─────────────────┐
       │               │                 │
       ▼               ▼                 ▼
   Students          Events           Content
       │               │                 │
       ▼               ▼                 ▼
 User Management  Event Management  Resource Management

Again, this should be presented as future scope.

17. Sitemap Legend

For your final visual sitemap, use the following categories:

Category	Suggested Meaning
🔵 Main Page	Home / Landing
🟡 Authentication	Login / Register
🟢 Student Portal	Dashboard / Events / Profile
🟣 Information & Support	About / FAQ / Contact
🟠 Administration	Admin Panel
⚪ Supporting Files	CSS / Images / Documentation
18. Current vs Future Features
Feature	Current Status
Home	✅ Implemented
About	✅ Implemented
Login UI	✅ Implemented
Registration UI	✅ Implemented
Dashboard UI	✅ Implemented
Events	✅ Implemented
Profile UI	✅ Implemented
Contact UI	✅ Implemented
FAQ	✅ Implemented
Admin UI	✅ Implemented
JavaScript functionality	❌ Not implemented
Real authentication	❌ Not implemented
Database	❌ Not implemented
Backend	❌ Not implemented
Dynamic dashboard	❌ Not implemented
Persistent profile	❌ Not implemented
Real event management	❌ Not implemented
Notifications	🔮 Future
Timetable	🔮 Future
Calendar	🔮 Future
Academic management	🔮 Future
Resources	🔮 Future
Feedback	🔮 Future
Privacy Policy	🔮 Future
Terms & Conditions	🔮 Future
19. Final Sitemap for SITEMAP.md

For your actual project documentation, I recommend keeping the main SITEMAP.md focused on the 10 existing pages, like this:

StudentHub Portal
│
├── Home / Landing
│   └── index.html
│
├── Information
│   └── About
│       └── about.html
│
├── Authentication
│   ├── Login
│   │   └── login.html
│   └── Register
│       └── register.html
│
├── Student Portal
│   ├── Dashboard
│   │   └── dashboard.html
│   ├── Events
│   │   └── events.html
│   └── Profile
│       └── profile.html
│
├── Support
│   ├── Contact
│   │   └── contact.html
│   └── FAQ
│       └── faq.html
│
└── Administration
    └── Admin Panel
        └── admin.html

Then below it:

Future Scope
│
├── Academic
├── Resources
│   ├── Notes
│   ├── Papers
│   ├── Videos
│   └── E-books
├── Notifications
├── Timetable
├── Calendar
├── Feedback
├── Edit Profile
└── Legal
    ├── Privacy Policy
    └── Terms & Conditions