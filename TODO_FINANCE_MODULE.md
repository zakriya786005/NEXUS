# TODO - Finance & Fee Management Module (NEXUS ERP)

## Step 1: Database Schema
- [x] Extend `database/nexus.sql` with tables: `student_fees`, `payments`, `expenses`, `finance_reports`
- [x] Add required indexes and FK relations to `students`/`courses`
(> Note: DB import command via terminal requires adjustment on Windows shells.)



## Step 2: Shared Finance Utilities
- [x] Create `includes/finance-utils.php` with:
  - fee status computation (Paid/Partial/Pending/Overdue)
  - profit/loss aggregations by date range
  - receipt number generation


## Step 3: Admin Pages
- [ ] Create admin pages:
  - `admin/finance.php`
  - `admin/add-fee.php`
  - [x] `admin/collect-payment.php`
  - [x] `admin/pending-fees.php`
  - `admin/expenses.php`
  - `admin/reports.php`

- [ ] Update navigation to include Finance + Reports


## Step 4: Student Portal Pages
- [ ] Create:
  - `student/my-fees.php`
  - `student/payment-history.php`

## Step 5: API + AJAX Endpoints
- [ ] Create `api/finance/*` endpoints for:
  - live search
  - dashboard metrics
  - pending fee list
  - reports/profit-loss aggregates
  - receipt data

## Step 6: Receipt Generation
- [ ] Add receipt rendering + print/download PDF endpoint

## Step 7: Charts (Chart.js)
- [ ] Implement charts on `admin/finance.php`

## Step 8: Security & Logging
- [ ] Enforce CSRF + prepared statements on all POST endpoints
- [ ] Add activity logging for finance actions

## Step 9: Testing
- [ ] Run DB migration (import `nexus.sql`)
- [ ] Manual test flows: add fee, collect payment, update statuses, receipts, reports, student portal

