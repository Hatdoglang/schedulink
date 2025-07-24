# 🚀 Role-Based Redirect Guide

This guide shows you how to create test data for different user roles and understand where each role redirects after login.

## 📊 **Step 1: Create Test Data**

### Run the Seeders
```bash
# Run all seeders (includes reference data and test users)
php artisan db:seed

# Or run specific seeders
php artisan db:seed --class=ReferenceDataSeeder
php artisan db:seed --class=UserRoleSeeder
```

### What Gets Created:
- **Reference Data**: Business Units, Company Codes, Branches, Departments, Roles
- **Test Users**: Admin, Manager, Regular Users, Driver

## 👥 **Step 2: Test User Accounts Created**

| Role | Email | Password | Full Name | Redirects To |
|------|-------|----------|-----------|--------------|
| **Admin** | `admin@example.com` | `password123` | John Admin | `/admin/livewire-dashboard` |
| **Manager** | `manager@example.com` | `password123` | Jane Manager | `/approver/livewire-dashboard` |
| **User** | `user@example.com` | `password123` | Bob Requester | `/requester/my-dashboard` |
| **Driver** | `driver@example.com` | `password123` | Mike Driver | `/requester/my-dashboard` |

### Additional Test Users:
- `user1@example.com` to `user5@example.com` (All Users/Requesters)
- `manager1@example.com` to `manager2@example.com` (All Managers/Approvers)

## 🎯 **Step 3: Understanding the Redirect Flow**

### Login Process:
1. **User enters credentials** → Login form
2. **Authentication succeeds** → `RoleRedirectService::getRedirectUrl()` called
3. **Service checks user role** → Returns appropriate URL
4. **User redirected** → To role-specific dashboard

### Redirect Logic:
```php
// From RoleRedirectService.php
return match($roleName) {
    'Admin' => '/admin/livewire-dashboard',        // Admin Dashboard
    'Manager' => '/approver/livewire-dashboard',   // Approver Dashboard  
    'Driver' => '/requester/my-dashboard',         // Requester Dashboard
    'User' => '/requester/my-dashboard',           // Requester Dashboard
    default => '/dashboard'                        // Fallback Dashboard
};
```

## 🖥️ **Step 4: Different UI Dashboards**

### 1. **Admin Dashboard** (`/admin/livewire-dashboard`)
**File**: `resources/views/livewire/admin/dashboard.blade.php`

**Features**:
- 📊 System health metrics (Total Users, Active Users, Assets, Bookings)
- 📈 Comprehensive booking statistics
- 👥 Most active users list
- 🚗 Asset utilization reports
- 📅 Recent bookings overview
- ⚡ Quick admin actions (Manage Users, Assets, Analytics)

**UI Elements**:
- System-wide statistics cards
- Recent bookings table with all users
- Asset utilization charts
- Admin quick action buttons

### 2. **Approver Dashboard** (`/approver/livewire-dashboard`)
**File**: `resources/views/livewire/approver/dashboard.blade.php`

**Features**:
- ⏳ Pending approvals count
- ✅ Daily approval statistics (Approved/Rejected today)
- 📋 Pending bookings requiring approval
- 📜 Recent approval actions history
- 🏢 Approval hierarchy display
- ⚡ Quick approval actions

**UI Elements**:
- Approval statistics cards
- Pending approvals table with action buttons
- Recent approval actions timeline
- Approval authority overview

### 3. **Requester Dashboard** (`/requester/my-dashboard`)
**File**: `resources/views/livewire/requester/dashboard.blade.php`

**Features**:
- 📊 Personal booking statistics
- 📅 Recent bookings (user's own)
- 🔜 Upcoming approved bookings
- ⚡ Quick booking actions
- 📈 Personal booking trends

**UI Elements**:
- Personal statistics cards
- Own bookings table
- Upcoming bookings calendar
- Quick action buttons for booking management

## 🧪 **Step 5: Testing the Redirect System**

### Manual Testing:
1. **Open your application** in browser
2. **Go to login page** (`/login`)
3. **Try each test account**:

#### Test Admin Redirect:
```
Email: admin@example.com
Password: password123
Expected: Redirects to /admin/livewire-dashboard
Shows: System-wide admin interface
```

#### Test Manager Redirect:
```
Email: manager@example.com  
Password: password123
Expected: Redirects to /approver/livewire-dashboard
Shows: Approval management interface
```

#### Test User Redirect:
```
Email: user@example.com
Password: password123
Expected: Redirects to /requester/my-dashboard
Shows: Personal booking interface
```

### Verification Steps:
1. ✅ **Login successful** - User is authenticated
2. ✅ **Automatic redirect** - No manual navigation needed
3. ✅ **Correct dashboard** - Role-appropriate interface loads
4. ✅ **Proper data** - Only relevant data shown for the role
5. ✅ **Working links** - All dashboard links function correctly

## 🛠️ **Step 6: Troubleshooting**

### If Redirects Don't Work:
1. **Check user has role assigned**:
```sql
SELECT users.email, roles.name as role 
FROM users 
JOIN roles ON users.role_id = roles.id;
```

2. **Verify middleware is registered**:
```php
// In bootstrap/app.php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

3. **Clear cache**:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### If Dashboards Don't Load:
1. **Check Livewire components exist**:
   - `app/Livewire/Admin/Dashboard.php`
   - `app/Livewire/Approver/Dashboard.php`
   - `app/Livewire/Requester/Dashboard.php`

2. **Check views exist**:
   - `resources/views/livewire/admin/dashboard.blade.php`
   - `resources/views/livewire/approver/dashboard.blade.php`
   - `resources/views/livewire/requester/dashboard.blade.php`

3. **Check routes are registered**:
```bash
php artisan route:list | grep dashboard
```

## 📱 **Step 7: Expected User Experience**

### For Admin Users:
1. **Login** → Immediately see admin dashboard
2. **View** → System-wide statistics and user management
3. **Access** → All system features and analytics
4. **Manage** → Users, assets, bookings, and system settings

### For Manager/Approver Users:
1. **Login** → Immediately see approver dashboard
2. **View** → Pending approvals and approval statistics
3. **Access** → Approval workflows and history
4. **Manage** → Booking approvals and team oversight

### For Regular Users/Drivers:
1. **Login** → Immediately see personal dashboard
2. **View** → Own bookings and personal statistics
3. **Access** → Booking creation and management
4. **Manage** → Personal booking requests and history

## 🎉 **Success Indicators**

✅ **Seeders run successfully** - Test data created  
✅ **Login with different roles** - Authentication works  
✅ **Automatic redirects** - No manual navigation needed  
✅ **Role-appropriate dashboards** - Correct UI for each role  
✅ **Proper data filtering** - Users only see relevant data  
✅ **Working functionality** - All dashboard features operational  

---

## 🚀 **Quick Start Commands**

```bash
# 1. Run migrations (if not done)
php artisan migrate

# 2. Seed the database with test data
php artisan db:seed

# 3. Start the server
php artisan serve

# 4. Test the login flow
# Visit: http://localhost:8000/login
# Try: admin@example.com / password123
```

Now you have a complete role-based system with test data and different UIs for each user type! 🎊