<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Vendor;
use App\Policies\InvoicePolicy;
use App\Policies\JournalEntryPolicy;
use App\Policies\VendorPolicy;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(): void
    {
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Vendor::class, VendorPolicy::class);
        Gate::policy(JournalEntry::class, JournalEntryPolicy::class);
    }
}
