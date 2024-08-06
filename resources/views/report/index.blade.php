@extends('layout')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Laporan
                    </h2>
                    <div class="text-secondary mt-1">Laporan Lengkap</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="javascript:window.print();" class="btn btn-info">
                            Cetak Laporan
                        </a>
                    </div>
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
                        <div class="card-header flex-column justify-content-center">
                            <h2 class="card-title mb-2">
                                Laporan Transaksi
                            </h2>
                            <div class="text-secondary">ICAD Photoworks</div>
                        </div>
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
                                    </tr>
                                </thead>
                                <tbody>
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
