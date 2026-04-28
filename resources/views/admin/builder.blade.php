@extends('layouts.app')

@section('title', 'Form Builder | ' . $exam->title)

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin Panel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Form Builder</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-primary mb-0">Form Builder: {{ $exam->title }}</h2>
            </div>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>

        <div class="row g-4">
            <!-- Exam Details Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-primary mb-3">Exam Details</h4>
                        <form method="POST" action="{{ route('admin.exams.update', $exam) }}" class="row g-3">
                            @csrf
                            @method('PATCH')
                            <div class="col-12">
                                <label class="small text-muted">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $exam->title }}" required>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted">Description</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ $exam->description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Category</label>
                                <input type="text" name="category" class="form-control" value="{{ $exam->category }}" placeholder="Category">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Fee</label>
                                <input type="number" step="0.01" name="fee" class="form-control" value="{{ $exam->fee }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Extra Label</label>
                                <input type="text" name="detail_label" class="form-control" value="{{ $exam->detail_label }}" placeholder="Detail label">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Extra Value</label>
                                <input type="text" name="detail_value" class="form-control" value="{{ $exam->detail_value }}" placeholder="Detail value">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Last Date</label>
                                <input type="date" name="last_date" class="form-control" value="{{ $exam->last_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" @selected($exam->status === 'active')>Active</option>
                                    <option value="inactive" @selected($exam->status === 'inactive')>Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 rounded-pill">Update Exam Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Fields Builder Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold text-primary mb-0">Form Fields</h4>
                            <span class="badge bg-primary rounded-pill">{{ $exam->formFields->count() }} Fields</span>
                        </div>

                        <!-- Add New Field Form -->
                        <div class="bg-light rounded-4 p-4 mb-4 border border-primary border-opacity-10">
                            <h6 class="fw-bold text-primary mb-3">Add New Field</h6>
                            <form method="POST" action="{{ route('admin.fields.store', $exam) }}" class="row g-3">
                                @csrf
                                <div class="col-md-4">
                                    <label class="small text-muted">Label</label>
                                    <input type="text" name="label" class="form-control" placeholder="e.g. Father's Name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">Field Name (Snake Case)</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. father_name">
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">Type</label>
                                    <select name="type" class="form-select" required>
                                        @foreach ($fieldTypes as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="small text-muted">Options (Comma separated for Select/Radio)</label>
                                    <input type="text" name="options_text" class="form-control" placeholder="Option 1, Option 2, Option 3">
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ ($exam->formFields->max('sort_order') ?? 0) + 1 }}">
                                </div>
                                <div class="col-md-6 d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input type="hidden" name="is_required" value="0">
                                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="new_is_required">
                                        <label class="form-check-label" for="new_is_required">Required</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="hidden" name="is_repeatable" value="0">
                                        <input type="checkbox" name="is_repeatable" value="1" class="form-check-input" id="new_is_repeatable">
                                        <label class="form-check-label" for="new_is_repeatable">Repeatable</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted">Max Repeat</label>
                                    <input type="number" name="max_repeat" class="form-control" placeholder="e.g. 5">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary w-100">Add Field</button>
                                </div>
                            </form>
                        </div>

                        <!-- Existing Fields List -->
                        <div class="fields-list">
                            @forelse ($exam->formFields as $field)
                                <div class="card border border-light shadow-sm rounded-4 mb-3">
                                    <div class="card-body p-3">
                                        <form method="POST" action="{{ route('admin.fields.update', [$exam, $field]) }}" class="row g-3 align-items-end">
                                            @csrf
                                            @method('PATCH')
                                            <div class="col-md-3">
                                                <label class="small text-muted">Label</label>
                                                <input type="text" name="label" class="form-control form-control-sm" value="{{ $field->label }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted">Name</label>
                                                <input type="text" name="name" class="form-control form-control-sm" value="{{ $field->name }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted">Type</label>
                                                <select name="type" class="form-select form-select-sm" required>
                                                    @foreach ($fieldTypes as $type)
                                                        <option value="{{ $type }}" @selected($field->type === $type)>{{ ucfirst($type) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="small text-muted">Sort</label>
                                                <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $field->sort_order }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted">Options</label>
                                                <input type="text" name="options_text" class="form-control form-control-sm" value="{{ implode(', ', $field->options ?? []) }}">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input type="hidden" name="is_required" value="0">
                                                    <input type="checkbox" name="is_required" value="1" class="form-check-input" id="req-{{ $field->id }}" @checked($field->is_required)>
                                                    <label class="form-check-label small" for="req-{{ $field->id }}">Required</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input type="hidden" name="is_repeatable" value="0">
                                                    <input type="checkbox" name="is_repeatable" value="1" class="form-check-input" id="rep-{{ $field->id }}" @checked($field->is_repeatable)>
                                                    <label class="form-check-label small" for="rep-{{ $field->id }}">Repeatable</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted">Max</label>
                                                <input type="number" name="max_repeat" class="form-control form-control-sm" value="{{ $field->max_repeat }}">
                                            </div>
                                            <div class="col-md-6 d-flex gap-2">
                                                <button class="btn btn-dark btn-sm px-3">Save</button>
                                                <button type="button" class="btn btn-outline-danger btn-sm px-3" 
                                                        onclick="if(confirm('Are you sure?')) document.getElementById('delete-field-{{ $field->id }}').submit();">
                                                    Delete
                                                </button>
                                            </div>
                                        </form>
                                        <form id="delete-field-{{ $field->id }}" method="POST" action="{{ route('admin.fields.destroy', [$exam, $field]) }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-tasks text-muted display-1"></i>
                                    <p class="text-muted mt-3">No fields added yet. Use the form above to start building your form.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
