# SRI SAI MISSION — COMPLETE MASTER PLAN
## Dynamic Website Conversion: Technical Blueprint

**Organization:** Sri Sai Mission Religious & Charitable Trust (Regd) 106/2014
**Document Version:** 1.1
**Date:** 2026-02-09
**Status:** Planning Complete — Ready to Build
**Local Dev Environment:** WampServer (Windows + Apache + MySQL 8 + PHP 8.2)
**Project Tracker:** See `PROJECT_TRACKER.md` for phase plan and task tracking

---

## TABLE OF CONTENTS

1. [Project Overview](#1-project-overview)
2. [System Architecture](#2-system-architecture)
3. [Database Design](#3-database-design)
4. [Admin Panel Features](#4-admin-panel-features)
5. [API Design](#5-api-design)
6. [Frontend Integration](#6-frontend-integration)
7. [Security & Performance](#7-security--performance)
8. [Deployment Plan](#8-deployment-plan)
9. [Project Tracker](#9-project-tracker)
10. [Development Roadmap](#10-development-roadmap)
11. [Assumptions & Best Practices](#11-assumptions--best-practices)

---

## 1. PROJECT OVERVIEW

### 1.1 What We Are Building

Converting a static WordPress "Gita" theme template into a fully dynamic, admin-controlled website for **Sri Sai Mission Religious & Charitable Trust**, a registered charitable trust (106/2014) based in Chennai, dedicated to the propagation of Sri Sai Baba's teachings and social welfare.

### 1.2 Source Template Analysis

**Template:** Gita — Spiritual Teachings & Yoga WordPress Theme (Elementor-based)
**Source Path:** `C:\Sai-site\saveweb2zip-com-gita-themerex-net\`

| Attribute | Detail |
|-----------|--------|
| Template Type | Single-page HTML (exported from WordPress/Elementor) |
| File Size | ~339 KB (index.html) |
| CSS Framework | Custom + Elementor + WooCommerce + ThemeREX Addons |
| JS Libraries | jQuery 3.x, Swiper, GSAP, Slider Revolution, Magnific Popup, Typed.js, Superfish |
| Fonts | Nunito, Kumbh Sans, Roboto, Roboto Slab + Fontello icons |
| Color Palette | Primary: #1D0427 (deep purple), Link: #5F2C70, Secondary: #724D67, Accent: #9FA73E, BG: #FFF8FD |
| Total CSS Files | 68 |
| Total JS Files | 88 |
| Total Images | 71 |
| Responsive | Yes (breakpoints at 1679px, 1439px, 1279px, 1023px, 767px) |

### 1.3 Template Section → Trust Content Mapping

| # | Template Section | Maps To (Sri Sai Mission) |
|---|-----------------|--------------------------|
| 1 | Header + Navigation | Header with Trust logo, dynamic menu |
| 2 | Hero Slider (Revolution Slider) | Home banner — Trust images, key messages |
| 3 | Services Icons (Festivals, Retreats, Meditation, Philosophy) | **Annadhanam, Cow Saala, Temple Pooja, Daily Programme** |
| 4 | About Us — "Divine bliss" | **About Sri Sai Mission** — vision, mission, founded 2014 |
| 5 | Stats Counter | Trust achievements (years active, lives touched, events held) |
| 6 | Donations Section | **Donations** — 80G tax exemption, online donation |
| 7 | Quote/CTA Banner | **Mission Statement** — Sri Sai Baba's message |
| 8 | Testimonials / Team | **Trustees** — Managing, Financial, Secretary, Co-opted |
| 9 | Prayer Times | **Temple Timings** — Morning 7am-12:30pm, Evening 4-7:30pm |
| 10 | Book a Tour | **Tourism** — Keerapakkam Temple, Perungalathur Centre |
| 11 | Upcoming Events | **Events** — Palki Procession, Sathya Narayana Pooja, etc. |
| 12 | Wisdom Blog (3-column grid) | **Sri Sai Dharisanam Magazine** + Blog articles |
| 13 | Contact Form | **Contact / Feedback** — 9841203311, srisaimission@gmail.com |
| 14 | Footer | Footer — Addresses, Important Links, Social media |
| 15 | Newsletter Subscribe | Newsletter subscription for devotees |
| 16 | Gallery (to be added) | **Gallery** — Event photos, temple photos, categories |

### 1.4 Content Modules Required

Based on the PDF content and template analysis:

| Module | Content Source | Admin-Managed |
|--------|--------------|---------------|
| Blog | New articles by trust | Yes — full CRUD |
| Magazine (Sri Sai Dharisanam) | Trust magazine articles | Yes — full CRUD |
| Gallery | Event/temple photos | Yes — albums + images |
| Trustees / Team | 14 trustees listed | Yes — full CRUD |
| Pages (About, Mission, Vision) | PDF content provided | No — static PHP files (views/pages/) |
| Events | Palki Procession, Poojas, special days | Yes — full CRUD |
| Temple Timings | Daily/weekly schedule | Yes — editable |
| Donations | Online donation tracking | Yes — forms + records |
| Tourism | Temple visit info | Yes — CMS page |
| Contact / Feedback | Form submissions | Yes — view + respond |

---

## 2. SYSTEM ARCHITECTURE

### 2.1 High-Level Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                        BROWSER (CLIENT)                          │
│                                                                  │
│  ┌─────────────────────┐     ┌─────────────────────────────┐    │
│  │   PUBLIC WEBSITE     │     │    ADMIN DASHBOARD (SPA)    │    │
│  │   (Server-rendered   │     │    React.js Application     │    │
│  │    PHP + Template    │     │    /admin/*                  │    │
│  │    CSS/JS assets)    │     │                             │    │
│  └─────────┬───────────┘     └──────────────┬──────────────┘    │
│            │                                │                    │
└────────────┼────────────────────────────────┼────────────────────┘
             │ HTTP (HTML)                    │ HTTP (JSON)
             │                                │
┌────────────┼────────────────────────────────┼────────────────────┐
│            │     APACHE (WampServer)         │                    │
│            │     (Dev: localhost/srisai)     │                    │
│  ┌─────────▼───────────┐     ┌──────────────▼──────────────┐    │
│  │   PHP BACKEND        │     │    REST API LAYER           │    │
│  │   (MVC Framework)    │     │    /api/v1/*                │    │
│  │                      │     │                             │    │
│  │   Controllers        │◄───►│    AuthController           │    │
│  │   Models             │     │    BlogController           │    │
│  │   Views (Blade-like) │     │    MagazineController       │    │
│  │   Services           │     │    GalleryController        │    │
│  │   Middleware          │     │    TrusteeController        │    │
│  │                      │     │    PageController           │    │
│  └─────────┬───────────┘     │    EventController          │    │
│            │                  │    DonationController       │    │
│            │                  │    ContactController        │    │
│            │                  │    MediaController          │    │
│            │                  │    SettingsController       │    │
│            │                  └──────────────┬──────────────┘    │
│            │                                 │                    │
│  ┌─────────▼─────────────────────────────────▼──────────────┐    │
│  │                    SERVICE LAYER                          │    │
│  │   QueryBuilder │ FileUpload │ Auth │ Cache │ SEO         │    │
│  └─────────────────────────┬────────────────────────────────┘    │
│                            │                                     │
│  ┌─────────────────────────▼────────────────────────────────┐    │
│  │                     MySQL 8.0                             │    │
│  │   (srisai_db)                                            │    │
│  └──────────────────────────────────────────────────────────┘    │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │   FILE STORAGE: /storage/uploads/                         │    │
│  │   ├── images/    (gallery, blog, magazine)                │    │
│  │   ├── media/     (general uploads)                        │    │
│  │   └── thumbnails/ (auto-generated)                        │    │
│  └──────────────────────────────────────────────────────────┘    │
│                                                                   │
│            DEV: WampServer (Windows) / PROD: Linux VPS            │
└──────────────────────────────────────────────────────────────────┘
```

### 2.2 Architecture Decisions

| Decision | Choice | Rationale |
|----------|--------|-----------|
| Backend Framework | Custom PHP MVC (Laravel-inspired) | Full control, no framework overhead, long-term maintainability |
| Frontend (Public) | Server-rendered PHP views | SEO-friendly, fast initial load, works without JS |
| Frontend (Admin) | React.js SPA | Rich interactive UI for CRUD operations |
| API | REST JSON | Standard, easy to consume from React and future mobile apps |
| Database | MySQL 8.0 | Mature, excellent for structured relational data |
| Auth | JWT (API) + Session (admin web) | Stateless API auth with secure session fallback |
| File Storage | Local disk with organized directories | Simple, reliable, no external dependency |
| Caching | File-based + MySQL query cache | Appropriate for expected traffic volume |

### 2.3 Frontend vs Backend Responsibility

```
BACKEND (PHP) is responsible for:
├── All business logic and data validation
├── Database queries and data persistence
├── Authentication and authorization
├── Server-side rendering of public pages (SEO)
├── REST API endpoints for admin panel
├── File upload processing and thumbnail generation
├── SEO metadata injection into HTML
├── Email notifications (contact form, donations)
├── URL routing (SEO-friendly slugs)
└── Security (CSRF, XSS prevention, input sanitization)

FRONTEND — PUBLIC WEBSITE (PHP Views + Template CSS/JS):
├── Server-rendered HTML pages using existing template design
├── Template CSS (68 files) loaded as-is
├── Template JS (jQuery, Swiper, GSAP, etc.) loaded as-is
├── Dynamic content injected via PHP into template HTML structure
├── Contact form submission via AJAX
├── Gallery lightbox (Magnific Popup — already in template)
└── Slider (Revolution Slider — already in template)

FRONTEND — ADMIN PANEL (React.js SPA):
├── Single-page application served at /admin
├── Communicates exclusively via REST API
├── State management (React Context + useReducer)
├── Rich text editor for blog/magazine content
├── Image upload with drag-and-drop
├── Data tables with pagination, search, filters
├── Role-based UI (show/hide based on permissions)
└── Dashboard with analytics widgets
```

### 2.4 API Flow

```
REACT ADMIN                    PHP API                         MySQL
    │                             │                               │
    │  POST /api/v1/auth/login    │                               │
    │ ──────────────────────────► │                               │
    │                             │  SELECT * FROM admin_users    │
    │                             │ ─────────────────────────────►│
    │                             │◄───────────────────────────── │
    │                             │  Verify password_hash         │
    │◄──────────────────────────  │  Return JWT token             │
    │  { token: "eyJ..." }        │                               │
    │                             │                               │
    │  GET /api/v1/blogs          │                               │
    │  Authorization: Bearer JWT  │                               │
    │ ──────────────────────────► │                               │
    │                             │  Validate JWT                 │
    │                             │  Check permissions            │
    │                             │  SELECT * FROM blogs ...      │
    │                             │ ─────────────────────────────►│
    │                             │◄───────────────────────────── │
    │◄──────────────────────────  │                               │
    │  { data: [...], meta: {} }  │                               │
    │                             │                               │
    │  POST /api/v1/blogs         │                               │
    │  { title, content, ... }    │                               │
    │ ──────────────────────────► │                               │
    │                             │  Validate input               │
    │                             │  Sanitize HTML                │
    │                             │  Generate slug                │
    │                             │  INSERT INTO blogs ...        │
    │                             │ ─────────────────────────────►│
    │                             │◄───────────────────────────── │
    │◄──────────────────────────  │                               │
    │  { data: { id: 1, ... } }   │                               │
```

### 2.5 Folder Structure

#### Backend (PHP)

```
backend/
├── public/                          # Web root (point Apache/Nginx here)
│   ├── index.php                    # Front controller
│   ├── .htaccess                    # URL rewriting rules
│   ├── assets/                      # Compiled/static assets
│   │   ├── css/                     # Template CSS files (from Gita theme)
│   │   ├── js/                      # Template JS files (from Gita theme)
│   │   ├── fonts/                   # Template fonts
│   │   └── images/                  # Template static images
│   └── admin/                       # React admin build output
│       ├── index.html
│       └── static/
│           ├── css/
│           └── js/
│
├── app/
│   ├── Controllers/
│   │   ├── Web/                     # Public-facing controllers
│   │   │   ├── HomeController.php
│   │   │   ├── BlogController.php
│   │   │   ├── MagazineController.php
│   │   │   ├── GalleryController.php
│   │   │   ├── EventController.php
│   │   │   ├── PageController.php
│   │   │   ├── TrusteeController.php
│   │   │   ├── DonationController.php
│   │   │   └── ContactController.php
│   │   │
│   │   └── Api/                     # REST API controllers (admin)
│   │       ├── AuthController.php
│   │       ├── BlogApiController.php
│   │       ├── MagazineApiController.php
│   │       ├── GalleryApiController.php
│   │       ├── EventApiController.php
│   │       ├── PageApiController.php
│   │       ├── TrusteeApiController.php
│   │       ├── DonationApiController.php
│   │       ├── ContactApiController.php
│   │       ├── MediaApiController.php
│   │       ├── SettingsApiController.php
│   │       └── DashboardApiController.php
│   │
│   ├── Models/
│   │   ├── Model.php                # Base model (query builder)
│   │   ├── AdminUser.php
│   │   ├── Blog.php
│   │   ├── Magazine.php
│   │   ├── Gallery.php
│   │   ├── GalleryImage.php
│   │   ├── Event.php
│   │   ├── Page.php
│   │   ├── Trustee.php
│   │   ├── Donation.php
│   │   ├── ContactMessage.php
│   │   ├── Media.php
│   │   ├── Setting.php
│   │   └── SeoMeta.php
│   │
│   ├── Middleware/
│   │   ├── AuthMiddleware.php       # JWT validation
│   │   ├── RoleMiddleware.php       # Permission check
│   │   ├── CorsMiddleware.php       # CORS headers for API
│   │   ├── CsrfMiddleware.php       # CSRF protection (web forms)
│   │   ├── RateLimitMiddleware.php
│   │   └── SanitizeMiddleware.php
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── ImageService.php         # Resize, thumbnail, optimize
│   │   ├── SlugService.php          # Generate URL slugs
│   │   ├── SeoService.php           # Meta tag generation
│   │   ├── MailService.php          # Email sending
│   │   ├── CacheService.php
│   │   └── PaginationService.php
│   │
│   └── Helpers/
│       ├── Response.php             # JSON/HTML response helpers
│       ├── Validator.php            # Input validation
│       └── FileHelper.php
│
├── config/
│   ├── app.php                      # App name, URL, environment
│   ├── database.php                 # MySQL credentials
│   ├── auth.php                     # JWT secret, token expiry
│   ├── mail.php                     # SMTP config
│   ├── upload.php                   # Max file size, allowed types
│   └── cache.php
│
├── database/
│   ├── migrations/                  # SQL migration files (versioned)
│   │   ├── 001_create_admin_users.sql
│   │   ├── 002_create_blogs.sql
│   │   ├── 003_create_magazines.sql
│   │   ├── 004_create_galleries.sql
│   │   ├── 005_create_events.sql
│   │   ├── 006_create_pages.sql
│   │   ├── 007_create_trustees.sql
│   │   ├── 008_create_donations.sql
│   │   ├── 009_create_contacts.sql
│   │   ├── 010_create_media.sql
│   │   ├── 011_create_settings.sql
│   │   └── 012_create_seo_meta.sql
│   │
│   └── seeders/
│       ├── AdminUserSeeder.php      # Default admin account
│       ├── PageSeeder.php           # About, Mission, Vision pages
│       ├── TrusteeSeeder.php        # 14 trustees from PDF
│       └── SettingSeeder.php        # Default site settings
│
├── routes/
│   ├── web.php                      # Public website routes
│   └── api.php                      # REST API routes
│
├── views/                           # PHP template views (Blade-like)
│   ├── layouts/
│   │   ├── master.php               # Base layout (header + footer)
│   │   └── admin-shell.php          # Admin SPA shell
│   ├── partials/
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── navigation.php
│   │   ├── hero-slider.php
│   │   ├── seo-meta.php
│   │   └── pagination.php
│   ├── home/
│   │   └── index.php
│   ├── blog/
│   │   ├── index.php
│   │   └── show.php
│   ├── magazine/
│   │   ├── index.php
│   │   └── show.php
│   ├── gallery/
│   │   ├── index.php
│   │   └── album.php
│   ├── events/
│   │   ├── index.php
│   │   └── show.php
│   ├── trustees/
│   │   └── index.php
│   ├── pages/
│   │   └── show.php                 # Dynamic: about, mission, vision, etc.
│   ├── donations/
│   │   └── index.php
│   ├── contact/
│   │   └── index.php
│   └── errors/
│       ├── 404.php
│       └── 500.php
│
├── storage/
│   ├── uploads/
│   │   ├── blogs/
│   │   ├── magazines/
│   │   ├── gallery/
│   │   ├── trustees/
│   │   ├── events/
│   │   ├── pages/
│   │   └── general/
│   ├── cache/
│   └── logs/
│       └── app.log
│
├── .env                             # Environment variables (NOT committed)
├── .env.example                     # Template env file
├── composer.json                    # PHP dependencies
└── migrate.php                      # CLI migration runner
```

#### Frontend (React Admin Panel)

```
frontend-admin/
├── public/
│   └── index.html
├── src/
│   ├── index.js
│   ├── App.js
│   ├── api/
│   │   ├── client.js                # Axios instance with JWT interceptor
│   │   ├── authApi.js
│   │   ├── blogApi.js
│   │   ├── magazineApi.js
│   │   ├── galleryApi.js
│   │   ├── eventApi.js
│   │   ├── pageApi.js
│   │   ├── trusteeApi.js
│   │   ├── donationApi.js
│   │   ├── contactApi.js
│   │   ├── mediaApi.js
│   │   └── settingsApi.js
│   │
│   ├── context/
│   │   ├── AuthContext.js
│   │   └── NotificationContext.js
│   │
│   ├── hooks/
│   │   ├── useAuth.js
│   │   ├── useFetch.js
│   │   └── usePagination.js
│   │
│   ├── components/
│   │   ├── common/
│   │   │   ├── Sidebar.jsx
│   │   │   ├── TopBar.jsx
│   │   │   ├── DataTable.jsx
│   │   │   ├── Pagination.jsx
│   │   │   ├── Modal.jsx
│   │   │   ├── ImageUploader.jsx
│   │   │   ├── RichTextEditor.jsx
│   │   │   ├── ConfirmDialog.jsx
│   │   │   ├── StatusBadge.jsx
│   │   │   ├── SearchBar.jsx
│   │   │   └── LoadingSpinner.jsx
│   │   │
│   │   ├── dashboard/
│   │   │   └── DashboardWidgets.jsx
│   │   ├── blogs/
│   │   │   ├── BlogList.jsx
│   │   │   └── BlogForm.jsx
│   │   ├── magazines/
│   │   │   ├── MagazineList.jsx
│   │   │   └── MagazineForm.jsx
│   │   ├── gallery/
│   │   │   ├── AlbumList.jsx
│   │   │   ├── AlbumForm.jsx
│   │   │   └── ImageManager.jsx
│   │   ├── events/
│   │   │   ├── EventList.jsx
│   │   │   └── EventForm.jsx
│   │   ├── trustees/
│   │   │   ├── TrusteeList.jsx
│   │   │   └── TrusteeForm.jsx
│   │   ├── pages/
│   │   │   ├── PageList.jsx
│   │   │   └── PageForm.jsx
│   │   ├── donations/
│   │   │   └── DonationList.jsx
│   │   ├── contacts/
│   │   │   └── ContactList.jsx
│   │   ├── media/
│   │   │   └── MediaLibrary.jsx
│   │   └── settings/
│   │       ├── GeneralSettings.jsx
│   │       ├── TempleTimings.jsx
│   │       └── UserManagement.jsx
│   │
│   ├── pages/
│   │   ├── LoginPage.jsx
│   │   ├── DashboardPage.jsx
│   │   ├── BlogsPage.jsx
│   │   ├── MagazinesPage.jsx
│   │   ├── GalleryPage.jsx
│   │   ├── EventsPage.jsx
│   │   ├── TrusteesPage.jsx
│   │   ├── PagesPage.jsx
│   │   ├── DonationsPage.jsx
│   │   ├── ContactsPage.jsx
│   │   ├── MediaPage.jsx
│   │   └── SettingsPage.jsx
│   │
│   ├── routes/
│   │   ├── AppRoutes.jsx
│   │   └── ProtectedRoute.jsx
│   │
│   └── utils/
│       ├── constants.js
│       ├── formatters.js
│       └── validators.js
│
├── package.json
├── .env
└── vite.config.js                   # Vite for fast builds
```

### 2.6 Environment Separation

| Environment | Purpose | Server | Database | URL | Debug |
|------------|---------|--------|----------|-----|-------|
| **Local (Dev)** | Development | WampServer (Apache + PHP 8.2) | srisai_db (root/empty) | http://localhost/srisai/ | ON |
| **Staging** | Testing/QA | Linux VPS | srisai_staging | https://staging.srisaimission.org | ON |
| **Production** | Live site | Linux VPS (Nginx + PHP-FPM) | srisai_prod | https://www.srisaimission.org | OFF |

**WampServer Local Paths:**
```
WampServer Root:       C:\wamp64\
Apache Web Root:       C:\wamp64\www\
Project Backend:       C:\wamp64\www\srisai\          (PHP MVC project)
React Admin Source:    C:\SriSai\frontend-admin\       (development)
React Admin Build:     C:\wamp64\www\srisai\public\admin\  (production build)
phpMyAdmin:            http://localhost/phpmyadmin
Database Admin:        Via phpMyAdmin (bundled with WampServer)
```

`.env` file structure (LOCAL):
```
APP_NAME="Sri Sai Mission"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/srisai

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=srisai_db
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=local_dev_secret_change_in_production_64chars_minimum_abcdef1234567890
JWT_EXPIRY=3600

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=srisaimission@gmail.com
MAIL_PASSWORD=<app_password>
MAIL_FROM_NAME="Sri Sai Mission"

UPLOAD_MAX_SIZE=5242880
UPLOAD_ALLOWED_TYPES=jpg,jpeg,png,gif,webp,pdf
```

`.env` file structure (PRODUCTION):
```
APP_NAME="Sri Sai Mission"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.srisaimission.org

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=srisai_prod
DB_USERNAME=srisai_user
DB_PASSWORD=<secure_32_char_password>

JWT_SECRET=<64_char_random_string_generated_via_openssl>
JWT_EXPIRY=3600

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=srisaimission@gmail.com
MAIL_PASSWORD=<app_password>
MAIL_FROM_NAME="Sri Sai Mission"

UPLOAD_MAX_SIZE=5242880
UPLOAD_ALLOWED_TYPES=jpg,jpeg,png,gif,webp,pdf
```

---

## 3. DATABASE DESIGN

### 3.1 Entity Relationship Summary

```
admin_users ──┬── blogs (created_by)
              ├── magazines (created_by)
              ├── gallery_albums (created_by)
              ├── events (created_by)
              ├── pages (created_by)
              └── media (uploaded_by)

roles ────────── admin_users (role_id)

blogs ─────────── seo_meta (entity_type='blog', entity_id)
magazines ─────── seo_meta (entity_type='magazine', entity_id)
events ────────── seo_meta (entity_type='event', entity_id)
pages ─────────── seo_meta (entity_type='page', entity_id)

gallery_albums ── gallery_images (album_id)

categories ────── blogs (category_id)
                  magazines (category_id)

contact_messages   (standalone — form submissions)
donations          (standalone — donation records)
trustees           (standalone — trust team members)
settings           (standalone — key-value site settings)
temple_timings     (standalone — daily/weekly schedule)
```

### 3.2 Complete MySQL Schema

#### Table: `roles`
```sql
CREATE TABLE roles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL UNIQUE,           -- 'super_admin', 'admin', 'editor', 'viewer'
    slug        VARCHAR(50) NOT NULL UNIQUE,
    permissions JSON NOT NULL,                          -- {"blogs":["create","read","update","delete"], ...}
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `admin_users`
```sql
CREATE TABLE admin_users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id         INT UNSIGNED NOT NULL,
    name            VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,             -- bcrypt hashed
    phone           VARCHAR(20) DEFAULT NULL,
    avatar          VARCHAR(255) DEFAULT NULL,         -- path to profile image
    is_active       TINYINT(1) DEFAULT 1,
    last_login_at   TIMESTAMP NULL DEFAULT NULL,
    last_login_ip   VARCHAR(45) DEFAULT NULL,          -- IPv4/IPv6
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_role (role_id),
    CONSTRAINT fk_admin_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `categories`
```sql
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    type        ENUM('blog', 'magazine', 'gallery', 'event') NOT NULL,
    description TEXT DEFAULT NULL,
    parent_id   INT UNSIGNED DEFAULT NULL,
    sort_order  INT DEFAULT 0,
    is_active   TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_type (type),
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id),
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `blogs`
```sql
CREATE TABLE blogs (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED DEFAULT NULL,
    created_by      INT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(280) NOT NULL UNIQUE,
    excerpt         TEXT DEFAULT NULL,                  -- Short summary (150-300 chars)
    content         LONGTEXT NOT NULL,                  -- Rich HTML content
    featured_image  VARCHAR(255) DEFAULT NULL,          -- Path to main image
    status          ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured     TINYINT(1) DEFAULT 0,              -- Pin to homepage
    view_count      INT UNSIGNED DEFAULT 0,
    published_at    TIMESTAMP NULL DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_published (published_at),
    INDEX idx_featured (is_featured),
    INDEX idx_category (category_id),
    FULLTEXT idx_search (title, excerpt, content),
    CONSTRAINT fk_blogs_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_blogs_author FOREIGN KEY (created_by) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `magazines`
```sql
CREATE TABLE magazines (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED DEFAULT NULL,
    created_by      INT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(280) NOT NULL UNIQUE,
    excerpt         TEXT DEFAULT NULL,
    content         LONGTEXT NOT NULL,
    featured_image  VARCHAR(255) DEFAULT NULL,
    issue_number    VARCHAR(50) DEFAULT NULL,           -- "Vol 5, Issue 3"
    issue_date      DATE DEFAULT NULL,                  -- Month/Year of issue
    pdf_file        VARCHAR(255) DEFAULT NULL,          -- Downloadable PDF link
    status          ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured     TINYINT(1) DEFAULT 0,
    view_count      INT UNSIGNED DEFAULT 0,
    published_at    TIMESTAMP NULL DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_issue_date (issue_date),
    INDEX idx_category (category_id),
    FULLTEXT idx_search (title, excerpt, content),
    CONSTRAINT fk_magazines_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_magazines_author FOREIGN KEY (created_by) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `gallery_albums`
```sql
CREATE TABLE gallery_albums (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED DEFAULT NULL,
    created_by      INT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(280) NOT NULL UNIQUE,
    description     TEXT DEFAULT NULL,
    cover_image     VARCHAR(255) DEFAULT NULL,          -- Album cover thumbnail
    status          ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    sort_order      INT DEFAULT 0,
    image_count     INT UNSIGNED DEFAULT 0,             -- Denormalized for performance
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_sort (sort_order),
    CONSTRAINT fk_albums_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_albums_author FOREIGN KEY (created_by) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `gallery_images`
```sql
CREATE TABLE gallery_images (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    album_id    INT UNSIGNED NOT NULL,
    file_path   VARCHAR(255) NOT NULL,                  -- Relative path from storage
    thumbnail   VARCHAR(255) DEFAULT NULL,              -- Auto-generated thumb
    caption     VARCHAR(500) DEFAULT NULL,
    alt_text    VARCHAR(255) DEFAULT NULL,              -- Accessibility
    sort_order  INT DEFAULT 0,
    file_size   INT UNSIGNED DEFAULT 0,                 -- Bytes
    width       INT UNSIGNED DEFAULT NULL,
    height      INT UNSIGNED DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_album (album_id),
    INDEX idx_sort (sort_order),
    CONSTRAINT fk_images_album FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `events`
```sql
CREATE TABLE events (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_by      INT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(280) NOT NULL UNIQUE,
    description     LONGTEXT DEFAULT NULL,
    featured_image  VARCHAR(255) DEFAULT NULL,
    event_date      DATE NOT NULL,
    event_time      TIME DEFAULT NULL,
    end_date        DATE DEFAULT NULL,                  -- Multi-day events
    end_time        TIME DEFAULT NULL,
    location        VARCHAR(255) DEFAULT NULL,          -- "Keerapakkam Baba Temple"
    is_recurring    TINYINT(1) DEFAULT 0,
    recurrence_rule VARCHAR(100) DEFAULT NULL,          -- "every_sunday", "every_full_moon", etc.
    status          ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    is_featured     TINYINT(1) DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_event_date (event_date),
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_featured (is_featured),
    CONSTRAINT fk_events_author FOREIGN KEY (created_by) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `pages`
```sql
CREATE TABLE pages (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_by      INT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(280) NOT NULL UNIQUE,       -- "about-us", "mission", "vision", "tourism"
    content         LONGTEXT NOT NULL,
    featured_image  VARCHAR(255) DEFAULT NULL,
    template        VARCHAR(50) DEFAULT 'default',      -- "default", "full-width", "sidebar"
    status          ENUM('draft', 'published') DEFAULT 'draft',
    sort_order      INT DEFAULT 0,
    show_in_menu    TINYINT(1) DEFAULT 0,
    menu_position   INT DEFAULT 0,
    parent_id       INT UNSIGNED DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_menu (show_in_menu, menu_position),
    CONSTRAINT fk_pages_author FOREIGN KEY (created_by) REFERENCES admin_users(id),
    CONSTRAINT fk_pages_parent FOREIGN KEY (parent_id) REFERENCES pages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `trustees`
```sql
CREATE TABLE trustees (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    designation     VARCHAR(100) NOT NULL,              -- "Managing Trustee", "Financial Trustee", etc.
    trustee_type    ENUM('main', 'co-opted') NOT NULL DEFAULT 'co-opted',
    bio             TEXT DEFAULT NULL,
    photo           VARCHAR(255) DEFAULT NULL,
    phone           VARCHAR(20) DEFAULT NULL,
    email           VARCHAR(150) DEFAULT NULL,
    qualification   VARCHAR(255) DEFAULT NULL,          -- "Dr.", "Mr.", "Mrs.", "Ms."
    sort_order      INT DEFAULT 0,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_type (trustee_type),
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `donations`
```sql
CREATE TABLE donations (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_name      VARCHAR(150) NOT NULL,
    donor_email     VARCHAR(150) DEFAULT NULL,
    donor_phone     VARCHAR(20) DEFAULT NULL,
    donor_address   TEXT DEFAULT NULL,
    donor_pan       VARCHAR(10) DEFAULT NULL,           -- For 80G receipt
    amount          DECIMAL(10,2) NOT NULL,
    currency        VARCHAR(3) DEFAULT 'INR',
    purpose         VARCHAR(255) DEFAULT NULL,          -- "Annadhanam", "Cow Saala", "General"
    payment_method  ENUM('online', 'cash', 'cheque', 'bank_transfer') DEFAULT 'online',
    transaction_id  VARCHAR(100) DEFAULT NULL,          -- Payment gateway ref
    receipt_number  VARCHAR(50) DEFAULT NULL,           -- 80G receipt number
    status          ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    notes           TEXT DEFAULT NULL,
    donated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_donor_email (donor_email),
    INDEX idx_donated_at (donated_at),
    INDEX idx_receipt (receipt_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `contact_messages`
```sql
CREATE TABLE contact_messages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    last_name   VARCHAR(100) DEFAULT NULL,
    email       VARCHAR(150) NOT NULL,
    phone       VARCHAR(20) DEFAULT NULL,
    subject     VARCHAR(255) DEFAULT NULL,
    message     TEXT NOT NULL,
    source_page VARCHAR(100) DEFAULT 'contact',        -- Which form/page submitted from
    ip_address  VARCHAR(45) DEFAULT NULL,
    status      ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
    admin_notes TEXT DEFAULT NULL,                      -- Internal notes
    replied_at  TIMESTAMP NULL DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `media`
```sql
CREATE TABLE media (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uploaded_by     INT UNSIGNED NOT NULL,
    file_name       VARCHAR(255) NOT NULL,             -- Original filename
    file_path       VARCHAR(255) NOT NULL,             -- Storage path
    thumbnail_path  VARCHAR(255) DEFAULT NULL,
    file_type       VARCHAR(50) NOT NULL,              -- "image/jpeg", "application/pdf"
    file_size       INT UNSIGNED NOT NULL,             -- Bytes
    width           INT UNSIGNED DEFAULT NULL,
    height          INT UNSIGNED DEFAULT NULL,
    alt_text        VARCHAR(255) DEFAULT NULL,
    used_in         VARCHAR(50) DEFAULT NULL,          -- "blog", "magazine", "gallery", etc.
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_type (file_type),
    INDEX idx_uploaded_by (uploaded_by),
    INDEX idx_used_in (used_in),
    CONSTRAINT fk_media_uploader FOREIGN KEY (uploaded_by) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `seo_meta`
```sql
CREATE TABLE seo_meta (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    entity_type     VARCHAR(50) NOT NULL,              -- "blog", "magazine", "page", "event"
    entity_id       INT UNSIGNED NOT NULL,
    meta_title      VARCHAR(70) DEFAULT NULL,          -- Max 60-70 chars for Google
    meta_description VARCHAR(160) DEFAULT NULL,        -- Max 150-160 chars
    meta_keywords   VARCHAR(255) DEFAULT NULL,
    og_title        VARCHAR(100) DEFAULT NULL,
    og_description  VARCHAR(200) DEFAULT NULL,
    og_image        VARCHAR(255) DEFAULT NULL,
    canonical_url   VARCHAR(255) DEFAULT NULL,
    no_index        TINYINT(1) DEFAULT 0,
    no_follow       TINYINT(1) DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX idx_entity (entity_type, entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `settings`
```sql
CREATE TABLE settings (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_name  VARCHAR(50) NOT NULL DEFAULT 'general', -- "general", "temple", "social", "contact"
    key_name    VARCHAR(100) NOT NULL UNIQUE,
    value       TEXT DEFAULT NULL,
    type        ENUM('text', 'textarea', 'number', 'boolean', 'json', 'image') DEFAULT 'text',
    label       VARCHAR(150) DEFAULT NULL,              -- Human-readable name
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_group (group_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `temple_timings`
```sql
CREATE TABLE temple_timings (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(100) NOT NULL,                 -- "Morning Session", "Evening Session"
    day_type    ENUM('daily', 'monday', 'tuesday', 'wednesday', 'thursday',
                     'friday', 'saturday', 'sunday', 'special') DEFAULT 'daily',
    start_time  TIME NOT NULL,
    end_time    TIME NOT NULL,
    description VARCHAR(255) DEFAULT NULL,             -- "Main pooja at 8am, 12pm, 6pm"
    location    VARCHAR(150) DEFAULT NULL,             -- "Perungalathur Athma Sai Temple"
    is_active   TINYINT(1) DEFAULT 1,
    sort_order  INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table: `activity_log`
```sql
CREATE TABLE activity_log (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED DEFAULT NULL,
    action      VARCHAR(50) NOT NULL,                  -- "created", "updated", "deleted", "login"
    entity_type VARCHAR(50) DEFAULT NULL,              -- "blog", "page", "trustee", etc.
    entity_id   INT UNSIGNED DEFAULT NULL,
    description VARCHAR(255) DEFAULT NULL,
    ip_address  VARCHAR(45) DEFAULT NULL,
    user_agent  VARCHAR(255) DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_user (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created (created_at),
    CONSTRAINT fk_log_user FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.3 Default Seeder Data

**Roles:**
| ID | Name | Slug | Key Permissions |
|----|------|------|----------------|
| 1 | Super Admin | super_admin | All modules — full CRUD + settings + user management |
| 2 | Admin | admin | All modules — full CRUD (no user management) |
| 3 | Editor | editor | Blogs, Magazines, Gallery, Events — create/read/update only |
| 4 | Viewer | viewer | All modules — read only |

**Default Admin User:**
| Field | Value |
|-------|-------|
| Name | Sri Sai Varadharajan |
| Email | admin@srisaimission.org |
| Role | Super Admin |
| Password | (set during first deployment) |

**Trustees Seed Data (14 records from PDF):**
| Name | Designation | Type |
|------|-------------|------|
| G. Varadharajan | Managing Trustee | main |
| V. Jayanthi | Financial Trustee | main |
| B. Veeramani | Secretary | main |
| Dr. G.V. Loga Adhithya | Co-opted Trustee | co-opted |
| Dr. P. Nandhivarman | Co-opted Trustee | co-opted |
| Mr. B. Muruganandham | Co-opted Trustee | co-opted |
| Mr. N. Balu | Co-opted Trustee | co-opted |
| Mr. Saravanakumar | Co-opted Trustee | co-opted |
| Mrs. Meera Bai | Co-opted Trustee | co-opted |
| Ms. Anandhi Tholkappiyan | Co-opted Trustee | co-opted |
| Mrs. Durgadevi | Co-opted Trustee | co-opted |
| Mrs. Lalitha Srinivasan | Co-opted Trustee | co-opted |
| Mrs. Usha Jagadeesan | Co-opted Trustee | co-opted |
| Mr. K. Murugan | Co-opted Trustee | co-opted |
| Ms. Ambika Balaji | Co-opted Trustee | co-opted |

**Pages Seed Data:**
| Slug | Title | Content Source |
|------|-------|---------------|
| about-us | About Us | PDF — "Sri Sai Mission Charitable Trust is a Non-Profitable..." |
| mission | Our Mission | PDF — Mission section content |
| vision | Our Vision | PDF — Vision section content |
| annadhanam | Annadhanam | Service description page |
| cow-saala | Cow Saala | Service description page |
| temple-pooja | Temple Pooja | Service/schedule page |
| tourism | Tourism | Temple visit information |
| donations | Donations | Donation information + 80G details |

**Settings Seed Data:**
| Key | Value | Group |
|-----|-------|-------|
| site_name | Sri Sai Mission | general |
| site_tagline | Religious & Charitable Trust (Regd) 106/2014 | general |
| site_logo | /assets/images/logo.png | general |
| contact_phone_1 | 9841203311 | contact |
| contact_phone_2 | 9094033288 | contact |
| contact_phone_3 | 044-48589900 | contact |
| contact_email | srisaimission@gmail.com | contact |
| contact_address | 3E/A, 2nd Street, Buddhar Nagar, New Perungalathur, Chennai- 600 063 | contact |
| facebook_url | (to be set) | social |
| instagram_url | (to be set) | social |
| youtube_url | (to be set) | social |
| temple_name_1 | Perungalathur Athma Sai Temple | temple |
| temple_name_2 | Keerapakkam Baba Temple | temple |
| tax_exemption_info | Section 80G of the Income Tax | general |

---

## 4. ADMIN PANEL FEATURES

### 4.1 Dashboard

**Widgets:**
- Total Blogs (published count)
- Total Magazine Issues
- Total Gallery Images
- Total Donations This Month (sum)
- Unread Contact Messages (count with alert badge)
- Recent Activity Feed (last 20 log entries)
- Quick Links (Add Blog, Add Event, View Messages)

### 4.2 Blog Management

| Feature | Detail |
|---------|--------|
| **List View** | Paginated table (15/page) — Title, Category, Status, Date, Author, Views, Actions |
| **Create** | Form: Title, Category (dropdown), Content (rich editor), Excerpt, Featured Image (upload), Status (draft/published), SEO fields |
| **Edit** | Same as create, pre-populated |
| **Delete** | Soft-delete with confirmation dialog |
| **Bulk Actions** | Select multiple → Publish, Archive, Delete |
| **Search** | Full-text search on title + content |
| **Filter** | By status, by category, by date range |

**Validation Rules:**
| Field | Rules |
|-------|-------|
| title | Required, 5-255 chars |
| content | Required, min 50 chars |
| excerpt | Optional, max 500 chars (auto-generate from content if empty) |
| featured_image | Optional, max 5MB, types: jpg/png/webp |
| status | Required, enum: draft/published/archived |
| category_id | Optional, must exist in categories table |

**React Components:**
- `BlogList.jsx` — DataTable with filters, search, bulk actions
- `BlogForm.jsx` — Create/Edit form with RichTextEditor, ImageUploader, SEO fields

**Permissions:**
| Role | Create | Read | Update | Delete |
|------|--------|------|--------|--------|
| Super Admin | Yes | Yes | Yes | Yes |
| Admin | Yes | Yes | Yes | Yes |
| Editor | Yes | Yes | Own only | No |
| Viewer | No | Yes | No | No |

### 4.3 Magazine / Articles Management (Sri Sai Dharisanam)

| Feature | Detail |
|---------|--------|
| **List View** | Table — Title, Issue Number, Issue Date, Status, Views, Actions |
| **Create** | Title, Issue Number ("Vol X, Issue Y"), Issue Date, Category, Content (rich editor), Excerpt, Featured Image, PDF Upload, Status, SEO |
| **Edit** | Same as create, pre-populated |
| **Delete** | Soft-delete with confirmation |
| **Bulk Actions** | Publish, Archive, Delete |
| **PDF Upload** | Accept PDF files up to 10MB for downloadable magazine issues |

**Validation Rules:**
| Field | Rules |
|-------|-------|
| title | Required, 5-255 chars |
| content | Required, min 50 chars |
| issue_number | Optional, max 50 chars |
| issue_date | Optional, valid date |
| pdf_file | Optional, max 10MB, type: pdf |
| featured_image | Optional, max 5MB, types: jpg/png/webp |

### 4.4 Gallery Management

**Two-Level Structure:** Albums → Images

**Album Management:**
| Feature | Detail |
|---------|--------|
| **List View** | Grid view of albums — Cover image, Title, Image Count, Status |
| **Create Album** | Title, Description, Category, Cover Image, Status |
| **Edit Album** | Same + manage images within |
| **Delete Album** | Cascade deletes all images within (with confirmation) |

**Image Management (within album):**
| Feature | Detail |
|---------|--------|
| **Upload** | Drag-and-drop multi-file upload (max 20 at once) |
| **Edit** | Caption, Alt Text, Sort Order |
| **Delete** | Individual delete with confirmation |
| **Reorder** | Drag-and-drop reordering |
| **Auto-thumbnail** | Server generates 300x300 thumbnail on upload |

**Validation Rules:**
| Field | Rules |
|-------|-------|
| Album title | Required, 3-255 chars |
| Image file | Required, max 5MB each, types: jpg/png/webp/gif |
| Caption | Optional, max 500 chars |
| Alt text | Optional, max 255 chars |

**Image Upload & Storage Logic:**
```
Upload Flow:
1. React sends file via multipart/form-data to POST /api/v1/gallery/{albumId}/images
2. PHP validates: file type, file size, dimensions
3. Generate unique filename: {albumId}_{timestamp}_{random}.{ext}
4. Save original to: /storage/uploads/gallery/{albumId}/
5. Generate thumbnail (300x300 center-crop) to: /storage/uploads/gallery/{albumId}/thumbs/
6. Insert record into gallery_images table
7. Increment album image_count
8. Return image record as JSON
```

### 4.5 Trustees / Team Management

| Feature | Detail |
|---------|--------|
| **List View** | Two sections: Main Trustees (3) + Co-opted Trustees (sorted) |
| **Create** | Name, Designation, Type (main/co-opted), Bio, Photo, Phone, Email, Qualification prefix |
| **Edit** | Same, pre-populated |
| **Delete** | With confirmation |
| **Reorder** | Drag-and-drop sort order |
| **Toggle Active** | Quick toggle to show/hide on public site |

**Validation Rules:**
| Field | Rules |
|-------|-------|
| name | Required, 2-150 chars |
| designation | Required, 2-100 chars |
| trustee_type | Required, enum: main/co-opted |
| photo | Optional, max 2MB, types: jpg/png/webp |
| phone | Optional, valid Indian phone format |
| email | Optional, valid email format |

### 4.6 Pages (Static CMS)

| Feature | Detail |
|---------|--------|
| **List View** | Table — Title, Slug, Status, Template, Menu Position |
| **Create** | Title, Slug (auto-generated from title, editable), Content (rich editor), Featured Image, Template, Status, Show in Menu, Menu Position, Parent Page, SEO |
| **Edit** | Same, pre-populated |
| **Delete** | With confirmation (prevent deleting pages with children) |

**Pre-seeded Pages (from PDF):**
- About Us, Our Mission, Our Vision, Annadhanam, Cow Saala, Temple Pooja, Tourism, Donations

### 4.7 Events Management

| Feature | Detail |
|---------|--------|
| **List View** | Table — Title, Date, Time, Location, Status, Recurring badge |
| **Create** | Title, Description, Event Date, Time, End Date/Time, Location, Featured Image, Recurring (toggle + rule), Status, SEO |
| **Edit** | Same, pre-populated |
| **Delete** | With confirmation |
| **Status Management** | Auto-update: upcoming → completed after end_date passes |

**Pre-seeded Events:**
- Palki Procession — Every Sunday 4 PM, Keerapakkam Temple
- Sathya Narayana Pooja — Every Full Moon Day

### 4.8 Donations Management

| Feature | Detail |
|---------|--------|
| **List View** | Table — Donor Name, Amount, Purpose, Method, Status, Date |
| **View Detail** | Full donor info + PAN for 80G |
| **Add Manual** | Record cash/cheque donations manually |
| **Export** | CSV export for accounting |
| **Filter** | By date range, purpose, status, payment method |
| **Receipt** | Generate 80G receipt (PDF) — future enhancement |

### 4.9 Contact Messages

| Feature | Detail |
|---------|--------|
| **List View** | Table — Name, Email, Subject, Status (color-coded), Date |
| **View** | Full message with admin notes field |
| **Mark Status** | Unread → Read → Replied → Archived |
| **Delete** | With confirmation |
| **Bulk** | Mark read, archive, delete |

### 4.10 Media Library

| Feature | Detail |
|---------|--------|
| **Grid View** | All uploaded media as thumbnails |
| **Upload** | Drag-and-drop, multi-file |
| **Filter** | By type (image/pdf), by module (blog/magazine/gallery) |
| **Delete** | With orphan check warning |
| **Copy URL** | Click to copy file URL |

### 4.11 Settings

| Section | Fields |
|---------|--------|
| **General** | Site Name, Tagline, Logo Upload, Favicon, Tax Info |
| **Contact** | Phone numbers (3), Email, Address |
| **Social** | Facebook, Instagram, YouTube, Twitter URLs |
| **Temple Timings** | CRUD for temple_timings table |
| **SEO Defaults** | Default meta title template, meta description |
| **User Management** | (Super Admin only) Add/edit/deactivate admin users, assign roles |

---

## 5. API DESIGN

### 5.1 Authentication Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| POST | `/api/v1/auth/login` | Admin login, returns JWT | No |
| POST | `/api/v1/auth/logout` | Invalidate token | Yes |
| GET | `/api/v1/auth/me` | Get current user profile | Yes |
| PUT | `/api/v1/auth/password` | Change password | Yes |

**POST /api/v1/auth/login**
```
Request:
{
    "email": "admin@srisaimission.org",
    "password": "********"
}

Response (200):
{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "expires_in": 3600,
        "user": {
            "id": 1,
            "name": "Sri Sai Varadharajan",
            "email": "admin@srisaimission.org",
            "role": "super_admin",
            "permissions": {...}
        }
    }
}

Response (401):
{
    "success": false,
    "error": {
        "code": "INVALID_CREDENTIALS",
        "message": "Invalid email or password"
    }
}
```

### 5.2 Blog Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/blogs` | List blogs (paginated, filtered) | Yes |
| GET | `/api/v1/blogs/{id}` | Get single blog | Yes |
| POST | `/api/v1/blogs` | Create blog | Yes (editor+) |
| PUT | `/api/v1/blogs/{id}` | Update blog | Yes (editor+) |
| DELETE | `/api/v1/blogs/{id}` | Delete blog | Yes (admin+) |
| POST | `/api/v1/blogs/bulk-action` | Bulk publish/archive/delete | Yes (admin+) |

**GET /api/v1/blogs?page=1&per_page=15&status=published&category_id=2&search=krishna**
```
Response (200):
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Traditions of Hare Krishna holidays",
            "slug": "traditions-of-hare-krishna-holidays",
            "excerpt": "...",
            "featured_image": "/storage/uploads/blogs/image-10.jpg",
            "status": "published",
            "category": { "id": 2, "name": "Krishna" },
            "author": { "id": 1, "name": "Sri Sai Varadharajan" },
            "view_count": 42,
            "published_at": "2026-01-15T10:00:00Z",
            "created_at": "2026-01-14T08:30:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 28,
        "last_page": 2
    }
}
```

**POST /api/v1/blogs**
```
Request (multipart/form-data):
{
    "title": "New Blog Post Title",
    "category_id": 2,
    "content": "<p>Rich HTML content...</p>",
    "excerpt": "Short summary...",
    "featured_image": (file),
    "status": "draft",
    "seo_title": "Custom SEO Title",
    "seo_description": "Custom meta description"
}

Response (201):
{
    "success": true,
    "data": {
        "id": 29,
        "title": "New Blog Post Title",
        "slug": "new-blog-post-title",
        ...
    },
    "message": "Blog created successfully"
}
```

### 5.3 Magazine Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/magazines` | List magazines (paginated) | Yes |
| GET | `/api/v1/magazines/{id}` | Get single magazine | Yes |
| POST | `/api/v1/magazines` | Create magazine | Yes (editor+) |
| PUT | `/api/v1/magazines/{id}` | Update magazine | Yes (editor+) |
| DELETE | `/api/v1/magazines/{id}` | Delete magazine | Yes (admin+) |

### 5.4 Gallery Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/gallery/albums` | List albums | Yes |
| GET | `/api/v1/gallery/albums/{id}` | Get album with images | Yes |
| POST | `/api/v1/gallery/albums` | Create album | Yes (editor+) |
| PUT | `/api/v1/gallery/albums/{id}` | Update album | Yes (editor+) |
| DELETE | `/api/v1/gallery/albums/{id}` | Delete album + images | Yes (admin+) |
| POST | `/api/v1/gallery/albums/{id}/images` | Upload images to album | Yes (editor+) |
| PUT | `/api/v1/gallery/images/{id}` | Update image caption/alt | Yes (editor+) |
| DELETE | `/api/v1/gallery/images/{id}` | Delete single image | Yes (editor+) |
| PUT | `/api/v1/gallery/albums/{id}/reorder` | Reorder images | Yes (editor+) |

### 5.5 Event Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/events` | List events (paginated) | Yes |
| GET | `/api/v1/events/{id}` | Get single event | Yes |
| POST | `/api/v1/events` | Create event | Yes (editor+) |
| PUT | `/api/v1/events/{id}` | Update event | Yes (editor+) |
| DELETE | `/api/v1/events/{id}` | Delete event | Yes (admin+) |

### 5.6 Trustee Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/trustees` | List all trustees | Yes |
| GET | `/api/v1/trustees/{id}` | Get single trustee | Yes |
| POST | `/api/v1/trustees` | Add trustee | Yes (admin+) |
| PUT | `/api/v1/trustees/{id}` | Update trustee | Yes (admin+) |
| DELETE | `/api/v1/trustees/{id}` | Remove trustee | Yes (super_admin) |
| PUT | `/api/v1/trustees/reorder` | Reorder trustees | Yes (admin+) |

### 5.7 Page Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/pages` | List pages | Yes |
| GET | `/api/v1/pages/{id}` | Get single page | Yes |
| POST | `/api/v1/pages` | Create page | Yes (admin+) |
| PUT | `/api/v1/pages/{id}` | Update page | Yes (admin+) |
| DELETE | `/api/v1/pages/{id}` | Delete page | Yes (super_admin) |

### 5.8 Donation Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/donations` | List donations (paginated, filtered) | Yes (admin+) |
| GET | `/api/v1/donations/{id}` | Get donation detail | Yes (admin+) |
| POST | `/api/v1/donations` | Record manual donation | Yes (admin+) |
| PUT | `/api/v1/donations/{id}` | Update donation status | Yes (admin+) |
| GET | `/api/v1/donations/export` | Export CSV | Yes (admin+) |
| GET | `/api/v1/donations/summary` | Monthly/yearly totals | Yes |

### 5.9 Contact Message Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/contacts` | List messages (paginated) | Yes |
| GET | `/api/v1/contacts/{id}` | View single message | Yes |
| PUT | `/api/v1/contacts/{id}/status` | Update status | Yes |
| DELETE | `/api/v1/contacts/{id}` | Delete message | Yes (admin+) |
| POST | `/api/v1/contacts/bulk-action` | Bulk mark read/archive/delete | Yes |

### 5.10 Media & Settings Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/media` | List media library | Yes |
| POST | `/api/v1/media/upload` | Upload file | Yes (editor+) |
| DELETE | `/api/v1/media/{id}` | Delete media | Yes (admin+) |
| GET | `/api/v1/settings` | Get all settings | Yes |
| PUT | `/api/v1/settings` | Update settings (batch) | Yes (super_admin) |
| GET | `/api/v1/settings/temple-timings` | Get temple timings | Yes |
| PUT | `/api/v1/settings/temple-timings` | Update temple timings | Yes (admin+) |
| GET | `/api/v1/dashboard/stats` | Dashboard analytics | Yes |
| GET | `/api/v1/activity-log` | Recent activity | Yes (admin+) |

### 5.11 Categories Endpoints

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/v1/categories?type=blog` | List categories by type | Yes |
| POST | `/api/v1/categories` | Create category | Yes (admin+) |
| PUT | `/api/v1/categories/{id}` | Update category | Yes (admin+) |
| DELETE | `/api/v1/categories/{id}` | Delete category | Yes (admin+) |

### 5.12 Auth Middleware Flow

```
Every API Request (except /auth/login):
│
├── 1. Extract Authorization header: "Bearer {token}"
│     └── Missing? → 401 { "error": "TOKEN_MISSING" }
│
├── 2. Decode JWT token
│     └── Invalid/Expired? → 401 { "error": "TOKEN_INVALID" }
│
├── 3. Load user from token payload (user_id)
│     └── User inactive? → 403 { "error": "ACCOUNT_DISABLED" }
│
├── 4. Check role permissions for requested endpoint
│     └── Insufficient? → 403 { "error": "INSUFFICIENT_PERMISSIONS" }
│
└── 5. Proceed to controller
```

### 5.13 Error Handling Standards

All API errors follow a consistent format:

```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid",
        "details": {
            "title": ["Title is required"],
            "content": ["Content must be at least 50 characters"]
        }
    }
}
```

| HTTP Code | Error Code | Meaning |
|-----------|-----------|---------|
| 400 | VALIDATION_ERROR | Input validation failed |
| 401 | TOKEN_MISSING | No auth token provided |
| 401 | TOKEN_INVALID | Token expired or malformed |
| 401 | INVALID_CREDENTIALS | Wrong email/password |
| 403 | INSUFFICIENT_PERMISSIONS | Role lacks permission |
| 403 | ACCOUNT_DISABLED | User account deactivated |
| 404 | NOT_FOUND | Resource doesn't exist |
| 409 | DUPLICATE_ENTRY | Unique constraint violation (e.g., slug) |
| 413 | FILE_TOO_LARGE | Upload exceeds max size |
| 415 | UNSUPPORTED_FILE_TYPE | File type not allowed |
| 429 | RATE_LIMIT_EXCEEDED | Too many requests |
| 500 | SERVER_ERROR | Unexpected server error |

---

## 6. FRONTEND INTEGRATION

### 6.1 How React Admin Consumes APIs

**API Client Setup (Axios):**
```
src/api/client.js
├── Base URL from env: VITE_API_URL
├── Request interceptor: Attach JWT from localStorage
├── Response interceptor: Handle 401 → redirect to login
├── Global error handling → show toast notification
```

**Pattern per module:**
```
src/api/blogApi.js
├── getBlogs(params)      → GET /api/v1/blogs?{query}
├── getBlog(id)           → GET /api/v1/blogs/{id}
├── createBlog(formData)  → POST /api/v1/blogs
├── updateBlog(id, data)  → PUT /api/v1/blogs/{id}
├── deleteBlog(id)        → DELETE /api/v1/blogs/{id}
└── bulkAction(action, ids) → POST /api/v1/blogs/bulk-action
```

### 6.2 State Management

**React Context + useReducer** (no Redux — appropriate for this scale):

```
AuthContext
├── user (current logged-in user object)
├── token (JWT)
├── permissions (role-based)
├── login(email, password)
├── logout()
└── isAuthenticated

NotificationContext
├── notifications[] (toast queue)
├── addNotification(type, message)
└── removeNotification(id)
```

**Per-page state:** Local component state via `useState` and `useEffect`. No global store for entity data — fetched on demand with loading states.

### 6.3 Routing Strategy

**Admin Panel (React Router v6):**
```
/admin/login              → LoginPage
/admin/                   → DashboardPage
/admin/blogs              → BlogsPage (list)
/admin/blogs/create       → BlogsPage (create form)
/admin/blogs/:id/edit     → BlogsPage (edit form)
/admin/magazines          → MagazinesPage
/admin/magazines/create   → MagazinesPage (create)
/admin/magazines/:id/edit → MagazinesPage (edit)
/admin/gallery            → GalleryPage (album list)
/admin/gallery/create     → GalleryPage (create album)
/admin/gallery/:id        → GalleryPage (manage images)
/admin/events             → EventsPage
/admin/events/create      → EventsPage (create)
/admin/events/:id/edit    → EventsPage (edit)
/admin/trustees           → TrusteesPage
/admin/pages              → PagesPage
/admin/pages/create       → PagesPage (create)
/admin/pages/:id/edit     → PagesPage (edit)
/admin/donations          → DonationsPage
/admin/contacts           → ContactsPage
/admin/media              → MediaPage
/admin/settings           → SettingsPage
```

**Public Website (PHP server-side routing):**
```
/                         → HomeController@index
/about-us                 → PageController@show (slug: about-us)
/mission                  → PageController@show (slug: mission)
/vision                   → PageController@show (slug: vision)
/trustees                 → TrusteeController@index
/blog                     → BlogController@index
/blog/{slug}              → BlogController@show
/magazine                 → MagazineController@index
/magazine/{slug}          → MagazineController@show
/gallery                  → GalleryController@index
/gallery/{slug}           → GalleryController@album
/events                   → EventController@index
/events/{slug}            → EventController@show
/donations                → DonationController@index
/contact                  → ContactController@index
/tourism                  → PageController@show (slug: tourism)
/{slug}                   → PageController@show (catch-all for dynamic pages)
```

### 6.4 Reusing Existing Template Styles

The template's CSS/JS assets are used **directly** for the public website:

```
Template Assets → Backend public/assets/
├── css/ (all 68 CSS files copied as-is)
│   Key files: __styles.css, __custom.css, __plugins.css, style.css
│   Responsive: __responsive.css, __responsive_1.css
│
├── js/ (key JS files retained)
│   jQuery + migrate, Swiper, GSAP, Magnific Popup, Superfish
│   Template scripts: __scripts.js, skin.js, skills.js
│
├── fonts/ (all font files)
│   Fontello, Kumbh Sans, Roboto, icon fonts
│
└── images/ (template static images)
    Logos, backgrounds, icons
```

**PHP views replicate the exact HTML structure** from the template's index.html, but with dynamic data injection:

```php
<!-- Before (static template): -->
<h2 class="sc_item_title sc_title_title">
    <span class="sc_item_title_text">Divine bliss at the Krishna temple</span>
</h2>

<!-- After (dynamic PHP view): -->
<h2 class="sc_item_title sc_title_title">
    <span class="sc_item_title_text"><?= htmlspecialchars($page->title) ?></span>
</h2>
```

**Admin Panel** uses its own CSS framework (a lightweight admin UI library or Tailwind CSS), completely separate from the template styles.

### 6.5 Dynamic Content Rendering

**Homepage (views/home/index.php):**
```
Fetches from database and renders into template sections:
├── Hero Slider      → Settings (images, captions — admin-configurable)
├── Services Icons   → Pages (Annadhanam, Cow Saala, Pooja, Programme)
├── About Section    → Page (about-us)
├── Stats            → Settings (counters — years, lives, events)
├── Donations        → Settings (donation info text)
├── Mission Quote    → Page (mission) — excerpt
├── Trustees         → Trustees table (first 4-6, with photos)
├── Temple Timings   → temple_timings table
├── Events           → Events table (next 3 upcoming)
├── Blog Grid        → Blogs table (latest 3 published)
├── Contact Form     → Static form → POST /contact/submit
├── Footer           → Settings (address, phones, social links)
```

### 6.6 SEO Considerations

| Strategy | Implementation |
|----------|---------------|
| **Server-side rendering** | All public pages rendered as full HTML by PHP — no client-side rendering dependency |
| **Semantic URLs** | `/blog/traditions-of-hare-krishna` not `/blog?id=3` |
| **Meta tags** | Dynamic `<title>`, `<meta description>`, Open Graph tags from seo_meta table |
| **Canonical URLs** | Set on every page to prevent duplicate content |
| **Sitemap.xml** | Auto-generated PHP script listing all published content URLs |
| **Robots.txt** | Allow all public, block /admin, /api, /storage |
| **Structured Data** | JSON-LD for Organization, Article, Event schemas |
| **Image alt text** | Enforced in admin (optional but encouraged via UI hints) |
| **Page speed** | CSS/JS minified, images lazy-loaded (template already has lazy loading) |
| **Heading hierarchy** | Template's existing H1-H6 structure preserved |

**Admin panel (React SPA)** does NOT need SEO — it's behind login and blocked by robots.txt.

---

## 7. SECURITY & PERFORMANCE

### 7.1 Authentication & Authorization

| Layer | Mechanism |
|-------|-----------|
| **Password Storage** | bcrypt with cost factor 12 (`password_hash()` PHP native) |
| **API Auth** | JWT (HS256) with 1-hour expiry, refresh via re-login |
| **Session** | Server-side PHP sessions for CSRF token generation |
| **Role-Based Access** | Permissions stored in roles.permissions JSON, checked by RoleMiddleware on every API call |
| **Login Throttling** | Max 5 failed attempts per IP in 15 minutes → 30-minute lockout |
| **Password Policy** | Min 8 chars, at least 1 uppercase, 1 number, 1 special character |

### 7.2 SQL Injection Protection

| Measure | Detail |
|---------|--------|
| **Prepared Statements** | ALL database queries use PDO prepared statements with parameterized bindings — zero string concatenation in queries |
| **ORM/Query Builder** | Custom Model base class wraps PDO — all methods use `?` or `:name` placeholders |
| **Input Type Casting** | IDs cast to `(int)`, amounts to `(float)` before query |
| **No raw queries** | No `mysqli_query()` with concatenated strings anywhere in codebase |

### 7.3 XSS Prevention

| Measure | Detail |
|---------|--------|
| **Output Escaping** | All dynamic content output via `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')` in views |
| **Content Sanitization** | Rich text (blog/magazine content) sanitized server-side with HTMLPurifier whitelist |
| **CSP Header** | `Content-Security-Policy` header set to restrict inline scripts |
| **HttpOnly Cookies** | Session cookies set with HttpOnly + Secure flags |

### 7.4 CSRF Protection

| Measure | Detail |
|---------|--------|
| **Web forms** | CSRF token generated per session, embedded as hidden field, validated on POST |
| **API** | JWT auth acts as CSRF protection (token not auto-sent by browser) |
| **SameSite Cookie** | `SameSite=Strict` on session cookies |

### 7.5 File Upload Security

```
Upload Validation Pipeline:
│
├── 1. Check file size (max 5MB images, 10MB PDFs)
├── 2. Check MIME type via finfo_file() (NOT just extension)
├── 3. Check file extension against whitelist (jpg, png, webp, gif, pdf)
├── 4. Rename file to random hash (prevent path traversal)
│       Format: {module}_{timestamp}_{random_16_chars}.{ext}
├── 5. Strip EXIF data from images (privacy)
├── 6. Scan for PHP code injection in image files
│       Reject if file starts with <?php or contains PHP tags
├── 7. Store OUTSIDE web root in /storage/uploads/
│       Serve via PHP proxy script that validates access
└── 8. Generate thumbnail for images (separate safe file)
```

### 7.6 Additional Security

| Measure | Detail |
|---------|--------|
| **HTTPS Only** | Force HTTPS redirect in .htaccess, HSTS header |
| **Rate Limiting** | API: 60 requests/minute per IP. Login: 5 attempts/15min |
| **Input Validation** | Whitelist validation on all inputs (type, length, format) |
| **Error Handling** | Production: generic error messages. No stack traces exposed. Errors logged to file |
| **Directory Listing** | Disabled in Apache/Nginx config |
| **Sensitive Files** | `.env`, `composer.json`, `/storage/` blocked from public access |
| **X-Frame-Options** | DENY (prevent clickjacking) |
| **X-Content-Type-Options** | nosniff |

### 7.7 Performance & Caching

| Strategy | Implementation |
|----------|---------------|
| **Query Caching** | Homepage queries cached to file for 15 minutes |
| **Page Caching** | Static pages (about, mission) cached as HTML files for 1 hour |
| **Image Optimization** | Thumbnails generated on upload, originals served only when needed |
| **CSS/JS** | Combined where possible, minified for production |
| **Lazy Loading** | Template already uses `loading="lazy"` on images |
| **Database Indexes** | All foreign keys, status columns, slug columns indexed (see schema) |
| **Pagination** | All list pages paginated (15 items default), prevent loading all records |
| **Gzip** | Enable gzip compression in Apache/Nginx |

### 7.8 Backup Strategy

| Type | Frequency | Retention | Method |
|------|-----------|-----------|--------|
| **Database** | Daily at 2:00 AM | 30 days | `mysqldump` → compressed → stored in `/backups/db/` |
| **Uploads** | Weekly | 12 weeks | Tar + gzip `/storage/uploads/` → `/backups/files/` |
| **Full Server** | Monthly | 6 months | VPS provider snapshot |
| **Off-site** | Weekly | 8 weeks | Copy latest DB backup to Google Drive / separate server |

Cron job example:
```
0 2 * * * /usr/bin/mysqldump -u srisai_user -p'xxx' srisai_prod | gzip > /backups/db/srisai_$(date +\%Y\%m\%d).sql.gz
0 3 * * 0 tar czf /backups/files/uploads_$(date +\%Y\%m\%d).tar.gz /var/www/srisai/storage/uploads/
```

---

## 8. DEPLOYMENT PLAN

### 8.1 Server Requirements

**LOCAL DEVELOPMENT (WampServer — already installed):**
| Requirement | Status |
|-------------|--------|
| **OS** | Windows 10/11 |
| **WampServer** | Installed (includes Apache + MySQL + PHP + phpMyAdmin) |
| **PHP** | 8.2 (via WampServer) |
| **MySQL** | 8.0 (via WampServer) |
| **Node.js** | 18 LTS or 20 LTS (install separately for React admin) |
| **Composer** | Install separately for PHP dependency management |
| **Git** | Install separately for version control |

**PRODUCTION SERVER:**
| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| **OS** | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| **Web Server** | Nginx 1.24 | Nginx 1.26 |
| **PHP** | 8.1 (PHP-FPM) | 8.2+ |
| **MySQL** | 8.0 | 8.0+ |
| **RAM** | 1 GB | 2 GB |
| **Storage** | 20 GB SSD | 40 GB SSD |
| **SSL** | Let's Encrypt (free) | Let's Encrypt |

**PHP Extensions Required:** pdo_mysql, mbstring, openssl, gd (image processing), fileinfo, json, curl
Enable these in WampServer via: **Tray icon → PHP → PHP Extensions → check each one**

### 8.2 Folder Structure & Permissions

**LOCAL (WampServer on Windows):**
```
C:\wamp64\www\srisai\              ← Project root (Apache serves this)
├── public\                        ← Web-accessible root
│   ├── index.php                  ← Front controller
│   ├── .htaccess                  ← URL rewriting
│   ├── assets\                    ← Template CSS/JS/fonts/images
│   └── admin\                     ← React admin build output
├── app\                           ← PHP MVC application code
├── config\                        ← Configuration files
├── database\                      ← Migrations and seeders
├── routes\                        ← Route definitions
├── views\                         ← PHP template views
├── storage\
│   ├── uploads\                   ← File uploads (writable)
│   ├── cache\                     ← Cache files (writable)
│   └── logs\                      ← Log files (writable)
├── vendor\                        ← Composer dependencies
├── .env                           ← Environment config (NOT in Git)
└── .env.example                   ← Template env file
```

**PRODUCTION (Linux VPS):**
```
/var/www/srisai/
├── public/              755 (www-data:www-data)
│   ├── index.php        644
│   ├── .htaccess        644
│   └── assets/          755 (read-only for web)
├── app/                 755 (read-only for web)
├── config/              750 (restricted — no web access)
├── storage/
│   ├── uploads/         775 (writable by PHP)
│   ├── cache/           775 (writable by PHP)
│   └── logs/            775 (writable by PHP)
├── .env                 640 (readable by PHP user only)
└── vendor/              755 (read-only)
```

### 8.3 Build Process

```
LOCAL DEVELOPMENT (WampServer):
1. Start WampServer (tray icon → green = all services running)
2. Create project folder: C:\wamp64\www\srisai\
3. git init (or clone if repo exists)
4. composer install (PHP dependencies)
5. Copy .env.example → .env, set DB_DATABASE=srisai_db, DB_USERNAME=root, DB_PASSWORD=
6. Open phpMyAdmin (http://localhost/phpmyadmin) → Create database "srisai_db"
7. Run migration SQL scripts in phpMyAdmin (or via php migrate.php up)
8. Run seeders (insert default data)
9. Test: http://localhost/srisai/ → should serve PHP response
10. For React admin: cd C:\SriSai\frontend-admin && npm install && npm run dev
    → React dev server at http://localhost:5173 (proxies API to localhost/srisai)

PRODUCTION DEPLOYMENT:
1. git pull origin main
2. composer install --no-dev --optimize-autoloader
3. cd frontend-admin && npm ci && npm run build
4. Copy build output → public/admin/
5. php migrate.php up (run pending migrations)
6. Clear cache: rm -rf storage/cache/*
7. Set file permissions (see 8.2 Production)
8. Restart PHP-FPM: sudo systemctl restart php8.2-fpm
9. Test: curl -I https://www.srisaimission.org
```

### 8.4 Environment Variables

Production `.env` checklist:
```
APP_ENV=production          ← MUST be 'production'
APP_DEBUG=false             ← MUST be false
APP_URL=https://www.srisaimission.org

DB_HOST=localhost
DB_DATABASE=srisai_prod
DB_USERNAME=srisai_user     ← NOT root
DB_PASSWORD=<32_char_random>

JWT_SECRET=<64_char_random> ← Generate: openssl rand -hex 32

MAIL_*                      ← SMTP credentials
UPLOAD_MAX_SIZE=5242880     ← 5MB
```

### 8.5 Domain & Hosting Setup

```
Domain: srisaimission.org (or .in / .com as available)
│
├── DNS A Record: srisaimission.org → Server IP
├── DNS A Record: www.srisaimission.org → Server IP
├── DNS A Record: staging.srisaimission.org → Server IP (optional)
│
├── SSL: Let's Encrypt via Certbot
│   sudo certbot --nginx -d srisaimission.org -d www.srisaimission.org
│
└── Nginx Server Block:
    server {
        listen 443 ssl http2;
        server_name srisaimission.org www.srisaimission.org;
        root /var/www/srisai/public;
        index index.php;

        # PHP processing
        location ~ \.php$ {
            fastcgi_pass unix:/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }

        # React admin SPA
        location /admin {
            try_files $uri $uri/ /admin/index.html;
        }

        # API routing
        location /api {
            try_files $uri $uri/ /index.php?$query_string;
        }

        # Clean URLs for public pages
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        # Block sensitive files
        location ~ /\.(env|git) { deny all; }
        location ~ ^/(config|storage|database|app) { deny all; }

        # Static asset caching
        location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2)$ {
            expires 30d;
            add_header Cache-Control "public, immutable";
        }
    }
```

### 8.6 CI/CD (Optional Enhancement)

```
GitHub Actions Workflow (.github/workflows/deploy.yml):

on push to main:
  1. Run PHP linter (syntax check)
  2. Run React build (npm run build)
  3. SSH into production server
  4. git pull
  5. composer install --no-dev
  6. Copy React build → public/admin/
  7. Run migrations
  8. Clear cache
  9. Restart PHP-FPM
  10. Notify via email/Slack
```

---

## 9. PROJECT TRACKER

### Phase 1: Planning & Setup

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 1.1 | Finalize master plan | Review and approve this document | Critical | — | None | In Progress |
| 1.2 | Set up Git repository | Initialize repo, .gitignore, README | High | 1h | 1.1 | To Do |
| 1.3 | Provision development environment | Install PHP 8.2, MySQL 8, Node.js 20, Composer | High | 2h | 1.1 | To Do |
| 1.4 | Create database | Run all migration scripts, seed data | High | 2h | 1.3 | To Do |
| 1.5 | Set up project folder structure | Create backend + frontend directory trees | High | 2h | 1.2 | To Do |
| 1.6 | Configure .env files | Local development credentials | Medium | 30m | 1.3 | To Do |

### Phase 2: Backend Core

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 2.1 | Build PHP MVC framework core | Router, Controller base, Model base, Request/Response | Critical | 8h | 1.5 | To Do |
| 2.2 | Database connection layer | PDO wrapper, query builder, migrations runner | Critical | 4h | 1.4 | To Do |
| 2.3 | Authentication system | JWT generation/validation, login endpoint, middleware | Critical | 6h | 2.1, 2.2 | To Do |
| 2.4 | Role & permissions middleware | Permission checking per route per role | High | 4h | 2.3 | To Do |
| 2.5 | File upload service | Upload, validate, resize, thumbnail, storage | High | 5h | 2.1 | To Do |
| 2.6 | Slug generation service | Auto-generate SEO slugs from titles | Medium | 2h | 2.1 | To Do |

### Phase 3: Backend API — All Modules

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 3.1 | Blog CRUD API | All 6 endpoints + validation + search | Critical | 6h | 2.1-2.6 | To Do |
| 3.2 | Magazine CRUD API | All endpoints + PDF upload | Critical | 5h | 2.1-2.6 | To Do |
| 3.3 | Gallery API | Albums CRUD + Image upload/reorder | Critical | 7h | 2.1-2.6 | To Do |
| 3.4 | Events CRUD API | All endpoints + recurring events | High | 5h | 2.1-2.6 | To Do |
| 3.5 | Pages CRUD API | All endpoints + parent-child | High | 4h | 2.1-2.6 | To Do |
| 3.6 | Trustees CRUD API | All endpoints + reorder | High | 4h | 2.1-2.6 | To Do |
| 3.7 | Donations API | CRUD + export CSV + summary | High | 5h | 2.1-2.6 | To Do |
| 3.8 | Contact messages API | CRUD + status management | Medium | 3h | 2.1-2.6 | To Do |
| 3.9 | Categories API | CRUD per type | Medium | 3h | 2.1-2.6 | To Do |
| 3.10 | Media library API | Upload, list, delete | Medium | 3h | 2.5 | To Do |
| 3.11 | Settings API | Read/write site settings | Medium | 3h | 2.1-2.2 | To Do |
| 3.12 | Dashboard stats API | Aggregate counts + recent activity | Low | 3h | 3.1-3.8 | To Do |

### Phase 4: React Admin Panel

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 4.1 | Admin scaffold | Vite + React + Router + Axios + AuthContext | Critical | 4h | None | To Do |
| 4.2 | Login page | Email/password form, JWT storage | Critical | 3h | 4.1, 2.3 | To Do |
| 4.3 | Admin layout | Sidebar, TopBar, ProtectedRoute | Critical | 5h | 4.2 | To Do |
| 4.4 | Common components | DataTable, Pagination, Modal, ImageUploader, RichTextEditor | Critical | 8h | 4.3 | To Do |
| 4.5 | Dashboard page | Stats widgets, activity feed | High | 4h | 4.4, 3.12 | To Do |
| 4.6 | Blog management UI | List + Create/Edit form | Critical | 6h | 4.4, 3.1 | To Do |
| 4.7 | Magazine management UI | List + Create/Edit form + PDF upload | Critical | 5h | 4.4, 3.2 | To Do |
| 4.8 | Gallery management UI | Album list/form + Image manager (drag-drop) | Critical | 8h | 4.4, 3.3 | To Do |
| 4.9 | Events management UI | List + Create/Edit form | High | 5h | 4.4, 3.4 | To Do |
| 4.10 | Pages management UI | List + Create/Edit form | High | 5h | 4.4, 3.5 | To Do |
| 4.11 | Trustees management UI | List + Create/Edit + reorder | High | 4h | 4.4, 3.6 | To Do |
| 4.12 | Donations management UI | List + detail view + export | High | 4h | 4.4, 3.7 | To Do |
| 4.13 | Contact messages UI | List + view + status management | Medium | 3h | 4.4, 3.8 | To Do |
| 4.14 | Media library UI | Grid view + upload + delete | Medium | 4h | 4.4, 3.10 | To Do |
| 4.15 | Settings UI | Forms for all setting groups + temple timings | Medium | 5h | 4.4, 3.11 | To Do |
| 4.16 | User management UI | (Super Admin only) Add/edit users | Medium | 4h | 4.4, 2.3 | To Do |

### Phase 5: Public Website (PHP Views)

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 5.1 | Extract template into PHP views | Split index.html into master layout + partials | Critical | 6h | 1.5 | To Do |
| 5.2 | Homepage view | Dynamic rendering of all 16 sections | Critical | 8h | 5.1, 3.1-3.11 | To Do |
| 5.3 | Blog listing + detail pages | List with pagination, single post page | Critical | 5h | 5.1, 3.1 | To Do |
| 5.4 | Magazine listing + detail pages | List with pagination, single article, PDF download | Critical | 5h | 5.1, 3.2 | To Do |
| 5.5 | Gallery listing + album pages | Album grid + lightbox image viewer | High | 5h | 5.1, 3.3 | To Do |
| 5.6 | Events listing + detail pages | Upcoming events + single event view | High | 4h | 5.1, 3.4 | To Do |
| 5.7 | Dynamic pages (About, Mission, etc.) | CMS page rendering | High | 3h | 5.1, 3.5 | To Do |
| 5.8 | Trustees page | Main + co-opted trustees display | High | 3h | 5.1, 3.6 | To Do |
| 5.9 | Donations page | Info + form | Medium | 3h | 5.1, 3.7 | To Do |
| 5.10 | Contact page | Form + submission handling | High | 3h | 5.1, 3.8 | To Do |
| 5.11 | SEO implementation | Sitemap.xml, robots.txt, meta tags, structured data | High | 4h | 5.2-5.10 | To Do |

### Phase 6: Testing

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 6.1 | API endpoint testing | Test all endpoints with Postman/Insomnia | Critical | 6h | Phase 3 | To Do |
| 6.2 | Admin panel functional testing | Test all CRUD flows in browser | Critical | 6h | Phase 4 | To Do |
| 6.3 | Public website testing | Test all pages, links, forms, responsiveness | Critical | 4h | Phase 5 | To Do |
| 6.4 | Security testing | SQL injection, XSS, CSRF, file upload tests | Critical | 4h | 6.1-6.3 | To Do |
| 6.5 | Cross-browser testing | Chrome, Firefox, Safari, Edge, mobile browsers | High | 3h | 6.3 | To Do |
| 6.6 | Performance testing | Page load times, image optimization, caching validation | Medium | 3h | 6.3 | To Do |
| 6.7 | SEO validation | Google Lighthouse, meta tags, sitemap, structured data | Medium | 2h | 5.11 | To Do |

### Phase 7: Deployment

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 7.1 | Provision production server | VPS setup, install LEMP stack | Critical | 3h | Phase 6 | To Do |
| 7.2 | Domain + SSL setup | DNS, Nginx config, Let's Encrypt | Critical | 2h | 7.1 | To Do |
| 7.3 | Deploy application | Git clone, build, migrate, seed | Critical | 3h | 7.2 | To Do |
| 7.4 | Configure backups | Cron jobs for DB + file backups | High | 2h | 7.3 | To Do |
| 7.5 | Smoke testing on production | Verify all features work live | Critical | 2h | 7.3 | To Do |
| 7.6 | Go-live checklist | Final DNS, email config, analytics | High | 2h | 7.5 | To Do |

### Phase 8: Post-Launch

| # | Task | Description | Priority | Est. | Dependencies | Status |
|---|------|-------------|----------|------|-------------|--------|
| 8.1 | Content population | Admin enters all real content (blogs, pages, gallery, events) | High | Ongoing | 7.6 | To Do |
| 8.2 | Admin training | Train trust members on admin panel usage | High | 2h | 7.6 | To Do |
| 8.3 | Monitor logs | Check error logs, performance, security | Medium | Ongoing | 7.6 | To Do |
| 8.4 | Bug fixes | Address any issues found post-launch | High | Ongoing | 7.6 | To Do |
| 8.5 | Google Search Console | Submit sitemap, verify indexing | Medium | 1h | 7.6 | To Do |
| 8.6 | Analytics setup | Google Analytics / similar | Medium | 1h | 7.6 | To Do |

---

## 10. DEVELOPMENT ROADMAP

### Milestone-Based Timeline

```
MILESTONE 1: FOUNDATION                         [~40 hours]
├── Week 1-2
├── Deliverables:
│   ├── Git repo initialized
│   ├── PHP MVC framework core working (routing, controllers, models)
│   ├── MySQL database created with all tables
│   ├── Authentication system (login, JWT, middleware)
│   ├── File upload service
│   └── React admin scaffold with login working
├── Exit Criteria:
│   └── Admin can log in at /admin and see empty dashboard

MILESTONE 2: CORE API + ADMIN CRUD              [~65 hours]
├── Week 3-5
├── Deliverables:
│   ├── All 12 API modules fully functional
│   ├── Blog, Magazine, Gallery, Events, Pages, Trustees admin UI
│   ├── Image upload and management working
│   ├── Rich text editor integrated
│   ├── Settings and media library
│   └── All CRUD operations tested
├── Exit Criteria:
│   └── Admin can create/edit/delete content for all modules

MILESTONE 3: PUBLIC WEBSITE                      [~45 hours]
├── Week 6-7
├── Deliverables:
│   ├── Template converted to PHP views
│   ├── Homepage dynamically rendering all sections
│   ├── All public pages (blog, magazine, gallery, events, etc.) working
│   ├── Contact form functional
│   ├── SEO implemented (sitemap, meta tags, structured data)
│   └── Responsive design verified
├── Exit Criteria:
│   └── Full website browsable with dynamic content, SEO validated

MILESTONE 4: TESTING + HARDENING                 [~22 hours]
├── Week 8
├── Deliverables:
│   ├── All API endpoints tested
│   ├── Security audit complete (injection, XSS, upload)
│   ├── Cross-browser testing done
│   ├── Performance optimized (caching, images, queries)
│   └── All critical bugs fixed
├── Exit Criteria:
│   └── Zero critical bugs, Lighthouse score > 80

MILESTONE 5: DEPLOYMENT + LAUNCH                 [~12 hours]
├── Week 9
├── Deliverables:
│   ├── Production server provisioned and configured
│   ├── Domain + SSL active
│   ├── Application deployed and tested live
│   ├── Backups configured
│   ├── Content populated by trust team
│   └── Google Search Console + Analytics set up
├── Exit Criteria:
│   └── Website live at srisaimission.org, admin accessible
```

### Critical Path

```
Items that BLOCK everything downstream — must be completed on time:

1. PHP MVC Core (Router + Controller + Model) ← Blocks ALL backend work
2. Database migrations ← Blocks ALL API development
3. JWT Authentication ← Blocks ALL admin panel work
4. File Upload Service ← Blocks Gallery, Blog images, Magazine PDFs
5. React Admin Scaffold ← Blocks ALL admin UI work
6. Template → PHP View Conversion ← Blocks ALL public pages
```

### Risk Points & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|-----------|
| Template CSS conflicts with dynamic content | Broken layouts | Medium | Test early with real content lengths; add CSS overrides file |
| Slider Revolution JS too tightly coupled | Hero section breaks | Medium | Replace with simpler Swiper-based slider if needed |
| Image upload performance with large files | Slow admin UX | Low | Client-side resize before upload; progress bar |
| MySQL full-text search insufficient | Poor search results | Low | Start with FULLTEXT; upgrade to Elasticsearch later if needed |
| JWT secret leaked | Full admin compromise | Low | Store in .env only; rotate periodically; monitor access logs |
| Content population delay | Empty website at launch | High | Pre-populate with PDF content + placeholder images during development |
| Browser compatibility (older devices) | Layout issues for some users | Medium | Test on actual mobile devices used by target audience |

---

## 11. ASSUMPTIONS & BEST PRACTICES

### 11.1 Assumptions

| # | Assumption |
|---|-----------|
| 1 | The website will be hosted on a Linux VPS (Ubuntu) with root access |
| 2 | Traffic is expected to be moderate (< 10,000 visits/month initially) |
| 3 | Content will be primarily in English with some Tamil text |
| 4 | The trust team has basic computer literacy for admin panel usage |
| 5 | Online donations will initially be manual recording; payment gateway integration is a future phase |
| 6 | The domain (srisaimission.org or similar) will be purchased separately |
| 7 | Email will use the trust's existing Gmail (srisaimission@gmail.com) via SMTP |
| 8 | The template's visual design is approved and will be faithfully reproduced |
| 9 | No mobile app is required in this phase but API is designed to support one |
| 10 | The website does not require multi-language support initially but database supports UTF-8 for Tamil text |

### 11.2 Coding Standards

**PHP:**
| Standard | Rule |
|----------|------|
| Naming | Classes: PascalCase (`BlogController`), Methods: camelCase (`getPublished`), Variables: camelCase, DB columns: snake_case |
| Files | One class per file, filename matches class name |
| Indentation | 4 spaces (no tabs) |
| Strings | Single quotes for plain strings, double quotes only for interpolation |
| Type hints | Use PHP type declarations on all method parameters and return types |
| Comments | PHPDoc blocks on all public methods; inline comments only for non-obvious logic |
| Error handling | Try-catch at controller level; custom exceptions for business logic |

**JavaScript/React:**
| Standard | Rule |
|----------|------|
| Naming | Components: PascalCase (`BlogList`), Functions: camelCase, Constants: UPPER_SNAKE |
| Files | One component per file, `.jsx` extension for React components |
| Formatting | Prettier with default config |
| Hooks | Prefer functional components with hooks over class components |
| Imports | Absolute imports grouped: React → third-party → local |
| State | Local state for UI, Context for auth/notifications only |

**SQL:**
| Standard | Rule |
|----------|------|
| Tables | Plural snake_case (`gallery_albums`) |
| Columns | Singular snake_case (`created_at`, `file_path`) |
| Primary keys | Always `id` (unsigned auto-increment) |
| Foreign keys | `{related_table_singular}_id` (e.g., `category_id`) |
| Timestamps | Every table has `created_at`, most have `updated_at` |
| Soft deletes | Use `status` column (archived) instead of actual deletion where appropriate |

### 11.3 Scalability & Future Extension Readiness

| Future Need | How Current Design Supports It |
|-------------|-------------------------------|
| **Mobile App** | REST API is decoupled — same API serves web admin and future mobile app |
| **Multi-language (Tamil/Hindi)** | Database uses utf8mb4; settings table can store translations; pages can have language variants |
| **Payment Gateway** | Donations table has `transaction_id`, `payment_method` fields ready for Razorpay/PayU integration |
| **Email Newsletters** | Contact messages + newsletter subscribers can be exported; Mailchimp integration via API |
| **Advanced Search** | FULLTEXT indexes ready; can upgrade to Elasticsearch without schema changes |
| **CDN** | Image paths are relative; easy to prefix with CDN URL via config |
| **Multi-site** | `settings` table is key-value — adding a `site_id` column enables multi-tenancy |
| **Activity Audit** | `activity_log` table captures all admin actions for compliance |
| **80G Receipt PDF** | Donations table has `receipt_number`, `donor_pan` — PDF generation is a pluggable service |

---

## APPENDIX A: Template File Inventory

### CSS Files (68 total)
Key files: `__styles.css`, `__custom.css`, `__plugins.css`, `style.css`, `style_1.css`
Responsive: `__responsive.css`, `__responsive_1.css`
Plugins: Swiper, Magnific Popup, Elementor, WooCommerce, Give (donation), Events Calendar

### JS Files (88 total)
Key files: `__scripts.js`, `skin.js`, `jquery.min.js`, `swiper.min.js`, `gsap.min.js`
To retain: jQuery, Swiper, GSAP, Magnific Popup, Superfish, Typed.js
To remove: WooCommerce, Give (donation plugin), WordPress-specific scripts

### Images (71 total)
Logos: `logo.png`, `logo-2.png`, `logo-2-retina.png`
Favicons: `cropped-favicon-32x32.jpg`, `cropped-favicon-192x192.jpg`, `cropped-favicon-180x180.jpg`
Content images: `image-*-copyright-*.jpg` (template demo — to be replaced with Sri Sai Mission photos)
UI: Loaders, grid tiles, icons, payment SVGs

---

## APPENDIX B: Contact Information for Integration

| Detail | Value |
|--------|-------|
| Trust Name | Sri Sai Mission Religious & Charitable Trust (Regd) 106/2014 |
| Address | 3E/A, 2nd Street, Buddhar Nagar, New Perungalathur, Chennai- 600 063 |
| Phone 1 | 9841203311 |
| Phone 2 | 9094033288 |
| Landline | 044-48589900 |
| Email | srisaimission@gmail.com |
| Temple 1 | Perungalathur Athma Sai Temple (Morning 7am-12:30pm, Evening 4-7:30pm) |
| Temple 2 | Keerapakkam Baba Temple (Palki Procession Sunday 4pm) |
| Pooja Times | 8:00 AM, 12:00 Noon, 6:00 PM |
| Tax Exemption | Section 80G of Income Tax Act |
| Registration | Section 10(23c)(iv) of Income Tax Act 1961 |

---

---

## APPENDIX C: PROJECT FOLDER STRUCTURE

```
C:\SriSai\                          ← Git repository root
│
├── app/                            ── BACKEND (PHP MVC)
│   ├── Controllers/
│   │   ├── Api/                    ── REST API controllers (JSON responses)
│   │   │   ├── AuthController.php
│   │   │   ├── BlogController.php
│   │   │   ├── DeliveryZoneController.php
│   │   │   ├── EventController.php
│   │   │   ├── GalleryController.php
│   │   │   ├── MagazineController.php
│   │   │   ├── PageController.php
│   │   │   ├── PoojaBookingController.php
│   │   │   ├── PoojaTypeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── SettingController.php
│   │   │   ├── ShopEnquiryController.php
│   │   │   ├── ShopOrderController.php
│   │   │   ├── TempleTimingController.php
│   │   │   ├── TourApiController.php
│   │   │   ├── TourBookingController.php
│   │   │   ├── TranslateController.php  ← LibreTranslate proxy
│   │   │   └── TrusteeController.php
│   │   └── Web/                    ── Public website controllers (HTML responses)
│   │       ├── BlogController.php
│   │       ├── ContactController.php
│   │       ├── DonationController.php
│   │       ├── EventController.php
│   │       ├── GalleryController.php
│   │       ├── HomeController.php
│   │       ├── MagazineController.php
│   │       ├── PageController.php      ← Dynamic DB-driven pages (catch-all)
│   │       ├── PaymentController.php   ← Donation Razorpay
│   │       ├── ShopController.php      ← Shop + Razorpay + Map delivery
│   │       ├── StaticPageController.php← Hardcoded pages (About, Mission, Vision)
│   │       ├── TourController.php
│   │       ├── TrusteeController.php
│   │       └── UserAuthController.php
│   ├── Core/                       ── MVC framework core
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Model.php               ← QueryBuilder base
│   │   ├── Middleware.php
│   │   └── Router.php
│   ├── Helpers/
│   │   ├── EnvLoader.php
│   │   ├── Lang.php                ← Language switcher (EN/TA session)
│   │   └── Response.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php      ← Admin JWT auth
│   │   ├── PublicAuthMiddleware.php← Public user session auth
│   │   └── RoleMiddleware.php
│   ├── Models/                     ── Database models (one per table)
│   │   ├── Blog.php
│   │   ├── DeliveryZone.php        ← Haversine distance zones
│   │   ├── Event.php
│   │   ├── GalleryAlbum.php
│   │   ├── GalleryImage.php
│   │   ├── Magazine.php
│   │   ├── Page.php
│   │   ├── PoojaBooking.php
│   │   ├── PoojaType.php
│   │   ├── Product.php
│   │   ├── PublicUser.php
│   │   ├── Setting.php
│   │   ├── ShopEnquiry.php
│   │   ├── ShopOrder.php
│   │   ├── TempleTiming.php
│   │   ├── Tour.php
│   │   ├── TourBooking.php
│   │   └── Trustee.php
│   └── Services/
│       └── ActivityLogger.php
│
├── database/
│   └── migrations/                 ── SQL migration files (run once in order)
│       ├── 001_initial_schema.sql
│       ├── 002_seed_data.sql
│       ├── 003_*.sql
│       ├── 004_shop_tours_users.sql
│       ├── 005_seed_shop_tours.sql
│       ├── 006_performance_indexes.sql
│       ├── 007_add_tamil_columns.sql   ← Tamil (_ta) columns for all tables
│       ├── 008_shop_orders_delivery.sql
│       ├── 009_map_delivery.sql        ← OpenStreetMap lat/lng delivery zones
│       └── 010_pooja_booking_payment.sql
│
├── frontend-admin/                 ── ADMIN PANEL (React.js SPA)
│   ├── src/
│   │   ├── api/
│   │   │   └── client.js           ← Axios instance (JWT interceptor)
│   │   ├── components/
│   │   │   ├── Modal.jsx
│   │   │   ├── StatusBadge.jsx
│   │   │   └── TranslateButton.jsx ← Auto EN→TA via LibreTranslate
│   │   ├── contexts/
│   │   │   ├── AuthContext.jsx
│   │   │   └── LangContext.jsx     ← Admin EN/TA language toggle
│   │   ├── lang/
│   │   │   ├── en.json             ← Admin panel English strings
│   │   │   └── ta.json             ← Admin panel Tamil strings
│   │   ├── layouts/
│   │   │   └── AdminLayout.jsx     ← Sidebar + top bar
│   │   └── pages/
│   │       ├── auth/LoginPage.jsx
│   │       ├── blogs/              ← BlogList + BlogForm
│   │       ├── contacts/
│   │       ├── dashboard/
│   │       ├── donations/
│   │       ├── events/             ← EventList + EventForm
│   │       ├── gallery/            ← GalleryList + GalleryForm
│   │       ├── magazines/          ← MagazineList + MagazineForm
│   │       ├── media/
│   │       ├── settings/
│   │       ├── shop/               ← ProductList, DeliveryZonePage, ShopOrdersPage,
│   │       │                          ShopSettingsPage (map picker), PoojaTypes
│   │       ├── timings/            ← TimingList + TimingForm
│   │       ├── tours/              ← TourList + TourBookingList
│   │       └── trustees/           ← TrusteeList + TrusteeForm
│   ├── package.json
│   └── vite.config.js
│
├── lang/                           ── PUBLIC SITE TRANSLATIONS
│   ├── en.php                      ← English strings for all public pages
│   └── ta.php                      ← Tamil strings for all public pages
│
├── public/                         ── WEB ROOT (Apache document root)
│   ├── admin/                      ← Built React SPA (deployed here, git-ignored)
│   ├── assets/
│   │   ├── css/
│   │   │   └── srisai-custom.css   ← Main custom stylesheet
│   │   ├── images/
│   │   └── js/
│   ├── storage → ../storage/       ← Symlink to uploads
│   └── index.php                   ← Front controller (bootstrap + router)
│
├── routes/
│   ├── api.php                     ── REST API routes (/api/v1/*)
│   └── web.php                     ── Public website routes
│
├── storage/
│   ├── uploads/                    ← User-uploaded files (git-ignored)
│   ├── cache/                      ← App cache (git-ignored)
│   └── logs/                       ← Error logs (git-ignored)
│
└── views/                          ── PUBLIC SITE TEMPLATES (PHP)
    ├── auth/                       ← Login/Register pages
    ├── blog/                       ← index.php + show.php
    ├── contact/
    ├── donations/
    ├── errors/                     ← 404, 500 pages
    ├── events/                     ← index.php + show.php
    ├── gallery/                    ← index.php + show.php
    ├── home/                       ← index.php (main landing page)
    ├── layouts/
    │   └── master.php              ← Main HTML wrapper layout
    ├── magazine/                   ← index.php + show.php
    ├── pages/
    │   ├── about.php               ← Static hardcoded (EN + TA sections)
    │   ├── mission.php             ← Static hardcoded (EN + TA sections)
    │   ├── show.php                ← Dynamic DB-driven page renderer
    │   └── vision.php              ← Static hardcoded (EN + TA sections)
    ├── partials/
    │   ├── footer.php
    │   ├── head.php
    │   └── header.php
    ├── shop/                       ← index.php + show.php + pooja-booking.php
    ├── tours/                      ← index.php + show.php
    └── trustees/
```

---

## APPENDIX D: COMPLETED FEATURES LOG

### Phase 8.5 — Bilingual (EN/TA) System ✅
- **Public lang files:** `lang/en.php` + `lang/ta.php` — translations for all pages
- **Helper:** `__('key')` for static text, `langField($obj, 'field')` for DB content
- **Session:** `?lang=ta` sets session, toggle in header persists choice
- **DB Tamil columns:** All content tables have `_ta` suffix fields (migration 007)
- **Admin forms:** All 8 CRUD forms have "Tamil Content" section + Auto-Translate button
- **Auto-translate:** `TranslateButton.jsx` → `POST /api/v1/translate` → LibreTranslate proxy

### Phase 8.6 — Static Pages System ✅
- **About Us** (`/about-us`, `/about`) → `views/pages/about.php`
- **Mission** (`/mission`) → `views/pages/mission.php`
- **Vision** (`/vision`) → `views/pages/vision.php`
- Pages removed from admin panel — content edited directly in PHP files
- Each file has clearly marked `<!-- ENGLISH CONTENT -->` and `<!-- TAMIL CONTENT -->` blocks

### Phase 8.7 — Shop + Razorpay + OpenStreetMap Delivery ✅
- **Payment:** Razorpay checkout (same flow as tour bookings)
- **Map delivery:** Leaflet.js (OpenStreetMap tiles) on product page
  - Address search via Nominatim geocoding API (no API key required)
  - Click/drag pin to set delivery location
  - Server-side Haversine formula calculates straight-line distance
- **Distance zones:** Admin-controlled slabs (e.g. 0–5 km = Free, 5–10 km = ₹50)
- **Shop dispatch origin:** Admin-configurable via map picker (Admin → Shop Location)
- **DB:** `shop_orders` table with `lat`, `lng`, `distance_km` columns
- **Admin:** Delivery Zones CRUD + Shop Orders list + Shop Location map picker

### Map/Location Services Summary
| Service | Provider | API Key Required |
|---------|----------|-----------------|
| Map tiles | OpenStreetMap (Leaflet.js) | No |
| Address search | Nominatim geocoding | No |
| Distance calc | Haversine formula (PHP, server-side) | No |
| Payment | Razorpay | Yes (in .env) |
| Auto-translate | LibreTranslate | Optional (in .env) |

---

**END OF MASTER PLAN**

*This document serves as the single source of truth for the Sri Sai Mission website project. Last updated: 2026-02-20. All development work should reference this plan.*
