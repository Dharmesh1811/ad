<div class="admit-card-box p-4 p-md-5 border rounded-4 shadow-sm bg-white position-relative overflow-hidden">
    <div class="admit-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Exam Portal Student ID Card</h4>
            <div class="small text-muted">Application-linked identity card</div>
        </div>
        <div class="text-end">
            <div class="small text-muted">Roll No.</div>
            <div class="fw-bold">{{ $candidate->application_number }}</div>
        </div>
    </div>

    <div class="row g-4 align-items-center">
        <div class="col-md-8">
            <table class="table table-borderless table-sm mb-0">
                <tr>
                    <td class="text-muted small py-2">Candidate Name</td>
                    <td class="fw-bold py-2">{{ $candidate->full_name }}</td>
                </tr>
                <tr>
                    <td class="text-muted small py-2">Application Number</td>
                    <td class="fw-bold py-2">{{ $candidate->application_number }}</td>
                </tr>
                <tr>
                    <td class="text-muted small py-2">Exam</td>
                    <td class="fw-bold py-2">{{ $application?->exam?->title }}</td>
                </tr>
                <tr>
                    <td class="text-muted small py-2">Payment Status</td>
                    <td class="fw-bold py-2 text-capitalize">{{ $candidate->payments->firstWhere('exam_id', $application?->exam_id)?->status }}</td>
                </tr>
                <tr>
                    <td class="text-muted small py-2">Approval Status</td>
                    <td class="fw-bold py-2 text-capitalize">{{ $application?->status }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-4 text-center">
            @if ($application?->photo)
                <img src="{{ asset('storage/' . $application->photo) }}" alt="Candidate Photo" class="img-fluid rounded border" style="max-height: 170px;">
            @else
                <div class="photo-placeholder bg-light mx-auto border d-flex align-items-center justify-content-center" style="width: 120px; height: 150px; border-style: dashed !important;">
                    <i class="fas fa-user text-muted fs-1"></i>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4 pt-3 border-top small text-muted">
        This card is generated from the candidate application profile and uses the application number as the primary system identity.
    </div>
</div>
