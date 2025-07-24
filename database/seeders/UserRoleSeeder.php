<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\BusinessUnit;
use App\Models\CompanyCode;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have reference data
        $this->ensureReferenceData();

        // Get reference data
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $userRole = Role::where('name', 'User')->first();
        $driverRole = Role::where('name', 'Driver')->first();

        $businessUnit = BusinessUnit::first();
        $companyCode = CompanyCode::first();
        $branch = Branch::first();
        $department = Department::first();

        // Create Admin User
        $admin = User::create([
            'first_name' => 'John',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'mobile_number' => '+1234567890',
            'business_unit_id' => $businessUnit->id,
            'company_code_id' => $companyCode->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Create Manager/Approver User
        $manager = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password123'),
            'mobile_number' => '+1234567891',
            'business_unit_id' => $businessUnit->id,
            'company_code_id' => $companyCode->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'role_id' => $managerRole->id,
            'is_active' => true,
        ]);

        // Create Regular User (Requester)
        $requester = User::create([
            'first_name' => 'Bob',
            'last_name' => 'Requester',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'mobile_number' => '+1234567892',
            'business_unit_id' => $businessUnit->id,
            'company_code_id' => $companyCode->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        // Create Driver User (Also acts as Requester)
        $driver = User::create([
            'first_name' => 'Mike',
            'last_name' => 'Driver',
            'email' => 'driver@example.com',
            'password' => Hash::make('password123'),
            'mobile_number' => '+1234567893',
            'business_unit_id' => $businessUnit->id,
            'company_code_id' => $companyCode->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'role_id' => $driverRole->id,
            'is_active' => true,
        ]);

        // Create additional test users
        $this->createAdditionalUsers($businessUnit, $companyCode, $branch, $department, $userRole, $managerRole);

        $this->command->info('✅ Test users created successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Admin: admin@example.com / password123');
        $this->command->info('   Manager: manager@example.com / password123');
        $this->command->info('   User: user@example.com / password123');
        $this->command->info('   Driver: driver@example.com / password123');
    }

    private function ensureReferenceData()
    {
        // Create roles if they don't exist
        $roles = ['Admin', 'Manager', 'User', 'Driver'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create business units if they don't exist
        if (BusinessUnit::count() === 0) {
            BusinessUnit::create(['name' => 'Information Technology']);
            BusinessUnit::create(['name' => 'Human Resources']);
            BusinessUnit::create(['name' => 'Finance & Accounting']);
        }

        // Create company codes if they don't exist
        if (CompanyCode::count() === 0) {
            CompanyCode::create(['name' => 'GMALL-HQ']);
            CompanyCode::create(['name' => 'GMALL-NORTH']);
        }

        // Create branches if they don't exist
        if (Branch::count() === 0) {
            Branch::create(['name' => 'Head Office']);
            Branch::create(['name' => 'Manila Branch']);
        }

        // Create departments if they don't exist
        if (Department::count() === 0) {
            Department::create(['name' => 'IT Development']);
            Department::create(['name' => 'HR Management']);
            Department::create(['name' => 'Finance']);
        }
    }

    private function createAdditionalUsers($businessUnit, $companyCode, $branch, $department, $userRole, $managerRole)
    {
        // Create 5 additional regular users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'first_name' => 'User',
                'last_name' => $i,
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password123'),
                'mobile_number' => '+123456789' . $i,
                'business_unit_id' => $businessUnit->id,
                'company_code_id' => $companyCode->id,
                'branch_id' => $branch->id,
                'department_id' => $department->id,
                'role_id' => $userRole->id,
                'is_active' => true,
            ]);
        }

        // Create 2 additional managers
        for ($i = 1; $i <= 2; $i++) {
            User::create([
                'first_name' => 'Manager',
                'last_name' => $i,
                'email' => "manager{$i}@example.com",
                'password' => Hash::make('password123'),
                'mobile_number' => '+123456780' . $i,
                'business_unit_id' => $businessUnit->id,
                'company_code_id' => $companyCode->id,
                'branch_id' => $branch->id,
                'department_id' => $department->id,
                'role_id' => $managerRole->id,
                'is_active' => true,
            ]);
        }
    }
}