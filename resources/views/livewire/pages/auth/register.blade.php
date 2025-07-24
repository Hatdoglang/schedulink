<div class="d-flex flex-column align-items-center justify-content-center text-center">
    <!-- Logo -->
    <div class="mb-3">
        <img src="{{ asset('images/gmall.png') }}" alt="Logo" style="height: 60px;">
    </div>

    <h3 class="mb-4">Register</h3>

    <form wire:submit="register" class="w-100" style="max-width: 400px;">
        <!-- Name -->
        <div class="mb-3 text-start">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="form-control" type="text" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mb-3 text-start">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="form-control" type="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="form-control" type="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3 text-start">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="form-control" type="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="text-center mt-3">
            <x-primary-button class="w-100 text-center d-block mx-auto">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Already Registered -->
    <div class="text-center mt-3">
        <p class="mb-0 small">
            {{ __('Already registered?') }}
            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none" wire:navigate>
                {{ __('Log in') }}
            </a>
        </p>
    </div>
</div>
