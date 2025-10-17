@extends('layouts.app')
@section('content')
    <div class="content-wrapper">

        <div class="d-xl-flex justify-content-between align-items-start mb-3">
            <h2 class="text-dark font-weight-bold mb-2">Service-create</h2>
            <a href="/service" class="btn btn-primary text-white fw-semibold" style="border-radius: 8px; padding: 8px 16px;">
                ← Kembali
            </a>
        </div>


        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body">
                    <h4 class="card-title">Buat Pesanan</h4>

                    <form class="forms-sample" action="/service/store" method="POST">
                        @csrf

                        {{-- ============ FORM UTAMA ============ --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_invoice">Invoice</label>
                                    <input type="text" class="form-control" name="no_invoice" id="no_invoice"
                                        value="{{ 'INV-' . now()->format('Ymd') . '-' . str_pad(\App\Models\Services::whereDate('created_at', now())->count() + 1, 3, '0', STR_PAD_LEFT) }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label for="teknisi">Teknisi</label>
                                    <select class="form-control" name="technician_id" id="teknisi">
                                        <option value="">--Pilih Teknisi--</option>
                                        @foreach ($technicians as $tech)
                                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="damage_description">Deskripsi Kerusakan</label>
                                    <textarea class="form-control" name="damage_description" id="damage_description" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Pelanggan">Pelanggan</label>
                                    <select class="form-control" name="customer_id" id="Pelanggan">
                                        <option value="">--Pilih Pelanggan--</option>
                                        @foreach ($customers as $cust)
                                            <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="received_date">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="received_date" id="received_date">
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="laptop">Laptop</label>
                                    <select class="form-control" name="laptop_id" id="laptop">
                                        <option value="">--Pilih Laptop--</option>
                                        @foreach ($laptops as $lap)
                                            <option value="{{ $lap->id }}">{{ $lap->brand }}</option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="form-group">
                                    <label for="completed_date">Tanggal Selesai</label>
                                    <input type="date" class="form-control" name="completed_date" id="completed_date">
                                </div>
                            </div>
                        </div>

                        {{-- ============ TABEL SERVICE ============ --}}
                        <div class="mt-4">
                            <h4 class="card-title mb-3">Service</h4>
                            <div class="table-responsive">
                                <table class="table align-middle" id="serviceTable" style="width:100%;">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th>#</th>
                                            <th>Action</th>
                                            <th>Service</th>
                                            <th>Harga</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="serviceTableBody">
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm fw-bold text-white delete-row"
                                                    style="background-color:#FF5B5B; border-radius:8px; padding:6px 14px;">X</button>
                                            </td>
                                            <td>
                                                <select class="form-select serviceSelect" name="serviceitem_id[]" required
                                                    style="border-radius:8px; height:44px;">
                                                    <option value="">Pilih Service Item</option>
                                                    @foreach ($serviceItems as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-price="{{ $item->price }}">
                                                            {{ $item->service_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="price[]" class="form-control harga" readonly
                                                    style="border-radius:8px; height:44px; background-color:#f4f5f7;"></td>
                                            <td><input type="text" name="subtotal[]" class="form-control subTotal"
                                                    readonly
                                                    style="border-radius:8px; height:44px; background-color:#f4f5f7;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" id="addServiceRow" class="btn w-100 mt-3 fw-semibold"
                                style="background-color:#E9E9E9; border-radius:10px; color:#001B36; height:45px;">
                                Add Service
                            </button>
                        </div>

                        {{-- ============ TOTAL KESELURUHAN ============ --}}
                        <div class="mt-4 text-end">
                            <div class="card border-0 shadow-sm d-inline-block px-4 py-3"
                                style="background-color:#f7f8fa; border-radius:12px;">
                                <h5 class="m-0">💰 Total Keseluruhan:
                                    <span id="totalSemua" class="fw-bold text-dark">Rp 0</span>
                                </h5>
                            </div>
                        </div>

                        {{-- ============ TOMBOL SUBMIT ============ --}}
                        <div class="mt-5 d-flex justify-content-end gap-2" style="padding-right: 40px;">
                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ SCRIPT ============ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceTable = document.querySelector('#serviceTable tbody');
            const addBtn = document.getElementById('addServiceRow');
            const totalDisplay = document.getElementById('totalSemua');

            // Format angka ke rupiah
            function formatNumber(value) {
                return value.toLocaleString('id-ID');
            }

            // Parse string jadi angka
            function parseNumber(value) {
                return parseFloat(value.replace(/\./g, '')) || 0;
            }

            // Fungsi update tombol hapus (disable kalau cuma 1 baris)
            function updateDeleteButtons() {
                const rows = serviceTable.querySelectorAll('tr');
                const deleteButtons = serviceTable.querySelectorAll('.delete-row');
                if (rows.length === 1) {
                    deleteButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.style.opacity = '0.5';
                        btn.style.cursor = 'not-allowed';
                    });
                } else {
                    deleteButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                        btn.style.cursor = 'pointer';
                    });
                }
            }

            // Tambah baris baru
            addBtn.addEventListener('click', function() {
                const rowCount = serviceTable.rows.length + 1;
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td class="text-center">${rowCount}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm fw-bold text-white delete-row"
                        style="background-color:#FF5B5B; border-radius:8px; padding:6px 14px;">X</button>
                </td>
                <td>
                    <select class="form-select serviceSelect" name="serviceitem_id[]" required style="border-radius:8px; height:44px;">
                        <option value="">Pilih Service Item</option>
                        @foreach ($serviceItems as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                {{ $item->service_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="price[]" class="form-control harga" readonly style="border-radius:8px; height:44px; background-color:#f4f5f7;"></td>
                <td><input type="text" name="subtotal[]" class="form-control subTotal" readonly style="border-radius:8px; height:44px; background-color:#f4f5f7;"></td>
            `;
                serviceTable.appendChild(newRow);
                updateRowNumbers();
                updateDeleteButtons();
            });

            // Hapus baris
            serviceTable.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-row')) {
                    const rows = serviceTable.querySelectorAll('tr');
                    if (rows.length > 1) {
                        e.target.closest('tr').remove();
                        updateRowNumbers();
                        updateTotal();
                        updateDeleteButtons();
                    }
                }
            });

            // Saat pilih service item, otomatis isi harga dan subtotal
            serviceTable.addEventListener('change', function(e) {
                if (e.target.classList.contains('serviceSelect')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                    const row = e.target.closest('tr');
                    row.querySelector('.harga').value = formatNumber(price);
                    row.querySelector('.subTotal').value = formatNumber(price);
                    updateTotal();
                }
            });

            // Update nomor urut
            function updateRowNumbers() {
                [...serviceTable.rows].forEach((row, i) => {
                    row.cells[0].innerText = i + 1;
                });
            }

            // Hitung total keseluruhan
            function updateTotal() {
                let total = 0;
                document.querySelectorAll('.subTotal').forEach(input => {
                    total += parseNumber(input.value);
                });
                totalDisplay.textContent = 'Rp ' + formatNumber(total);
            }

            // Panggil saat pertama kali halaman dimuat
            updateDeleteButtons();
        });
    </script>
@endsection
