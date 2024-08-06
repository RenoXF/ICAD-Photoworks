@extends('layout')
@use('\App\Enums\TransactionStatus')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        @if ($isClient)
                            Pesanan Saya
                        @else
                            Kelola Pesanan Masuk
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
                                                    @if ($isAdmin && $row->status === TransactionStatus::ORDER_CREATED)
                                                        <a href="#" class="btn btn-teal btn-icon"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalAcceptOrder{{ $row->id }}">
                                                            <i class="icon ti ti-checks"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-red btn-icon"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalRejectOrder{{ $row->id }}">
                                                            <i class="icon ti ti-deselect"></i>
                                                        </a>
                                                    @endif
                                                    @if ($isClient && $row->status === TransactionStatus::ORDER_CREATED)
                                                        <a href="#" class="btn btn-pink btn-icon"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDeleteOrder{{ $row->id }}">
                                                            <i class="icon ti ti-x"></i>
                                                        </a>
                                                    @endif
                                                    @if ($isClient && $row->status === TransactionStatus::ORDER_ACCEPTED)
                                                        <a href="{{ route('booking.pay', ['id' => $row->id]) }}"
                                                            class="btn btn-teal btn-icon">
                                                            <i class="icon ti ti-cash"></i>
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

    @foreach ($transactions as $row)
        <div class="modal modal-blur fade" id="modalDeleteOrder{{ $row->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <i class="icon mb-2 text-danger icon-lg ti ti-alert-triangle"></i>
                        <h3>Apakah kamu yakin ?</h3>
                        <div class="text-secondary">Apakah kamu benar-benar ingin menghapus pesanan
                            <strong>{{ $row->invoice }}</strong> ?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        Tidak
                                    </a></div>
                                <div class="col">
                                    <a href="{{ route('booking.destroy', ['id' => $row->id]) }}"
                                        class="btn btn-danger w-100">
                                        Ya, Hapus Pesanan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-blur fade" id="modalAcceptOrder{{ $row->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-success"></div>
                    <div class="modal-body text-center py-4">
                        <i class="icon mb-2 text-green icon-lg ti ti-circle-check"></i>
                        <h3>Konfirmasi Pesanan ?</h3>
                        <div class="text-secondary">Apakah kamu benar-benar ingin konfirmasi pesanan
                            <strong>{{ $row->invoice }}</strong> ?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        Tidak
                                    </a></div>
                                <div class="col"><a href="{{ route('booking.approve', ['id' => $row->id]) }}"
                                        class="btn btn-success w-100">
                                        Ya, Konfirmasi
                                    </a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-blur fade" id="modalRejectOrder{{ $row->id }}" tabindex="-1"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <i class="icon mb-2 text-danger icon-lg ti ti-alert-triangle"></i>
                        <h3>Apakah kamu yakin ?</h3>
                        <div class="text-secondary">Apakah kamu benar-benar ingin menolak pesanan
                            <strong>{{ $row->invoice }}</strong> ?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        Tidak
                                    </a></div>
                                <div class="col">
                                    <a href="{{ route('booking.reject', ['id' => $row->id]) }}"
                                        class="btn btn-danger w-100">
                                        Ya, Hapus Pesanan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
