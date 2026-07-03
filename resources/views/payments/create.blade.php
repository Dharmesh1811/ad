@extends('layouts.app')

@section('title', 'Complete Payment | ' . ($application->exam?->title ?? 'Exam Portal'))

@push('styles')
<style>
    /* =====================================================================
       Payment Page Styles — Premium Dark Glassmorphism Design
       ===================================================================== */
    :root {
        --rzp-primary:    #2563eb;
        --rzp-success:    #16a34a;
        --rzp-danger:     #dc2626;
        --rzp-dark:       #0f172a;
        --rzp-card-bg:    rgba(15, 23, 42, 0.85);
        --rzp-border:     rgba(255, 255, 255, 0.08);
        --rzp-glow:       rgba(37, 99, 235, 0.4);
    }

    .payment-section {
        min-height: 80vh;
        padding: 3rem 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        position: relative;
        overflow: hidden;
    }
    .payment-section::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 30% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 60%),
                    radial-gradient(ellipse at 70% 20%, rgba(37, 99, 235, 0.12) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Floating orbs */
    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.3;
        animation: floatOrb 8s ease-in-out infinite;
        pointer-events: none;
    }
    .orb-1 { width: 400px; height: 400px; background: #6366f1; top: -100px; left: -100px; animation-delay: 0s; }
    .orb-2 { width: 300px; height: 300px; background: #2563eb; bottom: -80px; right: -80px; animation-delay: 3s; }
    .orb-3 { width: 200px; height: 200px; background: #8b5cf6; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-delay: 1.5s; }

    @keyframes floatOrb {
        0%, 100% { transform: translateY(0px) scale(1); }
        50%       { transform: translateY(-30px) scale(1.05); }
    }

    /* Payment Card */
    .payment-card {
        background: var(--rzp-card-bg);
        border: 1px solid var(--rzp-border);
        border-radius: 24px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    .payment-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99,102,241,0.6), rgba(37,99,235,0.6), transparent);
    }

    /* Card Header */
    .payment-header {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(99, 102, 241, 0.15));
        border-bottom: 1px solid var(--rzp-border);
        padding: 2rem 2.5rem;
    }
    .payment-header .lock-icon {
        width: 48px; height: 48px;
        background: linear-gradient(135deg, #2563eb, #6366f1);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: #fff;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        flex-shrink: 0;
    }
    .payment-header h2 { color: #f1f5f9; font-size: 1.5rem; font-weight: 700; margin: 0; }
    .payment-header p  { color: #94a3b8; margin: 0; font-size: 0.9rem; }

    /* Amount Display */
    .amount-display {
        background: linear-gradient(135deg, rgba(37,99,235,0.15), rgba(99,102,241,0.1));
        border: 1px solid rgba(37,99,235,0.3);
        border-radius: 16px;
        padding: 1.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .amount-display::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, transparent 40%, rgba(37,99,235,0.05));
        pointer-events: none;
    }
    .amount-label { color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
    .amount-value {
        color: #fff;
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .amount-rupee { font-size: 1.8rem; }

    /* Info Rows */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: #64748b; font-size: 0.85rem; font-weight: 500; }
    .info-row .value { color: #e2e8f0; font-weight: 600; font-size: 0.9rem; }

    /* Pay Button */
    .btn-pay {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        border: none;
        border-radius: 14px;
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        width: 100%;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4);
        letter-spacing: 0.3px;
    }
    .btn-pay::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, #1d4ed8, #6d28d9);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(37, 99, 235, 0.6); }
    .btn-pay:hover::before { opacity: 1; }
    .btn-pay:active { transform: translateY(0); }
    .btn-pay:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-pay .btn-text { position: relative; z-index: 1; }
    .btn-pay .spinner { display: none; }
    .btn-pay.loading .btn-text { display: none; }
    .btn-pay.loading .spinner { display: inline-flex; align-items: center; gap: 0.5rem; position: relative; z-index: 1; }

    /* Payment Methods */
    .payment-methods {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
    .method-badge {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
        transition: all 0.2s;
    }
    .method-badge:hover { background: rgba(37,99,235,0.15); border-color: rgba(37,99,235,0.4); color: #93c5fd; }

    /* Security Badge */
    .security-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.78rem;
    }
    .security-badge .shield { color: #22c55e; }

    /* Error Alert */
    .alert-payment-error {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.3);
        border-radius: 12px;
        color: #fca5a5;
        padding: 1rem 1.25rem;
        font-size: 0.9rem;
    }

    /* Razorpay branding */
    .rzp-branding {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        color: #475569;
        font-size: 0.78rem;
    }
    .rzp-logo {
        background: linear-gradient(135deg, #072654, #3395ff);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }

    /* Processing overlay */
    #processingOverlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1.5rem;
        color: #fff;
    }
    #processingOverlay.active { display: flex; }
    #processingOverlay .spinner-ring {
        width: 64px; height: 64px;
        border: 4px solid rgba(255,255,255,0.2);
        border-top-color: #60a5fa;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Dark mode card body text */
    .payment-body { padding: 2rem 2.5rem; }
    .divider { height: 1px; background: rgba(255,255,255,0.06); margin: 1.5rem 0; }
    .section-title { color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 1rem; }

    @media (max-width: 576px) {
        .payment-body { padding: 1.5rem; }
        .payment-header { padding: 1.5rem; }
        .amount-value { font-size: 2.2rem; }
    }
</style>
@endpush

@section('content')

{{-- Processing Overlay (shown during AJAX verification) --}}
<div id="processingOverlay">
    <div class="spinner-ring"></div>
    <div style="font-size: 1.1rem; font-weight: 600; color: #e2e8f0;">Verifying your payment…</div>
    <div style="color: #94a3b8; font-size: 0.85rem;">Please do not close or refresh this page</div>
</div>

<section class="payment-section">
    {{-- Floating background orbs --}}
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                {{-- Error Alert --}}
                @if($orderError)
                    <div class="alert-payment-error mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $orderError }}</span>
                    </div>
                @endif

                {{-- Session Flash --}}
                @if(session('error'))
                    <div class="alert-payment-error mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <div class="payment-card">

                    {{-- Header --}}
                    <div class="payment-header d-flex align-items-center gap-3">
                        <div class="lock-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h2>Secure Checkout</h2>
                            <p>Your payment is encrypted and protected</p>
                        </div>
                    </div>

                    <div class="payment-body">

                        {{-- Amount Display --}}
                        <div class="amount-display mb-4">
                            <div class="amount-label mb-2">Amount to Pay</div>
                            <div class="amount-value">
                                <span class="amount-rupee">₹</span>{{ number_format((float)($payment->amount), 2) }}
                            </div>
                            <div class="mt-2 text-muted" style="font-size:0.8rem; color: #64748b !important;">
                                Amount fixed by system — cannot be modified
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        <div class="section-title">Payment Details</div>
                        <div class="mb-3">
                            <div class="info-row">
                                <span class="label"><i class="fas fa-file-alt me-2"></i>Exam / Form</span>
                                <span class="value">{{ $application->exam?->title ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label"><i class="fas fa-id-card me-2"></i>Application No.</span>
                                <span class="value">{{ auth()->user()->application_number ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label"><i class="fas fa-user me-2"></i>Applicant</span>
                                <span class="value">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label"><i class="fas fa-tag me-2"></i>Status</span>
                                <span class="value">
                                    <span class="badge {{ $payment->statusBadgeClass() }} rounded-pill px-3">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </span>
                            </div>
                            @if($razorpayOrder)
                            <div class="info-row">
                                <span class="label"><i class="fas fa-receipt me-2"></i>Order ID</span>
                                <span class="value" style="font-size:0.78rem; color: #94a3b8;">{{ $razorpayOrder['id'] }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="divider"></div>

                        {{-- Payment Methods Supported --}}
                        <div class="section-title">Accepted Payment Methods</div>
                        <div class="payment-methods mb-4">
                            <span class="method-badge"><i class="fas fa-mobile-alt me-1"></i> UPI</span>
                            <span class="method-badge"><i class="fab fa-google-pay me-1"></i> Google Pay</span>
                            <span class="method-badge">PhonePe</span>
                            <span class="method-badge">Paytm</span>
                            <span class="method-badge"><i class="fas fa-qrcode me-1"></i> QR Code</span>
                            <span class="method-badge"><i class="fas fa-credit-card me-1"></i> Cards</span>
                            <span class="method-badge"><i class="fas fa-university me-1"></i> Net Banking</span>
                            <span class="method-badge"><i class="fas fa-wallet me-1"></i> Wallets</span>
                        </div>

                        {{-- Pay Button --}}
                        @if($razorpayOrder && !$payment->isSuccessful())
                            <button
                                id="rzp-pay-btn"
                                class="btn-pay"
                                data-order-id="{{ $razorpayOrder['id'] }}"
                                data-amount="{{ $amountInPaise }}"
                                data-application-id="{{ $application->id }}"
                                data-application-name="{{ $application->exam?->title }}"
                                data-user-name="{{ auth()->user()->name }}"
                                data-user-email="{{ auth()->user()->email }}"
                                data-user-mobile="{{ auth()->user()->mobile ?? '' }}"
                                data-verify-url="{{ route('payments.store') }}"
                                data-success-url="{{ route('payments.success', $application) }}"
                                data-failed-url="{{ route('payments.failed', $application) }}"
                                data-csrf="{{ csrf_token() }}"
                            >
                                <span class="btn-text">
                                    <i class="fas fa-lock me-2"></i>Pay ₹{{ number_format((float)$payment->amount, 2) }} Now
                                </span>
                                <span class="spinner">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Processing…
                                </span>
                            </button>
                        @elseif($payment->isSuccessful())
                            <div class="text-center py-2">
                                <i class="fas fa-check-circle text-success" style="font-size:2rem;"></i>
                                <div class="mt-2" style="color: #22c55e; font-weight: 600;">Payment Already Completed</div>
                                <a href="{{ route('payments.success', $application) }}" class="btn btn-outline-success mt-3 rounded-pill px-4">View Receipt</a>
                            </div>
                        @else
                            <button class="btn-pay" disabled>
                                <span class="btn-text"><i class="fas fa-exclamation-triangle me-2"></i>Payment Unavailable</span>
                            </button>
                        @endif

                        <div class="divider"></div>

                        {{-- Security & Branding --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="security-badge">
                                <i class="fas fa-shield-alt shield"></i>
                                <span>256-bit SSL encrypted · PCI DSS compliant</span>
                            </div>
                            <div class="rzp-branding">
                                Powered by <span class="rzp-logo">Razorpay</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Back Link --}}
                <div class="text-center mt-4">
                    <a href="{{ route('dashboard') }}" style="color: #64748b; text-decoration: none; font-size: 0.85rem;">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
{{-- Razorpay Checkout Script --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
(function () {
    'use strict';

    const payBtn = document.getElementById('rzp-pay-btn');
    if (!payBtn) return; // Button not rendered (already paid or error)

    const overlay = document.getElementById('processingOverlay');

    /**
     * Show the full-screen processing overlay.
     */
    function showOverlay() {
        overlay.classList.add('active');
    }

    /**
     * Set the Pay button into loading state.
     */
    function setLoading(isLoading) {
        payBtn.disabled = isLoading;
        payBtn.classList.toggle('loading', isLoading);
    }

    /**
     * Send the Razorpay payment details to our server for verification.
     * Uses Fetch API (AJAX) — no page reload.
     *
     * @param {object} response - Razorpay success handler response
     */
    async function verifyPayment(response) {
        showOverlay();

        const payload = {
            razorpay_payment_id : response.razorpay_payment_id,
            razorpay_order_id   : response.razorpay_order_id,
            razorpay_signature  : response.razorpay_signature,
            application_id      : payBtn.dataset.applicationId,
            _token              : payBtn.dataset.csrf,
        };

        try {
            const res = await fetch(payBtn.dataset.verifyUrl, {
                method  : 'POST',
                headers : {
                    'Content-Type' : 'application/json',
                    'Accept'       : 'application/json',
                    'X-CSRF-TOKEN' : payBtn.dataset.csrf,
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (data.success) {
                // Redirect to success page
                window.location.href = data.redirect_url;
            } else {
                // Payment failed — redirect to failed page
                console.error('Payment verification failed:', data.message);
                window.location.href = payBtn.dataset.failedUrl + '?reason=' + encodeURIComponent(data.message ?? 'Verification failed');
            }

        } catch (err) {
            console.error('Network error during payment verification:', err);
            overlay.classList.remove('active');
            setLoading(false);
            alert('A network error occurred while verifying your payment.\nYour payment may have been deducted. Please contact support with your Payment ID: ' + response.razorpay_payment_id);
        }
    }

    /**
     * Handle Razorpay modal dismissal (user closed without paying).
     */
    function handleDismiss() {
        setLoading(false);
        console.info('Razorpay checkout dismissed by user.');
    }

    /**
     * Handle payment failure from Razorpay.
     * NOTE: Razorpay calls this callback before closing modal for failed payments.
     */
    async function handleFailure(error) {
        showOverlay();
        console.error('Razorpay payment failed:', error);

        // Optionally notify server about failure (fire-and-forget)
        try {
            await fetch(payBtn.dataset.verifyUrl, {
                method  : 'POST',
                headers : {
                    'Content-Type' : 'application/json',
                    'Accept'       : 'application/json',
                    'X-CSRF-TOKEN' : payBtn.dataset.csrf,
                },
                body: JSON.stringify({
                    razorpay_payment_id : error.error?.metadata?.payment_id ?? '',
                    razorpay_order_id   : payBtn.dataset.orderId,
                    razorpay_signature  : '',          // Empty — will fail verification
                    application_id      : payBtn.dataset.applicationId,
                    _token              : payBtn.dataset.csrf,
                    is_failure          : true,
                }),
            });
        } catch (_) { /* ignore network errors here */ }

        window.location.href = payBtn.dataset.failedUrl + '?reason=' + encodeURIComponent(error?.error?.description ?? 'Payment failed');
    }

    /**
     * Initialize and open Razorpay Checkout modal.
     */
    function openRazorpay() {
        setLoading(true);

        const options = {
            // Razorpay public Key ID (safe to expose in frontend)
            key: '{{ config('razorpay.key_id') }}',

            // Amount in PAISE — matches server-side order (never from user input)
            amount: payBtn.dataset.amount,

            // Currency
            currency: 'INR',

            // Order name shown in checkout
            name: '{{ config('app.name') }}',

            // Description shown in checkout
            description: payBtn.dataset.applicationName,

            // Razorpay Order ID (created server-side)
            order_id: payBtn.dataset.orderId,

            // Enable ALL payment methods
            method: {
                upi         : true,
                card        : true,
                netbanking  : true,
                wallet      : true,
                emi         : true,
                paylater    : true,
            },

            // Prefill user details to speed up checkout
            prefill: {
                name  : payBtn.dataset.userName,
                email : payBtn.dataset.userEmail,
                contact: payBtn.dataset.userMobile,
            },

            // Theme
            theme: {
                color         : '#2563eb',
                backdrop_color: 'rgba(0,0,0,0.8)',
            },

            // Callbacks
            handler: verifyPayment,

            modal: {
                ondismiss: handleDismiss,
                escape   : true,
            },
        };

        const rzp = new Razorpay(options);

        // Attach payment failure handler
        rzp.on('payment.failed', handleFailure);

        rzp.open();
    }

    // Attach click handler to Pay button
    payBtn.addEventListener('click', openRazorpay);

})();
</script>
@endpush
