<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDeposit extends Model
{
    use HasFactory;
      protected $fillable = [
        'ref_id',
        'amount',
        'date',
        'customer_id',
        'sales_id',
        'note',
    ];
    

       public function customer()
    {
        return $this->belongsTo(Client::class);
    }
}
