<?php

namespace App\Models;

use App\Models\Client;
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
    public function customer()
{
    return $this->belongsTo(Client::class, 'customer_id');
}

}
