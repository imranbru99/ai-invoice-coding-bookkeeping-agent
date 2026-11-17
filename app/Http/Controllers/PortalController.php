<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\VendorReconciliation;
use App\Services\ModuleManager;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(Request $request, ModuleManager $modules): View
    {
        abort_unless($modules->enabled('vendor_reconciliation') || $modules->enabled('invoice_ocr'), 404);

        $user = $request->user();
        $vendors = Vendor::query()
            ->where('portal_user_id', $user?->id)
            ->withCount('invoices')
            ->get();

        $invoices = Invoice::query()
            ->whereHas('vendor', fn ($q) => $q->where('portal_user_id', $user?->id))
            ->latest()
            ->limit(20)
            ->get();

        $reconciliations = VendorReconciliation::query()
            ->whereHas('vendor', fn ($q) => $q->where('portal_user_id', $user?->id))
            ->latest()
            ->limit(10)
            ->get();

        return view('portal.index', compact('vendors', 'invoices', 'reconciliations'));
    }
}
