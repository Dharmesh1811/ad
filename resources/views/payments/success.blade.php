@extends('layouts.app')

@section('title', 'Payment Successful | Exam Portal')

@push('styles')
<style>
    /* =====================================================================
       Payment Success Page Styles
       ===================================================================== */
    .success-section {
        min-height: 80vh;
        padding: 3rem 0;
        background: linear-gradient(135deg, #052e16 0%, #0f172a 50%, #052e16 100%);
        position: relative;
        overflow: hidden;
    }
    .success-section::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 50% 40%, rgba(22, 163, 74, 0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    /* Animated confetti dots */
    .confetti { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; }
    .confetti-dot {
        position: absolute;
        width: 8px; height: 8px;
        border-radius: 50%;
        animation: confettiFall linear infinite;
        opacity: 0;
    }
    @keyframes confettiFall {
        0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }

    .success-card {
        background: rgba(5, 46, 22, 0.6);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 24px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(34,197,94,0.05);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    .success-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(34,197,94,0.6), transparent);
    }

    /* Success Icon */
    .success-icon-wrapper {
        width: 100px; height: 100px;
        background: linear-gradient(135deg, #15803d, #166534);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 0 12px rgba(34,197,94,0.1), 0 0 0 24px rgba(34,197,94,0.05);
        animation: successPulse 2s ease-in-out infinite;
    }
    @keyframes successPulse {
        0%, 100% { box-shadow: 0 0 0 12px rgba(34,197,94,0.1), 0 0 0 24px rgba(34,197,94,0.05); }
        50%       { box-shadow: 0 0 0 16px rgba(34,197,94,0.15), 0 0 0 32px rgba(34,197,94,0.07); }
    }
    .success-icon-wrapper i { font-size: 2.8rem; color: #4ade80; }

    .success-title { color: #dcfce7; font-size: 1.8rem; font-weight: 800; margin-bottom: 0.5rem; }
    .success-subtitle { color: #86efac; font-size: 1rem; }

    /* Amount Badge */
    .amount-badge {
        background: linear-gradient(135deg, rgba(22,163,74,0.2), rgba(34,197,94,0.1));
        border: 1px solid rgba(34,197,94,0.3);
        border-radius: 16px;
        padding: 1.25rem 2rem;
        text-align: center;
        margin: 1.5rem 0;
    }
    .amount-badge .amt-label { color: #86efac; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
    .amount-badge .amt-value {
        font-size: 2.5rem; font-weight: 800; color: #4ade80;
        text-shadow: 0 0 20px rgba(74, 222, 128, 0.3);
    }

    /* Receipt Table */
    .receipt-table { width: 100%; }
    .receipt-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .receipt-row:last-child { border-bottom: none; }
    .receipt-label { color: #6b7280; font-size: 0.83rem; font-weight: 500; }
    .receipt-value { color: #d1fae5; font-size: 0.87rem; font-weight: 600; text-align: right; max-width: 60%; word-break: break-all; }

    .badge-success-pill {
        background: rgba(22,163,74,0.2);
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.3);
        border-radius: 999px;
        padding: 0.25rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .btn-dashboard {
        background: linear-gradient(135deg, #15803d, #166534);
        color: #fff; border: none;
        padding: 0.85rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(22,163,74,0.3);
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-dashboard:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(22,163,74,0.5); color: #fff; }

    .btn-outline-success-custom {
        background: transparent;
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.4);
        padding: 0.85rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-outline-success-custom:hover { background: rgba(34,197,94,0.1); border-color: #4ade80; color: #4ade80; }

    .success-header { padding: 2.5rem 2rem 0; text-align: center; }
    .success-body   { padding: 1.5rem 2.5rem 2.5rem; }

    @media (max-width: 576px) {
        .success-body { padding: 1.5rem; }
        .success-header { padding: 2rem 1.5rem 0; }
        .success-title { font-size: 1.5rem; }
    }
</style>
@endpush

@section('content')
<section class="success-section">

    {{-- Confetti --}}
    <div class="confetti" id="confettiContainer"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="success-card">

                    {{-- Success Header --}}
                    <div class="success-header">
                        <div class="success-icon-wrapper">
                            <i class="fas fa-check"></i>
                        </div>
                        <h1 class="success-title">Payment Successful!</h1>
                        <p class="success-subtitle">Your application has been <strong style="color:#4ade80;">Approved</strong> automatically! 🎉</p>
                    </div>

                    <div class="success-body">

                        {{-- Amount --}}
                        <div class="amount-badge">
                            <div class="amt-label">Amount Paid</div>
                            <div class="amt-value">{{ $payment->formattedAmount() }}</div>
                        </div>

                        {{-- Receipt Details --}}
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
                            <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 1rem;">
                                Payment Receipt
                            </div>

                            <div class="receipt-table">
                                <div class="receipt-row">
                                    <span class="receipt-label">Status</span>
                                    <span class="receipt-value"><span class="badge-success-pill">✓ PAID</span></span>
                                </div>
                                <div class="receipt-row">
                                    <span class="receipt-label">Application Status</span>
                                    <span class="receipt-value">
                                        <span style="background:rgba(22,163,74,0.25);color:#4ade80;border:1px solid rgba(34,197,94,0.5);border-radius:999px;padding:0.2rem 0.8rem;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">
                                            ✓ APPROVED
                                        </span>
                                    </span>
                                </div>
                                <div class="receipt-row">
                                    <span class="receipt-label">Exam / Form</span>
                                    <span class="receipt-value">{{ $application->exam?->title ?? '—' }}</span>
                                </div>
                                <div class="receipt-row">
                                    <span class="receipt-label">Applicant</span>
                                    <span class="receipt-value">{{ auth()->user()->name }}</span>
                                </div>
                                <div class="receipt-row">
                                    <span class="receipt-label">Application No.</span>
                                    <span class="receipt-value">{{ auth()->user()->application_number ?? '—' }}</span>
                                </div>
                                @if($payment->razorpay_order_id)
                                <div class="receipt-row">
                                    <span class="receipt-label">Order ID</span>
                                    <span class="receipt-value" style="font-size:0.75rem;">{{ $payment->razorpay_order_id }}</span>
                                </div>
                                @endif
                                @if($payment->razorpay_payment_id)
                                <div class="receipt-row">
                                    <span class="receipt-label">Payment ID</span>
                                    <span class="receipt-value" style="font-size:0.75rem;">{{ $payment->razorpay_payment_id }}</span>
                                </div>
                                @endif
                                @if($payment->payment_method)
                                <div class="receipt-row">
                                    <span class="receipt-label">Payment Method</span>
                                    <span class="receipt-value">{{ strtoupper($payment->payment_method) }}</span>
                                </div>
                                @endif
                                <div class="receipt-row">
                                    <span class="receipt-label">Transaction Time</span>
                                    <span class="receipt-value">{{ ($payment->transaction_time ?? $payment->paid_at)?->format('d M Y, h:i A') ?? now()->format('d M Y, h:i A') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('dashboard') }}" class="btn-dashboard flex-grow-1 justify-content-center">
                                <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                            </a>
                            <a href="{{ route('admit.index') }}" class="btn-outline-success-custom flex-grow-1 justify-content-center">
                                <i class="fas fa-id-card"></i> Admit Card
                            </a>
                        </div>

                        <div class="text-center mt-3" style="color: #4b5563; font-size: 0.78rem;">
                            <i class="fas fa-info-circle me-1"></i>
                            Save your Payment ID for future reference. A confirmation has been logged.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Generate confetti particles on success
(function() {
    const container = document.getElementById('confettiContainer');
    const colors = ['#4ade80','#86efac','#22c55e','#60a5fa','#a78bfa','#fbbf24','#f472b6'];
    const count = 60;

    for (let i = 0; i < count; i++) {
        const dot = document.createElement('div');
        dot.className = 'confetti-dot';
        dot.style.left          = Math.random() * 100 + '%';
        dot.style.top           = -20 + 'px';
        dot.style.background    = colors[Math.floor(Math.random() * colors.length)];
        dot.style.animationDuration  = (Math.random() * 3 + 2) + 's';
        dot.style.animationDelay     = (Math.random() * 4) + 's';
        dot.style.width         = (Math.random() * 8 + 4) + 'px';
        dot.style.height        = dot.style.width;
        dot.style.borderRadius  = Math.random() > 0.5 ? '50%' : '2px';
        container.appendChild(dot);
    }
})();
</script>
@endpush
