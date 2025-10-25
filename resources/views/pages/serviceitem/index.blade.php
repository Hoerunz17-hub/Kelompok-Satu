@extends('layouts.app')
@section('content')
    <div class="content-wrapper">

        <div class="d-xl-flex justify-content-between align-items-start mb-3">
            <h2 class="text-dark font-weight-bold mb-2">Index Service Item</h2>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <!-- Judul + Tombol Tambah -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Data Service Item</h4>

                        <div class="d-flex align-items-center" style="gap:85px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari Service Item..."
                                style="width:250px; border-radius:8px; font-size:14px;">
                            <select id="filterStatus" class="form-control" style="width:180px; border-radius:8px;">
                                <option value="all">Semua</option>
                                <option value="active">Aktif</option>
                                <option value="deleted">Dihapus</option>
                            </select>


                            <a href="/serviceitem/create" class="btn btn-success"
                                style="font-weight:600; padding:10px 22px; border-radius:8px; font-size:15px;">+ Tambah</a>
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Service</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($serviceitems->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No serviceitem found.</td>
                                </tr>
                            @endif
                            @foreach ($serviceitems as $serviceitem)
                                <tr data-deleted="{{ $serviceitem->deleted_at ? 'true' : 'false' }}">
                                    <td>{{ $serviceitem->id }}</td>
                                    <td>{{ $serviceitem->service_name }}</td>
                                    <td class="text-end"><strong>Rp
                                            {{ number_format($serviceitem->price, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if ($serviceitem->deleted_at)
                                            <span class="badge badge-deleted">Dihapus</span>
                                        @else
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status"
                                                    data-id="{{ $serviceitem->id }}"
                                                    {{ $serviceitem->is_active === 'active' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                    </td>


                                    <td>
                                        @if ($serviceitem->deleted_at)
                                            <a href="/serviceitem/restore/{{ $serviceitem->id }}"
                                                class="btn btn-icon-text btn-info">
                                                <i class="mdi mdi-restore btn-icon-prepend"></i>
                                                Restore
                                            </a>
                                        @else
                                            <a href="/serviceitem/edit/{{ $serviceitem->id }}" class="btn"
                                                style="background-color:#ffc107; border:none; color:white; font-weight:500; padding:8px 16px; border-radius:8px; text-decoration:none; display:inline-block;">
                                                Edit
                                            </a>
                                            <a href="javascript:void(0);"
                                                onclick="confirmDeleteServiceItem({{ $serviceitem->id }})" class="btn"
                                                style="background-color:#ff4d4d; border:none; color:white; font-weight:500; padding:8px 16px; border-radius:8px; text-decoration:none; display:inline-block; margin-left:4px;">
                                                Delete
                                            </a>
                                        @endif
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
        .badge-deleted {
            background-color: #dc3545;
            /* merah polos */
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 8px;
            box-shadow: none;
            /* hilangkan bayangan */
        }

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
            padding: 10px 28px;
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
        function confirmDeleteServiceItem(id) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Data service item yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/serviceitem/delete/' + id;
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            // === TOGGLE STATUS ===
            document.querySelectorAll(".toggle-status").forEach(toggle => {
                toggle.addEventListener("change", function() {
                    let id = this.dataset.id;
                    let status = this.checked ? 1 : 0;

                    fetch(`/serviceitem/toggle/${id}`, {
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
                        .then(data => console.log("Status updated:", data))
                        .catch(err => console.error("Error:", err));
                });
            });

            // === SEARCH + FILTER + PAGINATION ===
            const searchInput = document.getElementById("searchInput");
            const filterStatus = document.getElementById("filterStatus");
            const rows = Array.from(document.querySelectorAll("table tbody tr"));
            const rowsPerPage = 10;
            let currentPage = 1;
            let filteredRows = [...rows];

            const pagination = document.createElement("div");
            pagination.className = "d-flex justify-content-between align-items-center mt-3";
            pagination.innerHTML = `
        <small id="tableInfo" class="text-muted"></small>
        <div>
            <button id="prevBtn" class="btn btn-outline-secondary btn-sm">Prev</button>
            <span id="pageIndicator" class="mx-2 fw-bold">1</span>
            <button id="nextBtn" class="btn btn-outline-secondary btn-sm">Next</button>
        </div>`;
            document.querySelector(".card-body").appendChild(pagination);

            const tableInfo = pagination.querySelector("#tableInfo");
            const prevBtn = pagination.querySelector("#prevBtn");
            const nextBtn = pagination.querySelector("#nextBtn");
            const pageIndicator = pagination.querySelector("#pageIndicator");

            function renderTable() {
                const total = filteredRows.length;
                const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
                currentPage = Math.max(1, Math.min(currentPage, totalPages));

                rows.forEach(r => r.style.display = "none");
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                filteredRows.slice(start, end).forEach(r => r.style.display = "");

                tableInfo.textContent = total ?
                    `Menampilkan ${start + 1} - ${Math.min(end, total)} dari ${total} data` :
                    "Tidak ada data ditemukan";
                pageIndicator.textContent = `${total ? currentPage : 0}/${total ? totalPages : 0}`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || total === 0;
            }

            function applyFilters() {
                const keyword = searchInput.value.toLowerCase().trim();
                const filterType = filterStatus.value;

                filteredRows = rows.filter(row => {
                    const isDeleted = row.dataset.deleted === "true";
                    const matchesSearch = row.innerText.toLowerCase().includes(keyword);
                    if (filterType === "deleted") return isDeleted && matchesSearch;
                    if (filterType === "active") return !isDeleted && matchesSearch;
                    return matchesSearch;
                });

                currentPage = 1;
                renderTable();
            }

            searchInput.addEventListener("keyup", applyFilters);
            filterStatus.addEventListener("change", applyFilters);
            prevBtn.addEventListener("click", () => {
                if (currentPage > 1) currentPage--;
                renderTable();
            });
            nextBtn.addEventListener("click", () => {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages) currentPage++;
                renderTable();
            });

            renderTable();
        });
    </script>
@endsection
