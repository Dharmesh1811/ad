@extends('layouts.app')

@section('title', 'Download Admit Card / ID Card')

@push('styles')
<style>
    .download-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        background: #f8fafc;
        padding: 40px 0;
    }
    .download-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 30px;
        text-align: center;
        color: white;
    }
    .card-header-gradient h2 {
        font-weight: 700;
        margin-bottom: 5px;
        letter-spacing: -0.5px;
    }
    .card-header-gradient p {
        opacity: 0.9;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    .card-body-custom {
        padding: 40px;
    }
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #4f46e5;
    }
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
    }
    .captcha-wrapper {
        background: #f1f5f9;
        padding: 15px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .captcha-img-box {
        background: white;
        padding: 5px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .btn-refresh {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.2s;
    }
    .btn-refresh:hover {
        background: #f1f5f9;
        color: #4f46e5;
    }
    .btn-download {
        background: #4f46e5;
        border: none;
        padding: 14px;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 12px;
        transition: all 0.3s;
    }
    .btn-download:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
    }
    .info-note {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 20px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="download-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="download-card">
                    <div class="card-header-gradient">
                        <i class="fas fa-id-card fa-3x mb-3"></i>
                        <h2>ID Card Portal</h2>
                        <p>Enter your details to download your Admit Card</p>
                    </div>
                    
                    <div class="card-body-custom">
                        @if($errors->has('error'))
                            <div class="alert alert-danger border-0 shadow-sm mb-4">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first('error') }}
                            </div>
                        @endif

                        <form action="{{ route('id-card.download') }}" method="POST">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" name="application_number" id="app_no" class="form-control @error('application_number') is-invalid @enderror" placeholder="APP20240001" value="{{ old('application_number') }}" required>
                                <label for="app_no">Application Number</label>
                                @error('application_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" name="mobile" id="mobile_no" class="form-control @error('mobile') is-invalid @enderror" placeholder="9876543210" value="{{ old('mobile') }}" required maxlength="10">
                                <label for="mobile_no">Registered Mobile Number</label>
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="captcha-wrapper mb-3">
                                <div class="captcha-img-box">
                                    <img src="{{ url('captcha/flat') }}?{{ rand() }}" alt="captcha" id="captcha-img">
                                </div>
                                <button type="button" class="btn-refresh" onclick="refreshCaptcha()" title="Refresh Captcha">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" name="captcha" id="captcha_code" class="form-control @error('captcha') is-invalid @enderror" placeholder="Captcha" required>
                                <label for="captcha_code">Enter Security Code</label>
                                @error('captcha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-download w-100">
                                <i class="fas fa-file-download me-2"></i> GENERATE ID CARD
                            </button>

                            <div class="info-note">
                                <i class="fas fa-lock"></i> Secure & Direct Download
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshCaptcha() {
        const captchaImg = document.getElementById('captcha-img');
        captchaImg.src = "{{ url('captcha/flat') }}?" + Math.random();
    }
</script>
@endpush
