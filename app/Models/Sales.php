<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'product_id',
        'category_id',
        'category_name',
        'unit_id',
        'unit_name',
        'sub_unit_name',
        'sub_unit',
        'sub_unit_id',
        'unit_price',
        'sub_unit_price',
       'sale_quantity',
        'sale_unit',
       'sale_price',
        'date',
        'customer_name',
        'customer_id',
        'ref_id'
    ];
}
