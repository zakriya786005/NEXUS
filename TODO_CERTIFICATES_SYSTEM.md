# TODO - Advanced Certificate Management System (NEXUS)

> Project: NEXUS COMPUTER INSTITUTE

## Step 1: Schema validation
- [ ] Inspect `certificates` table columns vs runtime usage in `verify.php` and admin certificates pages.

## Step 2: Admin navigation
- [ ] Ensure “Certificates” exists in admin sidebar (already present via admin/header.php navigation config).

## Step 3: Admin certificate CRUD + list/search
- [x] Certificates list + live AJAX search exists in `admin/certificates.php`.
- [ ] Create `admin/add-certificate.php` (form + upload + save).
- [ ] Create `admin/edit-certificate.php` (edit + status + regenerate QR data).
- [ ] Create `admin/view-certificate.php` (preview + QR verification status).
- [ ] Create `admin/delete-certificate.php` (soft delete).
- [ ] Create `admin/print-certificate.php` (A4 landscape print view).

## Step 4: QR generation
- [ ] Create `certificates/generate-qr.php` to generate and save QR and persist filename in DB.
- [ ] Ensure QR payload includes: certificate_id, student_name, course_name, issue_date, verification URL.

## Step 5: PDF generation
- [ ] Create `certificates/generate-pdf.php` (TCPDF/FPDF fallback-free using included libs or basic HTML-to-PDF if available).
- [ ] Create `certificates/download.php` (download/save/print endpoints).

## Step 6: Student dashboard integration
- [ ] Update `dashboard.php` “Certificates” section to show list with Download/Print/Verify actions.
- [ ] Add student pages or route handlers for certificate download/print/verify.

## Step 7: Security hardening
- [ ] Ensure CSRF protection for all POST actions in admin/student certificate endpoints.
- [ ] Ensure file upload validation for student photo.
- [ ] Ensure XSS-safe escaping in all outputs.

## Step 8: End-to-end testing
- [ ] Create certificate -> QR generated -> verify.php shows VALID.
- [ ] Edit certificate -> regenerated QR/verification reflects changes.
- [ ] Delete/soft-delete certificate -> verify.php shows INVALID/REVOKED.
- [ ] Download/print PDF works.

