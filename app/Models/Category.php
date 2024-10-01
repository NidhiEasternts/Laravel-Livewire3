<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description',
    ];

    public $timestamps = true;

    public function product_category(){
        return $this->belongsTo(Category::class,'id','category_id');
    }
}
