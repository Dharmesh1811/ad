@extends('layouts.app')

@section('title', 'User Master | Admin Panel')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">User Master Table</h2>
                <p class="text-muted mb-0">Complete list of registered users, their applied exams, and payment history.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.index') }}" class="btn btn-outline-primary rounded-pill px-4">Manage Applications</a>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-4">
                <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0 py-2" placeholder="Search by Name, Email, Mobile, or Application Number..." value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 rounded-pill py-2">Search</button>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-bold small uppercase">User Details</th>
                                <th class="py-3 text-muted fw-bold small uppercase">Contact Info</th>
                                <th class="py-3 text-muted fw-bold small uppercase text-center">App No</th>
                                <th class="py-3 text-muted fw-bold small uppercase">Applied Exams</th>
                                <th class="py-3 text-muted fw-bold small uppercase">Payment History</th>
                                <th class="pe-4 py-3 text-muted fw-bold small uppercase text-end">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $user->full_name }}</div>
                                                <div class="small text-muted">ID: #{{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small"><i class="far fa-envelope me-1 text-primary"></i> {{ $user->email }}</div>
                                        <div class="small mt-1"><i class="fas fa-phone-alt me-1 text-success"></i> {{ $user->mobile }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                                            {{ $user->application_number }}
                                        </span>
                                    </td>
                                    <td>
                                        @forelse ($user->applications as $app)
                                            <div class="mb-3 p-2 border rounded bg-white shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-bold small text-primary">{{ $app->exam?->title }}</span>
                                                    <span class="badge {{ $app->status === 'approved' ? 'bg-success' : ($app->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} small">
                                                        {{ str_replace('_', ' ', ucfirst($app->status)) }}
                                                    </span>
                                                </div>
                                                <form method="POST" action="{{ route('admin.applications.update', $app) }}" class="d-flex gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm" style="font-size: 11px;">
                                                        @foreach (['not_filled', 'submitted', 'approved', 'rejected'] as $status)
                                                            <option value="{{ $status }}" @selected($app->status === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-dark btn-sm" style="font-size: 11px;">Update</button>
                                                </form>
                                                <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#data-{{ $app->id }}" style="font-size: 10px;">
                                                    <i class="fas fa-eye me-1"></i> View Submitted Data
                                                </button>
                                                <div class="collapse mt-2" id="data-{{ $app->id }}">
                                                    <div class="bg-light p-2 rounded small border" style="font-size: 11px;">
                                                        @php $formData = $app->form_data ?? []; @endphp
                                                        @foreach ($app->exam?->formFields ?? [] as $field)
                                                            @php $val = $formData[$field->name] ?? null; @endphp
                                                            <div class="mb-2 border-bottom pb-1">
                                                                <div class="text-muted fw-bold">{{ $field->label }}:</div>
                                                                @if ($field->type === 'file' && $val)
                                                                    <a href="{{ asset('storage/' . $val) }}" target="_blank">
                                                                        <img src="{{ asset('storage/' . $val) }}" class="img-fluid rounded border mt-1" style="max-height: 80px;">
                                                                    </a>
                                                                @else
                                                                    <div class="text-dark">{{ is_array($val) ? implode(', ', $val) : ($val ?? 'N/A') }}</div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-muted small italic">No exams applied</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse ($user->payments as $pay)
                                            <div class="mb-1">
                                                <span class="small fw-semibold {{ $pay->status === 'paid' ? 'text-success' : 'text-danger' }}">
                                                    ₹{{ number_format($pay->amount, 2) }} - {{ strtoupper($pay->status) }}
                                                </span>
                                                <div class="text-muted" style="font-size: 10px;">{{ $pay->exam?->title }}</div>
                                            </div>
                                        @empty
                                            <span class="text-muted small italic">No payments</span>
                                        @endforelse
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="small text-dark">{{ $user->created_at->format('d M Y') }}</div>
                                        <div class="text-muted small" style="font-size: 11px;">{{ $user->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted mb-2"><i class="fas fa-users-slash fa-3x"></i></div>
                                        <h5 class="fw-bold">No users found</h5>
                                        <p class="text-muted">Try adjusting your search filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</section>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background-color: #e9ecef;
    color: #495057;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
}
.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    letter-spacing: 0.5px;
}
.bg-primary-subtle { background-color: #cfe2ff !important; }
.text-primary { color: #0d6efd !important; }
</style>
@endsection
