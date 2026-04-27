@extends('layouts.app')

@section('title', 'Track Status | Exam Portal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/trackapplication.css') }}">
@endpush

@section('content')
<section class="track-hero py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold text-primary mb-3">Check Application <span class="text-warning">Status</span></h1>
                <p class="text-muted">Search by application number to view form, payment, and approval progress.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('status.show') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Application Number</label>
                                <div class="input-group tracking-input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-hashtag text-primary"></i></span>
                                    <input type="text" name="application_number" class="form-control border-0 bg-light" placeholder="e.g. APP202600001" value="{{ old('application_number') }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold tracking-btn">
                                <i class="fas fa-magnifying-glass me-2"></i>Check Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (request()->isMethod('post'))
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    @if ($result)
                        <div class="card border-0 shadow-lg rounded-4">
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex justify-content-between flex-wrap gap-3 mb-4">
                                    <div>
                                        <div class="small text-muted">Candidate</div>
                                        <h4 class="fw-bold mb-1">{{ $result->full_name }}</h4>
                                        <div class="text-primary fw-semibold">{{ $result->application_number }}</div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    @forelse ($result->applications as $application)
                                        @php $payment = $result->payments->firstWhere('exam_id', $application->exam_id); @endphp
                                        <div class="col-12">
                                            <div class="p-4 bg-light rounded-4 h-100">
                                                <div class="d-flex justify-content-between flex-wrap gap-3">
                                                    <div>
                                                        <div class="small text-muted mb-2">Exam</div>
                                                        <div class="fw-bold">{{ $application->exam?->title }}</div>
                                                    </div>
                                                    <div class="text-md-end">
                                                        <div class="small text-muted mb-2">Last Updated</div>
                                                        <div class="fw-semibold">{{ optional($application->updated_at)->format('d M Y, h:i A') }}</div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1">
                                                    <div class="col-md-4">
                                                        <div class="small text-muted mb-2">Form Status</div>
                                                        <div class="fw-bold text-capitalize">{{ str_replace('_', ' ', $application->status) }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="small text-muted mb-2">Payment Status</div>
                                                        <div class="fw-bold text-capitalize">{{ $payment?->status ?? 'pending' }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="small text-muted mb-2">Approval Status</div>
                                                        <div class="fw-bold text-capitalize">{{ in_array($application->status, ['approved', 'rejected']) ? $application->status : 'pending' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-light border rounded-4 mb-0">No applications found for this user yet.</div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning rounded-4 shadow-sm">No record found for that application number.</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
