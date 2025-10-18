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

                <!-- Foto -->
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

                <!-- Address -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Address</h6>
                    <p class="mb-0">{{ $user->address ?? '-' }}</p>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Email</h6>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>

                <!-- Role -->
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

                <!-- Phone Number -->
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
            <!-- History Service Laptop (Customer) -->
            <div class="card shadow-sm border-0 rounded-4 mt-5 mb-5"
                style="max-width: 1150px; margin:auto; margin-top: 60px;">
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
                                <tbody>
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
                                            <td>
                                                <span class="fw-semibold {{ $color }}">
                                                    {{ ucfirst($service->status ?? '-') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold {{ $paymentColor }}">
                                                    {{ ucfirst($service->status_paid ?? '-') }}
                                                </span>
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
                    @endif
                </div>
            </div>
        @elseif ($user->role === 'technician')
            <!-- History Service sebagai Teknisi -->
            <div class="card shadow-sm border-0 rounded-4 mt-5 mb-5"
                style="max-width: 900px; margin:auto; margin-top: 60px;">
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
                                <tbody>
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
                                            <td>
                                                <span class="fw-semibold {{ $color }}">
                                                    {{ ucfirst($service->status ?? '-') }}
                                                </span>
                                            </td>
                                            <td>{{ $service->created_at?->format('d M Y') ?? '-' }}</td>
                                            <td>Rp {{ number_format($service->total_cost ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif





    </div>

    <script>
        const toggleBtn = document.getElementById("togglePassword");
        if (toggleBtn) {
            toggleBtn.addEventListener("click", function() {
                const passwordField = document.getElementById("passwordField");
                const icon = document.getElementById("toggleIcon");

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    icon.classList.remove("mdi-eye-off");
                    icon.classList.add("mdi-eye");
                } else {
                    passwordField.type = "password";
                    icon.classList.remove("mdi-eye");
                    icon.classList.add("mdi-eye-off");
                }
            });
        }
    </script>
@endsection
