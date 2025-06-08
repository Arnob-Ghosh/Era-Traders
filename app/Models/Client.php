<?php

namespace App\Models;

use App\Models\CustomerDeposit;
use App\Models\Invoiceprices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address',
        'note',
    ];
    // Client.php
public function invoicePrices()
{
    return $this->hasMany(Invoiceprices::class, 'customer_id');
}

public function deposits()
{
    return $this->hasMany(CustomerDeposit::class, 'customer_id');
}

    
}
