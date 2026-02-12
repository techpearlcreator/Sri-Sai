# SRI SAI MISSION — PROJECT PHASE PLAN & TRACKER
## Dynamic Website Development

**Last Updated:** 2026-02-09
**Environment:** WampServer (Windows 10/11 + Apache + MySQL 8 + PHP 8.2)
**Status:** Phase 0 — Planning Complete, Ready to Begin

---

## HIGH-LEVEL SUMMARY

### What We Are Building
A fully dynamic, admin-controlled website for **Sri Sai Mission Religious & Charitable Trust** (Regd 106/2014, Chennai), converting a static WordPress "Gita" theme template into a production-ready system.

### Tech Stack (Finalized)
| Layer | Technology | Notes |
|-------|-----------|-------|
| **Local Dev Server** | WampServer | Apache + MySQL + PHP bundled on Windows |
| **Backend** | Custom PHP 8.2 MVC | Laravel-inspired, no framework dependency |
| **Database** | MySQL 8.0 | Via WampServer's built-in MySQL |
| **Admin Panel** | React.js (Vite) | SPA at `/admin`, communicates via REST API |
| **Public Website** | Server-rendered PHP views | Uses existing Gita template CSS/JS |
| **API** | REST JSON (`/api/v1/`) | JWT auth for admin panel |
| **DB Admin** | phpMyAdmin | Bundled with WampServer |

### Key Numbers
| Metric | Count |
|--------|-------|
| Database Tables | 15 |
| API Endpoints | 50+ |
| Admin Modules | 12 (Dashboard, Blogs, Magazine, Gallery, Events, Pages, Trustees, Donations, Contacts, Media, Settings, Users) |
| Public Pages | 15+ routes |
| React Components | ~40 |

### WampServer Working Paths
```
WampServer Root:     C:\wamp64\
Apache Web Root:     C:\wamp64\www\
Project Location:    C:\wamp64\www\srisai\           ← Backend (PHP)
Admin Panel Source:  C:\SriSai\frontend-admin\       ← React source code
Admin Panel Build:   C:\wamp64\www\srisai\public\admin\  ← React build output
phpMyAdmin:          http://localhost/phpmyadmin
Local Site:          http://localhost/srisai/
Local API:           http://localhost/srisai/api/v1/
Local Admin:         http://localhost/srisai/admin/
```

---

## PROJECT PHASE PLAN

---

### PHASE 0: PLANNING & APPROVAL
> **Status: COMPLETE**

| Attribute | Detail |
|-----------|--------|
| **Objective** | Define full system architecture, database design, API spec, and project roadmap |
| **Deliverables** | MASTER_PLAN.md (complete), PROJECT_TRACKER.md (this document) |
| **Dependencies** | Template files, Trust content PDF |
| **Duration** | Completed |

**Checkpoint:** Master plan reviewed and approved before any coding begins.

---

### PHASE 1: ENVIRONMENT SETUP & PROJECT SCAFFOLD
> **Objective:** Get WampServer configured, database created, project folder structure ready, Git initialized. End state: PHP "Hello World" served at localhost, empty database with all tables, React admin shell loads at /admin.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | WampServer configured (Apache vhost, PHP extensions, URL rewriting) |
| | MySQL database `srisai_db` created with all 15 tables |
| | Seed data loaded (roles, default admin, trustees, pages, settings) |
| | PHP backend folder structure created |
| | React admin project initialized (Vite + React Router) |
| | Git repository initialized with .gitignore |
| | .env.example created |
| **Dependencies** | WampServer installed (confirmed), MASTER_PLAN.md approved |
| **Estimated Duration** | 1-2 days |
| **Critical Tasks** | Apache mod_rewrite enabled, PHP extensions (pdo_mysql, gd, fileinfo, mbstring, openssl) |

**Checkpoint:** Visit `http://localhost/srisai/` and see PHP response. Visit `http://localhost/phpmyadmin` and see all 15 tables populated with seed data.

---

### PHASE 2: PHP MVC FRAMEWORK CORE
> **Objective:** Build the foundational backend framework — routing, controllers, models, middleware, request/response handling. This is the "engine" that powers everything else.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | Front controller (`public/index.php`) with .htaccess URL rewriting |
| | Router class (maps URLs → Controllers) |
| | Base Controller class (JSON + View responses) |
| | Base Model class (PDO query builder — CRUD, pagination, search) |
| | Database connection singleton (reads from .env) |
| | Request class (input sanitization, validation) |
| | Response helper (JSON success/error format) |
| | .env file parser |
| | Autoloader (PSR-4 style) |
| **Dependencies** | Phase 1 complete |
| **Estimated Duration** | 3-4 days |
| **Critical Risk** | This phase is the FOUNDATION — if the router/model layer is flawed, everything built on top breaks |

**Checkpoint:** Define a test route `/api/v1/test` that queries MySQL and returns JSON. If this works end-to-end, the framework core is solid.

---

