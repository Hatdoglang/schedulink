<div class="d-flex flex-column align-items-center justify-content-center text-center">
    <!-- Logo -->
    <div class="mb-3">
        <img src="{{ asset('images/gmall.png') }}" alt="Logo" style="height: 60px;">
    </div>

    <h3 class="mb-4">Create Account</h3>

    <form wire:submit="register" class="w-100" style="max-width: 600px;">
        <!-- Business Information Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Business Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <!-- Business Unit -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="business_unit_id" :value="__('Business Unit')" />
                        <select wire:model="business_unit_id" id="business_unit_id" class="form-select" required>
                            <option value="">Select Business Unit</option>
                            @if(isset($businessUnits) && $businessUnits->count() > 0)
                                @foreach($businessUnits as $businessUnit)
                                    <option value="{{ $businessUnit->id }}">{{ $businessUnit->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>No business units available</option>
                            @endif
                        </select>
                        <x-input-error :messages="$errors->get('business_unit_id')" class="mt-2" />
                    </div>

                    <!-- Company Code -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="company_code_id" :value="__('Company Code')" />
                        <select wire:model="company_code_id" id="company_code_id" class="form-select" required>
                            <option value="">Select Company Code</option>
                            @if(isset($companyCodes) && $companyCodes->count() > 0)
                                @foreach($companyCodes as $companyCode)
                                    <option value="{{ $companyCode->id }}">{{ $companyCode->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>No company codes available</option>
                            @endif
                        </select>
                        <x-input-error :messages="$errors->get('company_code_id')" class="mt-2" />
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Branch -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="branch_id" :value="__('Branch')" />
                        <select wire:model="branch_id" id="branch_id" class="form-select" required>
                            <option value="">Select Branch</option>
                            @if(isset($branches) && $branches->count() > 0)
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>No branches available</option>
                            @endif
                        </select>
                        <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                    </div>

                    <!-- Department -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="department_id" :value="__('Department')" />
                        <select wire:model="department_id" id="department_id" class="form-select" required>
                            <option value="">Select Department</option>
                            @if(isset($departments) && $departments->count() > 0)
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>No departments available</option>
                            @endif
                        </select>
                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <!-- First Name -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input wire:model="first_name" id="first_name" class="form-control" type="text" required autofocus autocomplete="given-name" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input wire:model="last_name" id="last_name" class="form-control" type="text" required autocomplete="family-name" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Email -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input wire:model="email" id="email" class="form-control" type="email" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Mobile Number -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="mobile_number" :value="__('Mobile Number')" />
                        <x-text-input wire:model="mobile_number" id="mobile_number" class="form-control" type="tel" required autocomplete="tel" placeholder="+63 XXX XXX XXXX" />
                        <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
                    </div>
                </div>

                <!-- Role -->
                <div class="mb-3 text-start">
                    <x-input-label for="role_id" :value="__('Role')" />
                    <select wire:model="role_id" id="role_id" class="form-select" required>
                        <option value="">Select Role</option>
                        @if(isset($roles) && $roles->count() > 0)
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No roles available</option>
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Security Information Section -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-lock me-2"></i>Security Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <!-- Password -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input wire:model="password" id="password" class="form-control" type="password" required autocomplete="new-password" />
                        <small class="form-text text-muted">Password must be at least 8 characters long</small>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 text-start">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input wire:model="password_confirmation" id="password_confirmation" class="form-control" type="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-center mt-4">
            <x-primary-button class="w-100 text-center d-block mx-auto py-3">
                <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Already Registered -->
    <div class="text-center mt-4">
        <p class="mb-0">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none" wire:navigate>
                {{ __('Sign In') }}
            </a>
        </p>
    </div>
</div>

<style>
.card-header h6 {
    font-weight: 600;
}

.form-select:focus,
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}
</style>
