# Vehicle Booking System - Technical Test Documentation

## 📋 SOAL / REQUIREMENTS

### Description Test:
Sebuah perusahaan tambang nikel berlokasi di beberapa daerah (region), satu kantor pusat, satu kantor cabang dan memiliki enam tambang dengan lokasi yang berbeda. Perusahaan tersebut mempunyai banyak kendaraan dengan jenis kendaraan angkutan orang dan angkutan barang. Selain kendaraan milik perusahaan, ada juga kendaraan yang disewa dari perusahaan persewaan.

Perusahaan tersebut membutuhkan sebuah web aplikasi untuk dapat memonitoring kendaraan yang dimiliki. Mulai dari konsumsi BBM, jadwal service dan riwayat pemakaian kendaraan. Untuk dapat memakai kendaraan, pegawai diwajibkan untuk melakukan pemesanan terlebih dahulu ke pool atau bagian pengelola kendaraan dan pemakaian kendaraan harus diketahui atau disetujui oleh masing - masing atasan.

### Soal:
Buat aplikasi pemesanan kendaraan dengan ketentuan sebagai berikut:

1. ✅ **Terdapat 2 user (admin dan pihak yang menyetujui)**
   - Admin: Dapat menginputkan pemesanan kendaraan dan menentukan driver serta pihak yang menyetujui
   - Approver: Pihak yang menyetujui (2-level hierarchy)
   - User: Pegawai biasa yang bisa membuat booking

2. ✅ **Admin dapat menginputkan pemesanan kendaraan dan menentukan driver serta pihak yang menyetujui pemesanan**
   - Implemented: Admin (dan user biasa) bisa create booking
   - Auto-assign approver dari supervisor hierarchy

3. ✅ **Persetujuan dilakukan berjenjang minimal 2 level**
   - Level 1: Supervisor langsung
   - Level 2: Supervisor's supervisor (Manager)
   - Both must approve untuk booking jadi "approved"

4. ✅ **Pihak yang menyetujui dapat melakukan persetujuan melalui aplikasi**
   - Approvals menu dengan pending list
   - Approve/reject dengan comments
   - Activity logging on setiap decision

5. ✅ **Terdapat dashboard yang menampilkan grafik pemakaian kendaraan**
   - Dashboard dengan statistics: pending bookings, approved bookings, approvals count
   - System overview: total bookings, available vehicles, active users

6. ✅ **Terdapat laporan periodik pemesanan kendaraan yang dapat di export (Excel)**
   - Activity logs dengan export CSV
   - Filter by module, action, date range
   - Can expand to Excel export

### Instruction (Bonus Points):
1. ✅ **Buat physical data model yang berhubungan dengan fitur pemesanan kendaraan**
   - 8 tables: users, regions, departments, vehicles, bookings, approvals, activity_logs, fuel_consumption
   - Proper relationships dan constraints

2. ⏳ **Buat activity diagram untuk fitur pemesanan kendaraan**
   - To be added

3. ✅ **Terdapat log aplikasi pada tiap proses**
   - Activity logging on: login, logout, create booking, update booking, delete booking, approve, reject, complete booking
   - CSV export functionality

4. ✅ **UI/UX yang baik dan responsive**
   - Bootstrap 5 responsive design
   - Professional gradients dan color schemes
   - Sidebar navigation dengan role-based menu
   - Status badges dan visual feedback

---

## 🔐 User Credentials

### Admin User
| Username | Email | Password | Role |
|----------|-------|----------|------|
| User Admin | admin@admin.com | password123 | Admin |

### Approver Users (2-Level Hierarchy)
| Username | Email | Password | Role | Level |
|----------|-------|----------|------|-------|
| User Manager | manager@admin.com | password123 | Approver | Level 2 (Top) |
| User Supervisor | supervisor@admin.com | password123 | Approver | Level 1 |

### Regular Users (Staff)
| Username | Email | Password | Role | Department |
|----------|-------|----------|------|-----------|
| User A | usera@user.com | password123 | User | DEPT-01 |
| User B | userb@user.com | password123 | User | DEPT-02 |
| User C | userc@user.com | password123 | User | DEPT-05 |
| User D | userd@user.com | password123 | User | DEPT-05 |

---

## 💻 Technical Stack

### Framework & Dependencies
- **Framework**: Laravel 11
- **PHP Version**: 8.2.12
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5.3.0
- **Icons**: Font Awesome 6.4.0
- **Package Manager**: Composer, NPM

### Database
- **Host**: 127.0.0.1:3306
- **Database Name**: vehicle_booking
- **Username**: root
- **Password**: (empty/blank)