### PHASE 3: AUTHENTICATION & SECURITY LAYER
> **Objective:** Implement JWT-based admin login, role/permission middleware, CORS handling, and all security foundations.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | AuthController (login, logout, me, change password) |
| | JWT token generation and validation (HS256) |
| | AuthMiddleware (validate token on every /api/ request) |
| | RoleMiddleware (check permissions per endpoint) |
| | CorsMiddleware (allow React dev server origin) |
| | Password hashing (bcrypt via `password_hash()`) |
| | Login throttling (5 attempts / 15 min) |
| | Activity logging on login events |
| **Dependencies** | Phase 2 complete (Router + Model working) |
| **Estimated Duration** | 2-3 days |
| **Critical Risk** | JWT secret must be in .env, never hardcoded |

**Checkpoint:** Use Postman to POST `/api/v1/auth/login` with admin credentials → receive JWT. Use token to GET `/api/v1/auth/me` → receive user object. Try without token → receive 401.

---

### PHASE 4: FILE UPLOAD & MEDIA SERVICE
> **Objective:** Build reusable image upload, validation, thumbnail generation, and storage system.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | ImageService (upload, validate MIME, resize, thumbnail, delete) |
| | FileHelper (unique filename generation, path management) |
| | MediaController API (upload, list, delete) |
| | Storage directory structure creation (`storage/uploads/{module}/`) |
| | GD library thumbnail generation (300x300 center-crop) |
| | Upload validation pipeline (type, size, PHP injection check) |
| **Dependencies** | Phase 2 complete, PHP GD extension enabled in WampServer |
| **Estimated Duration** | 2 days |

**Checkpoint:** POST a JPG to `/api/v1/media/upload` → file saved in `/storage/uploads/general/`, thumbnail generated, record inserted in `media` table, JSON response returned.

---

### PHASE 5: BACKEND API — ALL CRUD MODULES
> **Objective:** Build complete REST API for all 12 content modules. This is the largest backend phase.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | **5A — Categories API** (CRUD per type) |
| | **5B — Blogs API** (CRUD + search + bulk actions + pagination) |
| | **5C — Magazines API** (CRUD + PDF upload + issue fields) |
| | **5D — Gallery API** (Albums CRUD + Image upload/reorder/delete) |
| | **5E — Events API** (CRUD + recurring events + auto-status) |
| | **5F — Pages API** (CRUD + parent-child + menu position) |
| | **5G — Trustees API** (CRUD + reorder + toggle active) |
| | **5H — Donations API** (CRUD + CSV export + summary stats) |
| | **5I — Contact Messages API** (CRUD + status management + bulk) |
| | **5J — Settings API** (read/write site settings batch) |
| | **5K — Temple Timings API** (CRUD for schedule) |
| | **5L — Dashboard Stats API** (aggregate counts + activity log) |
| | **5M — SEO Meta API** (read/write per entity) |
| | SlugService (auto-generate SEO slugs from titles) |
| | PaginationService (standardized meta format) |
| **Dependencies** | Phase 2 + 3 + 4 complete |
| **Estimated Duration** | 8-10 days |
| **Build Order** | Categories → Blogs → Magazines → Gallery → Events → Pages → Trustees → Donations → Contacts → Settings → Dashboard |

**Checkpoint per sub-module:** Full Postman test — Create, Read (list + single), Update, Delete for each module returns correct JSON.

**Phase 5 Major Checkpoint:** ALL 50+ API endpoints tested and working. Export Postman collection as backup.

---

### PHASE 6: REACT ADMIN PANEL — SHELL & AUTH
> **Objective:** Set up React project, implement login flow, admin layout (sidebar + topbar), and protected routing.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | Vite + React 18 + React Router v6 project scaffold |
| | Axios API client with JWT interceptor |
| | AuthContext (login, logout, token storage, permission check) |
| | LoginPage (email + password form) |
| | Admin Layout (Sidebar with all module links, TopBar with user info + logout) |
| | ProtectedRoute component (redirect to login if not authenticated) |
| | NotificationContext (toast messages for success/error) |
| | Basic CSS/styling framework choice (Tailwind CSS or Bootstrap 5) |
| **Dependencies** | Phase 3 complete (Auth API working) |
| **Estimated Duration** | 3-4 days |

**Checkpoint:** Open `http://localhost:5173` (Vite dev) → see login page → log in → see admin dashboard layout with sidebar and working navigation. Refresh page → stay logged in (token persisted).

---

