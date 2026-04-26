<footer class="pt-5 pb-3 bg-white border-top">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 mb-4">
                <a class="footer-logo-brand d-flex align-items-center text-decoration-none" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Exam Portal Logo" class="footer-logo-img">
                    <div class="footer-brand-text">
                        <span class="brand-main">EXAM</span>
                        <span class="brand-accent">PORTAL</span>
                    </div>
                </a>
                <p class="text-muted small">The most trusted platform for student registrations, online
                    examinations, and admit card management. Secure, fast, and reliable.</p>
                <div class="social-links d-flex gap-3 justify-content-center">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Active Exams</a></li>
                    <li>
                        @auth
                            <a href="{{ route('dashboard') }}">Apply Online</a>
                        @else
                            <a href="#" data-bs-toggle="modal" data-bs-target="#loginPortal">Apply Online</a>
                        @endauth
                    </li>
                    <li><a href="{{ url('/track-status') }}">Track Status</a></li>
                    <li><a href="{{ url('/admit-card') }}">Admit Card</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 mb-4">
                <h6 class="fw-bold mb-3">Support</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">FAQ's</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Use</a></li>
                </ul>
            </div>

            <div class="col-lg-4 mb-4">
                <h6 class="fw-bold mb-3">Contact Us</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><i class="fas fa-location-dot me-2 text-primary"></i> Surat, Gujarat, India
                    </li>
                    <li class="mb-2"><i class="fas fa-phone me-2 text-primary"></i> +91 98765 43210</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> support@examportal.com</li>
                </ul>
                <div class="mt-3">
                    <span class="small fw-bold text-dark">Managed by
                        <a href="https://addigital.in" target="_blank"
                            class="text-primary text-decoration-none agency-link">
                            AD DIGITAL
                        </a>
                    </span>
                </div>
            </div>
        </div>

        <hr class="my-4 opacity-25">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small text-muted mb-0">&copy; 2026 Exam Portal. All Rights Reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="20"
                    class="me-3 grayscale" alt="PayPal">

                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20"
                    class="me-3 grayscale" alt="Mastercard">

                <img src="https://raw.githubusercontent.com/aaronfagan/svg-credit-card-payment-icons/master/flat/visa.svg"
                    height="25" class="grayscale visa-img" alt="Visa">
            </div>
        </div>
    </div>
</footer>
