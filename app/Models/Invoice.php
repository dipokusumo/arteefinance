<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ParseCurrency;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'taxpayer_id',
        'pph_type_id',
        'pic_id',
        'created_by',
        'invoice_number',
        'reference_number',
        'input_status',
        'payment_status',
        'invoice_date',
        'payment_date',
        'base_amount',
        'gross_up_amount',
        'pph_amount',
        'take_home_pay',
        'djp_tax_amount',
        'note',
    ];

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function pphType()
    {
        return $this->belongsTo(PphType::class, 'pph_type_id');
    }

    public function pic()
    {
        return $this->belongsTo(Pic::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::saving(function (Invoice $invoice): void {
            $invoice->base_amount = ParseCurrency::parseCurrency($invoice->base_amount);

            if (!$invoice->pphType || !$invoice->base_amount) {
                $invoice->pph_amount = 0;
                $invoice->gross_up_amount = 0;
                $invoice->take_home_pay = 0;
                $invoice->djp_tax_amount = 0;

                return;
            }

            $factor = (float) $invoice->pphType->factor;

            $pphAmount = $invoice->base_amount * $factor;

            $grossUpAmount = $invoice->base_amount + $pphAmount;

            $takeHomePay = $grossUpAmount - $pphAmount;

            $invoice->pph_amount = $pphAmount;
            $invoice->gross_up_amount = $grossUpAmount;
            $invoice->take_home_pay = $takeHomePay;
            $invoice->djp_tax_amount = $pphAmount;
        });
    }

}