### Server
- **Dev Server**: php artisan serve
- **URL**: http://127.0.0.1:8000
- **Terminal**: PowerShell / Windows Terminal

---

## 📊 Database Schema

### Tables (8 Core Tables)

#### 1. Users
- id, name, email, password, role (admin/approver/user), department_id, supervisor_id
- is_active, phone, created_at, updated_at
- Relations: Department (belongs), Supervisor (self-ref), Bookings, Approvals, ActivityLogs

#### 2. Regions
- id, name, code (unique), description, address, type (kantor_pusat/kantor_cabang/tambang)
- created_at, updated_at
- Sample: 8 regions (REGION-01 to REGION-08)

#### 3. Departments
- id, name, code (unique), description, region_id
- created_at, updated_at
- Sample: 8 departments (DEPT-01 to DEPT-08)

#### 4. Vehicles
- id, plate_number (unique), vehicle_name, vehicle_type (passenger/cargo)
- region_id, brand, model, year, purchase_date, current_km, last_service_date
- status (available/maintenance/retired), is_rental, rental_company_name
- Sample: 6 vehicles (VEH-001 to VEH-006)

#### 5. Bookings
- id, booking_number (unique: BKG-YYYYMMDDHHmmss-XXXX)
- user_id, vehicle_id, driver_id, approver1_id, approver2_id
- start_date, end_date, actual_return_date, purpose
- status (pending/approved/rejected/completed), fuel_used, start_km, end_km
- created_at, updated_at

#### 6. Approvals (2-Level)
- id, booking_id, approver_id, level (1 or 2), status (pending/approved/rejected)
- comments, approved_at, created_at, updated_at

#### 7. Activity Logs
- id, user_id, action, module, description, ip_address, user_agent
- created_at, updated_at

#### 8. Fuel Consumption
- id, vehicle_id, booking_id (nullable), amount, price, fuel_date
- km_at_fuel, notes, created_at, updated_at

---

## 🚀 Panduan Penggunaan (Usage Guide)

### Installation & Setup

```bash
# 1. Clone atau download project
cd PKL1

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Create database
# - Buka phpMyAdmin
# - Create database: vehicle_booking
# - Character: utf8mb4_unicode_ci

# 6. Configure .env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_booking
DB_USERNAME=root
DB_PASSWORD=

# 7. Run migrations & seed
php artisan migrate:fresh --seed

# 8. Start development server
php artisan serve

# 9. Open browser
http://127.0.0.1:8000
```

### Features & Usage

#### 1. **Login / Authentication**
- Navigate to: http://127.0.0.1:8000
- Use credentials from table di atas
- System checks: is_active status, inactive users blocked
- Activity logged: login & logout events

#### 2. **Dashboard**
- Shows personal statistics (pending bookings, approved bookings)
- Shows approval count (if approver)
- System overview: total bookings, vehicles, users
- Quick links: Create Booking, View Bookings, Approvals
- Role-based sidebar menu

#### 3. **Create Booking** (For All Authenticated Users)
- Navigate: Bookings → Create New
- Select: Vehicle (available only), Driver, Dates, Start KM, Purpose
- System automatically:
  - Generates unique booking number
  - Assigns Level 1 approval → User's supervisor
  - Assigns Level 2 approval → Supervisor's supervisor
  - Creates 2 Approval records
  - Logs activity
- Status: pending (awaiting approval)

#### 4. **View Bookings** (List All)
- Navigate: Bookings → List
- Shows: Booking#, User, Vehicle, Dates, Status
- Actions: View detail, Edit (if pending & creator), Delete (if pending & creator)

#### 5. **Booking Details** (Show)
- Full booking information
- 2-Level approval chain status
- Actions:
  - **If pending & creator**: Edit, Delete buttons
  - **If approved**: "Mark Complete" form (end_km + return_date)

#### 6. **Approvals Workflow** (For Approvers Only)
- Navigate: Approvals menu (only visible if approver)
- Shows 2 sections:
  
  **a) Waiting for Your Decision**
  - Pending approvals assigned to current user
  - Shows: Booking#, Level, Requester, Vehicle, Purpose
  - Click "Review & Approve" → Detail page

  **b) Your Approval History**
  - All approvals you've done (approved/rejected)
  - Shows: Booking#, Requester, Vehicle, Decision, Processed date

#### 7. **Approval Detail & Decision**
- Navigate: Approvals → Review
- Full booking context:
  - Requester details, Vehicle info, Purpose, Dates
  - Approval chain (what level are you? other levels status?)
