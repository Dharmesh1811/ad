@extends('layouts.app')

@section('title', 'ID Card | Exam Portal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admitcard.css') }}">
@endpush

@section('content')
<section class="admit-hero py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-7">
                <h1 class="fw-bold text-primary">Generate Student <span class="text-warning">ID Card</span></h1>
                <p class="text-muted">ID cards become available after the form is completed and payment is marked as paid.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admit.show') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Application Number</label>
                                <input type="text" name="application_number" class="form-control border-0 bg-light py-3 px-4 rounded-4 shadow-none" placeholder="e.g. APP202600001" value="{{ old('application_number') }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow">
                                <i class="fas fa-download me-2"></i>Generate ID Card
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (request()->isMethod('post'))
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    @if ($eligible && $candidate)
                        @include('pdfs.id-card', ['candidate' => $candidate, 'application' => $application])
                    @else
                        <div class="alert alert-warning rounded-4 shadow-sm">
                            @if ($candidate)
                                ID card is not available yet. Complete the form and payment first, then wait for admin approval if needed.
                            @else
                                No candidate found for that application number.
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
