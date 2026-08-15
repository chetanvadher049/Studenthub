# StudentHub – Sitemap
<img width="1536" height="1024" alt="ChatGPT Image Aug 15, 2026, 10_38_05 AM" src="https://github.com/user-attachments/assets/03b1fe1a-221d-4a1f-a48d-ba85eea46ac9" />

This sitemap is based on the actual HTML pages and navigation structure present in the uploaded StudentHub project.

## 1. Main Website Hierarchy

```text
StudentHub
│
├── Home
│   └── html/index.html
│
├── About
│   └── html/about.html
│
├── Authentication
│   ├── Login
│   │   └── html/login.html
│   └── Register
│       └── html/register.html
│
├── Student Portal
│   ├── Dashboard
│   │   └── html/dashboard.html
│   ├── Events
│   │   └── html/events.html
│   └── Profile
│       └── html/profile.html
│
├── Support
│   ├── Contact
│   │   └── html/contact.html
│   └── FAQ
│       └── html/faq.html
│
└── Administration
    └── Admin Panel
        └── html/admin.html
```

## 2. Main Navigation Flow

```text
                         ┌──────────────┐
                         │     HOME     │
                         │ index.html   │
                         └──────┬───────┘
                                │
       ┌────────────┬───────────┼───────────┬────────────┐
       ▼            ▼           ▼           ▼            ▼
     About      Dashboard     Events      Profile      Contact
       │            │           │           │            │
       │            └───────────┴───────────┘            │
       │                                                 ▼
       │                                                FAQ
       │
       ├──────────────► Login ───────────► Register
       │
       └──────────────► Admin Panel
```

## 3. Page Descriptions

### Home
`html/index.html`

Main entry page containing the StudentHub introduction, navigation, feature/resource sections and links to other portal pages.

### About
`html/about.html`

Provides information about the StudentHub platform and its purpose.

### Dashboard
`html/dashboard.html`

Central student area containing dashboard-style information and quick access to student activities/events.

### Events
`html/events.html`

Displays campus activities and event information such as workshops, hackathons and sports-related events.

### Profile
`html/profile.html`

Displays student profile information and includes navigation toward profile editing.

### Contact
`html/contact.html`

Provides a contact/enquiry interface for users.

### FAQ
`html/faq.html`

Provides frequently asked questions and student help information.

### Login
`html/login.html`

Provides the user login interface and a link to registration.

### Register
`html/register.html`

Provides the user registration interface and a link back to login.

### Admin Panel
`html/admin.html`

Provides an administrative dashboard/interface for portal management.

## 4. Existing HTML Pages

| # | Page | File |
|---:|---|---|
| 1 | Home | `html/index.html` |
| 2 | About | `html/about.html` |
| 3 | Dashboard | `html/dashboard.html` |
| 4 | Events | `html/events.html` |
| 5 | Profile | `html/profile.html` |
| 6 | Contact | `html/contact.html` |
| 7 | FAQ | `html/faq.html` |
| 8 | Admin Panel | `html/admin.html` |
| 9 | Login | `html/login.html` |
| 10 | Register | `html/register.html` |

## 5. CSS Relationship

```text
HTML Page                 CSS File
────────────────────────────────────────────
index.html          ───►  css/index.css
about.html          ───►  css/about.css
dashboard.html      ───►  css/dashboard.css
events.html         ───►  css/events.css
profile.html        ───►  css/profile.css
contact.html        ───►  css/contact.css
faq.html            ───►  css/faq.css
admin.html          ───►  css/admin.css
login.html          ───►  css/login.css
register.html       ───►  css/register.css
```

## 6. Recommended Complete Sitemap

The following is the recommended final structure if all links already referenced by the project are implemented:

```text
StudentHub
│
├── Home
├── About
│
├── Authentication
│   ├── Login
│   └── Register
│
├── Student Portal
│   ├── Dashboard
│   ├── Academic
│   ├── Resources
│   │   ├── Notes
│   │   ├── Papers
│   │   ├── Videos
│   │   └── E-books
│   ├── Events
│   ├── Notifications
│   ├── Timetable
│   ├── Calendar
│   └── Profile
│       └── Edit Profile
│
├── Support
│   ├── Contact
│   ├── FAQ
│   └── Feedback
│
├── Administration
│   └── Admin Panel
│
└── Legal
    ├── Privacy Policy
    └── Terms & Conditions
```

## 7. Referenced but Currently Missing Pages

The existing navigation references these files, but they are not present in the uploaded project:

```text
feedback.html
resources.html
notes.html
papers.html
videos.html
ebooks.html
academic.html
notifications.html
timetable.html
calendar.html
privacy.html
terms.html
edit-profile.html
```

**Recommendation:** either create these pages for the complete StudentHub portal or remove their links from the current navigation.

## 8. User Flow

```text
Visitor
  │
  ▼
Home
  │
  ├──► About
  ├──► Events
  ├──► Contact
  ├──► FAQ
  ├──► Login ───► Register
  ├──► Dashboard
  ├──► Profile
  └──► Admin Panel

After future authentication:
  Login
    │
    ▼
  Dashboard
    ├──► Academic
    ├──► Resources
    ├──► Events
    ├──► Notifications
    ├──► Timetable
    ├──► Calendar
    └──► Profile
```
