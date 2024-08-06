<?php

namespace App\Models;

use App\Enums\AddonEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'addons_id',
        'addon_type',
        'addon_title',
        'addon_price',
    ];

    protected function casts()
    {
        return [
            'addon_type' => AddonEnum::class,
            'addon_title' => 'string',
            'addon_price' => 'decimal:2',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
