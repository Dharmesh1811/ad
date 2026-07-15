@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3">Reset Password</h2>
                    <p class="text-muted mb-4">Set a new password for your account after OTP verification.</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control stylish-input @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email Address" value="{{ old('email', $email) }}" required>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3 position-relative">
                            <input type="password" class="form-control stylish-input @error('password') is-invalid @enderror" name="password" id="password" placeholder="New Password" required>
                            <label for="password">New Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4 position-relative">
                            <input type="password" class="form-control stylish-input" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                            <label for="password_confirmation">Confirm Password</label>
                        </div>

                        <button type="submit" class="btn btn-submit-premium w-100 py-3 shadow-sm" style="background: #00529b; border-radius: 10px; font-weight: 600; letter-spacing: 1px;">
                            Reset Password
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="{{ url('/') }}" class="text-decoration-none">Back to home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
