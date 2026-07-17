@extends('layouts.app')

@section('title', 'Daftar Member - Syifa Boxing Camp')
@section('meta_description', 'Daftarkan dirimu sebagai member Syifa Boxing Camp dan mulai perjalanan tinju profesionalmu bersama pelatih berpengalaman.')
@section('og_title', 'Daftar Member - Syifa Boxing Camp')
@section('og_description', 'Daftarkan dirimu sebagai member Syifa Boxing Camp dan mulai perjalanan tinju profesionalmu bersama pelatih berpengalaman.')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

    {{-- Page Header --}}
    <section class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1 class="fw-bold">Daftar Member</h1>
            <p class="lead text-muted">Bergabunglah bersama Syifa Boxing Camp</p>
        </div>
    </section>

    {{-- Form Pendaftaran --}}
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card border-0 shadow">
                        <div class="card-body p-5">
                            <h4 class="fw-bold mb-4 text-center">Form Pendaftaran</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label fw-semibold">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                        id="telepon" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required>
                                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin</label>
                                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                            id="jenis_kelamin" name="jenis_kelamin">
                                            <option value="">-- Pilih --</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="kelas" class="form-label fw-semibold">Pilih Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <option value="pemula" {{ old('kelas') == 'pemula' ? 'selected' : '' }}>Kelas Pemula</option>
                                        <option value="menengah" {{ old('kelas') == 'menengah' ? 'selected' : '' }}>Kelas Menengah</option>
                                        <option value="lanjutan" {{ old('kelas') == 'lanjutan' ? 'selected' : '' }}>Kelas Lanjutan</option>
                                    </select>
                                    @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror"
                                        id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger btn-lg fw-bold">
                                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
