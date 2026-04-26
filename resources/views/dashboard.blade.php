@extends('layouts.app')

@section('title', 'Dashboard | Exam Portal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="small text-muted">Application Number</div>
                        <h1 class="fw-bold text-primary mb-3">{{ $user->application_number }}</h1>
                        <p class="lead mb-4">Welcome, {{ $user->name }}. This dashboard shows your active exams, application flow, payment status, and ID card readiness.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('admit.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-3 fw-semibold">ID Card</a>
                            <a href="{{ route('status.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-3 fw-semibold">Track Status</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Profile</h5>
                        <div class="mb-2"><span class="text-muted">Name:</span> {{ $user->name }}</div>
                        <div class="mb-2"><span class="text-muted">Mobile:</span> {{ $user->mobile ?? 'N/A' }}</div>
                        <div class="mb-2"><span class="text-muted">Email:</span> {{ $user->email ?? 'N/A' }}</div>
                        <div><span class="text-muted">DOB:</span> {{ optional($user->date_of_birth)->format('d M Y') ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark display-6">Active <span class="text-primary">Examinations</span></h2>
                <p class="text-muted">Only active exams created by admin are shown here.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            @forelse ($exams as $exam)
                @php
                    $userApplication = $applications->firstWhere('exam_id', $exam->id);
                    $payment = $user->payments->firstWhere('exam_id', $exam->id);
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="premium-card {{ $loop->index === 1 ? 'highlighted' : '' }}">
                        <div class="p-card-header d-flex justify-content-between">
                            <span class="status-indicator {{ now()->diffInDays($exam->last_date, false) <= 2 ? 'warning' : 'active' }}">
                                {{ now()->diffInDays($exam->last_date, false) <= 2 ? 'Closing Soon' : 'Active Now' }}
                            </span>
                            <div class="exam-type">{{ $exam->category ?? 'Exam' }}</div>
                        </div>
                        <div class="p-card-body">
                            <h3 class="exam-title">{{ $exam->title }}</h3>
                            <p class="exam-desc">{{ $exam->description }}</p>
                            <div class="exam-details-grid">
                                <div class="detail-item">
                                    <span class="label">LAST DATE</span>
                                    <span class="value text-primary">{{ $exam->last_date->format('d M, Y') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">STATUS</span>
                                    <span class="value text-capitalize">{{ str_replace('_', ' ', $userApplication?->status ?? 'not_filled') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-card-footer">
                            <a href="{{ route('application.create', $exam) }}" class="apply-link">
                                {{ $userApplication ? 'Edit & Continue' : 'Register & Apply' }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border rounded-4 text-center">No active exams available.</div>
                </div>
            @endforelse
        </div>

        <div class="row g-4">
            @forelse ($applications as $application)
                @php $payment = $user->payments->firstWhere('exam_id', $application->exam_id); @endphp
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $application->exam?->title }}</h5>
                                    <div class="small text-muted">Last date: {{ $application->exam?->last_date?->format('d M Y') }}</div>
                                </div>
                                <span class="badge bg-light text-dark border text-capitalize px-3 py-2">{{ str_replace('_', ' ', $application->status) }}</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="small text-muted">Form Status</div>
                                    <div class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $application->status) }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Payment</div>
                                    <div class="fw-semibold text-capitalize">{{ $payment?->status ?? 'pending' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Transaction</div>
                                    <div class="fw-semibold">{{ $payment?->transaction_id ?? 'Pending' }}</div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex gap-3 flex-wrap">
                                <a href="{{ route('application.create', $application->exam) }}" class="btn btn-outline-primary rounded-pill">Open Form</a>
                                <a href="{{ route('payments.create', $application) }}" class="btn btn-outline-dark rounded-pill">Payment</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-5 text-center">
                            <h4 class="fw-bold mb-2">No applications yet</h4>
                            <p class="text-muted mb-0">Choose an active exam above to start your form.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
