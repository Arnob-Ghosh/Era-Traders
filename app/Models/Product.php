<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'productName',
        'productLabel',
        'barcode',

    ];
    public function categories()
{
    return $this->hasMany(ProductCategory::class, 'product_id');
}

}
