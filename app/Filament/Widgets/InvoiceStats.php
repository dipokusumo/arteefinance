<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvoiceStats extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 2;
    }
    
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Invoice',
                number_format(Invoice::count())
            )
                ->description('Lihat seluruh invoice')
                ->descriptionIcon('heroicon-m-arrow-right')
                ->color('primary')
                ->url(route('filament.admin.resources.invoices.index')),

            Stat::make(
                'Belum Dibayar',
                number_format(
                    Invoice::where('payment_status', false)->count()
                )
            )
                ->description('Klik untuk melihat')
                ->descriptionIcon('heroicon-m-arrow-right')
                ->color('danger')
                ->url(
                    route(
                        'filament.admin.resources.invoices.index',
                        [
                            'tableFilters' => [
                                'advanced' => [
                                    'payment_status' => '0',
                                ],
                            ],
                        ]
                    )
                ),
        ];
    }
}
