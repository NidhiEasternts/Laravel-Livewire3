<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name', 'price','user_id','category_id'
    ];

    public $timestamps = true;
    
    public function product_images(){
        return $this->hasMany(ProductImage::class,'product_id','id');
    }

    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}
