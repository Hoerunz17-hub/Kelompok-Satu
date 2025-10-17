@extends('layouts.app')
@section('content')
    <div class="content-wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Judul di pojok kiri atas -->
                    <h4 class="card-title mb-4" style="font-weight: 600;">Edit User</h4>

                    <form class="forms-sample" action="/user/update/{{ $user->id }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control" name="address" id="address" rows="4">{{ $user->address }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="phonenumber">No Handphone</label>
                            <input type="tel" class="form-control" name="phonenumber" id="phonenumber"
                                value="{{ $user->phonenumber }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ $user->email }}">
                        </div>
                        <div class="form-group position-relative">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Kosongkan jika tidak ingin mengganti">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                    <i class="mdi mdi-eye-off" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" name="role" id="role">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Technic
                                </option>
                            </select>
                        </div>

                        <!-- Upload foto -->
                        <div class="form-group">
                            <label for="foto" class="fw-bold mb-2">Ubah Foto</label><br>
                            <input type="file" id="foto" name="foto" accept="image/*"
                                onchange="previewImage(event)" style="border: none; background: none;">
                            <!-- Preview foto -->
                            @if ($user->image)
                                <div class="mb-3 text-center">
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="Foto lama"
                                        class="img-fluid rounded-3" style="max-height: 200px;">
                                </div>
                            @endif
                        </div>

                        <!-- Tombol di sebelah kiri -->
                        <div class="text-start mt-3">
                            <button type="submit" class="btn btn-primary mr-2">Tambah</button>
                            <button type="button" class="btn btn-light">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Warna tombol submit (#07CE04) */
        .btn-primary {
            background-color: #07CE04 !important;
            border-color: #07CE04 !important;
        }

        .btn-primary:hover {
            background-color: #07CE04 !important;
            border-color: #07CE04 !important;
        }

        /* Judul form di pojok kiri */
        .card-title {
            text-align: left !important;
            color: #333;
        }

        /* Hilangkan tampilan kotak besar dari input file */
        input[type="file"] {
            padding: 5px 0;
        }

        .input-group .btn {
            border-color: #ced4da;
        }

        .input-group .btn:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
