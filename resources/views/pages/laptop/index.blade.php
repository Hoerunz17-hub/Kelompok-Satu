@extends('layouts.app')
@section('content')
    <div class="content-wrapper">

        <div class="d-xl-flex justify-content-between align-items-start mb-3">
            <h2 class="text-dark font-weight-bold mb-2">Index Laptop</h2>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <!-- Judul + Tombol Tambah -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Data Laptop</h4>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari laptop..."
                            style="width:250px; border-radius:8px; font-size:14px; margin-left:8px;">
                        <a href="/laptop/create" class="btn btn-success"
                            style="font-weight:600; padding:10px 22px; border-radius:8px; font-size:15px;">
                            + Tambah
                        </a>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Relase year</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($laptops->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No Laptop found.</td>
                                </tr>
                            @endif
                            @foreach ($laptops as $laptop)
                                <tr>
                                    <td>{{ $laptop->id }}</td>
                                    <td>
                                        @if ($laptop->image)
                                            <img src="{{ asset('storage/' . $laptop->image) }}" alt="photo"
                                                class="img-fluid rounded"
                                                style="width:80px; height:50px; object-fit:cover;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $laptop->brand }}</td>
                                    <td>{{ $laptop->model }}</td>
                                    <td>{{ $laptop->release_year }}</td>


                                    <td>
                                        <!-- Toggle besar -->
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-status" data-id="{{ $laptop->id }}"
                                                {{ $laptop->is_active === 'active' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td>
                                        <a href="/laptop/edit/{{ $laptop->id }}" class="btn"
                                            style="background-color:#ffc107; border:none; color:white; font-weight:500; padding:8px 16px; border-radius:8px; text-decoration:none; display:inline-block;">
                                            Edit
                                        </a>
                                        <a href="javascript:void(0);"
                                            onclick="confirmDelete('/laptop/delete/{{ $laptop->id }}')" class="btn"
                                            style="background-color:#ff4d4d; border:none; color:white; font-weight:500; padding:8px 16px; border-radius:8px; text-decoration:none; display:inline-block; margin-left:4px;">
                                            Delete
                                        </a>

                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* === CSS Toggle === */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #44CE42;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        /* === PERATAAN TABEL === */
        table.table th,
        table.table td {
            vertical-align: middle !important;
            text-align: center;
        }

        /* Pastikan baris punya tinggi tetap */
        .table-hover tbody tr {
            height: 75px;
        }

        /* Kolom status (toggle) biar tengah */
        table.table td:nth-child(6) {
            text-align: center;
        }

        /* Kolom Action sejajar sempurna */
        /* Kolom Action sejajar sempurna */
        table.table td:last-child {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4px;
            /* semula 12px */
        }

        /* Biar tombol lebih proporsional (nggak gepeng) */
        .table a.btn {
            padding: 8px 16px;
            /* semula 10px 28px */
            border-radius: 8px;
            font-weight: 500;
            color: white;
            text-decoration: none;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        /* Padding kartu */
        .card-body {
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif
    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        document.addEventListener("DOMContentLoaded", function() {

            // === TOGGLE STATUS ===
            document.querySelectorAll(".toggle-status").forEach(toggle => {
                toggle.addEventListener("change", function() {
                    let laptopId = this.dataset.id;
                    let status = this.checked ? 1 : 0;

                    fetch(`/laptop/toggle/${laptopId}`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log("Status updated:", data);
                        })
                        .catch(err => console.error("Error:", err));
                });
            });

            // === SEARCH + PAGINATION ===
            const searchInput = document.getElementById("searchInput");
            const rows = Array.from(document.querySelectorAll("table.table tbody tr"));
            const rowsPerPage = 10;
            let currentPage = 1;
            let filteredRows = [...rows]; // data awal

            // Pagination container
            const pagination = document.createElement("div");
            pagination.className = "d-flex justify-content-between align-items-center mt-3";
            pagination.innerHTML = `
            <small id="tableInfo" class="text-muted"></small>
            <div>
                <button id="prevBtn" class="btn btn-outline-secondary btn-sm">Prev</button>
                <span id="pageIndicator" class="mx-2 fw-bold">1</span>
                <button id="nextBtn" class="btn btn-outline-secondary btn-sm">Next</button>
            </div>
        `;
            document.querySelector(".card-body").appendChild(pagination);

            const tableInfo = pagination.querySelector("#tableInfo");
            const prevBtn = pagination.querySelector("#prevBtn");
            const nextBtn = pagination.querySelector("#nextBtn");
            const pageIndicator = pagination.querySelector("#pageIndicator");

            function renderTable() {
                const total = filteredRows.length;
                const totalPages = Math.ceil(total / rowsPerPage);
                currentPage = Math.max(1, Math.min(currentPage, totalPages));

                // Sembunyikan semua
                rows.forEach(r => r.style.display = "none");

                // Tampilkan sesuai halaman
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                filteredRows.slice(start, end).forEach(r => r.style.display = "");

                // Update info
                tableInfo.textContent = total ?
                    `Menampilkan ${start + 1} - ${Math.min(end, total)} dari ${total} data` :
                    "Tidak ada data ditemukan";
                pageIndicator.textContent = `${total ? currentPage : 0}/${total ? totalPages : 0}`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || total === 0;
            }

            prevBtn.addEventListener("click", () => {
                if (currentPage > 1) currentPage--;
                renderTable();
            });

            nextBtn.addEventListener("click", () => {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages) currentPage++;
                renderTable();
            });

            // === SEARCH FUNCTION ===
            searchInput.addEventListener("keyup", function() {
                const keyword = this.value.toLowerCase().trim();
                filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(keyword));
                currentPage = 1; // reset halaman ke 1 saat cari
                renderTable();
            });

            renderTable();
        });
    </script>
@endsection
