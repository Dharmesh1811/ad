@extends('layouts.app')

@section('title', 'Payment Failed | Exam Portal')

@push('styles')
<style>
    /* =====================================================================
       Payment Failed Page Styles
       ===================================================================== */
    .failed-section {
        min-height: 80vh;
        padding: 3rem 0;
        background: linear-gradient(135deg, #1c0000 0%, #0f172a 50%, #1c0000 100%);
        position: relative;
        overflow: hidden;
    }
    .failed-section::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 50% 40%, rgba(220, 38, 38, 0.1) 0%, transparent 60%);
        pointer-events: none;
    }

    .failed-card {
        background: rgba(28, 0, 0, 0.5);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-radius: 24px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(220,38,38,0.05);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    .failed-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(220,38,38,0.6), transparent);
    }

    /* Failed Icon */
    .failed-icon-wrapper {
        width: 100px; height: 100px;
        background: linear-gradient(135deg, #7f1d1d, #991b1b);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 0 12px rgba(220,38,38,0.1), 0 0 0 24px rgba(220,38,38,0.05);
        animation: failedShake 0.6s ease-in-out, failedGlow 2s ease-in-out 0.6s infinite;
    }
    @keyframes failedShake {
        0%, 100% { transform: translateX(0); }
        15%       { transform: translateX(-8px); }
        30%       { transform: translateX(8px); }
        45%       { transform: translateX(-6px); }
        60%       { transform: translateX(6px); }
        75%       { transform: translateX(-4px); }
        90%       { transform: translateX(4px); }
    }
    @keyframes failedGlow {
        0%, 100% { box-shadow: 0 0 0 12px rgba(220,38,38,0.1), 0 0 0 24px rgba(220,38,38,0.05); }
        50%       { box-shadow: 0 0 0 16px rgba(220,38,38,0.15), 0 0 0 32px rgba(220,38,38,0.07); }
    }
    .failed-icon-wrapper i { font-size: 2.8rem; color: #f87171; }

    .failed-title    { color: #fee2e2; font-size: 1.8rem; font-weight: 800; margin-bottom: 0.5rem; }
    .failed-subtitle { color: #fca5a5; font-size: 1rem; }

    /* Error reason box */
    .error-box {
        background: rgba(220,38,38,0.08);
        border: 1px solid rgba(220,38,38,0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin: 1.5rem 0;
        color: #fca5a5;
        font-size: 0.88rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    .error-box i { color: #f87171; margin-top: 2px; flex-shrink: 0; }

    /* Info Rows */
    .info-row-dark {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .info-row-dark:last-child { border-bottom: none; }
    .info-row-dark .lbl { color: #6b7280; font-size: 0.83rem; font-weight: 500; }
    .info-row-dark .val { color: #fee2e2; font-size: 0.87rem; font-weight: 600; }

    /* Tips */
    .tip-list { list-style: none; padding: 0; margin: 0; }
    .tip-list li {
        color: #9ca3af;
        font-size: 0.83rem;
        padding: 0.35rem 0;
        padding-left: 1.25rem;
        position: relative;
    }
    .tip-list li::before { content: '•'; position: absolute; left: 0; color: #f87171; }

    /* Buttons */
    .btn-retry {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: #fff; border: none;
        padding: 0.85rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(220,38,38,0.3);
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-retry:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(220,38,38,0.5); color: #fff; }

    .btn-outline-danger-custom {
        background: transparent;
        color: #f87171;
        border: 1px solid rgba(220,38,38,0.4);
        padding: 0.85rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-outline-danger-custom:hover { background: rgba(220,38,38,0.1); border-color: #f87171; color: #f87171; }

    .failed-header { padding: 2.5rem 2rem 0; text-align: center; }
    .failed-body   { padding: 1.5rem 2.5rem 2.5rem; }
    .divider-dark  { height: 1px; background: rgba(255,255,255,0.05); margin: 1.25rem 0; }

    @media (max-width: 576px) {
        .failed-body { padding: 1.5rem; }
        .failed-header { padding: 2rem 1.5rem 0; }
        .failed-title { font-size: 1.5rem; }
    }
</style>
@endpush

@section('content')
<section class="failed-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="failed-card">

                    {{-- Failed Header --}}
                    <div class="failed-header">
                        <div class="failed-icon-wrapper">
                            <i class="fas fa-times"></i>
                        </div>
                        <h1 class="failed-title">Payment Failed</h1>
                        <p class="failed-subtitle">Your payment could not be processed</p>
                    </div>

                    <div class="failed-body">

                        {{-- Error Reason --}}
                        @if(request('reason'))
                        <div class="error-box">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>Reason:</strong> {{ urldecode(request('reason')) }}
                            </div>
                        </div>
                        @else
                        <div class="error-box">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>Your payment was not completed. No amount has been deducted from your account.</div>
                        </div>
                        @endif

                        {{-- Payment Details --}}
                        @if($payment)
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 14px; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem;">
                            <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 0.75rem;">
                                Transaction Details
                            </div>
                            <div class="info-row-dark">
                                <span class="lbl">Exam / Form</span>
                                <span class="val">{{ $application->exam?->title ?? '—' }}</span>
                            </div>
                            <div class="info-row-dark">
                                <span class="lbl">Amount</span>
                                <span class="val">{{ $payment->formattedAmount() }}</span>
                            </div>
                            <div class="info-row-dark">
                                <span class="lbl">Status</span>
                                <span class="val">
                                    <span style="background: rgba(220,38,38,0.15); color: #f87171; border: 1px solid rgba(220,38,38,0.3); border-radius: 999px; padding: 0.2rem 0.8rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Failed</span>
                                </span>
                            </div>
                            @if($payment->razorpay_order_id)
                            <div class="info-row-dark">
                                <span class="lbl">Order ID</span>
                                <span class="val" style="font-size:0.75rem; color: #9ca3af;">{{ $payment->razorpay_order_id }}</span>
                            </div>
                            @endif
                            <div class="info-row-dark">
                                <span class="lbl">Attempt Time</span>
                                <span class="val">{{ now()->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="divider-dark"></div>

                        {{-- Tips --}}
                        <div style="margin-bottom: 1.5rem;">
                            <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 0.75rem;">
                                What to do next
                            </div>
                            <ul class="tip-list">
                                <li>Check your internet connection and try again</li>
                                <li>Ensure sufficient balance in your UPI/bank account</li>
                                <li>Try a different payment method (card, net banking, etc.)</li>
                                <li>If amount was deducted, it will be refunded within 5–7 business days</li>
                                <li>Contact support if the problem persists</li>
                            </ul>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('payments.create', $application) }}" class="btn-retry flex-grow-1 justify-content-center">
                                <i class="fas fa-redo"></i> Try Again
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn-outline-danger-custom flex-grow-1 justify-content-center">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </div>

                        <div class="text-center mt-3" style="color: #4b5563; font-size: 0.78rem;">
                            <i class="fas fa-headset me-1"></i>
                            Need help? Contact support with your Order ID above.
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
