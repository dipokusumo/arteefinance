<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
