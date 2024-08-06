<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Enums\TransactionStatus;
use App\Models\Addon;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::query()->with([
            'product',
            'user',
            'details' => function ($query) {
                $query->limit(3);
            },
        ])
        ->whereIn('status', [
            TransactionStatus::ORDER_CREATED,
            TransactionStatus::ORDER_ACCEPTED,
            TransactionStatus::ORDER_REJECTED,
        ])
        ->latest();

        if (auth()->user()->role === RoleEnum::Client) {
            $transactions->where('user_id', auth()->user()->id);
        }

        $transactions = $transactions->paginate(10);

        return view('booking.index', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, int $productId)
    {
        if ($request->user()?->role !== RoleEnum::Client) {
            return redirect()->route('home')->with('error', 'Anda bukan client');
        }

        $product = Product::query()->where('id', $productId)->first();

        if (!$product) {
            return redirect()->route('home')->with('error', 'Produk tidak ditemukan');
        }

        $addons = Addon::all();

        return view('booking.create', [
            'product' => $product,
            'addons' => $addons,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $productId)
    {
        $data = $request->validate([
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id',
        ]);

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

        $isExist = Transaction::query()
            ->orWhereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->orWhere('start_date', '=<', $start)
            ->orWhere('end_date', '>=', $end)
            ->where('status', TransactionStatus::ORDER_PAID)
            ->count();

        if ($isExist > 0) {
            return redirect()
                ->back()
                ->withErrors(['Error: Tanggal yang dipilih tidak tersedia']);
        }

        $product = Product::query()->where('id', $productId)->first();

        if (!$product) {
            return redirect()->route('home')->with('error', 'Produk tidak ditemukan');
        }

        $details = [];

        if (!empty($data['addons'])) {
            foreach ($data['addons'] as $id) {
                $addon = Addon::query()->where('id', $id)->first();

                if (!$addon) {
                    return redirect()->route('home')->with('error', 'Addon tidak ditemukan');
                }

                $details[] = [
                    'addons_id' => $addon->id,
                    'addon_type' => $addon->type,
                    'addon_title' => $addon->title,
                    'addon_price' => $addon->price,
                ];
            }
        }

        $data['product_title'] = $product->title;
        $data['product_description'] = $product->description;
        $data['product_image'] = $product->image;
        $data['product_price'] = $product->price;
        $data['user_id'] = $request->user()->id;
        $data['product_id'] = $product->id;
        $data['invoice'] = 'INV-' . date('YmdHis');
        $data['total'] = $product->price + array_sum(array_column($details, 'addon_price'));

        DB::beginTransaction();
        try {
            $transaction = Transaction::query()->create($data);
            $transaction->details()->createMany($details);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['Error: ' . $th->getMessage()]);
        }

        return redirect()
            ->route('booking.show', $transaction->id)
            ->with('success', 'Booking berhasil');
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
            return redirect()->route('booking.index')->with('error', 'Data tidak ditemukan');
        }

        return view('booking.show', [
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
        $data = Transaction::query()->find($id);

        if (!$data) {
            return redirect()->route('booking.index')->with('error', 'Data tidak ditemukan');
        }

        if ($data->user_id !== auth()->user()->id) {
            return redirect()->route('booking.index')->with('error', 'Anda bukan pemilik data');
        }

        $data->delete();

        return redirect()->route('booking.index')->with('success', 'Data berhasil dihapus');
    }

    public function approve(string $id)
    {
        $data = Transaction::query()->find($id);

        if (!$data) {
            return redirect()
                ->route('booking.index')
                ->withErrors(['Data tidak ditemukan']);
        }

        try {
            $data->update([
                'status' => TransactionStatus::ORDER_ACCEPTED,
                'accepted_at' => now(),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('booking.index')
                ->withErrors(['Error: ' . $th->getMessage()]);
        }

        return redirect()->route('booking.index')->with('success', 'Pesanan berhasil dikonfirmasi');
    }

    public function reject(string $id)
    {
        $data = Transaction::query()->find($id);

        if (!$data) {
            return redirect()
                ->route('booking.index')
                ->withErrors(['Data tidak ditemukan']);
        }

        try {
            $data->update([
                'status' => TransactionStatus::ORDER_REJECTED,
                'rejected_at' => now(),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('booking.index')
                ->withErrors(['Error: ' . $th->getMessage()]);
        }

        return redirect()->route('booking.index')->with('success', 'Pesanan berhasil ditolak');
    }

    public function pay(string $id)
    {
        $data = Transaction::query()->find($id);

        if (!$data) {
            return redirect()->route('booking.index')->with('error', 'Data tidak ditemukan');
        }

        if ($data->status === TransactionStatus::ORDER_ACCEPTED) {
            // dd($data->payment_url);
            if ($data->payment_url) {
                return redirect()->away($data->payment_url);
            }

            $transaction_details = [
                'order_id' => $data->invoice,
                'gross_amount' => (int) ceil($data->total), // no decimal allowed for creditcard
            ];

            $item_details = [
                [
                    'id' => 'P' . sprintf('%03d', $data->product_id),
                    'price' => (int) $data->product_price,
                    'quantity' => 1,
                    'name' => $data->product_title,
                ],
            ];

            foreach ($data->details as $item) {
                $item_details[] = [
                    'id' => 'A' . sprintf('%03d', $item->id),
                    'price' => (int) $item->addon_price,
                    'quantity' => 1,
                    'name' => $item->addon_title,
                ];
            }

            $customer_details = [
                'first_name' => $data->customer_name,
                'last_name' => '',
                'email' => $data->customer_email,
                'phone' => $data->customer_phone,
            ];

            $params = [
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            ];

            $payment = \Midtrans\Snap::createTransaction($params);

            if (!$payment) {
                return redirect()
                    ->route('booking.index')
                    ->withErrors(['Gagal melakukan pembayaran']);
            }

            $data->update([
                'payment_token' => $payment->token,
                'payment_url' => $payment->redirect_url,
            ]);

            return redirect()->away($payment->redirect_url);
        }

        return redirect()
            ->route('booking.index')
            ->withErrors(['Pesanan tidak ditemukan']);
    }

    public function callback(Request $request)
    {
        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::alert('Callback: ' . $e->getMessage());

            return response()->json(
                [
                    'status' => false,
                ],
                200,
            );
        }

        $notif = (object) $notif->getResponse();
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $transactionData = Transaction::query()
            ->where('invoice', $order_id)
            ->first();

        if (empty($transactionData)) {
            return response()->json([
                'status' => false,
            ], 503);
        }

        $msg = '';

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    $msg = 'Transaction order_id: ' . $order_id . ' is challenged by FDS';
                    $transactionData->update([
                        'status' => TransactionStatus::ORDER_PAID,
                        'paid_at' => now(),
                    ]);
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    $transactionData->update([
                        'status' => TransactionStatus::ORDER_PAID,
                        'paid_at' => now(),
                    ]);
                    $msg = 'Transaction order_id: ' . $order_id . ' successfully captured using ' . $type;
                }
            }
        } elseif ($transaction == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $msg = 'Transaction order_id: ' . $order_id . ' successfully transfered using ' . $type;
            $transactionData->update([
                'status' => TransactionStatus::ORDER_PAID,
                'paid_at' => now(),
            ]);
        } elseif ($transaction == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $msg = 'Waiting customer to finish transaction order_id: ' . $order_id . ' using ' . $type;
            $transactionData->update([
                'status' => TransactionStatus::ORDER_PAID,
                'paid_at' => now(),
            ]);
        } elseif ($transaction == 'deny') {
            $msg = 'Payment using ' . $type . ' for transaction order_id: ' . $order_id . ' is denied.';
            $transactionData->update([
                'status' => TransactionStatus::ORDER_REJECTED,
                'rejected_at' => now(),
            ]);
        } elseif ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $msg = 'Payment using ' . $type . ' for transaction order_id: ' . $order_id . ' is expired.';
            $transactionData->update([
                'status' => TransactionStatus::ORDER_REJECTED,
                'rejected_at' => now(),
            ]);
        } elseif ($transaction == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            $msg = 'Payment using ' . $type . ' for transaction order_id: ' . $order_id . ' is canceled.';
            $transactionData->update([
                'status' => TransactionStatus::ORDER_REJECTED,
                'rejected_at' => now(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => $msg,
        ]);
    }
}