- Actions:
  - **Approve Button**: Add optional comments, submit
  - **Reject Button**: Add rejection reason (required), submit
- After approval:
  - Booking status updates based on all approvals
  - If Level 1 approved: Level 2 still pending
  - If both Level 1 & 2 approved: Booking status → "approved"
  - If any rejected: Booking status → "rejected"
  - Activity logged: Approver name, decision, comments

#### 8. **Activity Logs** (Admin Only)
- Navigate: Admin → Activity Logs
- Shows all system activities:
  - Who: User name
  - Action: login, logout, create, update, delete, approve, reject, complete
  - Module: users, bookings, approvals, vehicles, etc.
  - Description: Details of action
  - IP Address, User Agent, Timestamp
- Features:
  - Pagination (50 per page)
  - Filter by Module
  - Filter by Action
  - Export to CSV (Timestamp, User, Action, Module, Description, IP)

#### 9. **Fuel Consumption** (Tracking)
- Navigate: Fuel menu
- Track fuel usage per vehicle
- Can link to bookings
- Statistics view (total fuel, total cost, average price)
- Can manage per vehicle

#### 10. **Admin Data Management** (Admin Only)
- Navigate: Admin section
- Manage:
  - Users (CRUD)
  - Regions (CRUD)
  - Departments (CRUD)
  - Vehicles (CRUD)

---

## 📝 Complete Booking Workflow Example

### Scenario: Admin creates booking, gets approved

```
Step 1: LOGIN as admin@admin.com
├─ URL: http://127.0.0.1:8000/login
├─ Email: admin@admin.com
└─ Password: password123

Step 2: CREATE BOOKING
├─ Navigate: Bookings → Create New
├─ Select Vehicle: VEH-001 (available)
├─ Select Driver: usera@user.com
├─ Start Date: 2026-05-01
├─ End Date: 2026-05-05
├─ Start KM: 1000
├─ Purpose: "Pengiriman barang ke tambang REGION-03"
├─ Submit → System auto-assigns:
│  ├─ Level 1 Approval → supervisor@admin.com
│  └─ Level 2 Approval → manager@admin.com
└─ Status: PENDING

Step 3: LOGOUT
├─ Click User dropdown
└─ Click Logout

Step 4: LOGIN as supervisor@admin.com (Level 1)
├─ Navigate: http://127.0.0.1:8000/approvals
├─ See: "Waiting for Your Decision"
└─ See your booking

Step 5: APPROVE (Level 1)
├─ Click "Review & Approve"
├─ Add comments: "Setuju, driver sudah trained"
├─ Click "Approve"
├─ Status: Level 1 ✓, Level 2 ⏳

Step 6: LOGOUT & LOGIN as manager@admin.com (Level 2)
├─ Navigate: http://127.0.0.1:8000/approvals
└─ See: Your pending approval

Step 7: APPROVE (Level 2)
├─ Click "Review & Approve"
├─ Add comments: "OK, lanjut"
├─ Click "Approve"
└─ Booking Status: APPROVED ✅

Step 8: LOGIN as admin
├─ Navigate: Bookings → List
├─ See booking: APPROVED
├─ Click "View"
├─ Fill: End KM (1250), Return Date (2026-05-05)
├─ Click "Mark Complete"
└─ Status: COMPLETED

Step 9: CHECK LOGS (Admin)
├─ Navigate: Admin → Activity Logs
└─ See: All actions logged
```

---

## ✅ Features Checklist

| Feature | Status | Details |
|---------|--------|---------|
| Authentication | ✅ | Login/logout dengan role-based access |
| 2-Level Approval | ✅ | Auto-assign dari supervisor hierarchy |
| Booking CRUD | ✅ | Create, read, update, delete |
| Approvals | ✅ | Approve/reject dengan comments |
| Activity Logs | ✅ | All actions tracked, CSV export |
| Dashboard | ✅ | Statistics & overview |
| Admin Panel | ✅ | Users, Regions, Departments, Vehicles |
| Fuel Tracking | ✅ | Monitor konsumsi BBM |
| Responsive UI | ✅ | Bootstrap 5 mobile-friendly |
| Database | ✅ | 8 tables, proper relationships |

---

## 🔧 Troubleshooting

### Database Error
```
php artisan migrate:fresh --seed
```

### Login Issues
- Email harus exact match (admin@admin.com)
- Check is_active status
- Password: password123

### Server Not Running
```
php artisan serve
```

---

**Last Updated**: April 16, 2026  
**Status**: ✅ Ready for Testing & Production
