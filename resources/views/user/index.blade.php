@extends('layout')
@use('\App\Enums\RoleEnum')
@use('\App\Enums\ProductType')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-products-center">
                <div class="col">
                    <h2 class="page-title">
                        @if ($isOwner)
                            Kelola Pelanggan
                        @else
                            Kelola Pelanggan
                        @endif
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modalAdd">
                            <i class="icon ti ti-plus"></i>
                            @if ($isOwner)
                                Tambah Pelanggan
                            @else
                                Tambah Pelanggan
                            @endif
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
                            data-bs-target="#modalAdd" aria-label="Tambah Pelanggan baru">
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
                                        <th class="text-center">Nama Pelanggan</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Nomor Handphone</th>
                                        <th class="text-center">Hak Akses</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $row)
                                        <tr>
                                            <td class="center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex py-1 align-products-center">
                                                    {{-- <span class="avatar me-2"
                                                        style="background-image: url({{ asset($row->image) }})">
                                                        @empty($row->image)
                                                            {{ $row->id }}
                                                        @endempty
                                                    </span> --}}
                                                    {{-- <div class="flex-fill"> --}}
                                                    <div class="font-weight-medium">{{ $row->fullname }}</div>
                                                    {{-- <div class="text-secondary">
                                                            {{ str($row->type->name)->headline()->title() }}</div>
                                                    </div> --}}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-medium">
                                                    {{ $row->email }}
                                                </div>
                                            </td>
                                            <td class="text-secondary">
                                                {{ $row->phone }}
                                            </td>
                                            <td class="text-secondary">{{ $row->role->name }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a href="#" class="btn btn-icon btn-blue" data-bs-toggle="modal"
                                                        data-bs-target="#modalView{{ $row->id }}">
                                                        <i class="icon ti ti-zoom"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-icon btn-teal" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $row->id }}">
                                                        <i class="icon ti ti-edit"></i>
                                                    </a>
                                                    @if (auth()->user()->id !== $row->id)
                                                        @if ($isAdmin && $row->role !== RoleEnum::Owner)
                                                            <a href="#" class="btn btn-icon btn-red"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalDelete{{ $row->id }}">
                                                                <i class="icon ti ti-x"></i>
                                                            </a>
                                                        @elseif ($isOwner)
                                                            <a href="#" class="btn btn-icon btn-red"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalDelete{{ $row->id }}">
                                                                <i class="icon ti ti-x"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($users->links())
                            <div class="card-body">
                                {{ $users->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modalAdd" tabindex="-1" style="display: none;" aria-hidden="true">
        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah {{ $isOwner ? 'Pelanggan' : 'Pelanggan' }} Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama lengkap</label>
                            <input type="text" name="fullname" class="form-control" placeholder="Nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat email</label>
                            <input type="email" name="email" class="form-control" placeholder="Alamat email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor handphone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Nomor handphone"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password"
                                autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ulangi Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Password" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" placeholder="Alamat lengkap" rows="3" required></textarea>
                        </div>
                        @if ($isOwner)
                            <div class="mb-3">
                                <label class="form-label">Hak Akses</label>
                                <select name="role" id="type" class="form-control select2">
                                    <option value="">--- Pilih hak akses ---</option>
                                    @foreach (RoleEnum::array() as $val => $key)
                                        <option value="{{ $val }}">
                                            {{ str(ucfirst(strtolower($val)))->headline()->title() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
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

    @foreach ($users as $row)
        <div class="modal modal-blur fade" id="modalView{{ $row->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail: {{ $row->fullname }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nama lengkap</div>
                                <div class="datagrid-content">{{ $row->fullname }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nomor Handphone</div>
                                <div class="datagrid-content">{{ $row->phone }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Alamat email</div>
                                <div class="datagrid-content">{{ $row->email }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Alamat lengkap</div>
                                <div class="datagrid-content">{{ $row->address }}</div>
                            </div>
                            @if ($isOwner)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Hak Akses</div>
                                    <div class="datagrid-content">{{ $row->role->name }}</div>
                                </div>
                            @endif
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
            <form action="{{ route('user.update', ['id' => $row->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama lengkap</label>
                                <input type="text" name="fullname" class="form-control" placeholder="Nama lengkap"
                                    required value="{{ $row->fullname }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat email</label>
                                <input type="email" name="email" class="form-control" placeholder="Alamat email"
                                    required value="{{ $row->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor handphone</label>
                                <input type="text" name="phone" class="form-control" placeholder="Nomor handphone"
                                    required value="{{ $row->phone }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Password (isi apabila ingin mengubah password)" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ulangi Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Password (isi apabila ingin mengubah password)" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" placeholder="Alamat lengkap" rows="3" required>{{ $row->address }}</textarea>
                            </div>
                            @if ($isOwner)
                                <div class="mb-3">
                                    <label class="form-label">Hak Akses</label>
                                    <select name="role" id="type" class="form-control select2">
                                        <option value="">--- Pilih hak akses ---</option>
                                        @foreach (RoleEnum::array() as $val => $key)
                                            <option value="{{ $val }}" @selected($key === $row->role->name)>
                                                {{ str(ucfirst(strtolower($val)))->headline()->title() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
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
                        <div class="text-secondary">Apakah kamu benar ingin menghapus akun
                            <strong>{{ $row->fullname }}
                                ?</strong>
                        </div>
                    </div>
                    <form action="{{ route('user.destroy', ['id' => $row->id]) }}" method="post"
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
