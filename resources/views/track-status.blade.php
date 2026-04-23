@extends('layouts.app')

@section('title', 'Track Application | Exam Portal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/trackapplication.css') }}">
@endpush

@section('content')
<section class="track-hero py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <div class="status-badge mb-3">
                    <span class="badge rounded-pill bg-primary-light text-primary px-3 py-2">
                        <i class="fas fa-satellite-dish me-2 pulse-icon"></i>Live Tracking System
                    </span>
                </div>
                <h1 class="display-5 fw-bold text-primary mb-3">Track Your <span class="text-warning">Application</span></h1>
                <p class="text-muted">Enter your details below to check the real-time status of your examination application.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="#" id="trackingForm">
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Application Number</label>
                                <div class="input-group tracking-input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-hashtag text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 bg-light" 
                                           placeholder="e.g. AD-2026-X890" required>
                                </div>
                                <div class="form-text xsmall mt-2 text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Check your <strong>SMS or Email Receipt</strong> for the Application ID.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Registered Mobile Number</label>
                                <div class="input-group tracking-input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-phone-alt text-primary"></i>
                                    </span>
                                    <input type="tel" class="form-control border-0 bg-light" 
                                           placeholder="Enter 10 digit mobile number" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold tracking-btn">
                                <i class="fas fa-magnifying-glass me-2"></i>Check Status
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-footer bg-light border-0 p-3 text-center">
                        <p class="mb-0 small text-muted">
                            Managed and Secured by <strong>AD DIGITAL</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-top" id="tracking-result-section" style="display: none;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-4 bg-light rounded-4 border-dashed">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Current Status</h6>
                        <h4 class="text-success fw-bold mb-0"><i class="fas fa-circle-check me-2"></i>Under Processing</h4>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="d-block small text-muted text-md-end">Last Updated:</span>
                        <span class="fw-bold">17 April, 2026 - 10:30 AM</span>
                    </div>
                </div>

                <div class="tracking-timeline-container p-4 p-md-5">
                    
                    <div class="timeline-item completed">
                        <div class="timeline-icon">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Application Submitted</h6>
                            <p class="small text-muted mb-0">Your application (AD-2026-X890) has been successfully received by the system.</p>
                            <span class="badge bg-success-light text-success mt-2">15 April, 2026</span>
                        </div>
                    </div>

                    <div class="timeline-item completed">
                        <div class="timeline-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Payment Verified</h6>
                            <p class="small text-muted mb-0">Transaction of ₹500/- via G-Pay has been confirmed.</p>
                            <span class="badge bg-success-light text-success mt-2">15 April, 2026</span>
                        </div>
                    </div>

                    <div class="timeline-item active">
                        <div class="timeline-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Documents Under Verification</h6>
                            <p class="small text-muted mb-0">Our team is verifying your uploaded Photo, Signature, and ID Proof.</p>
                            <span class="badge bg-warning-light text-warning mt-2">In Progress</span>
                        </div>
                    </div>

                    <div class="timeline-item upcoming">
                        <div class="timeline-icon">
                            <i class="fas fa-id-card-clip"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Admit Card Generation</h6>
                            <p class="small text-muted mb-0">Admit card will be available once document verification is successful.</p>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-5">
                    <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Download Status Report
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
