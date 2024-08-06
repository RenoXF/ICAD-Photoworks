<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'image',
        'price',
    ];

    protected function casts()
    {
        return [
            'type' => ProductType::class,
            'price' => 'decimal:2',
        ];
    }
}
