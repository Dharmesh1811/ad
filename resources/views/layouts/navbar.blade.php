<nav class="navbar navbar-expand-lg fixed-top custom-nav">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                onerror="this.src='https://via.placeholder.com/40x40/00529b/ffffff?text=B'" class="logo-img me-2">
            <div class="brand-container">
                <span class="brd-text">BRD</span>
                <span class="edu-text">EDUCATION</span>
                <small class="pvt-ltd">PVT. LTD.</small>
            </div>
        </a>

        @guest
            <button class="btn btn-glow-login d-lg-none ms-auto me-2" data-bs-toggle="modal"
                data-bs-target="#loginPortal">
                <i class="fas fa-user-shield"></i>
            </button>
        @else
            <a href="{{ route('dashboard') }}" class="btn btn-glow-login d-lg-none ms-auto me-2">
                <i class="fas fa-gauge"></i>
            </a>
        @endguest

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fas fa-home me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    @auth
                        <a class="nav-link {{ Request::is('dashboard') || Request::is('apply-online/*') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-edit me-1"></i>
                            Apply Online</a>
                    @else
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginPortal"><i class="fas fa-edit me-1"></i>
                            Apply Online</a>
                    @endauth
                </li>
                <li class="nav-item"><a class="nav-link {{ Request::is('track-status') ? 'active' : '' }}" href="{{ url('/track-status') }}"><i
                            class="fas fa-map-marker-alt me-1"></i> Track Status</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('download-id-card') ? 'active' : '' }}" href="{{ route('id-card.form') }}"><i class="fas fa-id-card me-1"></i>
                        Admit Card</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-gauge me-1"></i>
                            Dashboard</a></li>
                    @if (auth()->user()->is_admin)
                        <li class="nav-item"><a class="nav-link {{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.index') }}"><i class="fas fa-user-gear me-1"></i>
                                Admin</a></li>
                    @endif
                @endauth
            </ul>
            @auth
                <div class="d-none d-lg-flex align-items-center gap-2">
                    <span class="text-white small fw-semibold">{{ auth()->user()->application_number }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-glow-login px-4">
                            <span><i class="fas fa-right-from-bracket me-2"></i>LOGOUT</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="d-none d-lg-block">
                    <button class="btn btn-glow-login px-4" data-bs-toggle="modal" data-bs-target="#loginPortal">
                        <span><i class="fas fa-user-shield me-2"></i>PORTAL LOGIN</span>
                    </button>
                </div>
            @endauth
        </div>
    </div>
</nav>

<div class="modal fade" id="loginPortal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal border-0 shadow-lg" style="border-radius: 20px;">
            
            <div class="modal-header border-0 justify-content-center pt-5">
                <div class="nav nav-pills auth-tabs" id="pills-tab" role="tablist" style="background: #f1f4f9; border-radius: 12px; padding: 5px;">
                    <button class="nav-link auth-tab active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#loginPane" type="button" role="tab" style="border-radius: 10px; font-weight: 600; padding: 10px 30px;">LOGIN</button>
                    <button class="nav-link auth-tab" id="pills-signup-tab" data-bs-toggle="pill" data-bs-target="#signupPane" type="button" role="tab" style="border-radius: 10px; font-weight: 600; padding: 10px 30px;">REGISTER</button>
                </div>
            </div>

            <div class="modal-body p-4 p-md-5">
                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="loginPane" role="tabpanel">
                        <form method="POST" action="{{ route('auth.login-password') }}">
                            @csrf
                            <input type="hidden" name="auth_tab" value="login">
                            <div class="form-floating mb-3 mt-2">
                                <input type="text" class="form-control stylish-input @error('mobile_or_email') is-invalid @enderror" name="mobile_or_email" id="loginMob" placeholder="Mobile Number or Email" value="{{ old('mobile_or_email') }}" required>
                                <label for="loginMob">Mobile Number or Email</label>
                                @error('mobile_or_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control stylish-input @error('password') is-invalid @enderror" name="password" id="loginPassword" placeholder="Password" required>
                                <label for="loginPassword">Password</label>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted me-2 border-0" onclick="togglePassword('loginPassword', this)">
                                    <i class="far fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @error('password_login')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-submit-premium w-100 py-3 shadow-sm" style="background: #00529b; border-radius: 10px; font-weight: 600; letter-spacing: 1px;">
                                LOGIN
                            </button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="signupPane" role="tabpanel">
                        <form method="POST" action="{{ route('auth.register-password') }}">
                            @csrf
                            <input type="hidden" name="auth_tab" value="signup">
                            <div class="form-floating mb-3 mt-2">
                                <input type="text" class="form-control stylish-input @error('full_name') is-invalid @enderror" name="full_name" id="regName" placeholder="Full Name" value="{{ old('full_name') }}" required>
                                <label for="regName">Full Name</label>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control stylish-input @error('mobile') is-invalid @enderror" name="mobile" id="regMob" placeholder="Mobile Number" value="{{ old('mobile') }}" required maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                                <label for="regMob">Mobile Number</label>
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control stylish-input @error('email') is-invalid @enderror" name="email" id="regEmail" placeholder="Email Address" value="{{ old('email') }}" required>
                                <label for="regEmail">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control stylish-input @error('dob') is-invalid @enderror" name="dob" id="regDob" value="{{ old('dob') }}" required>
                                <label for="regDob" style="color: #ff6600; font-weight: bold;">Date of Birth</label>
                                @error('dob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control stylish-input @error('password') is-invalid @enderror" name="password" id="regPassword" placeholder="Password" required>
                                <label for="regPassword">Password</label>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted me-2 border-0" onclick="togglePassword('regPassword', this)">
                                    <i class="far fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control stylish-input" name="password_confirmation" id="regPasswordConfirmation" placeholder="Confirm Password" required>
                                <label for="regPasswordConfirmation">Confirm Password</label>
                            </div>
                            @error('password_register')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn btn-submit-premium w-100 py-3 shadow-sm" style="background: #00529b; border-radius: 10px; font-weight: 600; letter-spacing: 1px;">
                                CREATE ACCOUNT
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endpush
