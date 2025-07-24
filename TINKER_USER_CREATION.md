# 🧪 Creating Users with All Roles Using Laravel Tinker

## 🚀 **Quick Start Guide**

### **Step 1: Open Tinker**
```bash
php artisan tinker
```

### **Step 2: Create Reference Data First**
```php
// Create Roles
$roles = ['Admin', 'Manager', 'User', 'Driver'];
foreach ($roles as $role) {
    \App\Models\Role::firstOrCreate(['name' => $role]);
}

// Create Business Units
$businessUnits = ['Information Technology', 'Human Resources', 'Finance'];
foreach ($businessUnits as $unit) {
    \App\Models\BusinessUnit::firstOrCreate(['name' => $unit]);
}

// Create Company Codes
$companyCodes = ['GMALL-HQ', 'GMALL-NORTH', 'GMALL-SOUTH'];
foreach ($companyCodes as $code) {
    \App\Models\CompanyCode::firstOrCreate(['name' => $code]);
}

// Create Branches
$branches = ['Head Office', 'Manila Branch', 'Cebu Branch'];
foreach ($branches as $branch) {
    \App\Models\Branch::firstOrCreate(['name' => $branch]);
}

// Create Departments
$departments = ['IT Development', 'HR Management', 'Finance', 'Operations'];
foreach ($departments as $dept) {
    \App\Models\Department::firstOrCreate(['name' => $dept]);
}
```

### **Step 3: Get Reference IDs**
```php
// Get the first IDs for foreign keys
$businessUnitId = \App\Models\BusinessUnit::first()->id;
$companyCodeId = \App\Models\CompanyCode::first()->id;
$branchId = \App\Models\Branch::first()->id;
$departmentId = \App\Models\Department::first()->id;

// Get role IDs
$adminRoleId = \App\Models\Role::where('name', 'Admin')->first()->id;
$managerRoleId = \App\Models\Role::where('name', 'Manager')->first()->id;
$userRoleId = \App\Models\Role::where('name', 'User')->first()->id;
$driverRoleId = \App\Models\Role::where('name', 'Driver')->first()->id;
```

### **Step 4: Create Users for Each Role**

#### **🔴 Create Admin User**
```php
$admin = \App\Models\User::create([
    'first_name' => 'John',
    'last_name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => \Hash::make('password123'),
    'mobile_number' => '+1234567890',
    'business_unit_id' => $businessUnitId,
    'company_code_id' => $companyCodeId,
    'branch_id' => $branchId,
    'department_id' => $departmentId,
    'role_id' => $adminRoleId,
    'is_active' => true,
]);

echo "✅ Admin user created: {$admin->email}\n";
```

#### **🟡 Create Manager User**
```php
$manager = \App\Models\User::create([
    'first_name' => 'Jane',
    'last_name' => 'Manager',
    'email' => 'manager@example.com',
    'password' => \Hash::make('password123'),
    'mobile_number' => '+1234567891',
    'business_unit_id' => $businessUnitId,
    'company_code_id' => $companyCodeId,
    'branch_id' => $branchId,
    'department_id' => $departmentId,
    'role_id' => $managerRoleId,
    'is_active' => true,
]);

echo "✅ Manager user created: {$manager->email}\n";
```

#### **🟢 Create Regular User**
```php
$user = \App\Models\User::create([
    'first_name' => 'Bob',
    'last_name' => 'Requester',
    'email' => 'user@example.com',
    'password' => \Hash::make('password123'),
    'mobile_number' => '+1234567892',
    'business_unit_id' => $businessUnitId,
    'company_code_id' => $companyCodeId,
    'branch_id' => $branchId,
    'department_id' => $departmentId,
    'role_id' => $userRoleId,
    'is_active' => true,
]);

echo "✅ User created: {$user->email}\n";
```

#### **🟢 Create Driver User**
```php
$driver = \App\Models\User::create([
    'first_name' => 'Mike',
    'last_name' => 'Driver',
    'email' => 'driver@example.com',
    'password' => \Hash::make('password123'),
    'mobile_number' => '+1234567893',
    'business_unit_id' => $businessUnitId,
    'company_code_id' => $companyCodeId,
    'branch_id' => $branchId,
    'department_id' => $departmentId,
    'role_id' => $driverRoleId,
    'is_active' => true,
]);

echo "✅ Driver user created: {$driver->email}\n";
```

## 🎯 **One-Command Solution**

