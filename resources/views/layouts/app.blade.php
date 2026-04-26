<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Exam Portal - Student Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    @stack('styles')
</head>

<body>

    @include('layouts.navbar')

    <main>
        <div class="container pt-5 mt-5">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('otp_notice'))
                <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                    {{ session('otp_notice') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    Please review the form and fix the highlighted errors.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    @if (session('openAuthModal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('loginPortal');
                if (!modalElement) {
                    return;
                }

                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                const trigger = document.getElementById('pills-{{ session('openAuthModal') }}-tab');
                if (trigger) {
                    bootstrap.Tab.getOrCreateInstance(trigger).show();
                }
            });
        </script>
    @endif
    
    @stack('scripts')
</body>

</html>
