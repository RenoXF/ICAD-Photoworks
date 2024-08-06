<?php

namespace App\Enums;

enum TransactionStatus: string
{
    use EnumToArray;

    case ORDER_CREATED = 'PESANAN_DIBUAT';
    case ORDER_ACCEPTED = 'PESANAN_DITERIMA';
    case ORDER_REJECTED = 'PESANAN_DITOLAK';
    case ORDER_PAID = 'PESANAN_DIBAYAR';
    case ORDER_COMPLETED = 'PESANAN_SELESAI';
}
