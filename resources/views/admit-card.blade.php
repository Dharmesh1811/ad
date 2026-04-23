@extends('layouts.app')

@section('title', 'Download Admit Card | Exam Portal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admitcard.css') }}">
@endpush

@section('content')
<section class="admit-hero py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-7">
                <div class="icon-box-circle bg-white shadow-sm mb-4 mx-auto">
                    <i class="fas fa-id-card-clip text-primary fs-3"></i>
                </div>
                <h1 class="fw-bold text-primary">Download <span class="text-warning">Admit Card</span></h1>
                <p class="text-muted">Enter your credentials to generate and download your hall ticket for the
                    upcoming examination.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form id="admitCardForm">
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Application / Roll
                                    Number</label>
                                <input type="text" name="roll"
                                    class="form-control border-0 bg-light py-3 px-4 rounded-4 shadow-none"
                                    placeholder="e.g. AD-123456" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Date of Birth</label>
                                <input type="date" name="dob"
                                    class="form-control border-0 bg-light py-3 px-4 rounded-4 shadow-none"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Security Code</label>
                                <div
                                    class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5 ls-3 text-primary"
                                        style="user-select: none; font-style: italic;">X 8 R 2 Q</span>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none"><i
                                            class="fas fa-arrows-rotate"></i> Refresh</button>
                                </div>
                                <input type="text"
                                    class="form-control border-0 bg-light py-3 px-4 rounded-4 mt-2 shadow-none"
                                    placeholder="Enter the code shown above" required>
                            </div>

                            <button type="submit"
                                class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow">
                                <i class="fas fa-download me-2"></i>Generate Admit Card
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Dummy Admit Card for PDF generation --}}
<div id="admitCard" style="display:none; width:600px; padding:20px; border:2px solid #000;">
    <h2 style="text-align:center;">Admit Card</h2>

    <p><strong>Name:</strong> <span id="c_name"></span></p>
    <p><strong>Mobile:</strong> <span id="c_mobile"></span></p>
    <p><strong>DOB:</strong> <span id="c_dob"></span></p>
    <p><strong>Roll No:</strong> <span id="c_roll"></span></p>

    <img id="c_img" src="" width="100" style="border:1px solid #000;">
</div>

<section class="py-5 bg-white border-top" id="admit-result-section" style="display: none;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div
                    class="admit-card-box p-4 p-md-5 border rounded-4 shadow-sm bg-white position-relative overflow-hidden">
                    <div
                        class="admit-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-university fs-1 text-primary me-3"></i>
                            <div>
                                <h5 class="fw-bold mb-0 text-primary">EXAM PORTAL BRD EDUCATION</h5>
                                <span class="small text-muted">Admit Card - 2026</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=AD-DIGITAL-SECURE"
                                alt="QR" class="img-fluid border p-1 rounded">
                        </div>
                    </div>

                    <div class="row g-4 position-relative" style="z-index: 2;">
                        <div class="col-md-8">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted small py-2">Candidate Name:</td>
                                    <td class="fw-bold py-2">Akshay Dorila</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small py-2">Roll Number:</td>
                                    <td class="fw-bold py-2">AD-2026-X890</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small py-2">Exam Center:</td>
                                    <td class="fw-bold py-2">Surat Digital Hub, Vesu Road, Gujarat</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small py-2">Exam Date:</td>
                                    <td class="fw-bold py-2 text-danger">25 May, 2026</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small py-2">Reporting Time:</td>
                                    <td class="fw-bold py-2">09:30 AM</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-center border-start">
                            <div class="photo-placeholder bg-light mx-auto mb-2 border d-flex align-items-center justify-content-center"
                                style="width: 120px; height: 150px; border-style: dashed !important;">
                                <i class="fas fa-user text-muted fs-1"></i>
                            </div>
                            <span class="small text-muted d-block">Candidate Photo</span>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-top text-center position-relative" style="z-index: 2;">
                        <button class="btn btn-success rounded-pill px-5 py-2 fw-bold" onclick="window.print()">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF Format
                        </button>
                        <p class="xsmall text-muted mt-3 mb-0">Note: This is a computer-generated document and
                            does not require a physical signature.</p>
                    </div>

                    <div class="watermark-text">BRD EDUCATION</div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="alert alert-info border-0 rounded-4 shadow-sm d-flex align-items-center p-4">
                    <div class="me-4 fs-1">
                        <i class="fas fa-shield-check text-primary"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Digitally Verified Document</h6>
                        <p class="small mb-0 text-muted">This admit card is digitally generated by **EXAM PORTAL
                            INDIA**. You can verify the authenticity by scanning the QR code mentioned on the
                            hall ticket.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-7">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <h5 class="fw-bold text-primary mb-4">
                        <i class="fas fa-clipboard-list me-2"></i>Important Instructions
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <span class="small">Candidates must carry a <strong>printed copy</strong> of this
                                Admit Card along with a valid Photo ID proof.</span>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <span class="small">Reporting time is strictly <strong>60 minutes</strong> before
                                the commencement of the examination.</span>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <span class="small">Electronic gadgets like mobile phones, smartwatches, and
                                calculators are strictly prohibited.</span>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <span class="small">Ensure your photograph and signature on the admit card are clear
                                and visible.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-5">
                <div
                    class="p-4 bg-primary text-white rounded-4 shadow-sm h-100 position-relative overflow-hidden">
                    <h5 class="fw-bold mb-4">Exam Center Checklist</h5>
                    <div class="checklist-item d-flex align-items-center mb-3">
                        <div class="check-box me-3"><i class="fas fa-check"></i></div>
                        <span class="small">Admit Card (Colored/BW)</span>
                    </div>
                    <div class="checklist-item d-flex align-items-center mb-3">
                        <div class="check-box me-3"><i class="fas fa-check"></i></div>
                        <span class="small">Aadhar Card / PAN Card / Voter ID</span>
                    </div>
                    <div class="checklist-item d-flex align-items-center mb-3">
                        <div class="check-box me-3"><i class="fas fa-check"></i></div>
                        <span class="small">2 Recent Passport Size Photos</span>
                    </div>
                    <div class="checklist-item d-flex align-items-center">
                        <div class="check-box me-3"><i class="fas fa-check"></i></div>
                        <span class="small">Blue/Black Ballpoint Pen</span>
                    </div>

                    <i class="fas fa-user-graduate position-absolute bottom-0 end-0 opacity-10 mb-n3 me-n3"
                        style="font-size: 8rem;"></i>
                </div>
            </div>

        </div>

        <div class="text-center mt-5">
            <p class="text-muted small">Facing issues with your Admit Card?
                <a href="https://wa.me/919876543210" class="text-primary fw-bold text-decoration-none mx-2">
                    <i class="fab fa-whatsapp me-1"></i>Contact Helpdesk
                </a>
            </p>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div
                    class="p-4 p-md-5 rounded-5 bg-light border d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="mb-4 mb-md-0 text-center text-md-start">
                        <h4 class="fw-bold mb-2">Technical Issue with Admit Card?</h4>
                        <p class="text-muted mb-0 small">If your details are incorrect or the photo is missing,
                            contact our support desk immediately.</p>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="tel:+919876543210" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                            <i class="fas fa-phone-volume me-2"></i>Call Support
                        </a>
                        <a href="https://wa.me/919876543210"
                            class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endpush
