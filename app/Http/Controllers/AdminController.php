<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Exam;
use App\Models\FormField;
use App\Models\Syllabus;
use App\Http\Controllers\Concerns\BuildsDynamicForms;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use BuildsDynamicForms;

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $examFilter = $request->string('exam')->toString();
        $statusFilter = $request->string('status')->toString();

        $applications = Application::with(['user', 'exam.formFields', 'user.payments'])
            ->when($search, function ($query, $search) {
                $query->where(function ($inner) use ($search): void {
                    $inner->whereHas('user', fn ($userQuery) => $userQuery
                        ->where('application_number', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('full_name', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%'));
                });
            })
            ->when($examFilter, fn ($query) => $query->where('exam_id', $examFilter))
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.index', [
            'applications' => $applications,
            'search' => $search,
            'examFilter' => $examFilter,
            'statusFilter' => $statusFilter,
            'exams' => Exam::with('formFields')->latest()->get(),
            'fieldTypes' => FormField::FIELD_TYPES,
            'totalExams' => Exam::count(),
            'totalUsers' => \App\Models\User::where('is_admin', false)->count(),
            'totalApplications' => Application::count(),
            'syllabi' => Syllabus::latest()->get(),
        ]);
    }

    public function builder(Exam $exam): View
    {
        return view('admin.builder', [
            'exam' => $exam->load('formFields'),
            'fieldTypes' => FormField::FIELD_TYPES,
        ]);
    }

    public function users(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $users = \App\Models\User::with(['applications.exam.formFields', 'payments.exam'])
            ->where('is_admin', false)
            ->when($search, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%')
                        ->orWhere('application_number', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $formDataMap = [];
        foreach ($users->getCollection() as $user) {
            foreach ($user->applications as $app) {
                $fields = ($app->exam?->formFields ?? collect())->map(function ($field) use ($app) {
                    $value = data_get($app->form_data ?? [], $field->name);
                    $files = [];

                    if ($field->type === 'file' && $value) {
                        $values = is_array($value) ? $value : [$value];
                        $files = collect($values)->map(function ($filePath) {
                            return [
                                'path' => $filePath,
                                'url' => \App\Models\Application::fileUrl($filePath),
                            ];
                        })->values()->all();
                    }

                    return [
                        'label' => $field->label,
                        'type' => $field->type,
                        'value' => $value,
                        'files' => $files,
                    ];
                })->values()->all();

                $formDataMap[$app->id] = [
                    'user_name' => $user->full_name,
                    'form_title' => $app->exam?->title,
                    'form_type' => $app->exam?->module_type === 'vacancy' ? 'Vacancy Form' : 'Exam Form',
                    'submitted_at' => $app->submitted_at ? $app->submitted_at->format('d M Y, h:i A') : 'N/A',
                    'status' => str_replace('_', ' ', ucfirst($app->status)),
                    'can_download_pdf' => $this->canDownloadApplicationPdf($app),
                    'fields' => $fields,
                ];
            }
        }


        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'formDataMap' => $formDataMap,
        ]);
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::in(['not_filled', 'submitted', 'approved', 'rejected'])],
        ]);

        $application->update($validated);

        return back()->with('status', 'Application status updated.');
    }

    public function downloadApplicationPdf(Application $application)
    {
        $application->loadMissing(['exam.formFields', 'user.payments']);

        abort_unless($this->canDownloadApplicationPdf($application), 403, 'PDF download is available only for approved and paid applications.');

        $payment = $application->user->payments->firstWhere('exam_id', $application->exam_id);
        $fields = $application->exam->formFields;

        $photoField = $fields->first(fn ($f) => $f->type === 'file' && (str_contains(Str::lower($f->name), 'photo') || str_contains(Str::lower($f->label), 'photo')));
        $sigField = $fields->first(fn ($f) => $f->type === 'file' && (str_contains(Str::lower($f->name), 'signature') || str_contains(Str::lower($f->label), 'signature')));

        $photoVal = $application->photo ?? ($photoField ? data_get($application->form_data, $photoField->name) : null);
        if (is_array($photoVal)) {
            $photoVal = $photoVal[0] ?? null;
        }

        $sigVal = $application->signature ?? ($sigField ? data_get($application->form_data, $sigField->name) : null);
        if (is_array($sigVal)) {
            $sigVal = $sigVal[0] ?? null;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.application', [
            'application' => $application,
            'exam' => $application->exam,
            'fields' => $fields,
            'payment' => $payment,
            'photoVal' => $photoVal,
            'sigVal' => $sigVal,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Application_' . $application->user->application_number . '_' . Str::slug($application->exam->title) . '.pdf');
    }

    public function storeExam(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->examValidationRules());
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['module_type'] = $validated['module_type'] ?? 'exam';

        $exam = DB::transaction(function () use ($validated) {
            $exam = Exam::create($validated);

            if ($exam->module_type === 'vacancy') {
                foreach ($this->vacancyDefaultFields() as $field) {
                    $exam->formFields()->create($field);
                }
            }

            return $exam;
        });

        return redirect()->route('admin.exams.builder', $exam)->with('status', 'Exam created successfully. Now you can add fields to your form.');
    }

    public function updateExam(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate($this->examValidationRules());
        $validated['status'] = $validated['status'] ?? $exam->status;
        $validated['module_type'] = $validated['module_type'] ?? $exam->module_type ?? 'exam';

        $exam->update($validated);

        return back()->with('status', 'Exam updated successfully.');
    }

    public function storeField(Request $request, Exam $exam): RedirectResponse
    {
        $payload = $this->normalizeFieldPayload($request, $exam);
        $payload['sort_order'] = $payload['sort_order'] ?: (($exam->formFields()->max('sort_order') ?? 0) + 1);

        $exam->formFields()->create($payload);

        return back()->with('status', 'Form field added successfully.');
    }

    public function updateField(Request $request, Exam $exam, FormField $field): RedirectResponse
    {
        abort_unless($field->exam_id === $exam->id, 404);

        $payload = $this->normalizeFieldPayload($request, $exam, $field);
        $field->update($payload);

        return back()->with('status', 'Form field updated successfully.');
    }

    public function destroyField(Exam $exam, FormField $field): RedirectResponse
    {
        abort_unless($field->exam_id === $exam->id, 404);

        $field->delete();

        return back()->with('status', 'Form field deleted successfully.');
    }

    public function destroyExam(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return back()->with('status', 'Exam removed successfully.');
    }

    public function toggleExam(Exam $exam): RedirectResponse
    {
        $exam->update([
            'status' => $exam->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('status', 'Exam status changed.');
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $applications = Application::with(['user', 'exam.formFields', 'user.payments'])->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications-export.csv"',
        ];

        $callback = function () use ($applications): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Application Number', 'Exam', 'Status', 'Payment Status', 'Submitted Data']);

            foreach ($applications as $application) {
                $payment = $application->user->payments->firstWhere('exam_id', $application->exam_id);
                $submittedData = collect($application->form_data ?? [])
                    ->filter(fn ($value, $key) => ! in_array($key, ['_token'], true))
                    ->map(function ($value, $key) use ($application) {
                        $label = $application->exam?->formFields->firstWhere('name', $key)?->label ?? Str::headline($key);

                        if (is_array($value)) {
                            $value = collect($value)->map(function ($item) {
                                return is_string($item) && str_starts_with($item, 'uploads/') ? asset('storage/' . $item) : $item;
                            })->implode(', ');
                        } elseif (is_string($value) && str_starts_with($value, 'uploads/')) {
                            $value = asset('storage/' . $value);
                        }

                        return $label . ': ' . $value;
                    })
                    ->implode(' | ');

                fputcsv($handle, [
                    $application->user->application_number,
                    $application->exam?->title,
                    $application->status,
                    $payment?->status ?? 'pending',
                    $submittedData,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeSyllabus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,jpeg,png,jpg,webp', 'max:10240'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'uploads/syllabus/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('uploads/syllabus', $file, basename($filename));
            $validated['file_path'] = $filename;
        }

        Syllabus::create($validated);

        return back()->with('status', 'Syllabus added successfully.');
    }

    public function destroySyllabus(Syllabus $syllabus): RedirectResponse
    {
        if ($syllabus->file_path && Storage::disk('public')->exists($syllabus->file_path)) {
            Storage::disk('public')->delete($syllabus->file_path);
        }

        $syllabus->delete();

        return back()->with('status', 'Syllabus deleted successfully.');
    }

    private function canDownloadApplicationPdf(Application $application): bool
    {
        $payment = $application->user?->payments?->firstWhere('exam_id', $application->exam_id);

        return $application->status === 'approved'
            && (
                ($payment?->status === 'paid')
                || ($payment?->payment_status === 'paid')
            );
    }
}
