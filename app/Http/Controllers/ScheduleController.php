<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::query()
            ->with([
                'product',
                'user',
                'details' => function ($query) {
                    $query->limit(3);
                },
            ])
            ->whereIn('status', [TransactionStatus::ORDER_PAID])
            ->latest();

        if (auth()->user()?->role === RoleEnum::Client) {
            $transactions->where('user_id', auth()->user()->id);
        }

        $transactions = $transactions->paginate(10);

        $schedules = [];

        foreach ($transactions as $transaction) {
            $schedules[] = [
                'title' => $transaction->product_title,
                'classNames' => "bg-pink text-pink-fg",
                'start' => $transaction->start_date?->toIso8601String(),
                'end' => $transaction->end_date?->toIso8601String()                ,
                'display' => 'block',
                'allDays' => false,
                'interactive' => true,
                'overlap' => true,
            ];
        }

        return view('schedule.index', [
            'transactions' => $transactions,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
