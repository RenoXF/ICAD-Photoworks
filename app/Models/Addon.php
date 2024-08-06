<?php

namespace App\Models;

use App\Enums\AddonEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'price',
    ];

    protected function casts() {
        return [
            'type' => AddonEnum::class,
            'title' => 'string',
            'price' => 'decimal:2',
        ];
    }
}
