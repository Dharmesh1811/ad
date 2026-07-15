@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3">Verify OTP</h2>
                    <p class="text-muted mb-4">Enter the 6-digit OTP sent to your email address.</p>

                    <form method="POST" action="{{ route('password.otp.verify') }}">
                        @csrf

                        <input type="hidden" name="email" value="{{ old('email', $email) }}">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control stylish-input @error('otp') is-invalid @enderror" name="otp" id="otp" placeholder="OTP" maxlength="6" inputmode="numeric" required>
                            <label for="otp">OTP</label>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-submit-premium w-100 py-3 shadow-sm" style="background: #00529b; border-radius: 10px; font-weight: 600; letter-spacing: 1px;">
                            Verify OTP
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
