<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoiceprices extends Model
{
    use HasFactory;
    protected $fillable = [
        'ref_id',
        'total_price',
        'paid',
        'due',
        'customer_id',  
        'customer_name',
        'date',
    ];
}
