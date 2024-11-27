<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'category_name',
        'category_id',
        'product_name',
    ];
        // Example: Relation to Product Model
        public function product()
        {
            return $this->belongsTo(Product::class, 'product_id');
        }
    
        // Example: Relation to Category Model
        public function category()
        {
            return $this->belongsTo(Category::class, 'category_id');
        }
}
