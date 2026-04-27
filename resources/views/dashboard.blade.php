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
                        <p class="lead mb-4">Welcome, {{ $user->full_name }}. This dashboard shows your active exams, application flow, payment status, and ID card readiness.</p>
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
                        <div class="mb-2"><span class="text-muted">Name:</span> {{ $user->full_name }}</div>
                        <div class="mb-2"><span class="text-muted">Mobile:</span> {{ $user->mobile ?? 'N/A' }}</div>
                        <div class="mb-2"><span class="text-muted">Email:</span> {{ $user->email ?? 'N/A' }}</div>
                        <div><span class="text-muted">DOB:</span> {{ optional($user->dob)->format('d M Y') ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Applications Section (Top) -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark display-6">My <span class="text-primary">Applications</span></h2>
                <p class="text-muted">History and status of the forms you have submitted or started.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            @forelse ($applications as $application)
                @php $payment = $user->payments->firstWhere('exam_id', $application->exam_id); @endphp
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $application->exam?->title }}</h5>
                                    <div class="small text-muted"><i class="far fa-clock me-1"></i> Submitted: {{ $application->created_at->format('d M Y') }}</div>
                                </div>
                                <span class="badge {{ $application->status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }} text-capitalize px-3 py-2">
                                    {{ str_replace('_', ' ', $application->status) }}
                                </span>
                            </div>
                            <div class="row g-3 py-3 border-top border-bottom my-3">
                                <div class="col-6 col-md-4">
                                    <div class="small text-muted mb-1">Form Status</div>
                                    <div class="fw-semibold text-capitalize text-dark">{{ str_replace('_', ' ', $application->status) }}</div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="small text-muted mb-1">Payment</div>
                                    <div class="fw-semibold text-capitalize {{ ($payment?->status ?? 'pending') === 'paid' ? 'text-success' : 'text-danger' }}">
                                        {{ $payment?->status ?? 'pending' }}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="small text-muted mb-1">Transaction ID</div>
                                    <div class="fw-semibold text-truncate" title="{{ $payment?->transaction_id ?? 'Pending' }}">
                                        {{ $payment?->transaction_id ?? 'Pending' }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('application.create', $application->exam) }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-edit me-2"></i> View / Edit
                                </a>
                                @if(($payment?->status ?? 'pending') !== 'paid')
                                    <a href="{{ route('payments.create', $application) }}" class="btn btn-dark rounded-pill px-4">
                                        <i class="fas fa-credit-card me-2"></i> Pay Fee
                                    </a>
                                @endif
                                @if($application->status === 'approved')
                                    <a href="{{ route('id-card.form') }}" class="btn btn-outline-success rounded-pill px-4">
                                        <i class="fas fa-id-card me-2"></i> ID Card
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 bg-light">
                        <div class="card-body p-5 text-center">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-2">No applications yet</h4>
                            <p class="text-muted mb-0">You haven't started any application yet. Choose an exam below to begin.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Active Examinations Section (Bottom) -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-5">
                <h2 class="fw-bold text-dark display-6">Examination <span class="text-primary">Portal</span></h2>
                <p class="text-muted">Explore and apply for new examination opportunities.</p>
            </div>
            <div class="col-md-7">
                <form action="{{ route('dashboard') }}" method="GET" class="row g-2 justify-content-md-end">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search exams..." value="{{ $filters['search'] }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>Active Forms</option>
                            <option value="closed" {{ $filters['status'] === 'closed' ? 'selected' : '' }}>Closed Forms</option>
                            <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All Forms</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    @if($filters['search'] || $filters['status'] !== 'active')
                        <div class="col-md-1">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100" title="Clear Filters"><i class="fas fa-times"></i></a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse ($exams as $exam)
                @php
                    $userApplication = $applications->firstWhere('exam_id', $exam->id);
                    $isClosed = $exam->last_date->isPast() || $exam->status !== 'active';
                @endphp
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="premium-card w-100 {{ $userApplication ? 'border-primary shadow-sm' : '' }} {{ $isClosed && !$userApplication ? 'status-closed' : '' }}">
                        <div class="p-card-header d-flex justify-content-between align-items-center mb-3">
                            @if($isClosed)
                                <span class="badge rounded-pill px-3 py-2 bg-secondary-subtle text-secondary border">
                                    <i class="fas fa-lock me-1"></i> Closed
                                </span>
                            @else
                                <span class="status-indicator {{ now()->diffInDays($exam->last_date, false) <= 5 ? 'warning' : 'active' }}">
                                    {{ now()->diffInDays($exam->last_date, false) <= 5 ? 'Closing Soon' : 'Accepting Applications' }}
                                </span>
                            @endif
                            <div class="exam-type text-uppercase small fw-bold px-2 py-1 bg-light rounded text-muted">{{ $exam->category ?? 'General' }}</div>
                        </div>
                        
                        <div class="p-card-body d-flex flex-column">
                            <h3 class="exam-title h4 fw-bold text-dark mb-2 text-capitalize">{{ $exam->title }}</h3>
                            <p class="exam-desc text-muted small mb-4 flex-grow-1">{{ Str::limit($exam->description, 80) }}</p>
                            
                            <div class="exam-details-grid bg-light p-3 rounded-4 mb-3">
                                <div class="detail-item">
                                    <span class="label text-uppercase x-small fw-bold text-muted mb-1" style="font-size: 0.65rem;">Last Date</span>
                                    <span class="value d-block fw-bold {{ $isClosed ? 'text-muted' : 'text-danger' }}">{{ $exam->last_date->format('d M, Y') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label text-uppercase x-small fw-bold text-muted mb-1" style="font-size: 0.65rem;">Exam Fee</span>
                                    <span class="value d-block fw-bold text-success">₹{{ number_format($exam->fee, 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-card-footer mt-auto">
                            @if($userApplication)
                                <div class="d-flex align-items-center justify-content-between bg-primary-subtle p-2 rounded-pill border border-primary-subtle">
                                    <div class="ps-2 small fw-bold text-primary">
                                        <i class="fas fa-check-circle me-1"></i> Applied
                                    </div>
                                    <a href="{{ route('application.create', $exam) }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm">
                                        View
                                    </a>
                                </div>
                            @elseif($isClosed)
                                <button class="btn btn-light w-100 rounded-pill py-2 fw-bold text-muted border" disabled>
                                    Registration Closed
                                </button>
                            @else
                                <a href="{{ route('application.create', $exam) }}" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-bold transition-all">
                                    Apply Now <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border rounded-4 text-center py-5">
                        <i class="fas fa-search fa-2x text-muted mb-3 d-block"></i>
                        No examinations match your current filters.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
