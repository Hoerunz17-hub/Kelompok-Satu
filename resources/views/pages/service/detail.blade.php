@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="d-xl-flex justify-content-between align-items-start mb-3">
            <h2 class="text-dark font-weight-bold mb-2">Service Payment Detail</h2>
            <a href="/service" class="btn btn-primary text-white fw-semibold" style="border-radius: 8px; padding: 8px 16px;">
                ← Kembali
            </a>
        </div>


        <!-- Detail Section -->
        <div class="card mb-4 shadow-sm border-0 rounded-4">
            <div class="card-header">
                <h3 class="mb-0 fw-bold text-dark">Detail Service</h3>
            </div>
            <div class="card-body py-4 px-4">
                <dl class="row mb-0">
                    <dt class="col-sm-3 mb-3 text-secondary">Invoice</dt>
                    <dd class="col-sm-9 mb-3 text-primary fw-bold">{{ $service->no_invoice }}</dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Nama Customer</dt>
                    <dd class="col-sm-9 mb-3">{{ optional($service->customer)->name ?? '-' }}</dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Nama Teknisi</dt>
                    <dd class="col-sm-9 mb-3">{{ optional($service->technician)->name ?? '-' }}</dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Deskripsi Kerusakan</dt>
                    <dd class="col-sm-9 mb-3">{{ $service->damage_description }}</dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Laptop</dt>
                    <dd class="col-sm-9 mb-3">{{ optional($service->laptop)->model ?? '-' }}</dd>

                    @php
                        $status = $service->status ?? null;
                        $statusColors = [
                            'accepted' => '#ff66b2',
                            'process' => '#ffc107',
                            'finished' => '#28a745',
                            'taken' => '#007bff',
                            'cancelled' => '#dc3545',
                        ];
                        $color = $statusColors[$status] ?? null;
                    @endphp

                    <dt class="col-sm-3 mb-3 text-secondary">Status Service</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($color)
                            <strong style="font-weight:700; color: {{ $color }};">
                                {{ ucfirst($status) }}
                            </strong>
                        @else
                            <strong style="font-weight:700;">
                                {{ ucfirst($status) }}
                            </strong>
                        @endif
                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Jenis Pembayaran</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->paymentmethod)
                            <strong class="text-dark">{{ $service->paymentmethod }}</strong>
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif

                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Status Bayar</dt>
                    <dd class="col-sm-9 mb-3"><strong>{{ $service->status_paid }}</strong></dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Biaya lain</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->other_cost)
                            <strong class="fw-bold text-dark">
                                Rp {{ number_format($service->other_cost, 0, ',', '.') }}
                            </strong>
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Total Biaya</dt>
                    <dd class="col-sm-9 mb-3" fw-bold text-primary">
                        @if ($service->total_cost)
                            <strong class="fw-bold text-dark">
                                Rp {{ number_format($service->total_cost, 0, ',', '.') }}
                            </strong>
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Biaya Estimasi</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->estimated_cost)
                            <strong class="fw-bold text-dark">
                                Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}
                            </strong>
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Bayar</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->paid)
                            <strong class="fw-bold text-dark">
                                Rp {{ number_format($service->paid, 0, ',', '.') }}
                            </strong>
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Kembalian</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->change)
                            <strong class="fw-bold text-primary">
                                Rp {{ number_format($service->change, 0, ',', '.') }}
                            </strong>
                        @else
                            <span class="fw-bold text-primary">—</span>
                        @endif
                    </dd>


                    <dt class="col-sm-3 mb-3 text-secondary">Tanggal Selesai</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->completed_date)
                            {{ $service->completed_date }}
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif

                    </dd>

                    <dt class="col-sm-3 mb-3 text-secondary">Tanggal Kembali</dt>
                    <dd class="col-sm-9 mb-3">
                        @if ($service->received_date)
                            {{ $service->received_date }}
                        @else
                            <span class="fw-bold text-dark">—</span>
                        @endif

                    </dd>
                </dl>
            </div>
        </div>


        <!-- Product / Service Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-dark">Product / Service Item</h3>
            </div>
            <div class="card-body">
                <table id="serviceTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service Name</th>
                            <th>Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($service->details as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($detail->serviceitem)->service_name ?? '-' }}</td>
                                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada item service</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Section -->
        <!-- Payment Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-dark">Payment & Status</h3>
            </div>
            <div class="card-body">

                <form action="/service/detail/{{ $service->id }}/update-payment" method="POST" id="paymentForm">

                    @csrf
                    @method('PATCH')

                    @php
                        $totalItems = $service->details->count();
                        $totalPrice = $service->details->sum('price');
                        $otherCost = $service->other_cost ?? 0;
                        $paid = $service->paid ?? 0;
                        $subtotal = $totalPrice + $otherCost;
                        $estimated = $totalPrice;
                        $remaining = $subtotal - $paid;
                    @endphp

                    <!-- Info Summary -->
                    <div class="row mb-4 payment-summary">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>Total Items</span>
                                <span><strong>{{ $totalItems }} Items</strong></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total Price</span>
                                <span><strong id="total_price">Rp
                                        {{ number_format($totalPrice, 0, ',', '.') }}</strong></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total Paid</span>
                                <span><strong id="total_paid">Rp {{ number_format($paid, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>Estimated Cost</span>
                                <span><strong id="estimated_cost">Rp
                                        {{ number_format($estimated, 0, ',', '.') }}</strong></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Other Cost</span>
                                <span><strong id="display_other_cost">Rp
                                        {{ number_format($otherCost, 0, ',', '.') }}</strong></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span><strong id="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Change</span>
                                <span><strong id="change">Rp
                                        {{ number_format(max(0, $paid - $subtotal), 0, ',', '.') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Tanggal kembali & selesai (hilang kalau sudah ada) --}}
                    @if (!$service->received_date)
                        <div class="form-group">
                            <label for="received_date">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="received_date" id="received_date">
                        </div>
                    @endif

                    @if (!$service->completed_date)
                        <div class="form-group">
                            <label for="completed_date">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="completed_date" id="completed_date">
                        </div>
                    @endif

                    <!-- Status -->
                    @if (!in_array($service->status, ['taken', 'cancelled']))
                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Service</label>
                            <select name="status" class="form-control w-100">
                                <option value="">-- Pilih Status --</option>
                                <option value="accepted" {{ $service->status == 'accepted' ? 'selected' : '' }}>Accepted
                                </option>
                                <option value="process" {{ $service->status == 'process' ? 'selected' : '' }}>Process
                                </option>
                                <option value="finished" {{ $service->status == 'finished' ? 'selected' : '' }}>Finished
                                </option>
                                <option value="taken" {{ $service->status == 'taken' ? 'selected' : '' }}>Taken</option>
                                <option value="cancelled" {{ $service->status == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                    @endif

                    {{-- ✅ Tambahkan alert di bawah ini --}}
                    @if ($service->status_paid === 'paid')
                        <div class="alert alert-success mt-3 mb-0">
                            <strong>✅ 'Semua biaya sudah dilunasi. Terima kasih 😊'</strong>
                        </div>
                    @endif

                    {{-- Payment section muncul hanya kalau belum lunas --}}
                    @if ($service->status_paid !== 'paid')
                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select name="paymentmethod" class="form-control w-100">
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="cash" {{ $service->paymentmethod == 'cash' ? 'selected' : '' }}>Cash
                                </option>
                                <option value="transfer" {{ $service->paymentmethod == 'transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                            </select>
                        </div>

                        <!-- Other Cost -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Other Cost</label>
                            <input type="text" name="other_cost" id="other_cost" class="form-control w-100 text-end"
                                value="{{ number_format($otherCost, 0, ',', '.') }}">
                        </div>

                        <!-- Paid -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Paid</label>
                            <input type="text" name="paid" id="paid" class="form-control w-100 text-end"
                                placeholder="Masukkan jumlah dibayar">
                        </div>


                        <!-- Remaining -->
                        <p class="mt-2 fs-5 fw-bold text-dark">
                            Remaining payment:
                            <span id="remaining_payment" style="color:#007bff; font-weight:600;">
                                {{ $remaining > 0 ? 'Rp ' . number_format($remaining, 0, ',', '.') : 'Rp 0' }}
                            </span>
                        </p>
                    @endif

                    <!-- Tombol Update -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary px-4"
                            {{ in_array($service->status, ['taken', 'cancelled']) ? 'disabled' : '' }}>
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </div>
    <style>
        .payment-summary {
            font-size: 18px;
        }

        .payment-summary .col-md-6 {
            padding-left: 30px;
            padding-right: 30px;
        }

        .payment-summary .d-flex {
            margin-bottom: 15px;
            /* jarak antar baris */
        }

        .payment-summary span:first-child {
            color: #6c757d;
        }

        .payment-summary strong {
            color: #343a40;
        }
    </style>

    @php
        $paidBefore = $service->paid ?? 0;
    @endphp

    <script>
        const formatRupiah = (angka) => {
            if (!angka) return 'Rp 0';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const parseNumber = (val) => parseInt(val.replace(/[^0-9]/g, '')) || 0;

        const totalPrice = {{ $totalPrice }};
        const paidBefore = {{ $paidBefore }};
        const otherInput = document.getElementById('other_cost');
        const displayOtherText = document.getElementById('display_other_cost');
        const paidInput = document.getElementById('paid');
        const estimatedText = document.getElementById('estimated_cost');
        const subtotalText = document.getElementById('subtotal');
        const changeText = document.getElementById('change');
        const totalPaidText = document.getElementById('total_paid');
        const remainingText = document.getElementById('remaining_payment');

        function updateCalculations() {
            const other = parseNumber(otherInput.value);
            const paidNow = parseNumber(paidInput.value);
            const subtotal = totalPrice + other;
            const totalPaid = paidBefore + paidNow;
            const change = totalPaid - subtotal;
            const remaining = subtotal - totalPaid;

            estimatedText.innerText = formatRupiah(totalPrice);
            displayOtherText.innerText = formatRupiah(other);
            subtotalText.innerText = formatRupiah(subtotal);
            totalPaidText.innerText = formatRupiah(totalPaid);

            remainingText.innerText = formatRupiah(remaining > 0 ? remaining : 0);
            changeText.innerText = change > 0 ? formatRupiah(change) : 'Rp 0';
        }


        function formatLiveInput(input) {
            input.addEventListener('input', function() {
                let value = parseNumber(this.value);
                this.value = value.toLocaleString('id-ID');
                updateCalculations();
            });
        }

        formatLiveInput(otherInput);
        formatLiveInput(paidInput);
        paidInput.value = '';
        updateCalculations();

        // === Tambahan logika untuk status cancelled ===
        document.addEventListener("DOMContentLoaded", function() {
            const serviceStatus = "{{ strtolower($service->status) }}";

            if (serviceStatus === "cancelled") {
                // Sembunyikan semua form field dari tanggal sampai remaining payment
                const fieldsToHide = document.querySelectorAll(
                    'label[for="received_date"], #received_date, ' +
                    'label[for="completed_date"], #completed_date, ' +
                    'label[for="paymentmethod"], [name="paymentmethod"], ' +
                    'label[for="other_cost"], #other_cost, ' +
                    'label[for="paid"], #paid, ' +
                    '#remaining_payment'
                );

                fieldsToHide.forEach(el => {
                    if (el) el.closest('.form-group, .mb-3, p')?.classList.add('d-none');
                });

                // Disable tombol Update Payment
                const updateBtn = document.querySelector('button[type="submit"]');
                if (updateBtn) updateBtn.disabled = true;

                // Tampilkan alert peringatan
                alert("Status service ini telah dibatalkan (cancelled). Pembayaran tidak dapat dilakukan.");
            }
        });
    </script>
@endsection
