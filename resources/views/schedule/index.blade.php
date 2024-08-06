@extends('layout')
@use('\App\Enums\TransactionStatus')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        @if ($isClient || empty(auth()->user()))
                            Jadwal Pesanan
                        @else
                            Lihat Jadwal Pesanan
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
                @guest
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Jadwal Pesanan</h2>
                            </div>
                            <div class="card-body">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>
                @endguest
                @auth
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
                                            <th class="text-center">Countdown</th>
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
                                                    <strong>{{ $row->start_date->diffForHumans() }}</strong>
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
                                                            <a href="{{ route('transaction.confirm', ['id' => $row->id]) }}"
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
                @endauth
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@latest/locales/id.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: 'dayGridMonth',
                events: {{ Js::from($schedules) }},
                resources: [{
                    id: 'a',
                    title: 'Room A'
                }],
            });
            calendar.render();
        });
    </script>
@endpush
