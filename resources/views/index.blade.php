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
                        <a href="{{ url('/apply-online') }}"
                            class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow-lg rounded-pill main-btn">
                            Apply Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
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
                    <p class="text-muted">General study exam july 2026.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="btn btn-outline-dark rounded-pill px-4">View All Exams</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="premium-card">
                        <div class="p-card-header d-flex justify-content-between">
                            <span class="status-indicator active">Active Now</span>
                            <div class="exam-type">Govt</div>
                        </div>

                        <div class="p-card-body">
                            <h3 class="exam-title">SSC CGL 2026</h3>
                            <p class="exam-desc">Staff Selection Commission - Combined Graduate Level Recruitment.</p>

                            <div class="exam-details-grid">
                                <div class="detail-item">
                                    <span class="label">LAST DATE</span>
                                    <span class="value text-primary">20 May, 2026</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">VACANCIES</span>
                                    <span class="value">7,500+</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-card-footer">
                            <a href="#" class="apply-link" data-bs-toggle="modal" data-bs-target="#sscModal">
                                Register & Apply <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="premium-card highlighted">
                        <div class="p-card-header d-flex justify-content-between">
                            <span class="status-indicator warning">Closing Soon</span>
                            <div class="exam-type">Entrance</div>
                        </div>

                        <div class="p-card-body">
                            <h3 class="exam-title">NEET UG 2026</h3>
                            <p class="exam-desc">National Eligibility cum Entrance Test for Medical Courses.</p>

                            <div class="exam-details-grid">
                                <div class="detail-item">
                                    <span class="label">LAST DATE</span>
                                    <span class="value text-danger">Tomorrow</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">LEVEL</span>
                                    <span class="value">All India</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-card-footer">
                            <a href="#" class="apply-link" data-bs-toggle="modal" data-bs-target="#neetModal">
                                Register & Apply <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="premium-card">
                        <div class="p-card-header d-flex justify-content-between">
                            <span class="status-indicator active">Active Now</span>
                            <div class="exam-type">Tech</div>
                        </div>

                        <div class="p-card-body">
                            <h3 class="exam-title">JEE Mains 2026</h3>
                            <p class="exam-desc">Joint Entrance Examination for Engineering Admissions.</p>

                            <div class="exam-details-grid">
                                <div class="detail-item">
                                    <span class="label">LAST DATE</span>
                                    <span class="value text-primary">15 June, 2026</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">SESSIONS</span>
                                    <span class="value">02 Sessions</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-card-footer">
                            <a href="#" class="apply-link" data-bs-toggle="modal" data-bs-target="#jeeModal">
                                Register & Apply <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
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
                        <p class="small text-muted">Mobile Number aur OTP ke saath apna account banayein.</p>
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
                            <h5 class="fw-bold">Two-Factor Authentication</h5>
                            <p class="small text-white-50">Every login and submission is secured with OTP verification
                                to ensure maximum account safety.</p>
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
                <button class="btn btn-warning btn-lg px-5 py-3 fw-bold rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#loginPortal">
                    Create Account
                </button>

                <a href="{{ url('/track-status') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                    Track Application
                </a>
            </div>
        </div>
    </section>

    {{-- Modals from index.html --}}
    <div class="modal fade" id="sscModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">SSC CGL 2026 - Registration Form</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                    <form>
                        <div class="section-title"><i class="fas fa-user me-2"></i>Personal Details</div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="small fw-bold">Name</label><input type="text"
                                    class="form-control custom-input" placeholder="Full Name" required></div>
                            <div class="col-md-3"><label class="small fw-bold">Age</label><input type="number"
                                    class="form-control custom-input" placeholder="Age"></div>
                            <div class="col-md-3"><label class="small fw-bold">Sex</label><select
                                    class="form-select custom-input">
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select></div>
                            <div class="col-md-6"><label class="small fw-bold">DOB (Date of Birth)</label><input
                                    type="date" class="form-control custom-input" required></div>
                            <div class="col-md-6"><label class="small fw-bold">Mobile No</label><input type="tel"
                                    class="form-control custom-input" placeholder="Phone Number" required></div>
                            <div class="col-12"><label class="small fw-bold">Full Address</label><textarea
                                    class="form-control custom-input" rows="2"
                                    placeholder="Street, City, State, Pincode" required></textarea></div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-graduation-cap me-2"></i>Qualification Info
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="small fw-bold">Qualification</label><input type="text"
                                    class="form-control custom-input" placeholder="Min 12th pass" required></div>
                            <div class="col-md-4"><label class="small fw-bold">Passing Year</label><input type="number"
                                    class="form-control custom-input" placeholder="YYYY" required></div>
                            <div class="col-md-4"><label class="small fw-bold">College/University</label><input
                                    type="text" class="form-control custom-input" placeholder="Institute Name" required>
                            </div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-upload me-2"></i>Documents Upload</div>
                        <div class="row g-3">
                            <div class="col-12 mb-2"><label class="small fw-bold">Aadhar Number</label><input
                                    type="text" class="form-control custom-input" placeholder="12 Digit Aadhar Number"
                                    required></div>
                            <div class="col-md-3 col-6"><input type="file" id="ssc-photo" class="d-none"
                                    accept="image/*"><label for="ssc-photo" class="upload-box"><i
                                        class="fas fa-image"></i><span>Photo</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="ssc-sign" class="d-none"
                                    accept="image/*"><label for="ssc-sign" class="upload-box"><i
                                        class="fas fa-pen-nib"></i><span>Signature</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="ssc-thumb" class="d-none"
                                    accept="image/*"><label for="ssc-thumb" class="upload-box"><i
                                        class="fas fa-fingerprint"></i><span>Thumb</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="ssc-doc" class="d-none"><label
                                    for="ssc-doc" class="upload-box"><i class="fas fa-file-alt"></i><span>Other
                                        Doc</span></label></div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-map-marker-alt me-2"></i>Exam Centre Preference
                        </div>
                        <div class="exam-center-grid">
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="ssc-loc"
                                    id="sk" checked><label for="sk" class="small mb-0">Kaithal</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="ssc-loc"
                                    id="sj"><label for="sj" class="small mb-0">Jind</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="ssc-loc"
                                    id="sh"><label for="sh" class="small mb-0">Hissar</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="ssc-loc"
                                    id="sa"><label for="sa" class="small mb-0">Ambala</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="ssc-loc"
                                    id="sr"><label for="sr" class="small mb-0">Rohtak</label></div>
                        </div>
                        <button type="submit" class="apply-now-btn mt-4">Final Submit SSC Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="neetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">NEET UG 2026 - Registration Form</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                    <form>
                        <div class="section-title"><i class="fas fa-user me-2"></i>Personal Details</div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="small fw-bold">Name</label><input type="text"
                                    class="form-control custom-input" placeholder="Full Name" required></div>
                            <div class="col-md-3"><label class="small fw-bold">Age</label><input type="number"
                                    class="form-control custom-input" placeholder="Age"></div>
                            <div class="col-md-3"><label class="small fw-bold">Sex</label><select
                                    class="form-select custom-input">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select></div>
                            <div class="col-md-6"><label class="small fw-bold">DOB</label><input type="date"
                                    class="form-control custom-input"></div>
                            <div class="col-md-6"><label class="small fw-bold">Mobile No</label><input type="tel"
                                    class="form-control custom-input" placeholder="Phone Number"></div>
                            <div class="col-12"><label class="small fw-bold">Full Address</label><textarea
                                    class="form-control custom-input" rows="2" placeholder="Address"></textarea></div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-graduation-cap me-2"></i>Education Info</div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="small fw-bold">Qualification</label><input type="text"
                                    class="form-control custom-input" placeholder="Min 12th pass" required></div>
                            <div class="col-md-4"><label class="small fw-bold">Passing Year</label><input type="number"
                                    class="form-control custom-input" placeholder="YYYY" required></div>
                            <div class="col-md-4"><label class="small fw-bold">School/Institute</label><input
                                    type="text" class="form-control custom-input" placeholder="Institute Name" required>
                            </div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-upload me-2"></i>Documents Upload</div>
                        <div class="row g-3">
                            <div class="col-12 mb-2"><input type="text" class="form-control custom-input"
                                    placeholder="Aadhar Number" required></div>
                            <div class="col-md-3 col-6"><input type="file" id="neet-photo" class="d-none"><label
                                    for="neet-photo" class="upload-box"><i
                                        class="fas fa-image"></i><span>Photo</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="neet-sign" class="d-none"><label
                                    for="neet-sign" class="upload-box"><i
                                        class="fas fa-pen-nib"></i><span>Signature</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="neet-thumb" class="d-none"><label
                                    for="neet-thumb" class="upload-box"><i
                                        class="fas fa-fingerprint"></i><span>Thumb</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="neet-doc" class="d-none"><label
                                    for="neet-doc" class="upload-box"><i class="fas fa-file-alt"></i><span>Other
                                        Doc</span></label></div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-map-marker-alt me-2"></i>Exam Centre Preference
                        </div>
                        <div class="exam-center-grid">
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="neet-loc"
                                    id="nk" checked><label for="nk" class="small mb-0">Kaithal</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="neet-loc"
                                    id="nj"><label for="nj" class="small mb-0">Jind</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="neet-loc"
                                    id="nh"><label for="nh" class="small mb-0">Hissar</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="neet-loc"
                                    id="na"><label for="na" class="small mb-0">Ambala</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="neet-loc"
                                    id="nr"><label for="nr" class="small mb-0">Rohtak</label></div>
                        </div>
                        <button type="submit" class="apply-now-btn mt-4"
                            style="background: linear-gradient(45deg, #dc3545, #ff4d5a);">Submit NEET
                            Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="jeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">JEE Mains 2026 - Registration Form</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                    <form>
                        <div class="section-title"><i class="fas fa-user me-2"></i>Personal Details</div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="small fw-bold">Name</label><input type="text"
                                    class="form-control custom-input" placeholder="Full Name" required></div>
                            <div class="col-md-3"><label class="small fw-bold">Age</label><input type="number"
                                    class="form-control custom-input" placeholder="Age"></div>
                            <div class="col-md-3"><label class="small fw-bold">Sex</label><select
                                    class="form-select custom-input">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select></div>
                            <div class="col-md-6"><label class="small fw-bold">DOB</label><input type="date"
                                    class="form-control custom-input"></div>
                            <div class="col-md-6"><label class="small fw-bold">Mobile No</label><input type="tel"
                                    class="form-control custom-input" placeholder="Phone Number"></div>
                            <div class="col-12"><label class="small fw-bold">Full Address</label><textarea
                                    class="form-control custom-input" rows="2" placeholder="Address"></textarea></div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-graduation-cap me-2"></i>Education Info</div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="small fw-bold">Qualification</label><input type="text"
                                    class="form-control custom-input" placeholder="Min 12th pass" required></div>
                            <div class="col-md-4"><label class="small fw-bold">Passing Year</label><input type="number"
                                    class="form-control custom-input" placeholder="YYYY" required></div>
                            <div class="col-md-4"><label class="small fw-bold">College/University</label><input
                                    type="text" class="form-control custom-input" placeholder="Institute Name" required>
                            </div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-upload me-2"></i>Documents Upload</div>
                        <div class="row g-3">
                            <div class="col-12 mb-2"><input type="text" class="form-control custom-input"
                                    placeholder="Aadhar Number" required></div>
                            <div class="col-md-3 col-6"><input type="file" id="jee-photo" class="d-none"><label
                                    for="jee-photo" class="upload-box"><i
                                        class="fas fa-image"></i><span>Photo</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="jee-sign" class="d-none"><label
                                    for="jee-sign" class="upload-box"><i
                                        class="fas fa-pen-nib"></i><span>Signature</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="jee-thumb" class="d-none"><label
                                    for="jee-thumb" class="upload-box"><i
                                        class="fas fa-fingerprint"></i><span>Thumb</span></label></div>
                            <div class="col-md-3 col-6"><input type="file" id="jee-doc" class="d-none"><label
                                    for="jee-doc" class="upload-box"><i class="fas fa-file-alt"></i><span>Other
                                        Doc</span></label></div>
                        </div>

                        <div class="section-title mt-4"><i class="fas fa-map-marker-alt me-2"></i>Exam Centre Preference
                        </div>
                        <div class="exam-center-grid">
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="jee-loc"
                                    id="jk" checked><label for="jk" class="small mb-0">Kaithal</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="jee-loc"
                                    id="jj"><label for="jj" class="small mb-0">Jind</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="jee-loc"
                                    id="jh"><label for="jh" class="small mb-0">Hissar</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="jee-loc"
                                    id="ja"><label for="ja" class="small mb-0">Ambala</label></div>
                            <div class="center-option"><input class="form-check-input me-2" type="radio" name="jee-loc"
                                    id="jr"><label for="jr" class="small mb-0">Rohtak</label></div>
                        </div>
                        <button type="submit" class="apply-now-btn mt-4 bg-dark">Submit JEE Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
