<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
            ->whereIn('status', [TransactionStatus::ORDER_COMPLETED])
            ->latest();

        if (auth()->user()->role === RoleEnum::Client) {
            $transactions->where('user_id', auth()->user()->id);
        }

        $transactions = $transactions->paginate(10);

        return view('transaction.index', [
            'transactions' => $transactions,
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
        $data = Transaction::query()
            ->with(['product', 'user', 'details'])
            ->find($id);

        if (!$data) {
            return redirect()->route('transaction.index')->with('error', 'Data tidak ditemukan');
        }

        return view('transaction.show', [
            'data' => $data,
        ]);
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

    public function confirm(string $id)
    {
        $data = Transaction::query()->find($id);

        if (!$data) {
            return redirect()->route('transaction.index')->withErrors(['Data tidak ditemukan']);
        }

        $data->status = TransactionStatus::ORDER_COMPLETED;
        $data->save();

        return redirect()->route('transaction.index')->with('success', 'Pesanan berhasil dikonfirmasi');
    }
}
