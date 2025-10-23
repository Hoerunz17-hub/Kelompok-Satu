@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="d-xl-flex justify-content-between align-items-start mb-3">
            <h2 class="text-dark font-weight-bold mb-2">Index Service</h2>
        </div>

        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Index Transaksi
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari Transaksi..."
                            style="width:250px; border-radius:8px; font-size:14px; margin-left:8px;">
                        <a href="/service/create" class="btn"
                            style="background-color:#007bff; color:white; font-weight:500; padding:8px 16px; border-radius:8px; text-decoration:none;">
                            Add Service
                        </a>
                    </h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Nama</th>
                                <th>Laptop</th>
                                <th>Date</th>
                                <th>Status Service</th>
                                <th>Total Harga</th>
                                <th>Status Bayar</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td style="color:#0069ff; font-weight:600;">
                                        {{ $service->no_invoice }}
                                    </td>

                                    <td>{{ $service->customer->name ?? '-' }}</td>
                                    <td>{{ $service->laptop->model ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($service->received_date)->format('d M Y') }}</td>

                                    {{-- STATUS SERVICE --}}
                                    <td>
                                        @php
                                            $statusColor = match ($service->status) {
                                                'accepted' => '#ff66b2', // pink
                                                'process' => '#ffc107', // kuning
                                                'finished' => '#28a745', // hijau
                                                'taken' => '#007bff', // biru
                                                'cancelled' => '#dc3545', // merah
                                            };
                                        @endphp
                                        <div
                                            style="
                                    display:inline-block;
                                    background-color: {{ $statusColor }};
                                    color: white;
                                    font-weight: 900;
                                    padding: 5px 10px;
                                    border-radius: 8px;
                                    text-transform: capitalize;
                                ">
                                            {{ $service->status }}
                                        </div>
                                    </td>

                                    {{-- TOTAL HARGA --}}
                                    <td style="font-weight:bold; color:#28a745;">
                                        Rp {{ number_format($service->total_cost, 0, ',', '.') }}
                                    </td>

                                    {{-- STATUS PEMBAYARAN --}}
                                    <td>
                                        @php
                                            $statusPaid = strtolower($service->status_paid ?? '');
                                        @endphp

                                        @if ($statusPaid === 'paid')
                                            <div
                                                style="
                                        display:inline-block;
                                        background-color:#d4f8d4;
                                        color:#00b300;
                                        padding:5px 10px;
                                        border-radius:8px;
                                        font-weight:900;
                                    ">
                                                Paid
                                            </div>
                                        @elseif ($statusPaid === 'unpaid')
                                            <div
                                                style="
                                        display:inline-block;
                                        background-color:#fff3cd;
                                        color:#ffbb00;
                                        padding:5px 10px;
                                        border-radius:8px;
                                        font-weight:900;
                                    ">
                                                Unpaid
                                            </div>
                                        @elseif ($statusPaid === 'debt')
                                            <div
                                                style="
                                        display:inline-block;
                                        background-color:#f8d7da;
                                        color:#dc3545;
                                        padding:5px 10px;
                                        border-radius:8px;
                                        font-weight:900;
                                    ">
                                                Debt
                                            </div>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>

                                    <link rel="stylesheet"
                                        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
                                    {{-- OPTIONS --}}
                                    <td class="text-center">
                                        <a href="/service/detail/{{ $service->id }}"
                                            class="btn d-inline-flex justify-content-center align-items-center"
                                            style="background-color:#0069ff; border:none; color:white; width:35px; height:35px; border-radius:8px;"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="/service/delete/{{ $service->id }}"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                                            class="btn d-inline-flex justify-content-center align-items-center text-white"
                                            style="background-color:#FF5B5B; width:35px; height:35px; border-radius:8px;">
                                            <i class="bi bi-trash"></i>
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

            // Ambil semua row
            const rows = Array.from(document.querySelectorAll("table.table tbody tr"));
            const rowsPerPage = 3; // tampilkan 3 data per halaman
            let currentPage = 1;
            let filteredRows = [...rows]; // untuk search + pagination

            // Buat elemen pagination di bawah tabel
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
                const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
                currentPage = Math.max(1, Math.min(currentPage, totalPages));

                // Sembunyikan semua row
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
            const searchInput = document.getElementById("searchInput");
            if (searchInput) {
                searchInput.addEventListener("keyup", function() {
                    const keyword = this.value.toLowerCase().trim();
                    filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(keyword));
                    currentPage = 1; // reset ke halaman 1 saat mencari
                    renderTable();
                });
            }

            renderTable();
        });
    </script>
@endsection