### PHASE 7: REACT ADMIN PANEL — CRUD MODULES
> **Objective:** Build admin UI for all content modules. This is the largest frontend phase.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | **Common components first:** DataTable, Pagination, Modal, ConfirmDialog, ImageUploader, RichTextEditor (TinyMCE/Quill), SearchBar, StatusBadge, LoadingSpinner |
| | **7A — Dashboard Page** (stat widgets, activity feed, quick links) |
| | **7B — Blog Management** (list + create/edit form + image upload + SEO) |
| | **7C — Magazine Management** (list + create/edit + PDF upload + SEO) |
| | **7D — Gallery Management** (album list/form + image manager with drag-drop reorder) |
| | **7E — Events Management** (list + create/edit + recurring toggle) |
| | **7F — Pages Management** (list + create/edit + parent/child + SEO) |
| | **7G — Trustees Management** (list + create/edit + reorder + toggle active) |
| | **7H — Donations Management** (list + detail view + CSV export) |
| | **7I — Contact Messages** (list + view + status update + admin notes) |
| | **7J — Media Library** (grid view + upload + delete + copy URL) |
| | **7K — Settings** (general, contact, social, temple timings, SEO defaults) |
| | **7L — User Management** (Super Admin only — add/edit/deactivate users) |
| **Dependencies** | Phase 5 complete (all APIs), Phase 6 complete (admin shell) |
| **Estimated Duration** | 10-14 days |
| **Build Order** | Common components → Dashboard → Blogs → Magazines → Gallery → Events → Pages → Trustees → Donations → Contacts → Media → Settings → Users |

**Checkpoint per module:** Complete CRUD cycle in browser — create item, see it in list, edit it, delete it. Verify data matches in phpMyAdmin.

**Phase 7 Major Checkpoint:** All 12 admin modules fully functional. Build React for production (`npm run build`), copy to `public/admin/`, verify it works at `http://localhost/srisai/admin/`.

---

### PHASE 8: PUBLIC WEBSITE — TEMPLATE CONVERSION
> **Objective:** Convert the static HTML template into dynamic PHP views that pull content from the database.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | **8A — Template Decomposition** (split index.html → master layout + header + footer + partials) |
| | **8B — Copy template assets** (CSS, JS, fonts, images → `public/assets/`) |
| | **8C — Public routing** (web.php — all SEO-friendly URLs) |
| | **8D — Homepage** (dynamic: slider, services, about, stats, donations, trustees, timings, events, blog, contact, footer) |
| | **8E — Blog pages** (listing with pagination + single post with full content) |
| | **8F — Magazine pages** (listing + single article + PDF download link) |
| | **8G — Gallery pages** (album grid + album detail with Magnific Popup lightbox) |
| | **8H — Events pages** (upcoming list + single event detail) |
| | **8I — Dynamic CMS pages** (About, Mission, Vision, Tourism, etc. via slug) |
| | **8J — Trustees page** (Main + Co-opted sections with photos) |
| | **8K — Donations page** (info + 80G details) |
| | **8L — Contact page** (form + AJAX submission + validation) |
| | **8M — Error pages** (404, 500 styled with template design) |
| **Dependencies** | Phase 5 complete (data available via models), Template files at `C:\Sai-site\` |
| **Estimated Duration** | 7-10 days |
| **Critical Risk** | Template CSS conflicts — test with real-length content early |

**Phase 8 Major Checkpoint:** Browse every public page — homepage loads all 16 sections dynamically, all internal links work, contact form submits, gallery lightbox opens, mobile responsive verified.

---

### PHASE 9: SEO & POLISH
> **Objective:** Implement all SEO features and final UI polish.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | Dynamic `<title>` and `<meta description>` on every page |
| | Open Graph tags (og:title, og:description, og:image) |
| | Auto-generated `sitemap.xml` (lists all published content URLs) |
| | `robots.txt` (allow public, block /admin, /api, /storage) |
| | JSON-LD structured data (Organization, Article, Event schemas) |
| | Canonical URLs on every page |
| | Image alt text rendering |
| | Lazy loading verification |
| | 301 redirects if needed |
| | Favicon (trust logo) |
| **Dependencies** | Phase 8 complete |
| **Estimated Duration** | 2-3 days |

**Checkpoint:** Run Google Lighthouse on homepage → Score 80+ on SEO. Validate sitemap.xml lists all URLs. Validate robots.txt blocks admin paths.

---

### PHASE 10: TESTING & SECURITY HARDENING
> **Objective:** Comprehensive testing of all systems. Fix all bugs. Harden security.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | **API Testing** — Test all 50+ endpoints with Postman (happy path + error cases) |
| | **Admin Testing** — Full CRUD cycle for every module in browser |
| | **Public Testing** — Every page, every link, every form |
| | **Security Testing** — SQL injection attempts, XSS attempts, file upload exploits, CSRF |
| | **Cross-browser Testing** — Chrome, Firefox, Edge, mobile Chrome/Safari |
| | **Responsive Testing** — All breakpoints (1679, 1439, 1279, 1023, 767px) |
| | **Performance Testing** — Page load times, image sizes, query performance |
| | **Content Population** — Enter real trust content (at least core pages + some blogs) |
| | **Bug Fix Log** — Track and resolve all issues found |
| **Dependencies** | Phase 7 + 8 + 9 complete |
| **Estimated Duration** | 4-5 days |

**Phase 10 GATE:** No critical bugs. All pages load. All CRUD works. No security vulnerabilities. Only after this gate → proceed to deployment.

---

### PHASE 11: PRODUCTION DEPLOYMENT
> **Objective:** Deploy to live server, configure domain, SSL, backups.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | Provision Linux VPS (Ubuntu + Nginx + PHP-FPM + MySQL) |
| | Configure domain DNS (srisaimission.org → server IP) |
| | SSL certificate (Let's Encrypt) |
| | Clone repository, install dependencies |
| | Run migrations + seed on production DB |
| | Build React admin, copy to public/admin/ |
| | Configure Nginx server blocks |
| | Set file permissions |
| | Configure automated backups (daily DB, weekly files) |
| | Smoke test all features on live site |
| | Configure production .env (debug=false, real SMTP) |
| **Dependencies** | Phase 10 gate passed, Domain purchased, VPS provisioned |
| **Estimated Duration** | 2-3 days |

**Checkpoint:** `https://www.srisaimission.org` loads correctly. Admin login works. All pages functional. SSL green lock visible.

