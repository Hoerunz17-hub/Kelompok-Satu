@extends('layouts.app')
@section('content')
    <div class="content-wrapper">

        <div class="d-xl-flex justify-content-between align-items-start mb-3">
            <h2 class="text-dark font-weight-bold mb-2">Index User</h2>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <!-- Judul + Tombol Tambah -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-3 mb-md-0 text-dark fw-bold">Data User</h4>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center" style="gap: 20px; margin-right: 50px;">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari user..."
                                    style="width:250px; border-radius:8px; font-size:14px;">

                                <select id="roleFilter" class="form-select"
                                    style="width:220px; border-radius:8px; font-size:14px;">
                                    <option value="">Semua Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="customer">Customer</option>
                                    <option value="technician">Technician</option>
                                </select>
                            </div>

                            <a href="/user/create" class="btn btn-success"
                                style="font-weight:600; padding:10px 22px; border-radius:8px; font-size:15px;">
                                + Tambah
                            </a>
                        </div>
                    </div>





                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">No User found.</td>
                                </tr>
                            @endif
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        @if ($user->image)
                                            <img src="{{ asset('storage/' . $user->image) }}" alt="photo"
                                                class="img-fluid rounded"
                                                style="width:80px; height:50px; object-fit:cover;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'admin' => 'text-primary',
                                                'customer' => 'text-success',
                                                'technician' => 'text-danger',
                                            ];
                                            $colorClass = $roleColors[$user->role] ?? 'text-secondary';
                                        @endphp
                                        <span class="fw-bold {{ $colorClass }}">{{ ucfirst($user->role) }}</span>
                                    </td>

                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-status" data-id="{{ $user->id }}"
                                                {{ $user->is_active === 'active' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td>
                                        <a href="/user/show/{{ $user->id }}" class="btn"
                                            style="background-color:#0069ff; border:none; color:white; padding:8px 16px; border-radius:8px;">Detail</a>
                                        <a href="/user/edit/{{ $user->id }}" class="btn"
                                            style="background-color:#ffc107; border:none; color:white; padding:8px 16px; border-radius:8px;">Edit</a>
                                        <a href="/user/delete/{{ $user->id }}" class="btn"
                                            style="background-color:#ff4d4d; border:none; color:white; padding:8px 16px; border-radius:8px;">Delete</a>
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

        table.table th,
        table.table td {
            vertical-align: middle !important;
            text-align: center;
        }

        .table-hover tbody tr {
            height: 75px;
        }

        table.table td:nth-child(6) {
            text-align: center;
        }

        table.table td:last-child {
            display: flex;
            justify-content: center;
            gap: 4px;
        }

        .table a.btn {
            padding: 10px 28px;
            border-radius: 8px;
            font-weight: 500;
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
        document.addEventListener("DOMContentLoaded", function() {
            // === TOGGLE STATUS ===
            document.querySelectorAll(".toggle-status").forEach(toggle => {
                toggle.addEventListener("change", function() {
                    let userId = this.dataset.id;
                    let status = this.checked ? 1 : 0;

                    fetch(`/user/toggle/${userId}`, {
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

            // === SEARCH + PAGINATION ===
            const searchInput = document.getElementById("searchInput");
            const rows = Array.from(document.querySelectorAll("table.table tbody tr"));
            const rowsPerPage = 10;
            let currentPage = 1;
            let filteredRows = [...rows];

            // === Pagination container ===
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
                currentPage = Math.max(1, Math.min(currentPage, totalPages || 1));

                rows.forEach(r => (r.style.display = "none"));
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                filteredRows.slice(start, end).forEach(r => (r.style.display = ""));

                tableInfo.textContent = total > 0 ?
                    `Menampilkan ${start + 1} - ${Math.min(end, total)} dari ${total} data` :
                    "Tidak ada data ditemukan";

                pageIndicator.textContent = `${currentPage}/${Math.max(totalPages, 1)}`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
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

            const roleFilter = document.getElementById("roleFilter");

            function applyFilters() {
                const keyword = searchInput.value.toLowerCase().trim();
                const selectedRole = roleFilter.value.toLowerCase();

                filteredRows = rows.filter(row => {
                    const textMatch = row.textContent.toLowerCase().includes(keyword);
                    const roleCell = row.querySelector("td:nth-child(5)");
                    const roleText = roleCell ? roleCell.textContent.toLowerCase().trim() : "";
                    const roleMatch = selectedRole ? roleText === selectedRole : true;
                    return textMatch && roleMatch;
                });

                currentPage = 1;
                renderTable();
            }

            searchInput.addEventListener("input", applyFilters);
            roleFilter.addEventListener("change", applyFilters);


            renderTable();
        });
    </script>
@endsection
