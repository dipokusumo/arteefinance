<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxpayer extends Model
{
    protected $fillable = [
        'name',
        'npwp',
        'nik',
        'address',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
