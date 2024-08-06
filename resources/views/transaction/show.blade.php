@extends('layout')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Invoice
                    </h2>
                    <div class="text-secondary mt-1">{{ $data->invoice }}</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="javascript:window.print();" class="btn btn-info">
                            Cetak Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card card-lg">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="h3">ICAD Photoworks</p>
                            <address>
                                Street Address<br>
                                State, City<br>
                                Region, Postal Code<br>
                                ltd@example.com
                            </address>
                        </div>
                        <div class="col-6 text-end">
                            <p class="h3">{{ $data->customer_name }}</p>
                            <address>
                                {!! nl2br($data->customer_address) !!}
                            </address>
                        </div>
                        <div class="col-12 my-5">
                            <h1>{{ $data->invoice }}</h1>
                        </div>
                    </div>
                    <table class="table table-transparent table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%"></th>
                                <th>Product</th>
                                <th>Schedule</th>
                                <th class="text-center" style="width: 1%">Qnt</th>
                                <th class="text-end" style="width: 1%">Unit</th>
                                <th class="text-end" style="width: 1%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <p class="strong mb-1">{{ $data->product_title }}</p>
                                    <div class="text-secondary">{{ str($data->product?->type?->name)->headline()->title() }}
                                    </div>
                                </td>
                                <td>
                                    {{ $data->start_date->isoFormat('dddd, D MMMM YYYY HH:mm') }} to
                                    {{ $data->end_date->isoFormat('dddd, D MMMM YYYY HH:mm') }}
                                </td>
                                <td class="text-center">
                                    1
                                </td>
                                <td class="text-end">Rp{{ number_format($data->product_price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($data->product_price, 0, ',', '.') }}</td>
                            </tr>
                            @foreach ($data->details as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + 1 }}</td>
                                <td>
                                    <p class="strong mb-1">{{ $item->addon_title }}</p>
                                    <div class="text-secondary">{{ str($item->addon_type->name)->headline()->title() }}
                                    </div>
                                </td>
                                <td>-</td>
                                <td class="text-center">
                                    1
                                </td>
                                <td class="text-end">Rp{{ number_format($item->addon_price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->addon_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="strong text-end">Subtotal</td>
                                <td class="text-end">Rp{{ number_format($data->details->sum('addon_price') + $data->product_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-uppercase text-end">Grand Total</td>
                                <td class="font-weight-bold text-end">Rp{{ number_format($data->details->sum('addon_price') + $data->product_price, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-secondary text-center mt-5">Thank you very much for doing business with us. We look
                        forward to working with
                        you again!</p>
                </div>
            </div>
        </div>
    </div>
@endsection
