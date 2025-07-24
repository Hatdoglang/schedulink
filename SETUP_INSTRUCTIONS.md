# 🚀 Setup Instructions for Role-Based Redirects

## ❌ **Issue Fixed**
The error `require(D:\Small-Proj\schedulink\routes/role-based.php): Failed to open stream` has been resolved by moving the routes inline to `routes/web.php`.

## ✅ **Step 1: Verify Routes Are Working**

The role-based routes are now directly in `routes/web.php`. You should see these routes:

```php
// Requester Routes
Route::prefix('requester')->name('requester.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-dashboard', App\Livewire\Requester\Dashboard::class)->name('livewire.dashboard');
});

// Admin Routes  
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    Route::get('/livewire-dashboard', App\Livewire\Admin\Dashboard::class)->name('livewire.dashboard');
});

// Approver Routes
Route::prefix('approver')->name('approver.')->middleware(['auth', 'verified', 'role:Manager,Admin'])->group(function () {
    Route::get('/livewire-dashboard', App\Livewire\Approver\Dashboard::class)->name('livewire.dashboard');
});
```

## 📊 **Step 2: Create Test Data**

### Option A: Use Artisan Command (If PHP is available)
```bash
php artisan migrate --fresh
php artisan db:seed --class=ReferenceDataSeeder
php artisan db:seed --class=UserRoleSeeder
```

### Option B: Manual Database Seeding (Recommended)
1. **Open your database management tool** (phpMyAdmin, MySQL Workbench, etc.)
2. **Run the SQL script** from `database/manual_seed.sql`
3. **Or copy and paste this SQL**:

```sql
-- Insert Roles
INSERT INTO roles (name, created_at, updated_at) VALUES
('Admin', NOW(), NOW()),
('Manager', NOW(), NOW()),
('User', NOW(), NOW()),
('Driver', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Insert Business Units
INSERT INTO business_units (name, created_at, updated_at) VALUES
('Information Technology', NOW(), NOW()),
('Human Resources', NOW(), NOW()),
('Finance & Accounting', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Insert Company Codes
INSERT INTO company_codes (name, created_at, updated_at) VALUES
('GMALL-HQ', NOW(), NOW()),
('GMALL-NORTH', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Insert Branches
INSERT INTO branches (name, created_at, updated_at) VALUES
('Head Office', NOW(), NOW()),
('Manila Branch', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Insert Departments
INSERT INTO departments (name, created_at, updated_at) VALUES
('IT Development', NOW(), NOW()),
('HR Management', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Get foreign key IDs
SET @admin_role_id = (SELECT id FROM roles WHERE name = 'Admin' LIMIT 1);
SET @manager_role_id = (SELECT id FROM roles WHERE name = 'Manager' LIMIT 1);
SET @user_role_id = (SELECT id FROM roles WHERE name = 'User' LIMIT 1);
SET @driver_role_id = (SELECT id FROM roles WHERE name = 'Driver' LIMIT 1);

SET @business_unit_id = (SELECT id FROM business_units LIMIT 1);
SET @company_code_id = (SELECT id FROM company_codes LIMIT 1);
SET @branch_id = (SELECT id FROM branches LIMIT 1);
SET @department_id = (SELECT id FROM departments LIMIT 1);

-- Insert Test Users (password is 'password123' for all)
INSERT INTO users (first_name, last_name, email, password, mobile_number, business_unit_id, company_code_id, branch_id, department_id, role_id, is_active, created_at, updated_at) VALUES
('John', 'Admin', 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567890', @business_unit_id, @company_code_id, @branch_id, @department_id, @admin_role_id, 1, NOW(), NOW()),
('Jane', 'Manager', 'manager@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567891', @business_unit_id, @company_code_id, @branch_id, @department_id, @manager_role_id, 1, NOW(), NOW()),
('Bob', 'Requester', 'user@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567892', @business_unit_id, @company_code_id, @branch_id, @department_id, @user_role_id, 1, NOW(), NOW()),
('Mike', 'Driver', 'driver@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567893', @business_unit_id, @company_code_id, @branch_id, @department_id, @driver_role_id, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);
```

## 🧪 **Step 3: Test the Role-Based Redirects**

### Start Your Laravel Server
```bash
php artisan serve
```

### Test Each Role:

#### 🔴 **Admin Test**
1. Visit: `http://localhost:8000/login`
2. Login: `admin@example.com` / `password123`
3. **Expected**: Redirects to `/admin/livewire-dashboard`
4. **Shows**: Admin dashboard with system-wide statistics

#### 🟡 **Manager Test**
1. Visit: `http://localhost:8000/login`
2. Login: `manager@example.com` / `password123`
3. **Expected**: Redirects to `/approver/livewire-dashboard`
4. **Shows**: Approver dashboard with pending approvals

#### 🟢 **User Test**
1. Visit: `http://localhost:8000/login`
2. Login: `user@example.com` / `password123`
3. **Expected**: Redirects to `/requester/my-dashboard`
4. **Shows**: Personal dashboard with own bookings

#### 🟢 **Driver Test**
1. Visit: `http://localhost:8000/login`
2. Login: `driver@example.com` / `password123`
3. **Expected**: Redirects to `/requester/my-dashboard`
4. **Shows**: Personal dashboard with own bookings

## 🔧 **Step 4: Verify Everything Works**

### Check Routes
```bash
php artisan route:list | grep dashboard
```

You should see:
- `admin.livewire.dashboard`
- `approver.livewire.dashboard`
- `requester.livewire.dashboard`

### Check Database
```sql
SELECT u.first_name, u.last_name, u.email, r.name as role 
FROM users u 
JOIN roles r ON u.role_id = r.id 
WHERE u.email LIKE '%@example.com';
```

Should show:
- John Admin (Admin)
- Jane Manager (Manager)  
- Bob Requester (User)
- Mike Driver (Driver)

## 🎯 **Expected Results**

### ✅ **Admin Dashboard Features:**
- System health metrics
- All user bookings
- User management links
- Asset management tools

### ✅ **Approver Dashboard Features:**
- Pending approvals count
- Approval statistics
- Recent approval actions
- Approval workflow info

### ✅ **Requester Dashboard Features:**
- Personal booking statistics
- Own recent bookings
- Upcoming approved bookings
- Quick booking actions

## 🛠️ **Troubleshooting**

### If Redirects Don't Work:
1. **Clear cache**: `php artisan route:clear`
2. **Check middleware**: Ensure `RoleMiddleware` is registered
3. **Verify user roles**: Check database that users have correct role_id

### If Dashboards Show Errors:
1. **Check Livewire components exist**
2. **Verify view files exist**
3. **Check for missing relationships in models**

### If Login Fails:
1. **Verify password hash**: The SQL uses Laravel's default test hash
2. **Check user is_active**: Should be 1 (true)
3. **Verify email verification**: Set `email_verified_at` if needed

## 🎉 **Success!**

Once you complete these steps, you'll have:
- ✅ Working role-based redirects
- ✅ Different dashboards for each role
- ✅ Test users for all roles
- ✅ Proper security and access control

Your role-based system is now ready to use! 🚀