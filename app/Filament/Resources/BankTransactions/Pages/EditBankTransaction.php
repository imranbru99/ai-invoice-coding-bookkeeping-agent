<?php

namespace App\Filament\Resources\BankTransactions\Pages;

use App\Filament\Resources\BankTransactions\BankTransactionResource;
use Filament\Resources\Pages\EditRecord;

class EditBankTransaction extends EditRecord
{
    protected static string $resource = BankTransactionResource::class;
}
