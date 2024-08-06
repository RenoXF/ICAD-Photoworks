@extends('guest')

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            @include('alert')
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
                    {{ config('app.name') }}
                </a>
            </div>
            <div class="card card-md">
                <form action="" method="post">
                    @csrf
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Create new account</h2>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="fullname" placeholder="Enter name" required
                            value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter email" required
                            value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Enter email" required
                            value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password"
                            autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ulangi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Password"
                            autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" cols="3" class="form-control" placeholder="Masukan alamat lengkap" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Create new account</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="text-center text-secondary mt-3">
                Already have account? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
            </div>
        </div>
    </div>
@endsection
