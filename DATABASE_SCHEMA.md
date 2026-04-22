# 🗄️ Database Schema & Entity Relationship Diagram

## Physical Data Model (8 Tables)

---

## 🔗 Entity Relationship Diagram

```
┌─────────────────┐
│     users       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (UNIQUE)  │
│ password        │
│ role            │
│ supervisor_id   │ ──┐ (Self-referencing)
│ department_id   │ ──┐
│ is_active       │   │
│ created_at      │   │
│ updated_at      │   │
└─────────────────┘   │
       ▲  ▲  ▲  ▲     │
       │  │  │  │     │
       │  │  │  └─────┘ supervisor_id → id
       │  │  │
       │  │  └──────────────────────┐
       │  │                         │
       │  └──────────┐              │
       │             │              │
       │   ┌─────────▼──────────┐   │
       │   │   departments      │   │
       │   ├────────────────────┤   │
       │   │ id (PK)            │   │
       │   │ name               │   │
       │   │ location           │   │
       │   │ created_at         │   │
       │   │ updated_at         │   │
       │   └────────────────────┘   │
       │           ▲                │
       │           │ department_id  │
       │           │ (FK)           │
       │           │                │
       │   ┌───────┴────────────┐   │
       │   │                    │   │
       │   │  ┌─────────────────┴───┘
       │   │  │ supervisor_id (FK)
       │   │  │
       │   │  │
┌──────┴───┴──┴──────────────┐
│       bookings             │
├────────────────────────────┤
│ id (PK)                    │
│ booking_number (UNIQUE)    │ ◄── Auto-generated BKG-YYYYMMDDHHMMSS-XXXX
│ vehicle_id (FK)            │─────┐
│ user_id (FK)               │     │
│ driver_id (FK)             │     │
│ start_date                 │     │
│ end_date                   │     │
│ purpose                    │     │
│ status                     │     │ (pending/approved/rejected/completed)
│ start_km                   │     │
│ end_km                     │     │
│ notes                      │     │
│ created_at                 │     │
│ updated_at                 │     │
└────────────────────────────┘     │
       ▲  ▲  ▲  ▲  ▲              │
       │  │  │  │  │              │
       │  │  │  │  └──────────────┘
       │  │  │  │ vehicle_id (FK)
       │  │  │  │
       │  │  │  └─ driver_id (FK) ──┐
       │  │  │                      │
       │  │  └─ user_id (FK) ───┐   │
       │  │  (booker)           │   │
       │  │                     │   │
       │  │  (rejector_id) ──┐  │   │
       │  │                 │  │   │
       │  │  ┌──────────────▼──┴───┴──────────────┐
       │  │  │       approvals                    │
       │  │  ├────────────────────────────────────┤
       │  │  │ id (PK)                            │
       │  │  │ booking_id (FK)            ──┐    │
       │  │  │ level (1 or 2)              │    │
       │  │  │ approver_id (FK)     ───────┼────┼──┐
       │  │  │ status (pending/approved/rejected) │
       │  │  │ comments                   │    │
       │  │  │ created_at                 │    │
       │  │  │ updated_at                 │    │
       │  │  └────────────────────────────┘    │
       │  │                                    │
       │  └────────────────────────────────────┘ (FK: user_id)
       │
       │  ┌────────────────────────┐
       │  │     vehicles           │
       └──┤ id (PK)                │
          │ name                   │
          │ plate_number (UNIQUE)  │
          │ model                  │
          │ year                   │
          │ color                  │
          │ status                 │ (available/maintenance)
          │ capacity (seats)       │
          │ region_id (FK)      ──┐
          │ created_at            │
          │ updated_at            │
          └────────────────────────┘
             ▲                     │
             │                     │
             │                  ┌──▼────────────────┐
             │                  │     regions       │
             │                  ├───────────────────┤
             │                  │ id (PK)           │
             │                  │ name              │
             │                  │ province          │
             │                  │ created_at        │
             │                  │ updated_at        │
             │                  └───────────────────┘
             │
             └───────────────────────────────────────┐
                                                    │
          ┌─────────────────────────────────────────┘
          │
          │  ┌──────────────────────────────────┐
          │  │  fuel_consumption                │
          │  ├──────────────────────────────────┤
          │  │ id (PK)                          │
          │  │ booking_id (FK)           ───┐  │
          │  │ vehicle_id (FK)           ───┼──┘
          │  │ fuel_date                     │
          │  │ amount (liters)               │
          │  │ price (Rp)                    │
          │  │ km_at_fuel                    │
          │  │ notes                         │
          │  │ created_at                    │
          │  │ updated_at                    │
          │  └──────────────────────────────────┘
          │
          └──────────────────────────────────────┐
                                                 │
             ┌────────────────────────────────────┘
             │
             │  ┌──────────────────────────────┐
             │  │   activity_logs              │
             │  ├──────────────────────────────┤
             │  │ id (PK)                      │
             │  │ user_id (FK)          ───────┼──┐
             │  │ module                       │  │
             │  │ action                       │  │
             │  │ details (JSON)               │  │
             │  │ ip_address                   │  │
             │  │ user_agent                   │  │
             │  │ model_id (for context)       │  │
             │  │ created_at                   │  │
             │  └──────────────────────────────┘  │
             │                                    │
             └────────────────────────────────────┘
                        (FK: user_id)
```

