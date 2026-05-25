<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
