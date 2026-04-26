<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Exam;
use App\Models\FormField;
use App\Http\Controllers\Concerns\BuildsDynamicForms;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function storeExam(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->examValidationRules());

        Exam::create($validated);

        return back()->with('status', 'Exam created successfully.');
    }

    public function updateExam(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate($this->examValidationRules());

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
}
