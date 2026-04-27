@extends('layouts.app')

@section('title', 'Exam Portal - Student Panel')

@section('content')
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white mb-5 mb-lg-0">
                    <div class="badge bg-warning text-dark mb-3 px-3 py-2 fw-bold rounded-pill shadow-sm">
                        🚀 Admissions Open 2026-27
                    </div>
                    <h1 class="display-3 fw-bold mb-4">
                        Future ki Taiyari, <br>
                        <span class="text-warning">Sahi Disha</span> se.
                    </h1>
                    <p class="lead mb-5 opacity-75">
                        India ke top examination portal par aapka swagat hai. Yahan aap Registration se lekar Admit Card
                        download tak ka safar ek hi jagah poora kar sakte hain.
                    </p>

                    <div class="d-flex gap-3 flex-wrap">
                        @auth
                            @if ($exams->isNotEmpty())
                                <a href="{{ route('application.create', $exams->first()) }}"
                                    class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow-lg rounded-pill main-btn">
                                    Apply Now <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @endif
                        @else
                            <button type="button" data-bs-toggle="modal" data-bs-target="#loginPortal"
                                class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow-lg rounded-pill main-btn">
                                Apply Now <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        @endauth
                        <a href="{{ url('/track-status') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                            Check Status
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="glass-box p-4 p-md-5">
                        <h4 class="fw-bold mb-4 text-white">Quick Statistics</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="stat-item d-flex align-items-center p-3">
                                    <div class="icon-circle bg-primary me-3">
                                        <i class="fas fa-user-graduate text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-white">50,000+</h5>
                                        <p class="small text-white-50 mb-0">Registered Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="stat-item d-flex align-items-center p-3">
                                    <div class="icon-circle bg-success me-3">
                                        <i class="fas fa-check-double text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-white">100%</h5>
                                        <p class="small text-white-50 mb-0">Secure Payments</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="active-exams" class="py-5 bg-white">
        <div class="container">
            <div class="row mb-5 align-items-center">
                <div class="col-md-6">
                    <h2 class="fw-bold text-dark display-6">Active <span class="text-primary">Examinations</span></h2>
                    <p class="text-muted">Admin managed exams with live last dates and application control.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ auth()->check() ? route('dashboard') : '#active-exams' }}" class="btn btn-outline-dark rounded-pill px-4">View All Exams</a>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($exams as $exam)
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
                                        <span class="value {{ now()->diffInDays($exam->last_date, false) <= 2 ? 'text-danger' : 'text-primary' }}">
                                            {{ $exam->last_date->format('d M, Y') }}
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">{{ strtoupper($exam->detail_label ?? 'FEE') }}</span>
                                        <span class="value">{{ $exam->detail_value ?? 'Rs. ' . number_format((float) $exam->fee, 0) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-card-footer">
                                @auth
                                    <a href="{{ route('application.create', $exam) }}" class="apply-link">
                                        Register & Apply <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @else
                                    <a href="#" class="apply-link" data-bs-toggle="modal" data-bs-target="#loginPortal">
                                        Register & Apply <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border rounded-4 text-center">No active exams are available right now.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase ls-2">Process</h6>
                <h2 class="fw-bold">Application <span class="text-primary">Process</span></h2>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card text-center">
                        <div class="step-number">01</div>
                        <div class="step-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h5>Registration</h5>
                        <p class="small text-muted">Mobile Number, email aur password ke saath apna account banayein.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card text-center">
                        <div class="step-number">02</div>
                        <div class="step-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h5>Fill Form</h5>
                        <p class="small text-muted">Apni details bharein aur documents upload karein.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card text-center">
                        <div class="step-number">03</div>
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h5>Payment</h5>
                        <p class="small text-muted">Net banking ya UPI se apni exam fees jama karein.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card text-center">
                        <div class="step-number">04</div>
                        <div class="step-icon">
                            <i class="fas fa-ticket"></i>
                        </div>
                        <h5>Admit Card</h5>
                        <p class="small text-muted">Approval ke baad apna Admit Card download karein.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-dark text-white overflow-hidden position-relative">
        <div class="bg-circle-design"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="row g-4 text-center">
                        <div class="col-6">
                            <div class="stat-box p-4 rounded-4">
                                <h2 class="display-5 fw-bold text-warning mb-0">50K+</h2>
                                <p class="small text-white-50 text-uppercase mb-0">Total Applications</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-4 rounded-4">
                                <h2 class="display-5 fw-bold text-warning mb-0">100%</h2>
                                <p class="small text-white-50 text-uppercase mb-0">Secure Payments</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-4 rounded-4">
                                <h2 class="display-5 fw-bold text-warning mb-0">24/7</h2>
                                <p class="small text-white-50 text-uppercase mb-0">Instant Alerts</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-4 rounded-4">
                                <h2 class="display-5 fw-bold text-warning mb-0">15+</h2>
                                <p class="small text-white-50 text-uppercase mb-0">Active Exams</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <h6 class="text-warning fw-bold text-uppercase mb-3">Security & Trust</h6>
                    <h2 class="fw-bold mb-4">Your Data Security is Our <span class="text-warning">Top Priority</span>
                    </h2>

                    <div class="d-flex align-items-start mb-4">
                        <div class="feature-icon-circle me-3">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Bank-Grade Encryption</h5>
                            <p class="small text-white-50">All personal details and uploaded documents are protected
                                using advanced end-to-end encryption.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="feature-icon-circle me-3">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Protected Account Access</h5>
                            <p class="small text-white-50">Every login and submission is protected with secure
                                password-based account access to keep your profile safe.</p>
                        </div>
                    </div>

                    <a href="#" class="btn btn-warning rounded-pill px-4 py-2 fw-bold">Review Privacy Policy</a>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 bg-primary text-white text-center position-relative overflow-hidden">
        <div class="container position-relative" style="z-index: 2;">
            <h2 class="fw-bold mb-3">Ready to Start Your Career Journey?</h2>
            <p class="lead mb-4 opacity-75">Create your account today and apply for the latest government and entrance
                examinations.</p>

            <div class="d-flex justify-content-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg px-5 py-3 fw-bold rounded-pill">
                        Go to Dashboard
                    </a>
                @else
                    <button class="btn btn-warning btn-lg px-5 py-3 fw-bold rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#loginPortal">
                        Create Account
                    </button>
                @endauth

                <a href="{{ url('/track-status') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                    Track Application
                </a>
            </div>
        </div>
    </section>

@endsection
