@extends('layouts.app')

@section('title', 'Admin Panel | Exam Portal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Admin Panel</h2>
                <p class="text-muted mb-0">Admin is the form creator, system is the form renderer, user is the form filler.</p>
            </div>
            <a href="{{ route('admin.export') }}" class="btn btn-outline-dark rounded-pill px-4">Download CSV</a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-primary mb-3">Create Exam</h4>
                        <form method="POST" action="{{ route('admin.exams.store') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <input type="text" name="title" class="form-control" placeholder="Exam Title" required>
                            </div>
                            <div class="col-12">
                                <textarea name="description" class="form-control" rows="3" placeholder="Description" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="category" class="form-control" placeholder="Category">
                            </div>
                            <div class="col-md-6">
                                <input type="number" step="0.01" name="fee" class="form-control" placeholder="Fee" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="detail_label" class="form-control" placeholder="Extra label">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="detail_value" class="form-control" placeholder="Extra value">
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="last_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <select name="status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 rounded-pill">Add Exam</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h4 class="fw-bold text-primary mb-0">Exam + Form Builder</h4>
                            <span class="small text-muted">Create fields manually for each exam. Users will see only those fields.</span>
                        </div>
                        <div class="row g-3">
                            @forelse ($exams as $exam)
                                <div class="col-12">
                                    <div class="border rounded-4 p-3">
                                        <form method="POST" action="{{ route('admin.exams.update', $exam) }}" class="row g-2 align-items-end">
                                            @csrf
                                            @method('PATCH')
                                            <div class="col-md-4">
                                                <label class="small text-muted">Title</label>
                                                <input type="text" name="title" class="form-control" value="{{ $exam->title }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted">Last Date</label>
                                                <input type="date" name="last_date" class="form-control" value="{{ $exam->last_date->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted">Fee</label>
                                                <input type="number" step="0.01" name="fee" class="form-control" value="{{ $exam->fee }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" @selected($exam->status === 'active')>Active</option>
                                                    <option value="inactive" @selected($exam->status === 'inactive')>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="category" class="form-control" value="{{ $exam->category }}" placeholder="Category">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="detail_label" class="form-control" value="{{ $exam->detail_label }}" placeholder="Detail label">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="detail_value" class="form-control" value="{{ $exam->detail_value }}" placeholder="Detail value">
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-dark btn-sm w-100">Save Exam</button>
                                            </div>
                                            <div class="col-12">
                                                <textarea name="description" class="form-control" rows="2" required>{{ $exam->description }}</textarea>
                                            </div>
                                        </form>

                                        <div class="d-flex gap-2 mt-2 mb-3">
                                            <form method="POST" action="{{ route('admin.exams.toggle', $exam) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-outline-primary btn-sm">{{ $exam->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">Delete</button>
                                            </form>
                                        </div>

                                        <div class="border-top pt-3">
                                            <h6 class="fw-bold text-primary mb-3">Create Form Field</h6>
                                            <form method="POST" action="{{ route('admin.fields.store', $exam) }}" class="row g-2">
                                                @csrf
                                                <div class="col-md-2">
                                                    <input type="text" name="label" class="form-control" placeholder="Label" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="name" class="form-control" placeholder="field_name">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="type" class="form-select" required>
                                                        @foreach ($fieldTypes as $type)
                                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="options_text" class="form-control" placeholder="Options: Male,Female">
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="number" name="sort_order" class="form-control" placeholder="Sort">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center gap-2">
                                                    <div class="form-check">
                                                        <input type="hidden" name="is_required" value="0">
                                                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="required-{{ $exam->id }}">
                                                        <label class="form-check-label small" for="required-{{ $exam->id }}">Req</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="hidden" name="is_repeatable" value="0">
                                                        <input type="checkbox" name="is_repeatable" value="1" class="form-check-input" id="repeatable-{{ $exam->id }}">
                                                        <label class="form-check-label small" for="repeatable-{{ $exam->id }}">Rep</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="number" name="max_repeat" class="form-control" placeholder="Max">
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-primary btn-sm">Add Field</button>
                                                </div>
                                            </form>

                                            <div class="row g-2 mt-2">
                                                @forelse ($exam->formFields as $field)
                                                    <div class="col-12">
                                                        <div class="bg-light rounded-4 p-3">
                                                            <form method="POST" action="{{ route('admin.fields.update', [$exam, $field]) }}" class="row g-2 align-items-end">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="col-md-3">
                                                                    <label class="small text-muted">Label</label>
                                                                    <input type="text" name="label" class="form-control" value="{{ $field->label }}" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="small text-muted">Name</label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $field->name }}">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="small text-muted">Type</label>
                                                                    <select name="type" class="form-select" required>
                                                                        @foreach ($fieldTypes as $type)
                                                                            <option value="{{ $type }}" @selected($field->type === $type)>{{ ucfirst($type) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="small text-muted">Sort</label>
                                                                    <input type="number" name="sort_order" class="form-control" value="{{ $field->sort_order }}">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="small text-muted">Options</label>
                                                                    <input type="text" name="options_text" class="form-control" value="{{ implode(', ', $field->options ?? []) }}" placeholder="A,B,C">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-check mt-4">
                                                                        <input type="hidden" name="is_required" value="0">
                                                                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="field-required-{{ $field->id }}" @checked($field->is_required)>
                                                                        <label class="form-check-label small" for="field-required-{{ $field->id }}">Required</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-check mt-4">
                                                                        <input type="hidden" name="is_repeatable" value="0">
                                                                        <input type="checkbox" name="is_repeatable" value="1" class="form-check-input" id="field-repeatable-{{ $field->id }}" @checked($field->is_repeatable)>
                                                                        <label class="form-check-label small" for="field-repeatable-{{ $field->id }}">Repeatable</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 mt-4">
                                                                    <input type="number" name="max_repeat" class="form-control" value="{{ $field->max_repeat }}" placeholder="Max">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="small text-muted mt-4">Field type: <span class="fw-semibold text-capitalize">{{ $field->type }}</span></div>
                                                                </div>
                                                                <div class="col-md-2 d-flex gap-2">
                                                                    <button class="btn btn-dark btn-sm flex-fill">Save</button>
                                                                </div>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.fields.destroy', [$exam, $field]) }}" class="mt-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-outline-danger btn-sm">Delete Field</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12">
                                                        <div class="alert alert-light border rounded-4 mb-0">No form fields added for this exam yet.</div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light border rounded-4 mb-0">No exams created yet.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">All Applications</h4>
                        <p class="text-muted mb-0">View dynamic submitted fields and payment status for every exam.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.index') }}" class="row g-2">
                        <div class="col-md">
                            <input type="text" name="search" class="form-control" placeholder="Application Number / Name / Mobile" value="{{ $search }}">
                        </div>
                        <div class="col-md">
                            <select name="exam" class="form-select">
                                <option value="">All Exams</option>
                                @foreach ($exams as $exam)
                                    <option value="{{ $exam->id }}" @selected($examFilter == $exam->id)>{{ $exam->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                @foreach (['not_filled', 'submitted', 'approved', 'rejected'] as $status)
                                    <option value="{{ $status }}" @selected($statusFilter === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                    @forelse ($applications as $application)
                        @php
                            $payment = $application->user->payments->firstWhere('exam_id', $application->exam_id);
                            $data = $application->form_data ?? [];
                        @endphp
                        <div class="col-12">
                            <div class="border rounded-4 p-4">
                                <div class="d-flex justify-content-between flex-wrap gap-3 mb-3">
                                    <div>
                                        <h5 class="fw-bold text-primary mb-1">{{ $application->exam?->title }}</h5>
                                        <div class="small text-muted">Application No: {{ $application->user->application_number }}</div>
                                        <div class="small text-muted">User: {{ $application->user?->name }}</div>
                                    </div>
                                    <div class="text-lg-end">
                                        <div class="badge bg-light text-dark border text-capitalize px-3 py-2">{{ str_replace('_', ' ', $application->status) }}</div>
                                        <div class="small text-muted mt-2">Payment: <span class="text-capitalize fw-semibold">{{ $payment?->status ?? 'pending' }}</span></div>
                                        <div class="small text-muted">Txn: {{ $payment?->transaction_id ?? 'Pending' }}</div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    @foreach ($application->exam?->formFields ?? [] as $field)
                                        @php $value = $data[$field->name] ?? null; @endphp
                                        <div class="{{ $field->type === 'textarea' ? 'col-12' : 'col-md-6' }}">
                                            <div class="bg-light rounded-4 p-3 h-100">
                                                <div class="small text-muted mb-1">{{ $field->label }}</div>
                                                @if ($field->type === 'file' && $value)
                                                    @if (is_array($value))
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach ($value as $v)
                                                                <a href="{{ asset('storage/' . $v) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $v) }}" alt="{{ $field->label }}" class="img-fluid rounded border" style="max-height: 100px;">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <a href="{{ asset('storage/' . $value) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $value) }}" alt="{{ $field->label }}" class="img-fluid rounded border" style="max-height: 160px;">
                                                        </a>
                                                    @endif
                                                @else
                                                    @if (is_array($value))
                                                        <ul class="mb-0 ps-3">
                                                            @foreach ($value as $v)
                                                                <li>{{ $v }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="fw-semibold">{{ filled($value) ? $value : 'N/A' }}</div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="d-flex gap-2 flex-wrap align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select" style="max-width: 220px;" required>
                                        @foreach (['not_filled', 'submitted', 'approved', 'rejected'] as $status)
                                            <option value="{{ $status }}" @selected($application->status === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-dark">Update Status</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border rounded-4 mb-0">No applications found.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
