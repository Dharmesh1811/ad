@extends('layouts.app')

@section('title', 'Apply Online | Exam Portal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/applyonline.css') }}">
@endpush

@section('content')
<section class="apply-hero py-5">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-7 mb-4 mb-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a>
                        </li>
                        <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Apply Online
                        </li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold text-primary mb-3">Online Application <span
                        class="text-warning">Portal</span></h1>
                <p class="lead text-muted mb-4">Search and apply for various examinations. Please read the
                    instructions carefully before filling out the application form.</p>

                <div class="d-flex gap-3 flex-wrap">
                    <div class="feature-badge">
                        <i class="fas fa-bolt text-warning me-2"></i> Instant Registration
                    </div>
                    <div class="feature-badge">
                        <i class="fas fa-shield-halved text-success me-2"></i> Verified Portal
                    </div>
                    <div class="feature-badge">
                        <i class="fas fa-headset text-info me-2"></i> Technical Support
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="instruction-card p-4 shadow-lg border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-sm bg-warning-light me-3">
                            <i class="fas fa-lightbulb text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Before You Start</h5>
                    </div>
                    <p class="small text-muted mb-4">Keep the following documents ready for a smooth application
                        process:</p>

                    <ul class="list-unstyled checklist-ui">
                        <li><i class="fas fa-circle-check text-success"></i> High-speed Internet Connection</li>
                        <li><i class="fas fa-circle-check text-success"></i> Scanned Photo & Signature (Max
                            50KB)</li>
                        <li><i class="fas fa-circle-check text-success"></i> Valid Identity Proof (Aadhar/PAN)
                        </li>
                        <li><i class="fas fa-circle-check text-success"></i> Educational Certificates (PDF/JPG)
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="filter-section position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="search-wrapper p-3 bg-white shadow-lg rounded-4 border">
                    <form class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-0 shadow-none"
                                    placeholder="Search by Exam Name (e.g. SSC)...">
                            </div>
                        </div>
                        <div class="col-md-4 border-start-md">
                            <select class="form-select border-0 shadow-none">
                                <option selected>All Categories</option>
                                <option>Government Jobs</option>
                                <option>Entrance Exams</option>
                                <option>Professional Courses</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                                Search Exams
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-3 justify-content-center mt-5">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="category-box active">
                    <div class="cat-icon"><i class="fas fa-building-columns"></i></div>
                    <span class="cat-label">Government</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="category-box">
                    <div class="cat-icon"><i class="fas fa-user-graduate"></i></div>
                    <span class="cat-label">Entrance</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="category-box">
                    <div class="cat-icon"><i class="fas fa-stethoscope"></i></div>
                    <span class="cat-label">Medical</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="category-box">
                    <div class="cat-icon"><i class="fas fa-laptop-code"></i></div>
                    <span class="cat-label">Technical</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="category-box">
                    <div class="cat-icon"><i class="fas fa-school"></i></div>
                    <span class="cat-label">Admission</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h4 class="fw-bold text-primary mb-1">Available Examinations</h4>
                <p class="text-muted small mb-0">Showing active recruitment for your selected category.</p>
            </div>
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                <i class="fas fa-filter me-2 text-primary"></i>Latest First
            </span>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="exam-selection-card h-100 shadow-sm">
                    <div class="exam-card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="exam-icon-box bg-primary-light">
                                <i class="fas fa-building-columns text-primary"></i>
                            </div>
                            <span class="badge rounded-pill bg-success-light text-success px-3">Open</span>
                        </div>
                        <h5 class="fw-bold mb-2">SSC CGL - 2026</h5>
                        <p class="text-muted small mb-4">Combined Graduate Level Examination for various Group B
                            & C posts.</p>

                        <div class="exam-info-list mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Closing Date:</span>
                                <span class="fw-bold small">20 May 2026</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Application Fee:</span>
                                <span class="fw-bold small">₹100 - ₹500</span>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold apply-btn"
                            data-bs-toggle="modal" data-bs-target="#sscModal">
                            Proceed to Apply <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="exam-selection-card h-100 shadow-sm urgent">
                    <div class="exam-card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="exam-icon-box bg-danger-light">
                                <i class="fas fa-stethoscope text-danger"></i>
                            </div>
                            <span class="badge rounded-pill bg-danger-light text-danger px-3">Closing
                                Soon</span>
                        </div>
                        <h5 class="fw-bold mb-2">NEET UG - 2026</h5>
                        <p class="text-muted small mb-4">National Eligibility cum Entrance Test for Medical
                            Degree Courses.</p>

                        <div class="exam-info-list mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Closing Date:</span>
                                <span class="fw-bold small text-danger">Tomorrow</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Application Fee:</span>
                                <span class="fw-bold small">₹800 - ₹1600</span>
                            </div>
                        </div>

                        <button class="btn btn-danger w-100 rounded-pill py-2 fw-bold apply-btn"
                            data-bs-toggle="modal" data-bs-target="#neetModal">
                            Apply Before Link Expires
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="exam-selection-card h-100 shadow-sm">
                    <div class="exam-card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="exam-icon-box bg-info-light">
                                <i class="fas fa-laptop-code text-info"></i>
                            </div>
                            <span class="badge rounded-pill bg-info-light text-info px-3">New</span>
                        </div>
                        <h5 class="fw-bold mb-2">JEE Mains Session-2</h5>
                        <p class="text-muted small mb-4">Joint Entrance Examination for Engineering Admissions
                            in IITs/NITs.</p>

                        <div class="exam-info-list mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Closing Date:</span>
                                <span class="fw-bold small">15 June 2026</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Application Fee:</span>
                                <span class="fw-bold small">₹500 - ₹1000</span>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#jeeModal">
                            Register & Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-7">
                <h6 class="text-primary fw-bold text-uppercase ls-2 mb-2">Support Center</h6>
                <h2 class="fw-bold mb-4">Commonly Asked <span class="text-primary">Questions</span></h2>

                <div class="accordion accordion-flush custom-faq" id="faqAccordion">
                    <div class="accordion-item mb-3 border rounded-4 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                                What documents are required for online registration?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                You generally need a scanned copy of your recent photograph, signature, a valid
                                ID proof (Aadhar/PAN), and your educational qualification certificates in PDF or
                                JPG format.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border rounded-4 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq2">
                                My payment was deducted but the status is still "Pending".
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Please wait for 24-48 hours for the payment gateway to reconcile. If it doesn't
                                update, you can raise a ticket from your dashboard with the transaction ID.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border rounded-4 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq3">
                                How can I download my Admit Card?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Once your application is verified and the admit cards are released, a download
                                link will appear in your "My Applications" section.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div
                    class="support-card p-4 p-md-5 rounded-5 bg-primary text-white h-100 position-relative overflow-hidden">
                    <div class="position-relative z-index-2">
                        <h3 class="fw-bold mb-3">Still need help?</h3>
                        <p class="opacity-75 mb-4">Our dedicated support team is available 24/7 to assist you
                            with your application process.</p>

                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-circle-white me-3">
                                <i class="fas fa-headset text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Helpline Number</h6>
                                <p class="mb-0 small">+91 98765 43210</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-circle-white me-3">
                                <i class="fas fa-envelope-open-text text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Email Support</h6>
                                <p class="mb-0 small">helpdesk@examportal.com</p>
                            </div>
                        </div>

                        <a href="https://wa.me/919876543210?text=Hello%2C%20I%20need%20help%20with%20my%20Exam%20Application."
                            target="_blank"
                            class="btn btn-warning w-100 rounded-pill py-3 fw-bold mt-2 text-decoration-none d-block text-center">
                            <i class="fab fa-whatsapp me-2 fs-5"></i>Contact via WhatsApp
                        </a>
                    </div>
                    <div class="bg-decoration-circle"></div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Modals --}}