---

### PHASE 12: POST-LAUNCH
> **Objective:** Content population, admin training, monitoring, and iteration.

| Attribute | Detail |
|-----------|--------|
| **Key Deliverables** | |
| | Full content population (all pages, blogs, gallery photos, events) |
| | Admin training for trust team members |
| | Google Search Console — submit sitemap, verify indexing |
| | Google Analytics setup |
| | Monitor error logs for first 2 weeks |
| | Fix any post-launch bugs |
| | Performance optimization based on real-world usage |
| | Documentation for admin users (PDF guide) |
| **Dependencies** | Phase 11 complete |
| **Estimated Duration** | Ongoing (first 2 weeks intensive) |

---

## PROJECT TRACKER

### How to Use This Tracker
1. Update the **Status** column as you work: `Not Started` → `In Progress` → `Completed` (or `Blocked`)
2. Fill in **Start Date** when you begin a task
3. Fill in **End Date** when you complete it
4. Use **Notes/Blockers** to record issues, decisions, or blockers
5. Tasks marked with **[CRITICAL]** are on the critical path — delays here delay everything

### Status Legend
| Status | Meaning |
|--------|---------|
| `Not Started` | Work has not begun |
| `In Progress` | Currently being worked on |
| `Completed` | Done and verified |
| `Blocked` | Cannot proceed — see Notes column |
| `CHECKPOINT` | Verification gate — must pass before next phase |

---

### PHASE 0: PLANNING & APPROVAL

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 0.1 | Analyze template structure (HTML sections, CSS, JS) | Completed | 2026-02-09 | 2026-02-09 | 68 CSS, 88 JS, 71 images, 16 sections mapped |
| 0.2 | Analyze trust content (PDF data extraction) | Completed | 2026-02-09 | 2026-02-09 | 14 trustees, 8 pages, all contact info extracted |
| 0.3 | Create MASTER_PLAN.md (full technical blueprint) | Completed | 2026-02-09 | 2026-02-09 | 11 sections, 15 DB tables, 50+ API endpoints |
| 0.4 | Create PROJECT_TRACKER.md (this document) | Completed | 2026-02-09 | 2026-02-09 | |
| 0.5 | **CHECKPOINT: Master plan approved** | Completed | 2026-02-09 | 2026-02-09 | Approved — ready to build |

---

### PHASE 1: ENVIRONMENT SETUP & PROJECT SCAFFOLD

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 1.1 | [CRITICAL] Verify WampServer running (Apache + MySQL + PHP) | Not Started | | | Test: http://localhost shows Wamp homepage |
| 1.2 | Enable required PHP extensions (pdo_mysql, gd, fileinfo, mbstring, openssl) | Not Started | | | Via WampServer tray → PHP → PHP Extensions |
| 1.3 | Enable Apache mod_rewrite | Not Started | | | Via WampServer tray → Apache → Modules → rewrite_module |
| 1.4 | Create project directory `C:\wamp64\www\srisai\` with full folder structure | Not Started | | | As per MASTER_PLAN.md Section 2.5 |
| 1.5 | [CRITICAL] Create MySQL database `srisai_db` via phpMyAdmin | Not Started | | | http://localhost/phpmyadmin |
| 1.6 | Run all 15 table creation SQL scripts | Not Started | | | Execute migration SQL from MASTER_PLAN.md Section 3.2 |
| 1.7 | Run seed data (roles, admin user, trustees, pages, settings) | Not Started | | | From MASTER_PLAN.md Section 3.3 |
| 1.8 | Create .env file with local credentials | Not Started | | | DB: localhost, user: root, pass: (wamp default empty) |
| 1.9 | Initialize Git repository + .gitignore | Not Started | | | Exclude: .env, storage/uploads/*, node_modules/ |
| 1.10 | Initialize React admin project (`C:\SriSai\frontend-admin\`) | Not Started | | | `npm create vite@latest frontend-admin -- --template react` |
| 1.11 | Create Apache virtual host for `srisai.local` (optional) | Not Started | | | Alternative: use `http://localhost/srisai/` directly |
| 1.12 | **CHECKPOINT: PHP serves response at localhost, DB has all tables** | Not Started | | | Test: visit URL + check phpMyAdmin |