---

## 📊 Table Specifications

### 1. **users** (System Users & Hierarchy)

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'approver', 'user') DEFAULT 'user',
    supervisor_id BIGINT NULL,
    department_id BIGINT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    INDEX idx_role (role),
    INDEX idx_supervisor (supervisor_id),
    INDEX idx_department (department_id),
    INDEX idx_active (is_active)
);
```

**Key Relationships:**
- **Self-referencing**: `supervisor_id` → `id` (hierarchical chain)
- **Has many**: bookings (as requester), approvals (as approver), activity_logs
- **Belongs to**: department, supervisor (user)

---

### 2. **regions** (Geographic Areas)

```sql
CREATE TABLE regions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    province VARCHAR(255),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_name (name)
);
```

**Relationships:**
- **Has many**: vehicles

---

### 3. **departments** (Organizational Units)

```sql
CREATE TABLE departments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    location VARCHAR(255),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_name (name)
);
```

**Relationships:**
- **Has many**: users

---

### 4. **vehicles** (Fleet Management)

```sql
CREATE TABLE vehicles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    model VARCHAR(255),
    year INT,
    color VARCHAR(50),
    status ENUM('available', 'maintenance', 'in_use') DEFAULT 'available',
    capacity INT DEFAULT 5,
    region_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE RESTRICT,
    INDEX idx_plate (plate_number),
    INDEX idx_status (status),
    INDEX idx_region (region_id)
);
```

**Relationships:**
- **Has many**: bookings, fuel_consumption
- **Belongs to**: region

---

### 5. **bookings** (Booking Requests)

```sql
CREATE TABLE bookings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_number VARCHAR(50) UNIQUE NOT NULL, -- BKG-YYYYMMDDHHmmss-XXXX
    vehicle_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,        -- Person requesting booking
    driver_id BIGINT NOT NULL,      -- Person driving vehicle
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    purpose VARCHAR(255),
    status ENUM('pending','approved','rejected','completed') DEFAULT 'pending',
    start_km INT,
    end_km INT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_booking_number (booking_number),
    INDEX idx_vehicle (vehicle_id),
    INDEX idx_user (user_id),
    INDEX idx_driver (driver_id),
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date)
);
```

**Relationships:**
- **Has many**: approvals, fuel_consumption, activity_logs
- **Belongs to**: vehicle (FK), user (FK: requester), driver (FK: user who drives)

---

### 6. **approvals** (2-Level Hierarchical Approval)

```sql
CREATE TABLE approvals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    level TINYINT NOT NULL,           -- 1=Supervisor, 2=Manager
    approver_id BIGINT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    comments TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (approver_id) REFERENCES users(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_approval (booking_id, level),
    INDEX idx_approver (approver_id),
    INDEX idx_status (status),
    INDEX idx_level (level)
);
```

**Key Features:**
- **2-Level Structure**: Level 1 (Supervisor) → Level 2 (Manager)
- **Both must approve**: Booking only approved if both levels approve
- **Cascade delete**: If booking deleted, approvals deleted
- **Unique constraint**: One approval record per booking per level

**Relationships:**
- **Belongs to**: booking, approver (user)

---

### 7. **fuel_consumption** (Fuel Tracking)

```sql
CREATE TABLE fuel_consumption (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    vehicle_id BIGINT NOT NULL,
    fuel_date DATE NOT NULL,
    amount DECIMAL(8,2) NOT NULL,      -- Liters
    price DECIMAL(15,2) NOT NULL,      -- Rp
    km_at_fuel INT,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
    INDEX idx_booking (booking_id),
    INDEX idx_vehicle (vehicle_id),
    INDEX idx_date (fuel_date)
);
```

**Relationships:**
- **Belongs to**: booking, vehicle

---

### 8. **activity_logs** (Comprehensive Audit Trail)

```sql
CREATE TABLE activity_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    module VARCHAR(50),                -- Module name (bookings, approvals, etc)
    action VARCHAR(50),                -- Action (create, update, delete, login, etc)
    details JSON NULL,                 -- Additional context
    ip_address VARCHAR(45),
    user_agent TEXT NULL,
    model_id BIGINT NULL,              -- Reference to related record
    created_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_user (user_id),
    INDEX idx_module (module),
    INDEX idx_action (action),
    INDEX idx_created (created_at),
    INDEX idx_combined (user_id, module, created_at)
);
```

**Logged Actions:**
- `login`, `logout`
- `create`, `update`, `delete` (for each module)
- `approve`, `reject` (approvals)
- `complete` (bookings)

---

## 🔑 Key Constraints & Rules

### Foreign Key Constraints

| FK | References | Action | Reason |
|---|---|---|---|
| `bookings.vehicle_id` | `vehicles.id` | RESTRICT | Don't delete vehicles in use |
| `bookings.user_id` | `users.id` | RESTRICT | Audit trail requirement |
| `bookings.driver_id` | `users.id` | RESTRICT | History preservation |
| `approvals.booking_id` | `bookings.id` | **CASCADE** | Clean approvals when booking deleted |
| `approvals.approver_id` | `users.id` | RESTRICT | Preserve approval records |
| `fuel_consumption.booking_id` | `bookings.id` | **CASCADE** | Auto-cleanup fuel records |
| `fuel_consumption.vehicle_id` | `vehicles.id` | RESTRICT | Don't delete vehicles |
| `users.department_id` | `departments.id` | SET NULL | Allow dept deletion |
| `users.supervisor_id` | `users.id` | SET NULL | Allow supervisor deletion |

### Unique Constraints

```
users.email                    -- No duplicate accounts
vehicles.plate_number          -- No duplicate plates
regions.name                   -- No duplicate regions
departments.name               -- No duplicate departments
bookings.booking_number        -- Unique auto-generated ID
approvals(booking_id, level)   -- One approval per booking per level
```

### Indexes (Performance Optimization)

**High-Traffic Queries:**
- `users` → idx_role, idx_active, idx_supervisor
- `bookings` → idx_status, idx_dates, idx_vehicle
- `approvals` → idx_approver, idx_status, idx_level
- `activity_logs` → idx_created, idx_combined (user, module, date)

---

## 📈 Data Flow Diagram

```
User Creates Booking
    ↓
