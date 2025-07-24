<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\CompanyCode;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Register extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $mobile_number = '';
    public $business_unit_id = '';
    public $company_code_id = '';
    public $branch_id = '';
    public $department_id = '';
    public $role_id = '';

    // Collections for dropdowns
    public $businessUnits;
    public $companyCodes;
    public $branches;
    public $departments;
    public $roles;

    public function mount()
    {
        // Load data for dropdowns
        $this->businessUnits = BusinessUnit::all();
        $this->companyCodes = CompanyCode::all();
        $this->branches = Branch::all();
        $this->departments = Department::all();
        $this->roles = Role::all();
        
        // Set default role to 'User' if exists
        $defaultRole = Role::where('name', 'User')->first();
        if ($defaultRole) {
            $this->role_id = $defaultRole->id;
        }
    }

    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'mobile_number' => ['required', 'string', 'max:15'],
            'business_unit_id' => ['required', 'exists:business_units,id'],
            'company_code_id' => ['required', 'exists:company_codes,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true; // Set as active by default

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.register');
    }
}
