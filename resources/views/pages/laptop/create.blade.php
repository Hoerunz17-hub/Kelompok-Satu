@extends('layouts.app')
@section('content')
    <div class="content-wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Judul di pojok kiri atas -->
                    <h4 class="card-title mb-4" style="font-weight: 600;">Tambah Laptop</h4>

                    <form class="forms-sample" action="/laptop/store" method="POST" enctype="multipart/form-data">
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
                            <label for="brand">Brand</label>
                            <input type="text" class="form-control" name="brand" id="brand">
                        </div>
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" name="model" id="model">
                        </div>
                        <div class="form-group">
                            <label for="release_year">Thn Rilis</label>
                            <input type="text" class="form-control" name="release_year" id="release_year">
                        </div>
                        <!-- Upload foto -->
                        <div class="form-group">
                            <label for="foto" class="fw-bold mb-2">Tambah Foto</label><br>
                            <input type="file" id="foto" name="foto" accept="image/*"
                                onchange="previewImage(event)" style="border: none; background: none;">
                            <!-- Preview foto -->
                            <div class="mt-3">
                                <img id="preview" src="#" alt="Preview Foto"
                                    style="display: none; max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
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
        /* Warna tombol submit (#A461D8) */
        .btn-primary {
            background-color: #A461D8 !important;
            border-color: #A461D8 !important;
        }

        .btn-primary:hover {
            background-color: #9247c5 !important;
            border-color: #9247c5 !important;
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

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.style.display = "none";
            }
        }
    </script>
@endsection
