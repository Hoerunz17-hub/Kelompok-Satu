@extends('layouts.app')
@section('content')
    <div class="content-wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Judul di pojok kiri atas -->
                    <h4 class="card-title mb-4" style="font-weight: 600;">Edit Service Item</h4>

                    <form class="forms-sample" action="/serviceitem/update/{{ $serviceitem->id }}" method="POST"
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
                            <label for="service_name">Nama Service</label>
                            <input type="text" class="form-control" name="service_name" id="service_name"
                                value="{{ $serviceitem->service_name }}">
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" name="price" id="price"
                                value="{{ $serviceitem->price }}">
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
