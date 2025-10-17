@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark font-weight-bold mb-0">Detail User</h2>
            <a href="/user" class="btn btn-secondary" style="font-weight:600; padding:10px 22px; border-radius:8px;">
                ← Kembali
            </a>
        </div>

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

                <!-- Password -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Password</h6>
                    <div class="input-group" style="max-width: 400px;">
                        <!-- Ganti value hash dengan bintang -->
                        <input type="password" class="form-control" id="passwordField" value="********" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="mdi mdi-eye-off" id="toggleIcon"></i>
                        </button>
                    </div>
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
    </div>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
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
    </script>
@endsection
