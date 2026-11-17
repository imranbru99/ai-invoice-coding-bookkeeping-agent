<?php

namespace App\Http\Controllers;

use App\Services\ModuleManager;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(ModuleManager $modules): View
    {
        return view('marketing.home', [
            'modules' => $modules->all(),
            'accent' => config('accounting.accent'),
            'product' => config('accounting.product_name'),
        ]);
    }

    public function features(ModuleManager $modules): View
    {
        return view('marketing.features', [
            'modules' => $modules->all(),
            'accent' => config('accounting.accent'),
        ]);
    }

    public function modules(ModuleManager $modules): View
    {
        return view('marketing.modules', [
            'modules' => $modules->all(),
            'accent' => config('accounting.accent'),
        ]);
    }

    public function pricing(): View
    {
        return view('marketing.pricing', [
            'accent' => config('accounting.accent'),
        ]);
    }

    public function security(): View
    {
        return view('marketing.security', [
            'accent' => config('accounting.accent'),
        ]);
    }

    public function demo(): View
    {
        return view('marketing.demo', [
            'accent' => config('accounting.accent'),
        ]);
    }
}
