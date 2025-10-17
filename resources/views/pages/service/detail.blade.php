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
        <div class="card mb-4">
            <div class="card-header">
                <h3>Detail Service</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Invoice</dt>
                    <dd class="col-sm-9 text-primary fw-bold">{{ $service->no_invoice }}</dd>

                    <dt class="col-sm-3">Nama Customer</dt>
                    <dd class="col-sm-9">{{ $service->customer->name }}</dd>

                    <dt class="col-sm-3">Nama Tekhnisi</dt>
                    <dd class="col-sm-9">{{ $service->technician->name }}</dd>

                    <dt class="col-sm-3">Deskripsi Kerusakan</dt>
                    <dd class="col-sm-9">{{ $service->damage_description }}</dd>

                    <dt class="col-sm-3">Laptop</dt>
                    <dd class="col-sm-9">{{ $service->laptop->model }}</dd>

                    <dt class="col-sm-3">Tanggal Selesai</dt>
                    <dd class="col-sm-9">{{ $service->completed_date }}</dd>

                    <dt class="col-sm-3">Tanggal Kembali</dt>
                    <dd class="col-sm-9">{{ $service->received_date }}</dd>
                </dl>
            </div>
        </div>

        <!-- Product / Service Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Product / Service Item</h3>
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
                                <td>{{ $detail->serviceItem->service_name }}</td>
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
                <h3>Payment & Status</h3>
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

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Status Service</label>
                        <select name="status" class="form-control w-100">
                            <option value="">-- Pilih Status --</option>
                            <option value="accepted" {{ $service->status == 'accepted' ? 'selected' : '' }}>Accepted
                            </option>
                            <option value="process" {{ $service->status == 'process' ? 'selected' : '' }}>Process</option>
                            <option value="taken" {{ $service->status == 'taken' ? 'selected' : '' }}>Taken</option>
                            <option value="finished" {{ $service->status == 'finished' ? 'selected' : '' }}>Finished
                            </option>
                            <option value="cancelled" {{ $service->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select name="paymentmethod" class="form-control w-100">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="cash" {{ $service->paymentmethod == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ $service->paymentmethod == 'transfer' ? 'selected' : '' }}>Transfer
                            </option>
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


                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">Update Payment</button>
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
            subtotalText.innerText = formatRupiah(subtotal);
            totalPaidText.innerText = formatRupiah(totalPaid);

            // tampilkan remaining payment (kalau sudah lunas, jadi Rp 0)
            remainingText.innerText = formatRupiah(remaining > 0 ? remaining : 0);

            // tampilkan total change (kalau ada kembalian)
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
    </script>
@endsection
