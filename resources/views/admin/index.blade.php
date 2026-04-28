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
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users') }}" class="btn btn-primary rounded-pill px-4">User Master Table</a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <h1 class="fw-bold mb-1">{{ $totalExams }}</h1>
                        <p class="mb-0 opacity-75">Total Forms Created</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                    <div class="card-body p-4 text-center">
                        <h1 class="fw-bold mb-1">{{ $totalUsers }}</h1>
                        <p class="mb-0 opacity-75">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-info text-white">
                    <div class="card-body p-4 text-center">
                        <h1 class="fw-bold mb-1">{{ $totalApplications }}</h1>
                        <p class="mb-0 opacity-75">Total Applications</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Create Form Section -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-primary mb-3">Create New Form</h4>
                        <p class="text-muted small mb-4">Fill in the basic details to create a new exam/form. You can add fields in the next step.</p>
                        <form method="POST" action="{{ route('admin.exams.store') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="small text-muted">Form Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Admission 2024" required>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Short description..." required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Category</label>
                                <input type="text" name="category" class="form-control" placeholder="Category">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Application Fee</label>
                                <input type="number" step="0.01" name="fee" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-12">
                                <label class="small text-muted">Last Date to Apply</label>
                                <input type="date" name="last_date" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 rounded-pill py-2">
                                    <i class="fas fa-plus-circle me-1"></i> Create & Continue
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Existing Forms Section -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold text-primary mb-0">Manage Existing Forms</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-top">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Last Date</th>
                                        <th>Fields</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($exams as $exam)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $exam->title }}</div>
                                                <div class="small text-muted">{{ $exam->category }}</div>
                                            </td>
                                            <td>{{ $exam->last_date->format('d M, Y') }}</td>
                                            <td>
                                                <span class="badge bg-light text-primary border">{{ $exam->formFields->count() }} fields</span>
                                            </td>
                                            <td>
                                                @if($exam->status === 'active')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3">Active</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.exams.builder', $exam) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                                        <i class="fas fa-edit me-1"></i> Edit Form
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.exams.toggle', $exam) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-outline-secondary btn-sm rounded-circle" title="{{ $exam->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}" onsubmit="return confirm('Delete this form and all its fields?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-danger btn-sm rounded-circle">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No forms created yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>
@endsection
