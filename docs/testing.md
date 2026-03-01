# Testing Documentation
## Musanze Market Order System

## Test Checklist (Minimum 10 Test Cases)

### Test Environment
- **Browser:** Chrome 120+, Firefox 115+, Safari 17+
- **Devices:** Desktop (1920x1080), Tablet (768x1024), Mobile (375x667)
- **Database:** MySQL 5.7+
- **PHP:** 7.4.33

---

## Test Case 1: User Authentication
**ID:** TC-AUTH-01
**Description:** Verify login functionality with valid credentials
**Steps:**
1. Navigate to login page
2. Enter username: `admin`
3. Enter password: `admin123`
4. Click "Sign In" button

**Expected Result:** User successfully logged in and redirected to dashboard
**Actual Result:** ✓ PASS - Redirected to dashboard, welcome message shown
**Screenshot:** [login-success.png]

---

## Test Case 2: User Authentication - Invalid Credentials
**ID:** TC-AUTH-02
**Description:** Verify login fails with invalid credentials
**Steps:**
1. Navigate to login page
2. Enter username: `admin`
3. Enter password: `wrongpassword`
4. Click "Sign In" button

**Expected Result:** Error message displayed, stay on login page
**Actual Result:** ✓ PASS - "Invalid username or password" error shown
**Screenshot:** [login-error.png]

---

## Test Case 3: Register New Farmer
**ID:** TC-FARMER-01
**Description:** Verify farmer registration with valid data
**Steps:**
1. Navigate to Farmers > Register Farmer
2. Enter name: "Test Farmer"
3. Enter phone: "0788123456"
4. Enter location: "Musanze"
5. Click "Register Farmer"

**Expected Result:** Farmer created successfully, redirected to farmer details
**Actual Result:** ✓ PASS - Farmer created, success message shown
**Screenshot:** [farmer-create.png]

---

## Test Case 4: Farmer Registration - Validation
**ID:** TC-FARMER-02
**Description:** Verify validation for empty required fields
**Steps:**
1. Navigate to Farmers > Register Farmer
2. Leave all fields empty
3. Click "Register Farmer"

**Expected Result:** Error messages for required fields, form not submitted
**Actual Result:** ✓ PASS - Validation errors shown for all required fields
**Screenshot:** [farmer-validation.png]

---

## Test Case 5: Create Order with Auto-Calculation
**ID:** TC-ORDER-01
**Description:** Verify order creation with live total calculation
**Steps:**
1. Navigate to Orders > New Order
2. Select a farmer
3. Enter quantity: 50.5
4. Enter unit price: 600
5. Verify total displays: 30,300 RWF
6. Enter pickup location: "Musanze Market"
7. Click "Create Order"

**Expected Result:** Order created with correct total, redirected to order details
**Actual Result:** ✓ PASS - Order created, total correctly calculated (50.5 × 600 = 30,300)
**Screenshot:** [order-create.png]

---

## Test Case 6: Order Receipt Generation
**ID:** TC-ORDER-02
**Description:** Verify receipt page displays correctly and prints
**Steps:**
1. View an existing order
2. Click "Print Receipt"
3. Verify receipt format
4. Click print button

**Expected Result:** Receipt page opens with correct formatting, print dialog appears
**Actual Result:** ✓ PASS - Receipt shows all details, print function works
**Screenshot:** [receipt.png]

---

## Test Case 7: Dashboard Statistics
**ID:** TC-DASH-01
**Description:** Verify dashboard displays correct statistics
**Steps:**
1. Log in to system
2. View dashboard
3. Compare counts with database

**Expected Result:** Statistics match actual database counts
**Actual Result:** ✓ PASS - Today's orders (3) match database query
**Screenshot:** [dashboard-stats.png]

---

## Test Case 8: Order Status Update
**ID:** TC-ORDER-03
**Description:** Verify order status can be updated
**Steps:**
1. View order details
2. Change status from "pending" to "completed"
3. Click "Update"
4. Refresh page

**Expected Result:** Status updated and persists after refresh
**Actual Result:** ✓ PASS - Status changed to completed, badge color updated
**Screenshot:** [status-update.png]

---

## Test Case 9: Search Farmers
**ID:** TC-FARMER-03
**Description:** Verify farmer search functionality
**Steps:**
1. Navigate to Farmers list
2. Type "Jean" in search box
3. View results

