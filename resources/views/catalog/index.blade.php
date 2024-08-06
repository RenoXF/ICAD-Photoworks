@extends('layout')
@use('\App\Enums\ProductType')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Katalog Produk
                    </h2>
                    {{-- <div class="text-secondary mt-1">1-12 of 241 photos</div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                @foreach ($products as $product)
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                        <a href="#" rel="_blank" class="d-block position-relative">
                            <img
                                src="{{ $product->image ? asset($product->image) : 'https://placehold.co/300x200' }}"
                                class="card-img-top">
                                <span class="badge bg-pink text-pink-fg position-absolute top-0 end-0">{{ str($product->type->name)->headline()->title }}</span>
                            </a>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                {{-- <span class="avatar me-3 rounded"
                                    style="background-image: url(https://preview.tabler.io/static/avatars/000m.jpg)"></span> --}}
                                <div>
                                    <h4 class="text-weight-bold mb-0">{{ $product->title }}</h4>
                                    @auth
                                    <div class="text-secondary">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                                    @endauth
                                    @guest
                                    <div class="text-secondary">{{ str($product->type->name)->headline()->title }}</div>
                                    @endguest
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('booking.create', ['id' => 1]) }}" class="btn btn-icon btn-teal">
                                        <i class="ti ti-calendar-event icon"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-2 row row-cards">
                <div class="col-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="bookingProduct" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordLabel">Pesan Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <img src="https://preview.tabler.io/static/photos/beautiful-blonde-woman-relaxing-with-a-can-of-coke-on-a-tree-stump-by-the-beach.jpg" alt=""
                            srcset="" class="img-fluid img-thumbnail mx-auto d-block">
                    </div>
                    <div class="modal-body">
                        <h3>Deskripsi</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quidem facere, unde optio officiis et impedit nesciunt mollitia amet tenetur aperiam libero, maxime in magni sed blanditiis quasi distinctio earum?</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos eum velit, numquam, impedit ea ab ipsum expedita officia harum quos tenetur dolorum tempora, nam aspernatur natus nobis consectetur fugit commodi.</p>
                    </div>
                    <div class="modal-body">
                        <h3>Tanggal Mulai</h3>
                        <input type="text" class="form-control" id="datetimepicker-start" placeholder="Pilih tanggal & waktu">
                    </div>
                    <div class="modal-body">
                        <h3>Tanggal Selesai</h3>
                        <input type="text" class="form-control" id="datetimepicker-end" placeholder="Pilih tanggal & waktu">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white"
                            data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-success text-white">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection

