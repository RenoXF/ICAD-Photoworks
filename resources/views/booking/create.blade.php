@extends('layout')
@use('\App\Enums\AddonEnum')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Booking Produk
                    </h2>
                    <div class="text-secondary mt-1">{{ str($product->type->name)->headline()->title() }}</div>
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
                            <div class="card-title">Pemesanan {{ str($product->type->name)->headline()->title() }}</div>
                        </div>
                        <form action="" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <img src="{{ $product->image ? asset($product->image) : 'https://placehold.co/600x300' }}"
                                            alt="" srcset="" class="img-fluid img-thumbnail mx-auto d-block">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <h1 class="position-relative">{{ $product->title }} <span
                                                class="badge badge-outline text-pink fs-6 position-absolute top-0 end-0">{{ str($product->type->name)->headline()->title() }}</span>
                                        </h1>
                                        <p class="badge bg-green-lt">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        <h3>Deskripsi</h3>
                                        <p>{!! nl2br($product->description) !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pelanggan</label>
                                    <input type="text" name="customer_name" class="form-control"
                                        placeholder="Nama Pelanggan" required
                                        value="{{ old('customer_name', $user->fullname) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="customer_address" cols="3" class="form-control" placeholder="Masukan alamat lengkap" required>{{ old('customer_address', $user->address) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Handphone</label>
                                    <input type="text" name="customer_phone" class="form-control"
                                        placeholder="Nomor Handphone" required
                                        value="{{ old('customer_phone', $user->phone) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="customer_email" class="form-control" placeholder="Email"
                                        required value="{{ old('customer_email', $user->email) }}">
                                </div>
                                <div class="mb-3">
                                    <h3>Tanggal Pelaksanaan</h3>
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <input name="start_date" type="text" class="form-control "
                                                id="datetimepicker-start" placeholder="Pilih tanggal mulai" required>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input name="end_date" type="text" class="form-control"
                                                id="datetimepicker-end" placeholder="Pilih tanggal selesai" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Addons</label>
                                <div class="input-group mb-2">
                                    <select name="addons[]" class="select2 form-control" aria-label="Default select example" multiple aria-placeholder="Silahkan pilih addons ">
                                        @foreach ($addons->groupBy('type') as $name => $addon)
                                            <optgroup label="{{ str(AddonEnum::from($name)->name)->headline()->title()  }}">
                                                @foreach ($addon as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }} - Rp{{ number_format($item->price, 0, ',', '.') }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    {{-- <button class="btn" type="submit">Tambah</button> --}}
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary text-white"
                                    {{ route('home') }}>Tidak</button>
                                <button type="submit" class="btn btn-success text-white">Pesan Sekarang !</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
