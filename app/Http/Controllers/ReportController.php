<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::query()
            ->where('status', TransactionStatus::ORDER_COMPLETED)
            ->latest()
            ->get();

        return view('report.index', [
            'transactions' => $transactions,
        ]);
    }
}
