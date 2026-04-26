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
                        <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" class="row g-4">
                            @csrf
                            <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                            <div class="col-12">
                                <h4 class="fw-bold text-primary mb-0">Candidate Details</h4>
                                <p class="text-muted small mb-0">Complete the admin-configured form fields below and continue to payment.</p>
                            </div>

                            @forelse ($fields as $field)
                                @php
                                    $value = data_get($application->form_data, $field->name);
                                    if (! filled($value)) {
                                        $value = match ($field->name) {
                                            'full_name' => auth()->user()->name,
                                            'dob' => optional(auth()->user()->date_of_birth)->format('Y-m-d'),
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
                                            >
                                        @elseif ($field->type === 'textarea')
                                            <textarea
                                                name="{{ $inputName }}"
                                                class="form-control"
                                                rows="3"
                                                {{ $field->is_required && $index === 0 ? 'required' : '' }}
                                            >{{ is_array($val) ? '' : $val }}</textarea>
                                        @elseif ($field->type === 'select')
                                            <select name="{{ $inputName }}" class="form-select" {{ $field->is_required && $index === 0 ? 'required' : '' }}>
                                                <option value="">Select {{ $field->label }}</option>
                                                @foreach ($field->options ?? [] as $option)
                                                    <option value="{{ $option }}" @selected($val == $option)>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @elseif ($field->type === 'radio')
                                            <div class="d-flex flex-wrap gap-3 pt-2">
                                                @foreach ($field->options ?? [] as $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="{{ $inputName }}" id="{{ $inputId }}_{{ \Illuminate\Support\Str::slug($option, '_') }}" value="{{ $option }}" @checked($val == $option) {{ $field->is_required && $index === 0 ? 'required' : '' }}>
                                                        <label class="form-check-label" for="{{ $inputId }}_{{ \Illuminate\Support\Str::slug($option, '_') }}">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif ($field->type === 'file')
                                            <input type="file" name="{{ $inputName }}" class="form-control" accept="image/*" {{ $field->is_required && !filled($val) && $index === 0 ? 'required' : '' }}>
                                            <div class="form-text">Only image files allowed. Max size 350 KB.</div>
                                            @if (is_string($val) && filled($val))
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $val) }}" target="_blank" class="small text-decoration-none">View uploaded file</a>
                                                </div>
                                            @endif
                                        @endif
                                        </div>
                                    @endforeach

                                    @if ($field->is_repeatable)
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" style="width: fit-content;" onclick="addField({{ $field->id }}, '{{ $field->name }}', '{{ $field->type }}', {{ json_encode($field->options ?? []) }})">
                                            + Add More
                                        </button>
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
                                <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold" {{ $fields->isEmpty() ? 'disabled' : '' }}>Save & Continue to Payment</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark px-4 py-3 rounded-pill">Back to Dashboard</a>
                            </div>
                        </form>
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