---

### PHASE 2: PHP MVC FRAMEWORK CORE

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 2.1 | [CRITICAL] Create `public/index.php` (front controller) | Not Started | | | All requests funnel through this file |
| 2.2 | [CRITICAL] Create `public/.htaccess` (Apache URL rewriting) | Not Started | | | RewriteEngine On → route to index.php |
| 2.3 | Build .env parser utility | Not Started | | | Read config from .env file safely |
| 2.4 | Build PSR-4 style autoloader (`app/autoload.php`) | Not Started | | | Auto-load classes from app/ directory |
| 2.5 | [CRITICAL] Build Router class | Not Started | | | Map GET/POST/PUT/DELETE → Controller@method |
| 2.6 | [CRITICAL] Build base Controller class | Not Started | | | json(), view(), validate() methods |
| 2.7 | Build Database connection class (PDO singleton) | Not Started | | | Read from .env, utf8mb4, error mode exception |
| 2.8 | [CRITICAL] Build base Model class (query builder) | Not Started | | | find(), all(), create(), update(), delete(), paginate(), where() |
| 2.9 | Build Request class (input handling + sanitization) | Not Started | | | get(), post(), file(), sanitize() |
| 2.10 | Build Response helper (JSON format standardization) | Not Started | | | success(), error(), paginated() formats |
| 2.11 | Build Validator class | Not Started | | | required, string, email, max, min, enum, exists rules |
| 2.12 | Define routes file structure (`routes/web.php`, `routes/api.php`) | Not Started | | | Separate public vs API routes |
| 2.13 | **CHECKPOINT: Test route `/api/v1/test` returns JSON from MySQL** | Not Started | | | End-to-end: URL → Router → Controller → Model → DB → JSON |

---

### PHASE 3: AUTHENTICATION & SECURITY

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 3.1 | Install JWT library (firebase/php-jwt via Composer or manual) | Not Started | | | Composer preferred: `composer require firebase/php-jwt` |
| 3.2 | Build AuthService (generateToken, validateToken, hashPassword) | Not Started | | | HS256, 1-hour expiry, bcrypt cost 12 |
| 3.3 | [CRITICAL] Build AuthController (login, logout, me, changePassword) | Not Started | | | POST /api/v1/auth/login → return JWT |
| 3.4 | [CRITICAL] Build AuthMiddleware (JWT validation on all /api/ routes) | Not Started | | | Extract Bearer token, decode, load user |
| 3.5 | Build RoleMiddleware (permission check per endpoint) | Not Started | | | Check role.permissions JSON against required action |
| 3.6 | Build CorsMiddleware (allow React dev server origin) | Not Started | | | Allow localhost:5173 during dev, production domain later |
| 3.7 | Implement login throttling (rate limiting) | Not Started | | | 5 failed attempts / 15 min per IP |
| 3.8 | Build ActivityLog helper (log login, CRUD actions) | Not Started | | | Insert into activity_log table |
| 3.9 | **CHECKPOINT: Postman login → JWT → auth/me → user data** | Not Started | | | Test: valid login, invalid login, expired token, missing token |

---

### PHASE 4: FILE UPLOAD & MEDIA SERVICE

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 4.1 | Verify PHP GD extension is enabled in WampServer | Not Started | | | phpinfo() should show GD section |
| 4.2 | Build ImageService (upload, validate, resize, thumbnail, delete) | Not Started | | | Center-crop 300x300 thumbs, validate MIME via finfo |
| 4.3 | Build FileHelper (unique filename generator, path builder) | Not Started | | | Format: {module}_{timestamp}_{random16}.{ext} |
| 4.4 | Create storage directories (uploads/blogs, magazines, gallery, etc.) | Not Started | | | Ensure Apache has write permission |
| 4.5 | Build MediaApiController (upload, list, delete endpoints) | Not Started | | | POST /api/v1/media/upload |
| 4.6 | Build Media model | Not Started | | | |
| 4.7 | **CHECKPOINT: Upload image via Postman → saved + thumbnail generated** | Not Started | | | Verify: file on disk, thumbnail on disk, record in DB |

---

