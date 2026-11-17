<?php

namespace App\Filament\Resources\VendorReconciliations\Pages;

use App\Filament\Resources\VendorReconciliations\VendorReconciliationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorReconciliations extends ListRecords
{
    protected static string $resource = VendorReconciliationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
