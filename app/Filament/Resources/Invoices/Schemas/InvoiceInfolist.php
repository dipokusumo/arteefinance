<?php

namespace App\Filament\Resources\Invoices\Schemas;


use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\IconEntry;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Filament\Resources\Taxpayers\TaxpayerResource;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')->columnSpanFull()->columns(3)->schema([
                    TextEntry::make('taxpayer.name')
                        ->label('Wajib Pajak')
                        ->placeholder('-'),
                    TextEntry::make('pic.name')
                        ->label('PIC')
                        ->placeholder('-'),
                    TextEntry::make('project_name')
                        ->label('Nama Projek')
                        ->placeholder('-'),
                ]),
                Section::make('Detail Invoice')->columnSpanFull()->columns(2)->schema([
                    TextEntry::make('invoice_date')
                        ->label('Tanggal Invoice')
                        ->date(format: 'd M Y')
                        ->placeholder('-'),
                    TextEntry::make('payment_date')
                        ->label('Tanggal Pembayaran')
                        ->date(format: 'd M Y')
                        ->placeholder('-'),
                    TextEntry::make('pphType.code')
                        ->label('Jenis PPh')
                        ->placeholder('-'),
                    TextEntry::make('base_amount')
                        ->label('Nilai Dasar')
                        ->numeric()
                        ->placeholder('-')
                        ->prefix('IDR '),
                    TextEntry::make('pph_amount')
                        ->label('Nilai PPh')
                        ->numeric()
                        ->placeholder('-')
                        ->prefix('IDR '),
                    TextEntry::make('gross_up_amount')
                        ->label('Nilai Gross Up')
                        ->numeric()
                        ->placeholder('-')
                        ->prefix('IDR '),
                    TextEntry::make('take_home_pay')
                        ->label('Jumlah (THP)')
                        ->numeric()
                        ->placeholder('-')
                        ->prefix('IDR '),
                    TextEntry::make('djp_tax_amount')
                        ->label('Nilai Pajak DJP')
                        ->numeric()
                        ->placeholder('-')
                        ->prefix('IDR '),
                    Section::make('Informasi Tambahan')->contained(false)->schema([
                        TextEntry::make('invoice_number')
                            ->label('Nomor Invoice')
                            ->placeholder('-'),
                        TextEntry::make('reference_number')
                            ->label('Nomor Referensi')
                            ->placeholder('-'),
                        TextEntry::make('note')
                            ->label('Catatan')
                            ->placeholder('-'),
                    ])->columns(3)->columnSpanFull()
                ]),
                Section::make('Status')->columnSpanFull()->columns(2)->schema([
                    IconEntry::make('input_status')
                        ->label('Status Input')
                        ->boolean(),
                    IconEntry::make('payment_status')
                        ->label('Status Pembayaran')
                        ->boolean(),
                ]),
                Section::make('Riwayat')->columnSpanFull()->columns(3)->schema([
                    TextEntry::make('created_at')
                        ->label('Tanggal Dibuat')
                        ->date(format: 'd M Y H:i:s')
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->label('Terakhir Diubah')
                        ->date(format: 'd M Y H:i:s')
                        ->placeholder('-'),
                    TextEntry::make('creator.name')
                        ->label('Dibuat Oleh')
                        ->placeholder('-'),
                ]),
            ]);
    }
}