### PHASE 5: BACKEND API — ALL CRUD MODULES

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 5A | Build Categories API (CRUD, filter by type) | Not Started | | | Foundation for blogs, magazines, gallery, events |
| 5B.1 | Build Blog Model | Not Started | | | With relationships: category, author, seo_meta |
| 5B.2 | Build BlogApiController (list, show, create, update, delete, bulk) | Not Started | | | Include search, pagination, status filter |
| 5B.3 | Test Blog API endpoints (Postman) | Not Started | | | |
| 5C.1 | Build Magazine Model | Not Started | | | Extra: issue_number, issue_date, pdf_file |
| 5C.2 | Build MagazineApiController (CRUD + PDF upload) | Not Started | | | |
| 5C.3 | Test Magazine API endpoints | Not Started | | | |
| 5D.1 | Build GalleryAlbum + GalleryImage Models | Not Started | | | Album has many images, cascade delete |
| 5D.2 | Build GalleryApiController (album CRUD + image upload/reorder/delete) | Not Started | | | Multi-image upload support |
| 5D.3 | Test Gallery API endpoints | Not Started | | | |
| 5E.1 | Build Event Model | Not Started | | | Recurring events, auto-status update |
| 5E.2 | Build EventApiController (CRUD) | Not Started | | | |
| 5E.3 | Test Event API endpoints | Not Started | | | |
| 5F.1 | Build Page Model | Not Started | | | Parent-child, menu position, template |
| 5F.2 | Build PageApiController (CRUD) | Not Started | | | |
| 5F.3 | Test Page API endpoints | Not Started | | | |
| 5G.1 | Build Trustee Model | Not Started | | | Sort order, type filter |
| 5G.2 | Build TrusteeApiController (CRUD + reorder) | Not Started | | | |
| 5G.3 | Test Trustee API endpoints | Not Started | | | |
| 5H.1 | Build Donation Model | Not Started | | | Summary stats, CSV export |
| 5H.2 | Build DonationApiController (CRUD + export + summary) | Not Started | | | |
| 5H.3 | Test Donation API endpoints | Not Started | | | |
| 5I.1 | Build ContactMessage Model | Not Started | | | Status management |
| 5I.2 | Build ContactApiController (CRUD + bulk status) | Not Started | | | |
| 5I.3 | Test Contact API endpoints | Not Started | | | |
| 5J.1 | Build Setting Model | Not Started | | | Group-based, key-value |
| 5J.2 | Build SettingsApiController (read all, update batch) | Not Started | | | |
| 5J.3 | Build TempleTimings API | Not Started | | | |
| 5J.4 | Test Settings + Timings API | Not Started | | | |
| 5K | Build DashboardApiController (stats + activity log) | Not Started | | | Aggregate counts from all modules |
| 5L | Build SeoMeta API (read/write per entity) | Not Started | | | |
| 5M | Build SlugService (auto-generate URL slugs) | Not Started | | | Handle duplicates: append -1, -2, etc. |
| 5N | **CHECKPOINT: ALL 50+ API endpoints tested via Postman** | Not Started | | | Export Postman collection as backup |

---

### PHASE 6: REACT ADMIN — SHELL & AUTH

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 6.1 | Set up Vite + React 18 + React Router v6 | Not Started | | | `npm create vite@latest` |
| 6.2 | Install dependencies (axios, react-router-dom) | Not Started | | | |
| 6.3 | Choose and install CSS framework (Tailwind CSS or Bootstrap 5) | Not Started | | | |
| 6.4 | [CRITICAL] Build API client (Axios instance with JWT interceptor) | Not Started | | | Base URL: http://localhost/srisai/api/v1 |
| 6.5 | [CRITICAL] Build AuthContext (login, logout, token, permissions) | Not Started | | | Persist token in localStorage |
| 6.6 | Build LoginPage component | Not Started | | | Email + Password form, error display |
| 6.7 | Build ProtectedRoute component | Not Started | | | Redirect to /admin/login if not authenticated |
| 6.8 | Build Admin Layout (Sidebar + TopBar) | Not Started | | | Sidebar: all module links with icons |
| 6.9 | Build NotificationContext (toast messages) | Not Started | | | Success (green), Error (red), auto-dismiss |
| 6.10 | Set up all admin routes (AppRoutes.jsx) | Not Started | | | 25+ routes from MASTER_PLAN.md Section 6.3 |
| 6.11 | **CHECKPOINT: Login works, sidebar visible, routes navigate** | Not Started | | | Test: login → dashboard → navigate sidebar → logout |

---

### PHASE 7: REACT ADMIN — CRUD MODULES

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 7.0a | Build DataTable component (sortable, pagination, selection) | Not Started | | | Reused by ALL list pages |
| 7.0b | Build Pagination component | Not Started | | | |
| 7.0c | Build Modal + ConfirmDialog components | Not Started | | | "Are you sure you want to delete?" |
| 7.0d | Build ImageUploader component (drag-drop, preview) | Not Started | | | Single and multi-file modes |
| 7.0e | Build RichTextEditor component (TinyMCE or Quill) | Not Started | | | For blog/magazine/page content |
| 7.0f | Build SearchBar + StatusBadge + LoadingSpinner | Not Started | | | |
| 7A | Build DashboardPage (stats widgets, activity feed) | Not Started | | | |
| 7B.1 | Build BlogList page (table + filters + search + bulk) | Not Started | | | |
| 7B.2 | Build BlogForm page (create + edit mode) | Not Started | | | |
| 7C.1 | Build MagazineList page | Not Started | | | |
| 7C.2 | Build MagazineForm page (with PDF upload) | Not Started | | | |
| 7D.1 | Build AlbumList page (grid view) | Not Started | | | |
| 7D.2 | Build AlbumForm page | Not Started | | | |
| 7D.3 | Build ImageManager (multi-upload + drag-reorder) | Not Started | | | Most complex UI component |
| 7E.1 | Build EventList page | Not Started | | | |
| 7E.2 | Build EventForm page | Not Started | | | |
| 7F.1 | Build PageList page | Not Started | | | |
| 7F.2 | Build PageForm page (with parent dropdown) | Not Started | | | |
| 7G.1 | Build TrusteeList page (with drag reorder) | Not Started | | | |
| 7G.2 | Build TrusteeForm page | Not Started | | | |
| 7H | Build DonationList page (table + export button) | Not Started | | | |
| 7I | Build ContactList page (table + status + admin notes) | Not Started | | | |
| 7J | Build MediaLibrary page (grid + upload + delete) | Not Started | | | |
| 7K.1 | Build GeneralSettings page | Not Started | | | |
| 7K.2 | Build TempleTimings settings page | Not Started | | | |
| 7L | Build UserManagement page (Super Admin only) | Not Started | | | |
| 7M | **CHECKPOINT: All 12 modules CRUD working in browser** | Not Started | | | Full cycle: create → list → edit → delete per module |
| 7N | Build React for production and deploy to public/admin/ | Not Started | | | `npm run build` → copy dist/ to public/admin/ |