bookings (status=pending) + activity_log
    ↓
Auto-create 2 approval records
    ↓
approvals level=1 + approvals level=2 (both status=pending)
    ↓ (Supervisor approves)
approval level=1 (status=approved) + activity_log
    ↓ (Manager approves)
approval level=2 (status=approved) + activity_log
    ↓
booking (status=approved) + activity_log
    ↓ (After booking dates)
User Completes Booking
    ↓
booking (status=completed, end_km filled) + activity_log
    ↓
Record Fuel Consumption
    ↓
fuel_consumption (amount, price, km_at_fuel) + activity_log
```

---

## 🗂️ Migration Files Created

All migrations in `database/migrations/`:

1. `0001_01_01_000000_create_users_table.php` - Users with hierarchy
2. `0001_01_01_000001_create_cache_table.php` - Laravel cache
3. `0001_01_01_000002_create_jobs_table.php` - Queue jobs
4. `2026_04_16_072755_create_regions_table.php` - Regions
5. `2026_04_16_072757_create_departments_table.php` - Departments
6. `2026_04_16_072759_create_vehicles_table.php` - Vehicles with region FK
7. `2026_04_16_072801_create_bookings_table.php` - Bookings (core entity)
8. `2026_04_16_072803_create_approvals_table.php` - 2-level approvals
9. `2026_04_16_072805_create_activity_logs_table.php` - Audit trail
10. `2026_04_16_072806_create_fuel_consumption_table.php` - Fuel tracking

---

## ✅ Seeding Data

**Test Data Created:**

```
Users:
- 1 Admin (admin@admin.com)
- 2 Approvers (supervisor@admin.com, manager@admin.com)
- 4 Staff (usera-userd@user.com)
- Total: 7 users

Hierarchy:
Staff → Supervisor (L1) → Manager (L2) → Admin

Vehicles:
- 6 vehicles with regions

Regions:
- 8 geographic regions

Departments:
- 8 organizational units

All connected with proper foreign keys and constraints.
```

---

## 📝 Notes

- **CASCADE deletes** used for approvals & fuel_consumption (related to bookings)
- **RESTRICT** used for core entities (users, vehicles, bookings) to prevent accidental data loss
- **JSON column** in activity_logs for flexible audit details
- **Indexes** optimized for common queries (filters, searches, reports)
- **Self-referencing** user hierarchy supports unlimited approval levels (currently set to 2)

---

## 🎯 Database Design Principles Applied

✅ **Normalization** - Proper 3NF structure
✅ **Referential Integrity** - Foreign key constraints
✅ **Audit Trail** - Complete activity logging
✅ **Scalability** - Proper indexes on all FK & common filters
✅ **Data Quality** - Unique constraints & validation
✅ **Performance** - Strategic indexes & denormalization where needed
✅ **Security** - No sensitive data in logs, encrypted passwords
