@extends('layouts.app')

@section('title', 'Application Form | Exam Portal')

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
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ $exam->title }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold text-primary mb-3">{{ $exam->title }} <span class="text-warning">Application Form</span></h1>
                <p class="lead text-muted mb-4">{{ $exam->description }}</p>

                <div class="d-flex gap-3 flex-wrap">
                    <div class="feature-badge">
                        <i class="fas fa-id-badge text-warning me-2"></i> {{ auth()->user()->application_number }}
                    </div>
                    <div class="feature-badge">
                        <i class="fas fa-calendar-days text-success me-2"></i> Last Date: {{ $exam->last_date->format('d M Y') }}
                    </div>
                    <div class="feature-badge">
                        <i class="fas fa-money-bill-wave text-info me-2"></i> Fee: Rs. {{ number_format((float) $exam->fee, 2) }}
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="instruction-card p-4 shadow-lg border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-sm bg-warning-light me-3">
                            <i class="fas fa-lightbulb text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Dynamic Form</h5>
                    </div>
                    <p class="small text-muted mb-4">આ form admin-created fields પરથી live generate થાય છે. Code change વગર દરેક exam માટે અલગ structure આપી શકો છો.</p>

                    <ul class="list-unstyled checklist-ui">
                        <li><i class="fas fa-circle-check text-success"></i> Admin configured fields only</li>
                        <li><i class="fas fa-circle-check text-success"></i> Dynamic validation by field type</li>
                        <li><i class="fas fa-circle-check text-success"></i> File/image upload max 350 KB</li>
                        <li><i class="fas fa-circle-check text-success"></i> Saved as JSON in application record</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        @if($isReadOnly ?? false)
                            <!-- Premium Document/PDF-like Preview Layout -->
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
                                <div>
                                    <h3 class="fw-bold text-primary mb-1"><i class="fas fa-file-invoice me-2"></i>Application Details</h3>
                                    <p class="text-muted small mb-0">Below are the details you submitted for this examination.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('application.pdf', $application) }}" class="btn btn-danger rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                        <i class="fas fa-file-pdf me-2"></i> Download Form PDF
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-semibold">
                                        <i class="fas fa-arrow-left me-2"></i> Dashboard
                                    </a>
                                </div>
                            </div>

                            <div class="position-relative border rounded-4 p-4 p-md-5 bg-white shadow-sm mb-4">
                                <!-- Watermark -->
                                <!-- <div class="position-absolute start-50 top-50 translate-middle opacity-5 pointer-events-none text-uppercase text-center w-100" style="font-size: 8rem; font-weight: 900; color: #15803d; transform: translate(-50%, -50%) rotate(-30deg); z-index: 0; user-select: none;">
                                    PAID
                                </div> -->

                                <div class="row g-4 position-relative" style="z-index: 1;">
                                    <!-- Left Column: Personal Data and Dynamic Fields -->
                                    <div class="col-lg-8 border-end-lg pe-lg-4">
                                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-user-circle text-primary me-2"></i>Candidate Profile</h5>
                                        
                                        <div class="row g-3">
                                            @foreach ($fields as $field)
                                                @php
                                                    $isPhoto = (str_contains(strtolower($field->name), 'photo') || str_contains(strtolower($field->label), 'photo')) && $field->type === 'file';
                                                    $isSig = (str_contains(strtolower($field->name), 'signature') || str_contains(strtolower($field->label), 'signature')) && $field->type === 'file';
                                                    if ($isPhoto || $isSig) continue;

                                                    $val = data_get($application->form_data, $field->name);
                                                @endphp
                                                <div class="col-sm-6 text-start">
                                                    <div class="text-muted small mb-1">{{ $field->label }}</div>
                                                    <div class="fw-semibold text-dark">
                                                        @if($field->type === 'file')
                                                            @if($val)
                                                                @if(is_array($val))
                                                                    @foreach($val as $filePath)
                                                                        <a href="{{ \App\Models\Application::fileUrl($filePath) }}" target="_blank" class="btn btn-sm btn-light border me-1 mb-1">
                                                                            <i class="fas fa-file-alt text-primary me-1"></i> View File
                                                                        </a>
                                                                    @endforeach
                                                                @else
                                                                    <a href="{{ \App\Models\Application::fileUrl($val) }}" target="_blank" class="btn btn-sm btn-light border">
                                                                        <i class="fas fa-file-alt text-primary me-1"></i> View File
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <span class="text-muted small italic">Not Uploaded</span>
                                                            @endif
                                                        @else
                                                            @if(is_array($val))
                                                                {{ implode(', ', $val) }}
                                                            @else
                                                                {{ $val ?? 'N/A' }}
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Right Column: Photo and Signature -->
                                    <div class="col-lg-4 ps-lg-4 text-center d-flex flex-column justify-content-between align-items-center">
                                        <!-- Photo Box -->
                                        <div class="mb-4 w-100">
                                            <div class="text-muted small fw-semibold mb-2 text-uppercase tracking-wider">Candidate Photo</div>
                                            <div class="d-inline-block p-1.5 border rounded bg-white shadow-sm" style="background: #f8fafc;">
                                                @php
                                                    $photoField = $fields->first(fn($f) => $f->type === 'file' && (str_contains(strtolower($f->name), 'photo') || str_contains(strtolower($f->label), 'photo')));
                                                    $photoVal = $application->photo ?? ($photoField ? data_get($application->form_data, $photoField->name) : null);
                                                    if (is_array($photoVal)) $photoVal = $photoVal[0] ?? null;
                                                @endphp
                                                @if($photoVal)
                                                    <img src="{{ \App\Models\Application::fileUrl($photoVal) }}" alt="Photo" class="rounded img-thumbnail" style="max-height: 180px; width: 140px; object-fit: cover; border: 1px solid #dee2e6;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted border rounded" style="height: 180px; width: 140px; border-style: dashed !important; display:none !important;">
                                                        <span class="small">No Photo</span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted border rounded" style="height: 180px; width: 140px; border-style: dashed !important;">
                                                        <span class="small">No Photo</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Signature Box -->
                                        <div class="w-100 mt-auto">
                                            <div class="text-muted small fw-semibold mb-2 text-uppercase tracking-wider">Candidate Signature</div>
                                            <div class="d-inline-block p-1 border rounded bg-white shadow-sm mb-2" style="background: #f8fafc;">
                                                @php
                                                    $sigField = $fields->first(fn($f) => $f->type === 'file' && (str_contains(strtolower($f->name), 'signature') || str_contains(strtolower($f->label), 'signature')));
                                                    $sigVal = $application->signature ?? ($sigField ? data_get($application->form_data, $sigField->name) : null);
                                                    if (is_array($sigVal)) $sigVal = $sigVal[0] ?? null;
                                                @endphp
                                                @if($sigVal)
                                                    <img src="{{ \App\Models\Application::fileUrl($sigVal) }}" alt="Signature" class="rounded" style="max-height: 60px; width: 180px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted border rounded" style="height: 60px; width: 180px; border-style: dashed !important; display:none !important;">
                                                        <span class="small">No Signature</span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted border rounded" style="height: 60px; width: 180px; border-style: dashed !important;">
                                                        <span class="small">No Signature</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @unless($exam->module_type === 'vacancy')
                                    <!-- Payment details section inside Card -->
                                    <div class="mt-5 pt-4 border-top">
                                        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-credit-card text-success me-2"></i>Receipt & Payment Info</h5>
                                        @php
                                            $payment = auth()->user()->payments->firstWhere('exam_id', $exam->id);
                                        @endphp
                                        <div class="p-4 bg-light rounded-4 border shadow-xs">
                                            <div class="row g-3 text-center text-sm-start">
                                                <div class="col-sm-5">
                                                    <span class="text-muted small d-block mb-1 text-start">Transaction ID / Razorpay Payment ID</span>
                                                    <span class="fw-bold text-dark font-monospace d-block text-start">{{ $payment?->transaction_id ?? $payment?->razorpay_payment_id ?? 'N/A' }}</span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <span class="text-muted small d-block mb-1 text-start">Amount Paid</span>
                                                    <span class="fw-bold text-success fs-5 d-block text-start">₹{{ number_format($payment?->amount ?? $exam->fee ?? 500, 2) }}</span>
                                                </div>
                                                <div class="col-sm-3 text-sm-end d-flex align-items-center justify-content-sm-end justify-content-center">
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold text-uppercase">
                                                        <i class="fas fa-check-circle me-1"></i> Paid
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endunless
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <a href="{{ route('application.pdf', $application) }}" class="btn btn-danger px-5 py-3 rounded-pill fw-bold shadow">
                                    <i class="fas fa-file-pdf me-2"></i> Download Full Application PDF
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark px-4 py-3 rounded-pill fw-semibold">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                                </a>
                            </div>
                        @else
                            <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" class="row g-4">
                                @csrf
                                <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                            <div class="col-12">
                                <h4 class="fw-bold text-primary mb-0">Candidate Details</h4>
                                <p class="text-muted small mb-0">
                                    {{ $exam->module_type === 'vacancy' ? 'Complete the vacancy form fields below and submit directly.' : 'Complete the admin-configured form fields below and continue to payment.' }}
                                </p>
                            </div>

                            @forelse ($fields as $field)
                                @php
                                    $value = data_get($application->form_data, $field->name);
                                    if (! filled($value)) {
                                        $value = match ($field->name) {
                                            'full_name' => auth()->user()->full_name,
                                            'dob' => optional(auth()->user()->dob)->format('Y-m-d'),
                                            'mobile' => auth()->user()->mobile,
                                            'email' => auth()->user()->email,
                                            default => $value,
                                        };
                                    }
                                    $columnClass = in_array($field->type, ['textarea'], true) ? 'col-12' : 'col-md-6';
                                @endphp
                                <div class="{{ $columnClass }}">
                                    <label class="form-label fw-semibold">
                                        {{ $field->label }}@if($field->is_required) <span class="text-danger">*</span>@endif
                                    </label>

                                    @php
                                        $values = $field->is_repeatable ? (is_array(old($field->name, $value)) ? old($field->name, $value) : (filled(old($field->name, $value)) ? [old($field->name, $value)] : [''])) : [old($field->name, $value)];
                                        if (empty($values)) $values = [''];
                                    @endphp

                                    @if ($field->is_repeatable)
                                        <div id="wrapper-{{ $field->id }}" class="d-flex flex-column gap-2" data-max="{{ $field->max_repeat }}">
                                    @endif

                                    @foreach ($values as $index => $val)
                                        @php
                                            $inputName = $field->is_repeatable ? "{$field->name}[]" : $field->name;
                                            $inputId = $field->is_repeatable ? "{$field->name}_{$index}" : $field->name;
                                        @endphp
                                        <div class="field-item">
                                        @if ($field->type === 'text' || $field->type === 'number' || $field->type === 'date')
                                            <input
                                                type="{{ str_contains(strtolower($field->name . ' ' . $field->label), 'mobile') ? 'tel' : $field->type }}"
                                                name="{{ $inputName }}"
                                                class="form-control"
                                                value="{{ is_array($val) ? '' : $val }}"
                                                @if (str_contains(strtolower($field->name . ' ' . $field->label), 'mobile'))
                                                    maxlength="10"
                                                    pattern="[0-9]{10}"
                                                    inputmode="numeric"
                                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                                                @endif
                                                {{ $field->is_required && $index === 0 ? 'required' : '' }}
                                                {{ ($isReadOnly ?? false) ? 'disabled' : '' }}
                                            >
                                        @elseif ($field->type === 'textarea')
                                            <textarea
                                                name="{{ $inputName }}"
                                                class="form-control"
                                                rows="3"
                                                {{ $field->is_required && $index === 0 ? 'required' : '' }}
                                                {{ ($isReadOnly ?? false) ? 'disabled' : '' }}
                                            >{{ is_array($val) ? '' : $val }}</textarea>
                                        @elseif ($field->type === 'select')
                                            <select name="{{ $inputName }}" class="form-select" {{ $field->is_required && $index === 0 ? 'required' : '' }} {{ ($isReadOnly ?? false) ? 'disabled' : '' }}>
                                                <option value="">Select {{ $field->label }}</option>
                                                @foreach ($field->options ?? [] as $option)
                                                    <option value="{{ $option }}" @selected($val == $option)>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @elseif ($field->type === 'radio')
                                            <div class="d-flex flex-wrap gap-3 pt-2">
                                                @foreach ($field->options ?? [] as $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="{{ $inputName }}" id="{{ $inputId }}_{{ \Illuminate\Support\Str::slug($option, '_') }}" value="{{ $option }}" @checked($val == $option) {{ $field->is_required && $index === 0 ? 'required' : '' }} {{ ($isReadOnly ?? false) ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="{{ $inputId }}_{{ \Illuminate\Support\Str::slug($option, '_') }}">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif ($field->type === 'file')
                                            @if(!($isReadOnly ?? false))
                                                <input type="file" name="{{ $inputName }}" class="form-control" accept="image/*" {{ $field->is_required && !filled($val) && $index === 0 ? 'required' : '' }}>
                                                <div class="form-text">Only image files allowed. Max size 350 KB.</div>
                                            @endif
                                            @if (is_string($val) && filled($val))
                                                <div class="mt-2 p-2 border rounded bg-white d-inline-block">
                                                    <div class="mb-2 text-muted small"><i class="fas fa-image me-1"></i>Uploaded {{ $field->label }}:</div>
                                                    <img src="{{ \App\Models\Application::fileUrl($val) }}" alt="{{ $field->label }}" class="img-thumbnail d-block mb-2" style="max-height: 150px; width: auto; object-fit: contain;">
                                                    <a href="{{ \App\Models\Application::fileUrl($val) }}" target="_blank" class="small text-decoration-none">
                                                        <i class="fas fa-external-link-alt me-1"></i>View full size
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                        </div>
                                    @endforeach

                                    @if ($field->is_repeatable)
                                        </div>
                                        @if(!($isReadOnly ?? false))
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" style="width: fit-content;" onclick="addField({{ $field->id }}, '{{ $field->name }}', '{{ $field->type }}', {{ json_encode($field->options ?? []) }})">
                                                + Add More
                                            </button>
                                        @endif
                                    @endif

                                    @error($field->name)
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @error($field->name . '.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning rounded-4">Admin has not created any fields for this exam yet.</div>
                                </div>
                            @endforelse

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Application Number</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->application_number }}" disabled>
                            </div>

                            <div class="col-12 d-flex flex-wrap gap-3">
                                @if($isReadOnly ?? false)
                                    <a href="{{ route('application.pdf', $application) }}" class="btn btn-danger px-5 py-3 rounded-pill fw-bold">
                                        <i class="fas fa-file-pdf me-2"></i> Download Form PDF
                                    </a>
                                @else
                                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold" {{ $fields->isEmpty() ? 'disabled' : '' }}>
                                        {{ $exam->module_type === 'vacancy' ? 'Submit Vacancy Form' : 'Save & Continue to Payment' }}
                                    </button>
                                @endif
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark px-4 py-3 rounded-pill">Back to Dashboard</a>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function addField(fieldId, fieldName, fieldType, options) {
        let wrapper = document.getElementById('wrapper-' + fieldId);
        let max = wrapper.getAttribute('data-max');
        let currentCount = wrapper.children.length;

        if (max && currentCount >= parseInt(max)) {
            alert('Maximum ' + max + ' entries allowed.');
            return;
        }

        let newField = document.createElement('div');
        newField.className = 'field-item mt-2 pt-2 border-top';

        let inputHtml = '';
        let inputName = fieldName + '[]';
        
        let isMobile = fieldName.toLowerCase().includes('mobile');
        
        if (fieldType === 'text' || fieldType === 'number' || fieldType === 'date') {
            let type = isMobile ? 'tel' : fieldType;
            let attrs = isMobile ? 'maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,\'\').slice(0,10)"' : '';
            inputHtml = `<input type="${type}" name="${inputName}" class="form-control" ${attrs}>`;
        } else if (fieldType === 'textarea') {
            inputHtml = `<textarea name="${inputName}" class="form-control" rows="3"></textarea>`;
        } else if (fieldType === 'select') {
            inputHtml = `<select name="${inputName}" class="form-select"><option value="">Select</option>`;
            options.forEach(opt => {
                inputHtml += `<option value="${opt}">${opt}</option>`;
            });
            inputHtml += `</select>`;
        } else if (fieldType === 'radio') {
            inputHtml = `<div class="d-flex flex-wrap gap-3 pt-2">`;
            options.forEach(opt => {
                let id = fieldName + '_' + currentCount + '_' + opt.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase();
                inputHtml += `<div class="form-check">
                    <input class="form-check-input" type="radio" name="${inputName}" id="${id}" value="${opt}">
                    <label class="form-check-label" for="${id}">${opt}</label>
                </div>`;
            });
            inputHtml += `</div>`;
        } else if (fieldType === 'file') {
            inputHtml = `<input type="file" name="${inputName}" class="form-control" accept="image/*">
            <div class="form-text">Only image files allowed. Max size 350 KB.</div>`;
        }

        newField.innerHTML = inputHtml + '<div class="text-end mt-1"><button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times me-1"></i>Remove</button></div>';
        wrapper.appendChild(newField);
    }
</script>
@endsection