<div class="modal fade" id="sscModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0 p-4">
                <h5 class="modal-title fw-bold">General Study Exam July 2026 (SSC)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <form>
                    <div class="section-title text-primary fw-bold mb-3">Personal Details</div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold">Name</label><input type="text"
                                class="form-control" required></div>
                        <div class="col-md-3"><label class="small fw-bold">Age</label><input type="number"
                                class="form-control"></div>
                        <div class="col-md-3"><label class="small fw-bold">Sex</label><select class="form-select">
                                <option>Male</option>
                                <option>Female</option>
                            </select></div>
                        <div class="col-md-6"><label class="small fw-bold">DOB</label><input type="date"
                                class="form-control"></div>
                        <div class="col-md-6"><label class="small fw-bold">Mobile No</label><input type="tel"
                                class="form-control"></div>
                        <div class="col-12"><label class="small fw-bold">Address</label><textarea
                                class="form-control" rows="2"></textarea></div>
                    </div>
                    <div class="section-title text-primary fw-bold mt-4 mb-3">Education Info</div>
                    <div class="row g-3">
                        <div class="col-md-4"><input type="text" class="form-control"
                                placeholder="Qualification (12th+)"></div>
                        <div class="col-md-4"><input type="number" class="form-control" placeholder="Passing Year">
                        </div>
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="College/Uni">
                        </div>
                    </div>
                    <div class="section-title text-primary fw-bold mt-4 mb-3">Documents Upload</div>
                    <div class="row g-3">
                        <div class="col-12"><input type="text" class="form-control mb-2"
                                placeholder="Aadhar Number"></div>
                        <div class="col-md-3 col-6"><input type="file" id="ssc-p" class="d-none"><label for="ssc-p"
                                class="upload-box-ui">Photo</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="ssc-s" class="d-none"><label for="ssc-s"
                                class="upload-box-ui">Sign</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="ssc-t" class="d-none"><label for="ssc-t"
                                class="upload-box-ui">Thumb</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="ssc-o" class="d-none"><label for="ssc-o"
                                class="upload-box-ui">Other</label></div>
                    </div>
                    <div class="section-title text-primary fw-bold mt-4 mb-3">Exam Centre</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="form-check border p-2 rounded px-3"><input name="c1" type="radio" id="sk1"
                                checked> <label for="sk1">Kaithal</label></div>
                        <div class="form-check border p-2 rounded px-3"><input name="c1" type="radio" id="sj1">
                            <label for="sj1">Jind</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c1" type="radio" id="sh1">
                            <label for="sh1">Hissar</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c1" type="radio" id="sa1">
                            <label for="sa1">Ambala</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c1" type="radio" id="sr1">
                            <label for="sr1">Rohtak</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-4 py-3 fw-bold rounded-pill shadow">Submit
                        SSC Application</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="neetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-danger text-white border-0 p-4">
                <h5 class="modal-title fw-bold">General Study Exam July 2026 (NEET)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <form>
                    <div class="section-title text-danger fw-bold mb-3">Personal Details</div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold">Name</label><input type="text"
                                class="form-control" required></div>
                        <div class="col-md-3"><label class="small fw-bold">Age</label><input type="number"
                                class="form-control"></div>
                        <div class="col-md-3"><label class="small fw-bold">Sex</label><select class="form-select">
                                <option>Male</option>
                                <option>Female</option>
                            </select></div>
                        <div class="col-md-6"><label class="small fw-bold">DOB</label><input type="date"
                                class="form-control"></div>
                        <div class="col-md-6"><label class="small fw-bold">Mobile No</label><input type="tel"
                                class="form-control"></div>
                        <div class="col-12"><label class="small fw-bold">Address</label><textarea
                                class="form-control" rows="2"></textarea></div>
                    </div>
                    <div class="section-title text-danger fw-bold mt-4 mb-3">Education Info</div>
                    <div class="row g-3">
                        <div class="col-md-4"><input type="text" class="form-control"
                                placeholder="Qualification (12th+)"></div>
                        <div class="col-md-4"><input type="number" class="form-control" placeholder="Passing Year">
                        </div>
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="College/Uni">
                        </div>
                    </div>
                    <div class="section-title text-danger fw-bold mt-4 mb-3">Documents Upload</div>
                    <div class="row g-3">
                        <div class="col-12"><input type="text" class="form-control mb-2"
                                placeholder="Aadhar Number"></div>
                        <div class="col-md-3 col-6"><input type="file" id="neet-p" class="d-none"><label
                                for="neet-p" class="upload-box-ui">Photo</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="neet-s" class="d-none"><label
                                for="neet-s" class="upload-box-ui">Sign</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="neet-t" class="d-none"><label
                                for="neet-t" class="upload-box-ui">Thumb</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="neet-o" class="d-none"><label
                                for="neet-o" class="upload-box-ui">Other</label></div>
                    </div>
                    <div class="section-title text-danger fw-bold mt-4 mb-3">Exam Centre</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="form-check border p-2 rounded px-3"><input name="c2" type="radio" id="nk2"
                                checked> <label for="nk2">Kaithal</label></div>
                        <div class="form-check border p-2 rounded px-3"><input name="c2" type="radio" id="nj2">
                            <label for="nj2">Jind</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c2" type="radio" id="nh2">
                            <label for="nh2">Hissar</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c2" type="radio" id="na2">
                            <label for="na2">Ambala</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c2" type="radio" id="nr2">
                            <label for="nr2">Rohtak</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 mt-4 py-3 fw-bold rounded-pill shadow">Submit
                        NEET Application</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold">General Study Exam July 2026 (JEE)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <form>
                    <div class="section-title text-dark fw-bold mb-3">Personal Details</div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold">Name</label><input type="text"
                                class="form-control" required></div>
                        <div class="col-md-3"><label class="small fw-bold">Age</label><input type="number"
                                class="form-control"></div>
                        <div class="col-md-3"><label class="small fw-bold">Sex</label><select class="form-select">
                                <option>Male</option>
                                <option>Female</option>
                            </select></div>
                        <div class="col-md-6"><label class="small fw-bold">DOB</label><input type="date"
                                class="form-control"></div>
                        <div class="col-md-6"><label class="small fw-bold">Mobile No</label><input type="tel"
                                class="form-control"></div>
                        <div class="col-12"><label class="small fw-bold">Address</label><textarea
                                class="form-control" rows="2"></textarea></div>
                    </div>
                    <div class="section-title text-dark fw-bold mt-4 mb-3">Education Info</div>
                    <div class="row g-3">
                        <div class="col-md-4"><input type="text" class="form-control"
                                placeholder="Qualification (12th+)"></div>
                        <div class="col-md-4"><input type="number" class="form-control" placeholder="Passing Year">
                        </div>
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="College/Uni">
                        </div>
                    </div>
                    <div class="section-title text-dark fw-bold mt-4 mb-3">Documents Upload</div>
                    <div class="row g-3">
                        <div class="col-12"><input type="text" class="form-control mb-2"
                                placeholder="Aadhar Number"></div>
                        <div class="col-md-3 col-6"><input type="file" id="jee-p" class="d-none"><label for="jee-p"
                                class="upload-box-ui">Photo</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="jee-s" class="d-none"><label for="jee-s"
                                class="upload-box-ui">Sign</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="jee-t" class="d-none"><label for="jee-t"
                                class="upload-box-ui">Thumb</label></div>
                        <div class="col-md-3 col-6"><input type="file" id="jee-o" class="d-none"><label for="jee-o"
                                class="upload-box-ui">Other</label></div>
                    </div>
                    <div class="section-title text-dark fw-bold mt-4 mb-3">Exam Centre</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="form-check border p-2 rounded px-3"><input name="c3" type="radio" id="jk3"
                                checked> <label for="jk3">Kaithal</label></div>
                        <div class="form-check border p-2 rounded px-3"><input name="c3" type="radio" id="nj3">
                            <label for="nj3">Jind</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c3" type="radio" id="nh3">
                            <label for="nh3">Hissar</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c3" type="radio" id="na3">
                            <label for="na3">Ambala</label>
                        </div>
                        <div class="form-check border p-2 rounded px-3"><input name="c3" type="radio" id="nr3">
                            <label for="nr3">Rohtak</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mt-4 py-3 fw-bold rounded-pill shadow">Submit
                        JEE Application</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
