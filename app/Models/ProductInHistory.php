<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'category_id',
        'category_name',
        'quantity',
        'unit_id',
        'unit',
        'sub_unit_id',
        'sub_unit',
        'unit_price',
        'sub_unit_price',
        'created_by',
        'product_in_num',
        'date',

    ];
}
