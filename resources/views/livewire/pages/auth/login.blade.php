<div class="d-flex flex-column align-items-center justify-content-center text-center">
    <!-- Logo -->
    <div class="mb-3">
        <img src="{{ asset('images/gmall.png') }}" alt="Logo" style="height: 60px;">
    </div>

    <h3 class="mb-4">Login</h3>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form wire:submit="login" class="w-100" style="max-width: 400px;">
        <!-- Email -->
        <div class="mb-3 text-start">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="form-control" type="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password" class="form-control" type="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check text-start mb-3">
            <input wire:model="form.remember" class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label" for="remember">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Submit -->
        <div class="text-center mt-3">
            <x-primary-button class="w-100 text-center d-block mx-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Forgot Password -->
    @if (Route::has('password.request'))
        <div class="text-end mt-2" style="margin-bottom: 10px;">
            <a href="{{ route('password.request') }}" class="text-decoration-none text-secondary small">
                {{ __('Forgot your password?') }}
            </a>
        </div>
    @endif

    <!-- Sign Up Link -->
    <div class="text-center mt-3">
        <p class="mb-0 small">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                {{ __('Sign Up') }}
            </a>
        </p>
    </div>
</div>
