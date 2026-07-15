@extends('layouts.app')

@section('title', 'Vacancy Forms | Exam Portal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col-lg-8">
                <h2 class="fw-bold text-primary mb-2">Vacancy Module</h2>
                <p class="text-muted mb-0">Apply for vacancy forms and job openings created by admin.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse ($vacancies as $vacancy)
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="card border-0 shadow-sm rounded-4 w-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">Vacancy</span>
                                <span class="small text-muted">{{ $vacancy->last_date->format('d M, Y') }}</span>
                            </div>
                            <h4 class="fw-bold mb-2">{{ $vacancy->title }}</h4>
                            <p class="text-muted small flex-grow-1">{{ $vacancy->description }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div class="small text-muted">{{ $vacancy->formFields->count() }} fields</div>
                                <a href="{{ route('application.create', $vacancy) }}" class="btn btn-primary rounded-pill px-4">
                                    Apply Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border rounded-4 text-center py-5">
                        No vacancy forms available right now.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
