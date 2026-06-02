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
                Section::make('Informasi Utama')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('taxpayer.name')
                                    ->label('Wajib Pajak')
                                    ->limit(30)
                                    ->url(fn($record): string => TaxpayerResource::getUrl(
                                        'edit',
                                        ['record' => $record->taxpayer],
                                    ))
                                    ->openUrlInNewTab()
                                    ->color('primary')
                                    ->weight('medium')
                                    ->extraAttributes([
                                        'class' => '
                                        cursor-pointer
                                        transition
                                        duration-200
                                        hover:text-primary-500
                                        hover:underline
                                        hover:underline-offset-4
                                    ',
                                    ])
                                    ->tooltip(function ($record): HtmlString {
                                        return new HtmlString(implode('<br>', [
                                            "Nama: {$record->taxpayer?->name}",
                                            "NPWP: {$record->taxpayer?->npwp}",
                                            "NIK: {$record->taxpayer?->nik}",
                                            "Email: {$record->taxpayer?->email}",
                                            "No HP: {$record->taxpayer?->phone}",
                                            "Alamat: {$record->taxpayer?->address}",
                                        ]));
                                    })
                                    ->placeholder('-'),

                                TextEntry::make('pphType.code')
                                    ->label('Jenis PPH')
                                    ->badge()
                                    ->color(fn(?string $state): string => match ($state) {
                                        'PPH 21' => 'success',
                                        'PPH 23' => 'warning',
                                        'PPH 4(2)' => 'info',
                                        default => 'gray',
                                    })
                                    ->placeholder('-'),

                                TextEntry::make('project_name')
                                    ->label('Nama Projek')
                                    ->columnSpanFull()
                                    ->placeholder('-'),

                                TextEntry::make('pic.name')
                                    ->label('PIC')
                                    ->placeholder('-'),

                                TextEntry::make('creator.name')
                                    ->label('Dibuat Oleh')
                                    ->placeholder('-'),
                            ]),
                    ]),

                Section::make('Invoice & Referensi')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('invoice_number')
                                    ->label('Nomor Invoice')
                                    ->copyable()
                                    ->placeholder('-'),

                                TextEntry::make('reference_number')
                                    ->label('Nomor Referensi')
                                    ->copyable()
                                    ->placeholder('-'),
                            ]),
                    ]),

                Section::make('Status')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                IconEntry::make('input_status')
                                    ->label('Sudah Diinput')
                                    ->boolean(),

                                IconEntry::make('payment_status')
                                    ->label('Sudah Dibayar')
                                    ->boolean(),
                            ]),
                    ]),
                Section::make('Tanggal')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('invoice_date')
                                    ->label('Tanggal Invoice')
                                    ->date('d/m/Y')
                                    ->placeholder('-'),

                                TextEntry::make('payment_date')
                                    ->label('Tanggal Pembayaran')
                                    ->date('d/m/Y')
                                    ->placeholder('-'),
                            ]),
                    ]),

                Section::make('Nominal')
                    ->icon('heroicon-o-banknotes')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('base_amount')
                                    ->label('Nilai Dasar')
                                    ->money('IDR')
                                    ->placeholder('-'),

                                TextEntry::make('gross_up_amount')
                                    ->label('Gross Up')
                                    ->money('IDR')
                                    ->placeholder('-'),

                                TextEntry::make('pph_amount')
                                    ->label('Nilai PPH')
                                    ->money('IDR')
                                    ->color('danger')
                                    ->placeholder('-'),

                                TextEntry::make('take_home_pay')
                                    ->label('Take Home Pay')
                                    ->money('IDR')
                                    ->color('success')
                                    ->placeholder('-'),

                                TextEntry::make('djp_tax_amount')
                                    ->label('Nilai Pajak DJP')
                                    ->money('IDR')
                                    ->placeholder('-'),
                            ]),
                    ]),

                Section::make('Catatan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        TextEntry::make('note')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Metadata')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d/m/Y H:i'),

                                TextEntry::make('updated_at')
                                    ->label('Diupdate Pada')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ]),
            ]);
    }
}