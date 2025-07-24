# 📝 Complete Code Files

## 🔧 **CORE UPDATED FILES**

### 1. `app/Models/User.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'branch_id',
        'department_id',
        'business_unit_id',
        'company_code_id',
        'role_id',
        'first_name',
        'last_name',
        'mobile_number',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the branch that owns the user.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the department that owns the user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the business unit that owns the user.
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    /**
     * Get the company code that owns the user.
     */
    public function companyCode(): BelongsTo
    {
        return $this->belongsTo(CompanyCode::class);
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the bookings for the user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the approvers for the user.
     */
    public function approvers(): HasMany
    {
        return $this->hasMany(Approver::class);
    }

    /**
     * Get the approval logs for the user.
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class, 'approver_id');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
```

### 2. `routes/web.php`
```php
<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/welcome');

// Protected Routes (require auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
});

// Role-based routes (inline to avoid path issues)

// Requester Routes
Route::prefix('requester')->name('requester.')->middleware(['auth', 'verified'])->group(function () {
    // Livewire Routes
    Route::get('/my-dashboard', App\Livewire\Requester\Dashboard::class)->name('livewire.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    // Livewire Dashboard
    Route::get('/livewire-dashboard', App\Livewire\Admin\Dashboard::class)->name('livewire.dashboard');
});

// Approver Routes
Route::prefix('approver')->name('approver.')->middleware(['auth', 'verified', 'role:Manager,Admin'])->group(function () {
    // Livewire Dashboard
    Route::get('/livewire-dashboard', App\Livewire\Approver\Dashboard::class)->name('livewire.dashboard');
});

// Fallback redirect to login for any unmatched route
Route::fallback(function () {
    return redirect('/login');
});

require __DIR__ . '/auth.php';
```

### 3. `routes/auth.php`
```php
<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
```

### 4. `bootstrap/app.php`
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

### 5. `database/seeders/DatabaseSeeder.php`
```php
<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed reference data first
        $this->call([
            ReferenceDataSeeder::class,
            UserRoleSeeder::class,
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
```

---

## 🔐 **AUTHENTICATION FILES**

### 6. `app/Livewire/Pages/Auth/Register.php`
```php
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
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $full_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $mobile_number = '';
    public $business_unit_id = '';
    public $company_code_id = '';
    public $branch_id = '';
    public $department_id = '';
    public $role_id = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'mobile_number' => ['required', 'string', 'max:20'],
            'business_unit_id' => ['required', 'exists:business_units,id'],
            'company_code_id' => ['required', 'exists:company_codes,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Split full name into first and last name
        $nameParts = explode(' ', trim($validated['full_name']), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Prepare data for user creation
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'mobile_number' => $validated['mobile_number'],
            'business_unit_id' => $validated['business_unit_id'],
            'company_code_id' => $validated['company_code_id'],
            'branch_id' => $validated['branch_id'],
            'department_id' => $validated['department_id'],
            'role_id' => $validated['role_id'],
            'is_active' => true, // Set as active by default
        ];

        event(new Registered($user = User::create($userData)));

        Auth::login($user);

        // Redirect based on user role
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        $this->redirect($redirectUrl, navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        try {
            // Load reference data with fallbacks
            $businessUnits = BusinessUnit::all();
            $companyCodes = CompanyCode::all();
            $branches = Branch::all();
            $departments = Department::all();
            $roles = Role::all();

            // Create default data if tables are empty
            if ($businessUnits->isEmpty()) {
                BusinessUnit::firstOrCreate(['name' => 'Information Technology']);
                BusinessUnit::firstOrCreate(['name' => 'Human Resources']);
                $businessUnits = BusinessUnit::all();
            }

            if ($companyCodes->isEmpty()) {
                CompanyCode::firstOrCreate(['name' => 'GMALL-HQ']);
                CompanyCode::firstOrCreate(['name' => 'GMALL-NORTH']);
                $companyCodes = CompanyCode::all();
            }

            if ($branches->isEmpty()) {
                Branch::firstOrCreate(['name' => 'Head Office']);
                Branch::firstOrCreate(['name' => 'Manila Branch']);
                $branches = Branch::all();
            }

            if ($departments->isEmpty()) {
                Department::firstOrCreate(['name' => 'IT Development']);
                Department::firstOrCreate(['name' => 'HR Management']);
                $departments = Department::all();
            }

            if ($roles->isEmpty()) {
                Role::firstOrCreate(['name' => 'Admin']);
                Role::firstOrCreate(['name' => 'Manager']);
                Role::firstOrCreate(['name' => 'User']);
                Role::firstOrCreate(['name' => 'Driver']);
                $roles = Role::all();
            }

        } catch (\Exception $e) {
            // Fallback to empty collections if there's any error
            $businessUnits = collect();
            $companyCodes = collect();
            $branches = collect();
            $departments = collect();
            $roles = collect();
        }

        return view('livewire.pages.auth.register', [
            'businessUnits' => $businessUnits,
            'companyCodes' => $companyCodes,
            'branches' => $branches,
            'departments' => $departments,
            'roles' => $roles,
        ]);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Full Name -->
        <div class="mb-3 text-start">
            <x-input-label for="full_name" :value="__('Full Name')" />
            <x-text-input wire:model="full_name" id="full_name" class="form-control" type="text" required autofocus autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-3 text-start">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="form-control" type="email" required autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mobile Number -->
        <div class="mb-3 text-start">
            <x-input-label for="mobile_number" :value="__('Mobile Number')" />
            <x-text-input wire:model="mobile_number" id="mobile_number" class="form-control" type="text" required placeholder="Enter your mobile number" />
            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
        </div>

        <!-- Business Unit -->
        <div class="mb-3 text-start">
            <x-input-label for="business_unit_id" :value="__('Business Unit')" />
            <select wire:model="business_unit_id" id="business_unit_id" class="form-control" required>
                <option value="">Select Business Unit</option>
                @if(isset($businessUnits) && $businessUnits->count() > 0)
                    @foreach($businessUnits as $businessUnit)
                        <option value="{{ $businessUnit->id }}">{{ $businessUnit->name }}</option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('business_unit_id')" class="mt-2" />
        </div>

        <!-- Company Code -->
        <div class="mb-3 text-start">
            <x-input-label for="company_code_id" :value="__('Company')" />
            <select wire:model="company_code_id" id="company_code_id" class="form-control" required>
                <option value="">Select Company</option>
                @if(isset($companyCodes) && $companyCodes->count() > 0)
                    @foreach($companyCodes as $companyCode)
                        <option value="{{ $companyCode->id }}">{{ $companyCode->name }}</option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('company_code_id')" class="mt-2" />
        </div>

        <!-- Branch -->
        <div class="mb-3 text-start">
            <x-input-label for="branch_id" :value="__('Branch')" />
            <select wire:model="branch_id" id="branch_id" class="form-control" required>
                <option value="">Select Branch</option>
                @if(isset($branches) && $branches->count() > 0)
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
        </div>

        <!-- Department -->
        <div class="mb-3 text-start">
            <x-input-label for="department_id" :value="__('Department')" />
            <select wire:model="department_id" id="department_id" class="form-control" required>
                <option value="">Select Department</option>
                @if(isset($departments) && $departments->count() > 0)
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mb-3 text-start">
            <x-input-label for="role_id" :value="__('Role')" />
            <select wire:model="role_id" id="role_id" class="form-control" required>
                <option value="">Select Role</option>
                @if(isset($roles) && $roles->count() > 0)
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="form-control" type="password" required autocomplete="new-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3 text-start">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="form-control" type="password" required autocomplete="new-password" placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
```

### 7. `app/Livewire/Pages/Auth/Login.php`
```php
<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Login extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();

        // Redirect based on user role
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        $this->redirectIntended(default: $redirectUrl, navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
```

---

## 🎛️ **NAVIGATION FILES**

### 8. `resources/views/livewire/layout/navigation.blade.php`
```php
<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->full_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->full_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
```

### 9. `resources/views/layouts/navigation.blade.php`
```php
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->full_name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->full_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
```

---

This is the first part of the complete code files. The document is getting quite long. Would you like me to continue with the remaining files (middleware, services, Livewire components, etc.) in a separate response, or would you prefer a specific subset of files?

The tracker shows we have 72 total files, and I can provide the complete code for all of them systematically.