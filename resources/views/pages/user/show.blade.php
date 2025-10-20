@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark font-weight-bold mb-0">Detail User</h2>
            <a href="/user" class="btn btn-secondary" style="font-weight:600; padding:10px 22px; border-radius:8px;">
                ← Kembali
            </a>
        </div>

        <!-- Card Detail User -->
        <div class="card shadow-sm border-0 rounded-4" style="max-width: 700px; margin:auto;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    @if ($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" alt="User Photo"
                            class="rounded-circle shadow-sm mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/120" alt="No Photo" class="rounded-circle shadow-sm mb-3">
                    @endif
                    <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Address</h6>
                    <p class="mb-0">{{ $user->address ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Email</h6>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Role</h6>
                    @php
                        $roleColors = [
                            'admin' => 'text-primary',
                            'customer' => 'text-success',
                            'technician' => 'text-danger',
                        ];
                        $roleClass = $roleColors[$user->role] ?? 'text-secondary';
                    @endphp
                    <p class="fw-bold mb-0 {{ $roleClass }}">{{ ucfirst($user->role) }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Phone Number</h6>
                    <p class="mb-0">{{ $user->phonenumber ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- ====================== --}}
        {{-- HISTORY BERDASARKAN ROLE --}}
        {{-- ====================== --}}

        @if ($user->role === 'customer')
            <div class="card shadow-sm border-0 rounded-4 mt-5 mb-5" style="max-width: 1150px; margin:auto;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-dark border-bottom pb-2">History Service Laptop</h4>

                    @if ($user->customerServices->isEmpty())
                        <p class="text-muted mb-0">Belum ada riwayat service.</p>
                    @else
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered align-middle text-center" style="min-width: 1100px;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Laptop</th>
                                        <th>Kerusakan</th>
                                        <th>Status</th>
                                        <th>Status Payment</th>
                                        <th>Paid</th>
                                        <th>Change</th>
                                        <th>Payment Method</th>
                                        <th>Tanggal</th>
                                        <th>Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody id="customerHistory">
                                    @foreach ($user->customerServices as $index => $service)
                                        @php
                                            $statusColors = [
                                                'accepted' => 'text-warning',
                                                'process' => 'text-primary',
                                                'finished' => 'text-success',
                                                'taken' => 'text-info',
                                                'cancelled' => 'text-danger',
                                            ];
                                            $paymentColors = [
                                                'paid' => 'text-success',
                                                'debt' => 'text-warning',
                                                'unpaid' => 'text-danger',
                                            ];
                                            $color = $statusColors[$service->status ?? ''] ?? 'text-secondary';
                                            $paymentColor =
                                                $paymentColors[$service->status_paid ?? ''] ?? 'text-secondary';
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $service->laptop->model ?? ($service->laptop->name ?? '-') }}</td>
                                            <td>{{ $service->damage_description ?? '-' }}</td>
                                            <td><span
                                                    class="fw-semibold {{ $color }}">{{ ucfirst($service->status ?? '-') }}</span>
                                            </td>
                                            <td><span
                                                    class="fw-semibold {{ $paymentColor }}">{{ ucfirst($service->status_paid ?? '-') }}</span>
                                            </td>
                                            <td>Rp {{ number_format($service->paid ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($service->change ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $service->paymentmethod ? ucfirst($service->paymentmethod) : '-' }}</td>
                                            <td>{{ $service->created_at?->format('d M Y') ?? '-' }}</td>
                                            <td>Rp {{ number_format($service->total_cost ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="customerPagination" class="d-flex justify-content-center mt-3"></div>
                    @endif
                </div>
            </div>
        @elseif ($user->role === 'technician')
            <div class="card shadow-sm border-0 rounded-4 mt-5 mb-5" style="max-width: 900px; margin:auto;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-dark border-bottom pb-2">Riwayat Service sebagai Teknisi</h4>

                    @if ($user->technicianServices->isEmpty())
                        <p class="text-muted mb-0">Belum ada riwayat service sebagai teknisi.</p>
                    @else
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Laptop</th>
                                        <th>Kerusakan</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody id="techHistory">
                                    @foreach ($user->technicianServices as $index => $service)
                                        @php
                                            $statusColors = [
                                                'accepted' => 'text-warning',
                                                'process' => 'text-primary',
                                                'finished' => 'text-success',
                                                'taken' => 'text-info',
                                                'cancelled' => 'text-danger',
                                            ];
                                            $color = $statusColors[$service->status ?? ''] ?? 'text-secondary';
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $service->laptop->model ?? ($service->laptop->name ?? '-') }}</td>
                                            <td>{{ $service->damage_description ?? '-' }}</td>
                                            <td><span
                                                    class="fw-semibold {{ $color }}">{{ ucfirst($service->status ?? '-') }}</span>
                                            </td>
                                            <td>{{ $service->created_at?->format('d M Y') ?? '-' }}</td>
                                            <td>Rp {{ number_format($service->total_cost ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="techPagination" class="d-flex justify-content-center mt-3"></div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <style>
        .pagination button {
            border: none;
            background-color: #f0f0f0;
            padding: 6px 14px;
            margin: 0 3px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination button:hover {
            background-color: #007bff;
            color: white;
        }

        .pagination button.active {
            background-color: #007bff;
            color: white;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // === FUNGSI PAGINATION UNTUK TABEL HISTORY ===
            const table = document.querySelector(".card-body table");
            if (!table) return; // kalau gak ada tabel, hentikan

            const rows = Array.from(table.querySelectorAll("tbody tr"));
            const rowsPerPage = 2;
            let currentPage = 1;
            let filteredRows = [...rows];

            // Tambahkan elemen pagination di bawah tabel
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
            table.parentElement.appendChild(pagination);

            const tableInfo = pagination.querySelector("#tableInfo");
            const prevBtn = pagination.querySelector("#prevBtn");
            const nextBtn = pagination.querySelector("#nextBtn");
            const pageIndicator = pagination.querySelector("#pageIndicator");

            // === Fungsi render tabel berdasarkan halaman ===
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

            renderTable();
        });
    </script>

@endsection
