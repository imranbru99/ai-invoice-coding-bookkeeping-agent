<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\JournalEntry;
use App\Models\User;

class JournalEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::Client;
    }

    public function view(User $user, JournalEntry $journalEntry): bool
    {
        return $this->viewAny($user);
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

    public function update(User $user, JournalEntry $journalEntry): bool
    {
        return $this->create($user) && $journalEntry->status !== 'posted';
    }

    public function delete(User $user, JournalEntry $journalEntry): bool
    {
        return $user->isAdmin() && $journalEntry->status !== 'posted';
    }
}