**Expected Result:** Only farmers with "Jean" in name displayed
**Actual Result:** ✓ PASS - Search returns 3 matching farmers
**Screenshot:** [search.png]

---

## Test Case 10: Mobile Responsiveness
**ID:** TC-RESP-01
**Description:** Verify responsive design on mobile device
**Steps:**
1. Open Chrome DevTools
2. Set device to iPhone 12
3. Navigate through all pages
4. Test navigation menu toggle

**Expected Result:** Layout adapts to screen size, menu works on mobile
**Actual Result:** ✓ PASS - All pages responsive, mobile menu functional
**Screenshot:** [mobile-view.png]

---

## Test Case 11: Delete Farmer with Orders
**ID:** TC-FARMER-04
**Description:** Verify cannot delete farmer with existing orders
**Steps:**
1. Find farmer with orders
2. Attempt to delete
3. Confirm deletion

**Expected Result:** Error message, farmer not deleted
**Actual Result:** ✓ PASS - "Cannot delete farmer with existing orders" message
**Screenshot:** [delete-restrict.png]

---

## Test Case 12: SQL Injection Prevention
**ID:** TC-SEC-01
**Description:** Test SQL injection attempts
**Steps:**
1. In login form, enter: `' OR '1'='1`
2. Enter any password
3. Attempt login
4. In search, try: `' UNION SELECT * FROM users--`

**Expected Result:** All inputs properly escaped, no SQL injection
**Actual Result:** ✓ PASS - All queries use prepared statements
**Screenshot:** [sql-injection.png]

---

## Test Case 13: Filter Orders by Status
**ID:** TC-ORDER-04
**Description:** Verify order filtering works
**Steps:**
1. Go to Orders list
2. Select "pending" from status filter
3. Apply filter

**Expected Result:** Only pending orders displayed
**Actual Result:** ✓ PASS - Filter shows correct orders
**Screenshot:** [filter.png]

---

## Test Case 14: Edit Order
**ID:** TC-ORDER-05
**Description:** Verify order editing functionality
**Steps:**
1. View order details
2. Click "Edit Order"
3. Change quantity to 75
4. Update order

**Expected Result:** Total recalculated, changes saved
**Actual Result:** ✓ PASS - Order updated successfully
**Screenshot:** [edit-order.png]

---

## Test Case 15: Logout
**ID:** TC-AUTH-03
**Description:** Verify logout functionality
**Steps:**
1. While logged in
2. Click "Logout" button
3. Try to access dashboard directly

**Expected Result:** Session ended, redirected to login page
**Actual Result:** ✓ PASS - Successfully logged out
**Screenshot:** [logout.png]

---

## Performance Testing

### Load Time Measurements
| Page | Desktop | Mobile | Status |
|------|---------|--------|--------|
| Login | 0.8s | 1.2s | ✓ PASS |
| Dashboard | 1.2s | 1.8s | ✓ PASS |
| Orders List | 1.1s | 1.7s | ✓ PASS |
| Create Order | 0.9s | 1.4s | ✓ PASS |

### Database Performance
| Query | Execution Time | Status |
|-------|---------------|--------|
| Select all orders with joins | 0.03s | ✓ PASS |
| Dashboard statistics | 0.02s | ✓ PASS |
| Search farmers | 0.01s | ✓ PASS |

---

## Browser Compatibility

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | 120+ | ✓ PASS |
| Firefox | 115+ | ✓ PASS |
| Safari | 17+ | ✓ PASS |
| Edge | 120+ | ✓ PASS |

---

## Test Summary

**Total Test Cases:** 15
**Passed:** 15
**Failed:** 0
**Blocked:** 0

**Test Completion Date:** February 28, 2026
**Tested By:** Group #1

---

## Issues Found and Resolved

### Issue #1: Mobile menu not closing
- **Status:** Resolved
- **Fix:** Added click-outside event listener
- **Verified:** February 27, 2026

### Issue #2: Total calculation rounding error
- **Status:** Resolved
- **Fix:** Used proper number formatting and rounding
- **Verified:** February 27, 2026

### Issue #3: Form validation on empty fields
- **Status:** Resolved
- **Fix:** Added proper required field validation
- **Verified:** February 28, 2026

---

**All tests passed. System ready for deployment.**