### **Complete Script (Copy & Paste)**
```php
// === COMPLETE TINKER SCRIPT ===

// 1. Create Roles
$roles = ['Admin', 'Manager', 'User', 'Driver'];
foreach ($roles as $role) {
    \App\Models\Role::firstOrCreate(['name' => $role]);
}

// 2. Create Reference Data
$businessUnits = ['Information Technology', 'Human Resources', 'Finance'];
foreach ($businessUnits as $unit) {
    \App\Models\BusinessUnit::firstOrCreate(['name' => $unit]);
}

$companyCodes = ['GMALL-HQ', 'GMALL-NORTH', 'GMALL-SOUTH'];
foreach ($companyCodes as $code) {
    \App\Models\CompanyCode::firstOrCreate(['name' => $code]);
}

$branches = ['Head Office', 'Manila Branch', 'Cebu Branch'];
foreach ($branches as $branch) {
    \App\Models\Branch::firstOrCreate(['name' => $branch]);
}

$departments = ['IT Development', 'HR Management', 'Finance', 'Operations'];
foreach ($departments as $dept) {
    \App\Models\Department::firstOrCreate(['name' => $dept]);
}

// 3. Get Reference IDs
$businessUnitId = \App\Models\BusinessUnit::first()->id;
$companyCodeId = \App\Models\CompanyCode::first()->id;
$branchId = \App\Models\Branch::first()->id;
$departmentId = \App\Models\Department::first()->id;

$adminRoleId = \App\Models\Role::where('name', 'Admin')->first()->id;
$managerRoleId = \App\Models\Role::where('name', 'Manager')->first()->id;
$userRoleId = \App\Models\Role::where('name', 'User')->first()->id;
$driverRoleId = \App\Models\Role::where('name', 'Driver')->first()->id;

// 4. Create Test Users
$users = [
    ['John', 'Admin', 'admin@example.com', '+1234567890', $adminRoleId],
    ['Jane', 'Manager', 'manager@example.com', '+1234567891', $managerRoleId],
    ['Bob', 'Requester', 'user@example.com', '+1234567892', $userRoleId],
    ['Mike', 'Driver', 'driver@example.com', '+1234567893', $driverRoleId],
];

foreach ($users as [$firstName, $lastName, $email, $mobile, $roleId]) {
    $user = \App\Models\User::firstOrCreate(
        ['email' => $email],
        [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => \Hash::make('password123'),
            'mobile_number' => $mobile,
            'business_unit_id' => $businessUnitId,
            'company_code_id' => $companyCodeId,
            'branch_id' => $branchId,
            'department_id' => $departmentId,
            'role_id' => $roleId,
            'is_active' => true,
        ]
    );
    echo "✅ User created: {$user->email} ({$user->role->name})\n";
}

echo "\n🎉 All users created successfully!\n";
echo "📧 Login credentials (password: password123):\n";
echo "   🔴 Admin: admin@example.com\n";
echo "   🟡 Manager: manager@example.com\n";
echo "   🟢 User: user@example.com\n";
echo "   🟢 Driver: driver@example.com\n";
```

## 🔧 **Advanced Tinker Commands**

### **Check Created Users**
```php
// View all users with their roles
\App\Models\User::with('role')->get()->each(function($user) {
    echo "{$user->full_name} ({$user->email}) - {$user->role->name}\n";
});
```

### **Create Additional Users**
```php
// Create multiple users of same role
for ($i = 1; $i <= 5; $i++) {
    \App\Models\User::create([
        'first_name' => "User{$i}",
        'last_name' => 'Test',
        'email' => "user{$i}@example.com",
        'password' => \Hash::make('password123'),
        'mobile_number' => "+12345678{$i}0",
        'business_unit_id' => $businessUnitId,
        'company_code_id' => $companyCodeId,
        'branch_id' => $branchId,
        'department_id' => $departmentId,
        'role_id' => $userRoleId,
        'is_active' => true,
    ]);
}
```

### **Update User Role**
```php
// Change user role
$user = \App\Models\User::where('email', 'user@example.com')->first();
$user->role_id = $managerRoleId;
$user->save();
echo "✅ User role updated to Manager\n";
```

### **Verify User Login**
```php
// Test user authentication
$user = \App\Models\User::where('email', 'admin@example.com')->first();
if (\Hash::check('password123', $user->password)) {
    echo "✅ Password verification successful for {$user->email}\n";
} else {
    echo "❌ Password verification failed\n";
}
```

## 🧪 **Testing the Redirect System**

### **Test Role-Based Redirects**
```php
// Simulate the redirect service
$users = \App\Models\User::with('role')->whereIn('email', [
    'admin@example.com', 
    'manager@example.com', 
    'user@example.com', 
    'driver@example.com'
])->get();

foreach ($users as $user) {
    \Auth::login($user);
    $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
    echo "{$user->role->name}: {$user->email} → {$redirectUrl}\n";
    \Auth::logout();
}
```

## 🎯 **Quick Commands Reference**

```php
// === QUICK REFERENCE ===

// Create single user
\App\Models\User::create([...]);

// Find user by email
\App\Models\User::where('email', 'admin@example.com')->first();

// Get all users with roles
\App\Models\User::with('role')->get();

// Count users by role
\App\Models\Role::withCount('users')->get();

// Delete test users
\App\Models\User::where('email', 'like', '%@example.com')->delete();

// Reset auto-increment
\DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
```

## 🚀 **Step-by-Step Execution**

1. **Open Terminal**: `php artisan tinker`
2. **Copy the complete script** from above
3. **Paste into Tinker** (it will execute automatically)
4. **Exit Tinker**: `exit` or `Ctrl+C`
5. **Test Login**: Visit `/login` and try each account

## ✅ **Expected Results**

After running the script, you should have:
- ✅ 4 roles created (Admin, Manager, User, Driver)
- ✅ Reference data created (Business Units, Branches, etc.)
- ✅ 4 test users with different roles
- ✅ All users can login with password `password123`
- ✅ Each user redirects to their role-specific dashboard

## 🎉 **Success!**

Your role-based system is now ready with test users created via Tinker! 🚀