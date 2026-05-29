<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('taxpayer_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('pph_type_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('pic_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('invoice_number')
                    ->placeholder('-'),
                TextEntry::make('reference_number')
                    ->placeholder('-'),
                IconEntry::make('input_status')
                    ->boolean(),
                IconEntry::make('payment_status')
                    ->boolean(),
                TextEntry::make('invoice_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('payment_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('base_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('gross_up_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('pph_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('take_home_pay')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('djp_tax_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('note')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
