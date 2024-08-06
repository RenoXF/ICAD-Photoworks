@extends('layout')
@use('\App\Enums\RoleEnum')
@use('\App\Enums\ProductType')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-products-center">
                <div class="col">
                    <h2 class="page-title">
                        Kelola Katalog
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modalAdd">
                            <i class="icon ti ti-plus"></i>
                            Tambah Katalog baru
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
                            data-bs-target="#modalAdd" aria-label="Tambah Katalog baru">
                            <i class="icon ti ti-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    @include('alert')
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th class="text-center">Nama Produk</th>
                                        <th class="text-center">Jenis Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $row)
                                        <tr>
                                            <td class="center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex py-1 align-products-center">
                                                    <span class="avatar me-2"
                                                        style="background-image: url({{ asset($row->image) }})">
                                                    @empty($row->image)
                                                    {{ $row->id }}
                                                    @endempty
                                                    </span>
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $row->title }}</div>
                                                        <div class="text-secondary">{{ str($row->type->name)->headline()->title() }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-medium">
                                                    {{ str($row->type->name)->headline()->title() }}
                                                </div>
                                            </td>
                                            <td class="text-secondary">
                                                Rp{{ number_format($row->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-secondary">{{ str($row->description ?? '-')->limit(50) }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a href="#" class="btn btn-icon btn-blue"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalView{{ $row->id }}">
                                                        <i class="icon ti ti-zoom"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-icon btn-teal" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $row->id }}">
                                                        <i class="icon ti ti-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-icon btn-red" data-bs-toggle="modal"
                                                        data-bs-target="#modalDelete{{ $row->id }}">
                                                        <i class="icon ti ti-x"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($products->links())
                            <div class="card-body">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modalAdd" tabindex="-1" style="display: none;" aria-hidden="true">
        <form action="{{ route('catalog.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Barang Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="form-label">Gambar Barang</div>
                            <input type="file" accept="image/*" name="image" class="form-control" placeholder="Foto"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="type" id="type" class="form-control select2">
                                <option value="">--- Pilih jenis produk ---</option>
                                @foreach (ProductType::array() as $val => $key)
                                    <option value="{{ $val }}">{{ str(ucfirst(strtolower($val)))->headline()->title() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="title" class="form-control" placeholder="Nama produk"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Produk</label>
                            <input type="number" name="price" class="form-control" placeholder="Harga produk" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="description" class="form-control" placeholder="Keterangan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-danger" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="icon ti ti-plus"></i>
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @foreach ($products as $row)
        <div class="modal modal-blur fade" id="modalView{{ $row->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk: {{ $row->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nama produk</div>
                                <div class="datagrid-content">{{ $row->title }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Jenis produk</div>
                                <div class="datagrid-content">{{ str($row->type->name)->headline()->title() }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Harga</div>
                                <div class="datagrid-content">Rp{{ number_format($row->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Deskripsi</div>
                                <div class="datagrid-content">{{ $row->description }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Gambar</div>
                                <div class="datagrid-content">
                                    @if ($row->foto)
                                        <img src="{{ asset($row->foto) }}" alt="" class="img-fluid">
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-danger" data-bs-dismiss="modal">
                            Tutup
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal modal-blur fade" id="modalEdit{{ $row->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <form action="{{ route('catalog.update', ['id' => $row->id]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit data produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="form-label">Gambar Barang</div>
                                <input type="file" accept="image/*" name="image" class="form-control" placeholder="Foto"
                                    >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis</label>
                                <select name="type" id="type" class="form-control select2">
                                    <option value="">--- Pilih jenis produk ---</option>
                                    @foreach (ProductType::array() as $val => $key)
                                        <option @selected($row->type->name === $key) value="{{ $val }}">{{ str(ucfirst(strtolower($val)))->headline()->title() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="title" class="form-control" placeholder="Nama produk"
                                    required value="{{ $row->title }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Produk</label>
                                <input type="number" name="price" class="form-control" placeholder="Harga produk" required value="{{ $row->price }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="description" class="form-control" placeholder="Keterangan" rows="3" required>{{ $row->description }}</textarea>
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

        <div class="modal modal-blur fade" id="modalDelete{{ $row->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <i class="ti ti-alert-triangle icon mb-2 text-danger icon-lg"></i>
                        <h3>Are you sure?</h3>
                        <div class="text-secondary">Apakah kamu benar ingin menghapus produk
                            <strong>{{ $row->title }}
                                ?</strong>
                        </div>
                    </div>
                    <form action="{{ route('catalog.destroy', ['id' => $row->id]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-footer">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                            Batalkan
                                        </a></div>
                                    <div class="col"><button type="submit" class="btn btn-danger w-100">
                                            Hapus
                                        </button></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
