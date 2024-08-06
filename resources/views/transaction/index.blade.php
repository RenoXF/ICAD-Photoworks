@extends('layout')
@use('\App\Enums\TransactionStatus')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        @if ($isClient)
                            Riwayat Transaksi Saya
                        @else
                            Kelola Transaksi
                        @endif
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            @include('alert')
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Kode Pesanan</th>
                                        <th>Paket</th>
                                        <th>Addons</th>
                                        <th>Jadwal Mulai</th>
                                        <th>Jadwal Selesai</th>
                                        <th>Nominal</th>
                                        <th class="text-center">Status</th>
                                        <th class="w-1">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $row)
                                    @endforeach
                                    @forelse ($transactions as $row)
                                        <tr>
                                            <td>
                                                {{ $row->invoice }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-outline text-pink">{{ $row->product->title }}</span>
                                            </td>
                                            <td>
                                                @foreach ($row->details as $item)
                                                    <span
                                                        class="mb-1 badge badge-outline text-orange">{{ $item->addon_title }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $row->start_date->isoFormat('dddd, D MMMM YYYY HH:mm') }}</td>
                                            <td>{{ $row->end_date->isoFormat('dddd, D MMMM YYYY HH:mm') }}</td>
                                            <td>Rp{{ number_format($row->total, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if ($row->status === TransactionStatus::ORDER_CREATED)
                                                    <span class="badge badge-outline text-pink">Menunggu Konfirmasi</span>
                                                @elseif ($row->status === TransactionStatus::ORDER_ACCEPTED)
                                                    <span class="badge badge-outline text-teal">Pesanan Diterima,
                                                        <br />Menunggu Pembayaran</span>
                                                @elseif ($row->status === TransactionStatus::ORDER_REJECTED)
                                                    <span class="badge badge-outline text-red">Pesanan DITOLAK</span>
                                                @elseif ($row->status === TransactionStatus::ORDER_PAID)
                                                    <span class="badge badge-outline text-green">Pesanan DIBAYAR</span>
                                                @elseif ($row->status === TransactionStatus::ORDER_COMPLETED)
                                                    <span class="badge badge-outline text-purple">Pesanan SELESAI</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a href="{{ route('booking.show', ['id' => $row->id]) }}"
                                                        class="btn btn-blue btn-icon">
                                                        <i class="icon ti ti-zoom"></i>
                                                    </a>
                                                    <a href="{{ route('transaction.show', ['id' => $row->id]) }}"
                                                        class="btn btn-azure btn-icon">
                                                        <i class="icon ti ti-file-invoice"></i>
                                                    </a>
                                                    @if ($isClient && $row->status === TransactionStatus::ORDER_PAID)
                                                        <a href="{{ route('transaction.confirm', ['id' => $data->id]) }}"
                                                            class="btn btn-green btn-icon">
                                                            <i class="icon ti ti-cash-register"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                Anda belum memiliki pesanan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
