<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::Client;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($user->role === UserRole::Client) {
            return $invoice->vendor?->portal_user_id === $user->id;
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

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin();
    }
}
