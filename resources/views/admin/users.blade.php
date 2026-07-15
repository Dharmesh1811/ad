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
                                <th class="py-3 text-muted fw-bold small uppercase">Applied Forms</th>
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
                                            @php $details = $formDataMap[$app->id] ?? []; @endphp
                                            <div class="mb-3 p-2 border rounded bg-white shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                                                    <div>
                                                        <div class="fw-bold small text-primary">{{ $details['form_title'] ?? $app->exam?->title }}</div>
                                                        <div class="text-muted" style="font-size: 10px;">
                                                            {{ $details['form_type'] ?? ($app->exam?->module_type === 'vacancy' ? 'Vacancy Form' : 'Exam Form') }}
                                                            @if($details['submitted_at'] ?? $app->submitted_at)
                                                                • Submitted {{ $details['submitted_at'] ?? $app->submitted_at->format('d M Y, h:i A') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="badge {{ $app->status === 'approved' ? 'bg-success' : ($app->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} small">
                                                        {{ $details['status'] ?? str_replace('_', ' ', ucfirst($app->status)) }}
                                                    </span>
                                                </div>

                                                <form method="POST" action="{{ route('admin.applications.update', $app) }}" class="d-flex gap-1 mb-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm" style="font-size: 11px;">
                                                        @foreach (['not_filled', 'submitted', 'approved', 'rejected'] as $status)
                                                            <option value="{{ $status }}" @selected($app->status === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-dark btn-sm" style="font-size: 11px;">Update</button>
                                                </form>

                                                <button
                                                    class="btn btn-outline-primary btn-sm w-100 view-form-details"
                                                    type="button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#formDetailsModal"
                                                    data-user-name="{{ $details['user_name'] ?? $user->full_name }}"
                                                    data-form-title="{{ $details['form_title'] ?? $app->exam?->title }}"
                                                    data-form-type="{{ $details['form_type'] ?? ($app->exam?->module_type === 'vacancy' ? 'Vacancy Form' : 'Exam Form') }}"
                                                    data-submitted-at="{{ $details['submitted_at'] ?? ($app->submitted_at ? $app->submitted_at->format('d M Y, h:i A') : 'N/A') }}"
                                                    data-status="{{ $details['status'] ?? str_replace('_', ' ', ucfirst($app->status)) }}"
                                                    data-fields='@json($details["fields"] ?? [])'
                                                    style="font-size: 10px;"
                                                >
                                                    <i class="fas fa-eye me-1"></i> View Full Form Details
                                                </button>
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

<div class="modal fade" id="formDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-primary mb-1" id="formDetailsTitle">Form Details</h5>
                    <div class="text-muted small" id="formDetailsMeta"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="small text-muted">User</div>
                        <div class="fw-semibold" id="formDetailsUser"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Form Type</div>
                        <div class="fw-semibold" id="formDetailsType"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Status</div>
                        <div class="fw-semibold" id="formDetailsStatus"></div>
                    </div>
                </div>
                <div class="border rounded-4 p-3 bg-light" id="formDetailsFields"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('formDetailsModal');
    if (!modal) return;

    const title = document.getElementById('formDetailsTitle');
    const meta = document.getElementById('formDetailsMeta');
    const user = document.getElementById('formDetailsUser');
    const type = document.getElementById('formDetailsType');
    const status = document.getElementById('formDetailsStatus');
    const fieldsWrap = document.getElementById('formDetailsFields');

    document.querySelectorAll('.view-form-details').forEach((button) => {
        button.addEventListener('click', () => {
            const fields = JSON.parse(button.dataset.fields || '[]');

            title.textContent = button.dataset.formTitle || 'Form Details';
            meta.textContent = button.dataset.submittedAt || '';
            user.textContent = button.dataset.userName || 'N/A';
            type.textContent = button.dataset.formType || 'N/A';
            status.textContent = button.dataset.status || 'N/A';

            fieldsWrap.innerHTML = fields.length
                ? fields.map((field) => {
                    const isFile = field.type === 'file';
                    const value = field.value;

                    if (isFile && field.files && field.files.length) {
                        return `
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="text-muted fw-bold mb-1">${field.label}:</div>
                                <div class="d-flex flex-wrap gap-2">
                                    ${field.files.map((file) => `
                                        <a href="${file.url}" target="_blank" class="d-inline-block">
                                            <img src="${file.url}" class="img-thumbnail rounded" style="max-height: 90px;">
                                        </a>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    return `
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="text-muted fw-bold mb-1">${field.label}:</div>
                            <div class="text-dark">${Array.isArray(value) ? value.join(', ') : (value || 'N/A')}</div>
                        </div>
                    `;
                }).join('')
                : '<div class="text-muted">No form fields found for this form.</div>';
        });
    });
});
</script>
@endsection
