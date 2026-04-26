@extends('layouts.app')

@section('title', 'Payment | Exam Portal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold text-primary mb-2">Payment Gateway</h2>
                        <p class="text-muted mb-4">This screen is gateway-ready. After a successful transaction, the application is marked as submitted.</p>

                        <div class="alert alert-light border rounded-4 mb-4">
                            <div><strong>Application Number:</strong> {{ auth()->user()->application_number }}</div>
                            <div><strong>Exam:</strong> {{ $application->exam?->title }}</div>
                            <div><strong>Current Status:</strong> <span class="text-capitalize">{{ $payment->status }}</span></div>
                            <div><strong>Amount:</strong> Rs. {{ number_format((float) $payment->amount, 2) }}</div>
                        </div>

                        <form method="POST" action="{{ route('payments.store') }}" class="row g-3">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $application->id }}">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $payment->amount) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Transaction Reference</label>
                                <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id', $payment->transaction_id) }}" required>
                            </div>
                            <div class="col-12 d-flex gap-3 flex-wrap">
                                <button type="submit" class="btn btn-success rounded-pill px-4 py-3 fw-semibold">Mark as Paid</button>
                                <a href="{{ route('application.create', $application->exam) }}" class="btn btn-outline-dark rounded-pill px-4 py-3">Back to Form</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
