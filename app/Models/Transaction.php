<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'invoice',
        'customer_name',
        'customer_address',
        'customer_phone',
        'customer_email',
        'product_title',
        'product_description',
        'product_image',
        'product_price',
        'total',
        'status',
        'start_date',
        'end_date',
        'accepted_at',
        'rejected_at',
        'paid_at',
        'completed_at',

        'payment_token',
        'payment_url',
    ];

    protected function casts()
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'status' => TransactionStatus::class,
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
            'total' => 'decimal:2',
            'product_price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
