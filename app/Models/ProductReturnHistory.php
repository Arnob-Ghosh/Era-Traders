<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturnHistory extends Model
{
    use HasFactory; 
     protected $fillable = [
        'product_id',
        'product_name',
        'category_id',
        'category_name',
        'unit_id',
        'unit',
        'sub_unit_id',
        'sub_unit_name',
        'quantity',
        'unit_price',
        'return_num',
        'date',
        'created_by',
        'note',
    ];

}