---

### PHASE 8: PUBLIC WEBSITE — TEMPLATE CONVERSION

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 8A.1 | [CRITICAL] Split index.html into master layout PHP template | Not Started | | | Extract: head, header, footer, scripts into partials |
| 8A.2 | Copy template CSS/JS/fonts/images to `public/assets/` | Not Started | | | Preserve exact directory structure |
| 8A.3 | Fix all asset paths in PHP views (relative → absolute) | Not Started | | | href="css/..." → href="/srisai/assets/css/..." |
| 8B | Define all public routes in `routes/web.php` | Not Started | | | SEO-friendly: /blog/{slug}, /events/{slug}, /{slug} |
| 8C | Build Web Controllers (Home, Blog, Magazine, Gallery, Event, Page, Trustee, Donation, Contact) | Not Started | | | Fetch data via Models, pass to views |
| 8D | [CRITICAL] Build Homepage view (all 16 sections dynamic) | Not Started | | | Largest single view — pulls from 6+ tables |
| 8E | Build Blog list + detail views | Not Started | | | Pagination, category filter, date display |
| 8F | Build Magazine list + detail views | Not Started | | | Include PDF download button |
| 8G | Build Gallery album list + album detail views | Not Started | | | Magnific Popup lightbox integration |
| 8H | Build Events list + detail views | Not Started | | | Show recurring badge, filter upcoming/past |
| 8I | Build dynamic CMS page view (About, Mission, Vision, Tourism) | Not Started | | | Single view template, content from pages table |
| 8J | Build Trustees page view | Not Started | | | Split: Main Trustees + Co-opted sections |
| 8K | Build Donations info page view | Not Started | | | 80G details, how to donate |
| 8L | Build Contact page (form + AJAX submission) | Not Started | | | Validate client-side + server-side |
| 8M | Build 404 and 500 error pages | Not Started | | | Styled with template design |
| 8N | **CHECKPOINT: All public pages render with dynamic data** | Not Started | | | Browse every page, check content, check responsiveness |

---

### PHASE 9: SEO & POLISH

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 9.1 | Implement dynamic `<title>` and `<meta description>` on all pages | Not Started | | | Pull from seo_meta table or auto-generate |
| 9.2 | Add Open Graph tags (og:title, og:description, og:image) | Not Started | | | For social media sharing |
| 9.3 | Create `sitemap.xml` auto-generator | Not Started | | | List all published blogs, magazines, events, pages |
| 9.4 | Create `robots.txt` | Not Started | | | Allow: /, Disallow: /admin, /api, /storage |
| 9.5 | Add JSON-LD structured data (Organization, Article, Event) | Not Started | | | Helps Google rich results |
| 9.6 | Set canonical URLs on every page | Not Started | | | Prevent duplicate content |
| 9.7 | Set up favicon (trust logo) | Not Started | | | Replace template favicons |
| 9.8 | **CHECKPOINT: Lighthouse SEO score > 80** | Not Started | | | Run on homepage and 2-3 inner pages |

---

### PHASE 10: TESTING & SECURITY HARDENING

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 10.1 | API endpoint testing — all 50+ routes (Postman) | Not Started | | | Test success + error cases |
| 10.2 | Admin panel testing — all 12 modules full CRUD | Not Started | | | |
| 10.3 | Public website testing — all pages, links, forms | Not Started | | | |
| 10.4 | Security: SQL injection test attempts | Not Started | | | Try injecting in all form fields and URL params |
| 10.5 | Security: XSS test attempts | Not Started | | | Try script tags in blog/page content |
| 10.6 | Security: File upload exploit tests | Not Started | | | Try uploading .php files, oversized files |
| 10.7 | Security: Auth bypass tests | Not Started | | | Try accessing API without token, with forged token |
| 10.8 | Cross-browser testing (Chrome, Firefox, Edge) | Not Started | | | Desktop + mobile views |
| 10.9 | Responsive testing (all 5 breakpoints) | Not Started | | | 1679, 1439, 1279, 1023, 767px |
| 10.10 | Performance: check page load times | Not Started | | | Target: < 3s on 3G mobile |
| 10.11 | Populate with real content (minimum: About, Mission, Vision pages + 3 blogs + 1 album) | Not Started | | | Via admin panel |
| 10.12 | Fix all bugs found | Not Started | | | Track in separate bug list |
| 10.13 | **GATE: Zero critical bugs, all tests pass** | Not Started | | | Must pass before deployment |

