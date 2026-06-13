# TODO — Advanced Certificate Management System (Implementation)

## Step 1 — Audit existing certificate module
- [x] Confirm `admin/certificates.php` exists (list + AJAX search)
- [x] Confirm `verify.php` exists (HTML/JSON verification by `cert`)
- [ ] Confirm which referenced admin/certificates helper files are missing

## Step 2 — Create admin pages
- [ ] Create `admin/add-certificate.php`
- [ ] Create `admin/edit-certificate.php`
- [ ] Create `admin/view-certificate.php`
- [ ] Create `admin/delete-certificate.php`
- [ ] Create `admin/print-certificate.php`

## Step 3 — Create certificate helpers
- [ ] Create `certificates/generate-qr.php`
- [ ] Create `certificates/generate-pdf.php`
- [ ] Create `certificates/download.php`

## Step 4 — Wire flows
- [ ] Ensure Add/Edit call QR generator and update `certificates.qr_code`
- [ ] Ensure View shows preview + QR verification status
- [ ] Ensure Delete removes certificate + recycle bin behavior (if DB supports)

## Step 5 — Student dashboard integration
- [ ] Add student “My Certificates” listing
- [ ] Add download/print/verify actions

## Step 6 — Security + testing
- [ ] Add CSRF tokens for admin POST actions
- [ ] Validate file uploads (photo/certificate_file)
- [ ] Smoke test: add/edit/view/delete/verify

