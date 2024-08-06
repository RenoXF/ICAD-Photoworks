@extends('layout')
@use('\App\Enums\TransactionStatus')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Detail Pemesanan
                    </h2>
                    <div class="text-secondary mt-1">Detail Pemesanan {{ $data->invoice }}</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    @if ($isAdmin && $data->status === TransactionStatus::ORDER_CREATED)
                        <a href="#" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#modalAcceptOrder">
                            Terima
                        </a>
                        <a href="#" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#modalRejectOrder">
                            Tolak
                        </a>
                    @endif
                    @if ($isClient && $data->status === TransactionStatus::ORDER_CREATED)
                        <a href="#" class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#modalDeleteOrder">
                            Hapus
                        </a>
                    @endif
                    @if ($isClient && $data->status === TransactionStatus::ORDER_ACCEPTED)
                        <a href="{{ route('booking.pay', ['id' => $data->id]) }}" class="btn btn-teal">
                            Bayar
                        </a>
                    @endif
                    @if ($isAdmin && $data->status === TransactionStatus::ORDER_PAID)
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEdit">
                            Edit
                        </a>
                    @endif
                    @if ($isClient && $data->status === TransactionStatus::ORDER_PAID)
                    <a href="{{ route('transaction.confirm', ['id' => $data->id]) }}" class="btn btn-teal">
                        Selesaikan Pesanan
                    </a>
                    @endif
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
                        <div class="card-header">
                            <h3 class="card-title">Informasi Pemesanan</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Paket</div>
                                    <div class="datagrid-content"><span
                                            class="badge badge-outline text-pink">{{ $data->product_title }}</span></div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Jenis</div>
                                    <div class="datagrid-content">
                                        {{ str($data->product?->type?->name)->headline()->title() }}
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Jadwal Mulai</div>
                                    <div class="datagrid-content">
                                        {{ $data->start_date->isoFormat('dddd, D MMMM YYYY HH:mm') }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Jadwal Selesai</div>
                                    <div class="datagrid-content">
                                        {{ $data->end_date->isoFormat('dddd, D MMMM YYYY HH:mm') }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Status</div>
                                    <div class="datagrid-content">
                                        @if ($data->status === TransactionStatus::ORDER_CREATED)
                                            <span class="badge badge-outline text-pink">Menunggu Konfirmasi</span>
                                        @elseif ($data->status === TransactionStatus::ORDER_ACCEPTED)
                                            <span class="badge badge-outline text-teal">Pesanan DITERIMA</span>
                                        @elseif ($data->status === TransactionStatus::ORDER_REJECTED)
                                            <span class="badge badge-outline text-red">Pesanan DITOLAK</span>
                                        @elseif ($data->status === TransactionStatus::ORDER_PAID)
                                            <span class="badge badge-outline text-green">Pesanan DIBAYAR</span>
                                        @elseif ($data->status === TransactionStatus::ORDER_COMPLETED)
                                            <span class="badge badge-outline text-purple">Pesanan SELESAI</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Harga</div>
                                    <div class="datagrid-content">Rp{{ number_format($data->total, 0, ',', '.') }}</div>
                                </div>
                                @if ($data->accepted_at)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tanggal Diterima</div>
                                        <div class="datagrid-content">
                                            {{ $data->accepted_at->isoFormat('dddd, D MMMM YYYY HH:mm') }}
                                        </div>
                                    </div>
                                @endif
                                @if ($data->completed_at)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tanggal Selesai</div>
                                        <div class="datagrid-content">
                                            {{ $data->completed_at->isoFormat('dddd, D MMMM YYYY HH:mm') }}
                                        </div>
                                    </div>
                                @endif
                                @if ($data->rejected_at)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tanggal Ditolak</div>
                                        <div class="datagrid-content">
                                            {{ $data->rejected_at->isoFormat('dddd, D MMMM YYYY HH:mm') }}
                                        </div>
                                    </div>
                                @endif
                                @if ($data->paid_at)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tanggal Dibayar</div>
                                        <div class="datagrid-content">
                                            {{ $data->paid_at->isoFormat('dddd, D MMMM YYYY HH:mm') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Addons</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Addons</th>
                                        <th>Jenis</th>
                                        <th class="w-1">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data->details->isEmpty())
                                        <tr>
                                            <td colspan="3">Tidak ada addons</td>
                                        </tr>
                                    @else
                                        @foreach ($data->details as $detail)
                                            <tr>
                                                <td>{{ $detail->addon_title }}</td>
                                                <td>{{ str($detail->addon_type->name)->headline()->title() }}</td>
                                                <td class="text-end w-1">
                                                    Rp{{ number_format($detail->addon_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <th colspan="2" class="text-end">Addons:</th>
                                        <td class="text-end">
                                            Rp{{ number_format($data->details->sum('addon_price'), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end">Paket</th>
                                        <td class="text-end">Rp{{ number_format($data->product_price, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-end">Rp{{ number_format($data->total, 0, ',', '.') }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Pelanggan</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nama</div>
                                    <div class="datagrid-content">{{ $data->customer_name }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Email</div>
                                    <div class="datagrid-content">{{ $data->customer_email }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nomor HP</div>
                                    <div class="datagrid-content">{{ $data->customer_phone }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Alamat</div>
                                    <div class="datagrid-content">{{ $data->customer_address }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Produk</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $data->product_image ? asset($data->product_image) : 'https://placehold.co/300x200' }}"
                                        alt="{{ $data->product_title }}" class="img-fluid" width="100%" />
                                </div>
                                <div class="col-md-8">
                                    <div class="datagrid">
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Nama</div>
                                            <div class="datagrid-content">{{ $data->product_title }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Jenis</div>
                                            <div class="datagrid-content">
                                                {{ str($data->product?->type?->name)->headline()->title() }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Harga</div>
                                            <div class="datagrid-content">
                                                Rp{{ number_format($data->product_price, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Deskripsi</div>
                                            <div class="datagrid-content">{!! nl2br($data->product_description) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modalDeleteOrder" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <i class="icon mb-2 text-danger icon-lg ti ti-alert-triangle"></i>
                    <h3>Apakah kamu yakin ?</h3>
                    <div class="text-secondary">Apakah kamu benar-benar ingin menghapus pesanan
                        <strong>{{ $data->invoice }}</strong> ?
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                    Tidak
                                </a></div>
                            <div class="col">
                                <a href="{{ route('booking.destroy', ['id' => $data->id]) }}"
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

    <div class="modal modal-blur fade" id="modalAcceptOrder" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center py-4">
                    <i class="icon mb-2 text-green icon-lg ti ti-circle-check"></i>
                    <h3>Konfirmasi Pesanan ?</h3>
                    <div class="text-secondary">Apakah kamu benar-benar ingin konfirmasi pesanan
                        <strong>{{ $data->invoice }}</strong> ?
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                    Tidak
                                </a></div>
                            <div class="col"><a href="{{ route('booking.approve', ['id' => $data->id]) }}"
                                    class="btn btn-success w-100">
                                    Ya, Konfirmasi
                                </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modalRejectOrder" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <i class="icon mb-2 text-danger icon-lg ti ti-alert-triangle"></i>
                    <h3>Apakah kamu yakin ?</h3>
                    <div class="text-secondary">Apakah kamu benar-benar ingin menolak pesanan
                        <strong>{{ $data->invoice }}</strong> ?
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                    Tidak
                                </a></div>
                            <div class="col">
                                <a href="{{ route('booking.reject', ['id' => $data->id]) }}"
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

    <div class="modal modal-blur fade" id="modalEdit" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <form action="{{ route('booking.update', ['id' => $data->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                <div class="mb-3">
                                    <h3>Tanggal Pelaksanaan</h3>
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <input name="start_date" type="text" class="form-control "
                                                id="datetimepicker-start" placeholder="Pilih tanggal mulai" required value="{{ $data->start_date->format('Y-m-d H:i:s') }}">
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input name="end_date" type="text" class="form-control"
                                                id="datetimepicker-end" placeholder="Pilih tanggal selesai" required  value="{{ $data->end_date->format('Y-m-d H:i:s') }}">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-danger" data-bs-dismiss="modal">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary ms-auto">
                                <i class="icon ti ti-send"></i>
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
@endsection

@push('js')
    <script>
        const initDateTimePicker = () => {
            $('#datetimepicker-end').Zebra_DatePicker({
                direction: 1,
                format: 'Y-m-d H:i',
            })
            $('#datetimepicker-start').Zebra_DatePicker({
                format: 'Y-m-d H:i',
                // pair: $('#datetimepicker-end'),
                direction: 1,
            });
        }
        $(document).ready(initDateTimePicker);
        $('.modal').on('show.bs.modal', initDateTimePicker)
        $('.modal').on('shown.bs.modal', initDateTimePicker)
    </script>
@endpush
