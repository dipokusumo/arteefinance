<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PphType extends Model
{
    protected $fillable = [
        'code',
        'description',
        'factor',
        'tax_rate',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'pph_type_id');
    }
}
