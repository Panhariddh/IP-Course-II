<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'pricing', 'description', 'images'];

    protected $casts = [
        'images' => 'array', // Ensure Laravel knows it's an array
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
