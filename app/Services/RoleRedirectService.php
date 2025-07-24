<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class RoleRedirectService
{
    /**
     * Get the appropriate redirect URL based on user role
     */
    public static function getRedirectUrl(): string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return '/dashboard'; // Default fallback
        }

        $roleName = $user->role->name;

        return match($roleName) {
            'Admin' => '/admin/livewire-dashboard',
            'Manager' => '/approver/livewire-dashboard', 
            'Driver' => '/requester/my-dashboard',
            'User' => '/requester/my-dashboard',
            default => '/dashboard'
        };
    }

    /**
     * Get the appropriate Livewire component based on user role
     */
    public static function getDashboardComponent(): string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return 'dashboard'; // Default fallback
        }

        $roleName = $user->role->name;

        return match($roleName) {
            'Admin' => 'admin.dashboard',
            'Manager' => 'approver.dashboard', 
            'Driver' => 'requester.dashboard',
            'User' => 'requester.dashboard',
            default => 'dashboard'
        };
    }

    /**
     * Check if user has access to a specific role section
     */
    public static function hasRoleAccess(string $role): bool
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return false;
        }

        $userRole = $user->role->name;
        $allowedRoles = explode(',', $role);

        return in_array($userRole, $allowedRoles);
    }

    /**
     * Get role-specific menu items
     */
    public static function getMenuItems(): array
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return [];
        }

        $roleName = $user->role->name;

        return match($roleName) {
            'Admin' => [
                ['name' => 'Admin Dashboard', 'url' => '/admin/dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Manage Users', 'url' => '/admin/users', 'icon' => 'fas fa-users'],
                ['name' => 'All Bookings', 'url' => '/admin/bookings', 'icon' => 'fas fa-calendar-alt'],
                ['name' => 'Manage Assets', 'url' => '/admin/asset-types', 'icon' => 'fas fa-car'],
                ['name' => 'Analytics', 'url' => '/admin/analytics', 'icon' => 'fas fa-chart-line'],
                ['name' => 'Approvers', 'url' => '/admin/approvers', 'icon' => 'fas fa-user-check'],
            ],
            'Manager' => [
                ['name' => 'Approver Dashboard', 'url' => '/approver/dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Pending Approvals', 'url' => '/approver/approvals/pending', 'icon' => 'fas fa-clock'],
                ['name' => 'Approval History', 'url' => '/approver/approvals/my-history', 'icon' => 'fas fa-history'],
                ['name' => 'My Bookings', 'url' => '/requester/my-dashboard', 'icon' => 'fas fa-calendar'],
            ],
            'Driver' => [
                ['name' => 'My Dashboard', 'url' => '/requester/my-dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'My Bookings', 'url' => '/requester/bookings', 'icon' => 'fas fa-calendar'],
                ['name' => 'New Booking', 'url' => '/requester/bookings/create', 'icon' => 'fas fa-plus'],
            ],
            'User' => [
                ['name' => 'My Dashboard', 'url' => '/requester/my-dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'My Bookings', 'url' => '/requester/bookings', 'icon' => 'fas fa-calendar'],
                ['name' => 'New Booking', 'url' => '/requester/bookings/create', 'icon' => 'fas fa-plus'],
            ],
            default => []
        };
    }

    /**
     * Get the user's role display name
     */
    public static function getRoleDisplayName(): string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return 'User';
        }

        return $user->role->name;
    }
}