---

### PHASE 11: PRODUCTION DEPLOYMENT

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 11.1 | Purchase domain (srisaimission.org or alternative) | Not Started | | | |
| 11.2 | Provision Linux VPS (Ubuntu 24 + Nginx + PHP-FPM + MySQL) | Not Started | | | DigitalOcean/Vultr/Hostinger — 2GB RAM recommended |
| 11.3 | Install server stack (Nginx, PHP 8.2, MySQL 8, Certbot) | Not Started | | | |
| 11.4 | Configure domain DNS (A records → server IP) | Not Started | | | |
| 11.5 | Set up SSL (Let's Encrypt via Certbot) | Not Started | | | |
| 11.6 | Clone repository to server | Not Started | | | |
| 11.7 | Run migrations + seed data on production DB | Not Started | | | |
| 11.8 | Build React admin on server, copy to public/admin/ | Not Started | | | |
| 11.9 | Configure Nginx server block | Not Started | | | As per MASTER_PLAN.md Section 8.5 |
| 11.10 | Configure production .env (debug=false, real SMTP, strong JWT secret) | Not Started | | | |
| 11.11 | Set file permissions (storage/ writable, .env restricted) | Not Started | | | |
| 11.12 | Set up automated backups (cron: daily DB, weekly files) | Not Started | | | |
| 11.13 | Smoke test every feature on live site | Not Started | | | |
| 11.14 | **CHECKPOINT: Site live at https://srisaimission.org** | Not Started | | | |

---

### PHASE 12: POST-LAUNCH

| # | Task / Activity | Status | Start Date | End Date | Notes / Blockers |
|---|----------------|--------|------------|----------|-----------------|
| 12.1 | Populate all real content via admin panel | Not Started | | | All pages, blogs, gallery, events, trustees |
| 12.2 | Submit sitemap to Google Search Console | Not Started | | | |
| 12.3 | Set up Google Analytics | Not Started | | | |
| 12.4 | Train trust team on admin panel | Not Started | | | |
| 12.5 | Monitor error logs (first 2 weeks) | Not Started | | | |
| 12.6 | Fix post-launch bugs | Not Started | | | |
| 12.7 | Create admin user guide (PDF) | Not Started | | | |

---

## SUMMARY STATISTICS

| Metric | Count |
|--------|-------|
| Total Phases | 13 (0-12) |
| Total Tasks | 120+ |
| Critical Path Tasks | 18 |
| Checkpoints/Gates | 14 |
| Estimated Total Effort | 45-65 working days |

---

## CRITICAL MILESTONES (must-hit dates)

| # | Milestone | Phase | Significance |
|---|-----------|-------|-------------|
| M1 | PHP serves response + DB has all tables | Phase 1 | Foundation confirmed |
| M2 | Test API route returns JSON from MySQL | Phase 2 | Backend engine works |
| M3 | Admin login returns JWT | Phase 3 | Security layer works |
| M4 | Image upload + thumbnail works | Phase 4 | File handling works |
| M5 | All 50+ API endpoints tested | Phase 5 | Backend complete |
| M6 | Admin panel login + navigation works | Phase 6 | Frontend shell works |
| M7 | All 12 admin modules CRUD working | Phase 7 | Admin panel complete |
| M8 | All public pages render dynamically | Phase 8 | Public website complete |
| M9 | Lighthouse SEO > 80 | Phase 9 | SEO complete |
| M10 | **QUALITY GATE: Zero critical bugs** | Phase 10 | Ready for deployment |
| M11 | Site live at production URL | Phase 11 | Launched |
| M12 | Content populated + team trained | Phase 12 | Project delivered |

---

## ASSUMPTIONS

1. WampServer is installed at `C:\wamp64\` and running (user confirmed)
2. Development happens on the user's local Windows machine using WampServer
3. Production deployment will be on a separate Linux VPS (Nginx + PHP-FPM)
4. Single developer working on this project (no parallel team assignments)
5. Composer is available (or will be installed) for PHP dependency management
6. Node.js 18+ is installed (or will be installed) for React admin development
7. The trust will purchase a domain separately when ready for deployment
8. Phase durations assume focused effort — actual elapsed time may vary
9. Payment gateway integration is NOT in scope for v1 (manual donation recording only)

---

*This document is the single operational reference for day-to-day execution. Update it after every work session.*
