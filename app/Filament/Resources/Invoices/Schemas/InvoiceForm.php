<?php

namespace App\Filament\Resources\Invoices\Schemas;

use function App\Helpers\ParseCurrency;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

use App\Helpers\ParseCurrency;
use Filament\Schemas\Components\Fieldset;
use App\Models\PphType;

use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {

        $calculateTax = function (callable $get, callable $set): void {
            $baseAmount = ParseCurrency::parseCurrency($get('base_amount'));
            $pphTypeId = $get('pph_type_id');

            if (!$pphTypeId || !$baseAmount) {
                $set('pph_amount', 0);
                $set('gross_up_amount', 0);
                $set('take_home_pay', 0);
                $set('djp_tax_amount', 0);

                return;
            }

            $pphType = PphType::find($pphTypeId);

            if (!$pphType) {
                return;
            }

            $pphFactor = (float) $pphType->factor;

            $grossUpAmount = ($baseAmount / $pphFactor);

            $pphAmount = ($grossUpAmount - $baseAmount);

            $takeHomePay = $baseAmount;

            $set('pph_amount', ParseCurrency::formatIDR($pphAmount));
            $set('gross_up_amount', ParseCurrency::formatIDR($grossUpAmount));
            $set('take_home_pay', ParseCurrency::formatIDR($takeHomePay));
            $set('djp_tax_amount', ParseCurrency::formatIDR($pphAmount));
        };

        return $schema
            ->components([

                Fieldset::make('Informasi Umum')
                    ->columns(2)
                    ->columnSpan('full')
                    ->schema([
                        Select::make('taxpayer_id')
                            ->label('Pilih Wajib Pajak')
                            ->searchable()
                            ->preload()
                            ->relationship('taxpayer', 'name')
                            ->required(),
                        Select::make('pic_id')
                            ->label('Pilih PIC')
                            ->searchable()
                            ->preload()
                            ->relationship('pic', 'name')
                            ->required(),

                    ]),
                Fieldset::make('Detail Invoice')
                    ->columns(2)
                    ->columnSpan('full')
                    ->schema([
                        DatePicker::make('invoice_date')
                            ->label('Tanggal Invoice')
                            ->required(),
                        DatePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->required(),

                        Select::make('pph_type_id')
                            ->label('Jenis PPH')
                            ->relationship('pphType', 'code')
                            ->required()
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn($state, callable $set, callable $get) => $calculateTax($get, $set)),

                        InputIDR('base_amount', 'Nilai Dasar')
                            ->required()
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn($state, callable $set, callable $get) => $calculateTax($get, $set)),

                        TextInput::make('pph_amount')
                            ->label('Nilai PPH')
                            ->numeric()
                            ->disabled()
                            ->prefix('IDR'),

                        TextInput::make('gross_up_amount')
                            ->label('Nilai Gross Up')
                            ->disabled()
                            ->required()
                            ->prefix('IDR'),

                        TextInput::make('take_home_pay')
                            ->label('Jumlah (THP)')
                            ->disabled()
                            ->prefix('IDR'),


                        TextInput::make('djp_tax_amount')
                            ->label('Nilai Pajak DJP')
                            ->numeric()
                            ->disabled()
                            ->prefix('IDR'),

                        Fieldset::make('Informasi Tambahan')
                            ->contained(false)
                            ->schema([
                                TextInput::make('invoice_number'),
                                TextInput::make('reference_number'),
                                TextInput::make('note'),
                            ])->columns(2)->columnSpan('full')
                    ]),

                Fieldset::make('Status')
                    ->schema([
                        Toggle::make('input_status')
                            ->required(),
                        Toggle::make('payment_status')
                            ->required(),
                    ])
                    ->label('Status')
                    ->columns(2)
                    ->columnSpan('full'),
                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }
}
