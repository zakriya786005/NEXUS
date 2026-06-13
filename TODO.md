# TODO — NEXUS COMPUTER INSTITUTE (Educational ERP)

## Phase 1 — Repo stabilization & security foundations
- [ ] Audit `includes/header.php`, `includes/navbar.php`, `includes/footer.php` for session_start(), CSRF token injection, and header assets.
- [x] Ensure session + CSRF token generation is available globally (`config/database.php`, `includes/ai-csrf.php`, used by `includes/footer.php`).
- [x] Ensure all POST endpoints validate CSRF using `verifyCsrfToken()` (at least in certificate admin endpoints reviewed).

- [ ] Ensure all DB writes use PDO prepared statements (no string-concatenated SQL).

- [ ] Standardize safe output via `sanitize()` (no raw `$_GET/$_POST` echo).
- [ ] Confirm file upload validation uses `uploadImage()` and rejects unsafe types/sizes.

## Phase 2 — Certificate system end-to-end hardening (QR + PDF + verify)
- [ ] Confirm `certificates.certificate_id` is always the verification token.
- [ ] Verify `certificates/generate-qr.php` payload points to `verify.php?cert=...`.
- [ ] Verify `certificates/generate-pdf.php` embeds QR + shows certificate_id + student/course snapshot (current fallback may not embed QR).



- [ ] Align `verify.php` HTML/JSON output fields with the columns populated in `certificates`.
- [ ] Ensure admin certificate CRUD endpoints properly set:
  - [ ] `certificate_id`
  - [ ] `student_name`, `course_name`, `issue_date`
  - [ ] `qr_code` and `certificate_file`
  - [ ] `status` transitions (active/revoked)
- [ ] Ensure student portal links (view/download/print/verify) correctly route to certificate actions.

## Phase 3 — Finance & fee tracking consistency
- [ ] Validate tables usage against `database/nexus.sql`:
  - [ ] `student_fees` creation/updates
  - [ ] `payments` insertion updates fee remaining/status
  - [ ] `expenses` entries and profit/loss computation
- [ ] Ensure `admin/collect-payment.php` runs updates in a DB transaction.
- [ ] Ensure pending-fees module lists correct due/overdue logic from `student_fees`.

## Phase 4 — Reports module (Daily/Monthly/Yearly) + exports
- [ ] Implement server-side report calculations using `payments` + `expenses`.
- [ ] Add export options: CSV (always), PDF (DomPDF/TCPDF if installed), Excel (CSV-compatible if needed).
- [ ] Wire UI in `admin/reports.php` for range selection and downloads.

## Phase 5 — AI assistant completion (widget + leads)
- [ ] Ensure AI widget loads (`assets/js/ai-chat.js`) and calls correct endpoints.
- [ ] Ensure AI lead collection inserts into `ai_leads`.
- [ ] Add CSRF validation + basic rate limiting for lead submission.
- [ ] Add admin view for leads (dashboard or admissions section).

## Phase 6 — Full CRUD coverage for remaining modules
- [ ] Students CRUD (add/edit/update/delete/view) in `admin/students.php`.
- [ ] Courses CRUD in `admin/courses.php`.
- [ ] Teachers CRUD in `admin/teachers.php`.
- [ ] Admissions approval flow in `admin/admissions.php` + student registration on accept.

## Phase 7 — Final polish (premium UI/UX + performance)
- [ ] Ensure Bootstrap 5 + assets are consistent across public/admin/student.
- [ ] Add lazy loading for images & reduce render cost.
- [ ] Validate no missing assets (icons/css/js) on every page.
- [ ] Performance pass: remove redundant scripts, fix chart/Swiper initializations.

