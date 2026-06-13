# TODO - NEXUS COMPUTER INSTITUTE (ERP/LMS) - Full Modules Implementation

## Phase 1: Repo Audit & Schema Alignment
- [ ] Review `database/nexus.sql` and verify required tables/columns for:
  - [ ] students, teachers, courses
  - [ ] admissions, certificates
  - [ ] payments, expenses, attendance
  - [ ] notifications, users/admins
  - [ ] gallery, testimonials, blogs, faqs, ai_conversations
- [ ] Verify code references (admin + api + student + certificates) against schema.
- [ ] Patch schema or code to remove mismatches.

## Phase 2: Public Website Pages (Full Set)
- [ ] Ensure these pages exist and are functional:
  - [ ] Home (`index.php`)
  - [ ] About (`about.php`)
  - [ ] Courses (`courses.php`)
  - [ ] Admissions (`admission.php`)
  - [ ] Teachers (`teachers.php`)
  - [ ] Certificates landing (`certificates.php`)
  - [ ] Gallery (`gallery.php`)
  - [ ] Testimonials (`testimonials.php`)
  - [ ] Blog (`blog.php`)
  - [ ] Contact (`contact.php`)
  - [ ] FAQ (`faq.php`)
  - [ ] Verify Certificate (`verify.php` already exists)
- [ ] Add DB-driven implementations where data is missing.

## Phase 3: Admin Panel (CRUD + Modules)
- [ ] Students CRUD module in `admin/students.php` + related API endpoints
  - [ ] Add/Edit/Update/Delete/View
  - [ ] Search by name and registration number
- [ ] Courses CRUD in `admin/courses.php`
- [ ] Teachers CRUD in `admin/teachers.php`
- [ ] Admissions workflow in `admin/admissions.php`
  - [ ] approve/reject -> student registration creation
- [ ] Certificates management in `admin/certificates.php`
  - [ ] Add/Edit/Update/Delete
  - [ ] Generate QR
  - [ ] Generate/Download PDF
  - [ ] Print
  - [ ] Status (active/inactive)
  - [ ] Certificate verification linkage
- [ ] Finance module
  - [ ] Fee management CRUD (`admin/add-fee.php` / `admin/finance.php`)
  - [ ] Payment collection (`admin/collect-payment.php`)
  - [ ] Pending/overdue tracking (`admin/pending-fees.php`)
  - [ ] Profit & Loss calculation
- [ ] Reports module in `admin/reports.php`
  - [ ] Daily/Monthly/Yearly report generation
  - [ ] Export PDF/Excel/CSV
- [ ] Notifications system
  - [ ] Create notifications on key events

## Phase 4: Student Portal
- [ ] Student login + guards
- [ ] Student dashboard (`student/dashboard.php`)
- [ ] Student profile (`student/profile.php` if missing)
- [ ] Student courses view
- [ ] Student certificates view
- [ ] Student payment history
- [ ] Downloads + print certificate
- [ ] Fee status + attendance tracking

## Phase 5: Certificate QR + Verification UX
- [ ] Validate QR token format and DB storage (`certificates.qr_code`, etc.)
- [ ] Ensure `verify.php` handles:
  - [ ] QR scan token
  - [ ] certificate ID lookup
  - [ ] JSON response for AJAX verification
- [ ] Add friendlier UI + status badges

## Phase 6: AI Smart Assistant
- [ ] Floating chat widget (public + student portal)
- [ ] Multi-language support (EN/UR/AR/HI) routing
- [ ] Course recommendations based on conversation / lead collection
- [ ] Integrate/ensure logging in `ai_conversations` table
- [ ] Admin AI analytics page working (`admin/ai-dashboard.php` + API)

## Phase 7: Security & Hardening
- [ ] CSRF protection for all POST/AJAX endpoints
- [ ] XSS protection: escape all dynamic outputs
- [ ] SQL injection: ensure all queries use prepared statements
- [ ] File upload validation for images/documents
- [ ] Session security: regenerate session IDs on login, set secure flags where feasible
- [ ] Login/activity logs

## Phase 8: Performance / UI
- [ ] Ensure AOS/Swiper/Chart.js/Canvas particles init works
- [ ] Ensure lazy loading and pagination where required
- [ ] Fix broken routes/assets links

## Phase 9: Testing Checklist
- [ ] Run through:
  - [ ] Home rendering
  - [ ] Courses listing
  - [ ] Admission form submission
  - [ ] Admin approve admission -> student appears
  - [ ] Admin add certificate -> QR works -> verify
  - [ ] Student download/print certificate
  - [ ] Finance add fee + collect payment -> pending fees updated
  - [ ] Reports export
  - [ ] AI chat -> conversation saved -> admin analytics shows entries

