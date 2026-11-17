<?php

namespace Tests\Feature;

use App\Services\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_lists_modules(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('AI Invoice Coding');
        $response->assertSee('Invoice OCR');
    }

    public function test_module_manager_can_toggle(): void
    {
        $manager = app(ModuleManager::class);
        $manager->toggle('bank_feed', false);

        $this->assertFalse($manager->enabled('bank_feed'));
    }
}
