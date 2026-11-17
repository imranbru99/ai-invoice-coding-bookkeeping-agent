<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::Client;
    }

    public function view(User $user, Vendor $vendor): bool
    {
        if ($user->role === UserRole::Client) {
            return $vendor->portal_user_id === $user->id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            UserRole::SuperAdmin,
            UserRole::Admin,
            UserRole::Manager,
            UserRole::Staff,
            UserRole::Demo,
        ], true);
    }

    public function update(User $user, Vendor $vendor): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin();
    }
}
