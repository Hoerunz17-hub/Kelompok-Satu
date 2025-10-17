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
                <h5>Detail Service</h5>
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
                <h5>Product / Service Item</h5>
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
        <div class="card mb-4">
            <div class="card-header">
                <h5>Payment & Status</h5>
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
                        $estimated = $totalPrice; // hanya dari total price
                    @endphp

                    <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Service</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="accepted" {{ $service->status == 'accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="process" {{ $service->status == 'process' ? 'selected' : '' }}>Process
                                    </option>
                                    <option value="taken" {{ $service->status == 'taken' ? 'selected' : '' }}>Taken
                                    </option>
                                    <option value="finished" {{ $service->status == 'finished' ? 'selected' : '' }}>
                                        Finished</option>
                                    <option value="cancelled" {{ $service->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Payment Method</label>
                                <select name="paymentmethod" class="form-control">
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="cash" {{ $service->paymentmethod == 'cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="transfer" {{ $service->paymentmethod == 'transfer' ? 'selected' : '' }}>
                                        Transfer</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Other Cost</label>
                                <input type="text" name="other_cost" id="other_cost" class="form-control"
                                    value="{{ number_format($otherCost, 0, ',', '.') }}"
                                    placeholder="Masukkan biaya lain (optional)">
                            </div>

                            <div class="form-group">
                                <label>Paid</label>
                                <input type="text" name="paid" id="paid" class="form-control"
                                    value="{{ number_format($paid, 0, ',', '.') }}" placeholder="Masukkan jumlah dibayar">
                            </div>


                        </div>

                        <!-- Kolom kanan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Items</label>
                                <input type="text" readonly class="form-control" id="total_items"
                                    value="{{ $totalItems }}">
                            </div>

                            <div class="form-group">
                                <label>Total Price</label>
                                <input type="text" readonly class="form-control" id="total_price"
                                    value="Rp {{ number_format($totalPrice, 0, ',', '.') }}">
                            </div>

                            <div class="form-group">
                                <label>Estimated Cost</label>
                                <input type="text" readonly class="form-control" id="estimated_cost"
                                    value="Rp {{ number_format($estimated, 0, ',', '.') }}">
                            </div>


                            <div class="form-group">
                                <label>Change</label>
                                <input type="text" readonly class="form-control" id="change" name="change"
                                    value="Rp {{ number_format(max(0, $paid - $subtotal), 0, ',', '.') }}">
                            </div>

                            <!-- Subtotal pindah ke sini -->
                            <div class="form-group">
                                <label>Subtotal</label>
                                <input type="text" readonly class="form-control" id="subtotal"
                                    value="Rp {{ number_format($subtotal, 0, ',', '.') }}">
                            </div>

                        </div>
                    </div>


                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const formatRupiah = (angka) => {
            if (!angka) return '';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const parseNumber = (val) => parseInt(val.replace(/[^0-9]/g, '')) || 0;

        const totalPrice = {{ $totalPrice }};
        const otherInput = document.getElementById('other_cost');
        const paidInput = document.getElementById('paid');
        const estimatedInput = document.getElementById('estimated_cost');
        const subtotalInput = document.getElementById('subtotal');
        const changeInput = document.getElementById('change');

        function updateCalculations() {
            const other = parseNumber(otherInput.value);
            const paid = parseNumber(paidInput.value);
            const estimated = totalPrice; // cuma dari total price
            const subtotal = totalPrice + other;
            const change = paid - subtotal;

            estimatedInput.value = formatRupiah(estimated);
            subtotalInput.value = formatRupiah(subtotal);
            changeInput.value = formatRupiah(change > 0 ? change : 0);
        }

        function formatLiveInput(input) {
            input.addEventListener('input', function(e) {
                let value = parseNumber(this.value);
                this.value = value.toLocaleString('id-ID');
                updateCalculations();
            });
        }

        formatLiveInput(otherInput);
        formatLiveInput(paidInput);

        updateCalculations();
    </script>
@endsection
