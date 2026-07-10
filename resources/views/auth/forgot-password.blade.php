@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3">Forgot Password</h2>
                    <p class="text-muted mb-4">Enter your email address and we will send you a password reset link.</p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control stylish-input @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email Address" value="{{ old('email') }}" required>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-submit-premium w-100 py-3 shadow-sm" style="background: #00529b; border-radius: 10px; font-weight: 600; letter-spacing: 1px;">
                            Send Reset Link
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
