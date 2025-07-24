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

        // Get user info for debugging
        $user = auth()->user();
        $role = $user->role;
        
        // Redirect based on user role
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        
        // Log the redirection for debugging
        \Log::info('User login successful', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'role_name' => $role?->name,
            'redirect_url' => $redirectUrl
        ]);
        
        $this->redirectIntended(default: $redirectUrl, navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
