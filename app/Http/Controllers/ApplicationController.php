<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Exam;
use App\Http\Controllers\Concerns\BuildsDynamicForms;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApplicationController extends Controller
{
    use BuildsDynamicForms;

    public function showUpload(string $path): BinaryFileResponse
    {
        $path = ltrim($path, '/');

        if (Storage::disk('public')->exists($path)) {
            $fullPath = Storage::disk('public')->path($path);
            return response()->file($fullPath);
        }

        $publicPath = public_path($path);
        if (file_exists($publicPath)) {
            return response()->file($publicPath);
        }

        abort(404);
    }

    public function create(Request $request, Exam $exam): View|RedirectResponse
    {
        if (! $exam->isOpen()) {
            return redirect()->route('dashboard')->with('status', 'This application is closed.');
        }

        $application = $request->user()->applications()->firstOrCreate(
            ['exam_id' => $exam->id],
            ['status' => 'not_filled']
        );

        $payment = $request->user()->payments->firstWhere('exam_id', $exam->id);
        $isReadOnly = in_array($application->status, ['submitted', 'approved']) ||
                      ($payment?->status === 'paid') ||
                      ($payment?->payment_status === 'paid');

        return view('apply-online', [
            'application' => $application,
            'exam' => $exam,
            'fields' => $exam->formFields,
            'isReadOnly' => $isReadOnly,
        ]);
    }



    public function store(Request $request): RedirectResponse
    {
        $baseValidated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
        ]);

        $exam = Exam::findOrFail($baseValidated['exam_id']);

        if (! $exam->isOpen()) {
            return redirect()->route('dashboard')->with('status', 'Application closed for this exam.');
        }

        if (now()->startOfDay()->gt($exam->last_date)) {
            return redirect()->route('dashboard')->with('status', 'Application closed');
        }

        $application = $request->user()->applications()->firstOrNew([
            'exam_id' => $exam->id,
        ]);

        $fields = $exam->formFields;
        $existingData = $application->form_data ?? [];
        $validated = $request->validate($this->dynamicApplicationRules($fields, $existingData));
        $formData = $existingData;

        foreach ($fields as $field) {
            if ($field->type === 'file') {
                if ($field->is_repeatable) {
                    if ($request->hasFile($field->name)) {
                        $paths = [];
                        foreach ($request->file($field->name) as $file) {
                            $filename = 'uploads/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                            Storage::disk('public')->putFileAs('uploads', $file, basename($filename));
                            $paths[] = $filename;
                        }
                        $formData[$field->name] = array_merge((array)($existingData[$field->name] ?? []), $paths);
                    } else {
                        $formData[$field->name] = $existingData[$field->name] ?? [];
                    }
                } else {
                    if ($request->hasFile($field->name)) {
                        $file = $request->file($field->name);
                        $filename = 'uploads/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('uploads', $file, basename($filename));
                        $formData[$field->name] = $filename;
                    }
                }
            } else {
                $formData[$field->name] = $validated[$field->name] ?? null;
            }
        }

        $request->user()->update(array_filter([
            'full_name' => $formData['full_name'] ?? null,
            'mobile' => $formData['mobile'] ?? null,
            'email' => $formData['email'] ?? null,
            'dob' => $formData['dob'] ?? null,
        ], fn ($value) => filled($value)));

        // Find matches for application columns based on fields name/label
        $photoField = $fields->first(fn($f) => $f->type === 'file' && (str_contains(Str::lower($f->name), 'photo') || str_contains(Str::lower($f->label), 'photo')));
        $sigField = $fields->first(fn($f) => $f->type === 'file' && (str_contains(Str::lower($f->name), 'signature') || str_contains(Str::lower($f->label), 'signature')));
        
        $nameField = $fields->first(fn($f) => str_contains(Str::lower($f->name), 'name') || str_contains(Str::lower($f->label), 'name'));
        $dobField = $fields->first(fn($f) => str_contains(Str::lower($f->name), 'dob') || str_contains(Str::lower($f->label), 'birth') || str_contains(Str::lower($f->label), 'dob'));
        $genderField = $fields->first(fn($f) => str_contains(Str::lower($f->name), 'gender') || str_contains(Str::lower($f->label), 'gender'));
        $mobileField = $fields->first(fn($f) => str_contains(Str::lower($f->name), 'mobile') || str_contains(Str::lower($f->name), 'phone') || str_contains(Str::lower($f->label), 'mobile'));
        $emailField = $fields->first(fn($f) => str_contains(Str::lower($f->name), 'email') || str_contains(Str::lower($f->label), 'email'));
        $addressField = $fields->first(fn($f) => str_contains(Str::lower($f->name), 'address') || str_contains(Str::lower($f->label), 'address'));

        $photoVal = null;
        if ($photoField) {
            $val = $formData[$photoField->name] ?? null;
            $photoVal = is_array($val) ? ($val[0] ?? null) : $val;
        } else {
            $photoVal = $formData['photo'] ?? null;
            $photoVal = is_array($photoVal) ? ($photoVal[0] ?? null) : $photoVal;
        }

        $sigVal = null;
        if ($sigField) {
            $val = $formData[$sigField->name] ?? null;
            $sigVal = is_array($val) ? ($val[0] ?? null) : $val;
        } else {
            $sigVal = $formData['signature'] ?? null;
            $sigVal = is_array($sigVal) ? ($sigVal[0] ?? null) : $sigVal;
        }

        $isVacancy = $exam->module_type === 'vacancy';

        $application->fill([
            'form_data' => $formData,
            'status' => $isVacancy ? 'submitted' : 'not_filled',
            'full_name' => $nameField ? ($formData[$nameField->name] ?? null) : ($formData['full_name'] ?? null),
            'dob' => $dobField ? ($formData[$dobField->name] ?? null) : ($formData['dob'] ?? null),
            'gender' => $genderField ? ($formData[$genderField->name] ?? null) : ($formData['gender'] ?? null),
            'mobile' => $mobileField ? ($formData[$mobileField->name] ?? null) : ($formData['mobile'] ?? null),
            'email' => $emailField ? ($formData[$emailField->name] ?? null) : ($formData['email'] ?? null),
            'address' => $addressField ? ($formData[$addressField->name] ?? null) : ($formData['address'] ?? null),
            'photo' => $photoVal,
            'signature' => $sigVal,
        ]);
        $application->user()->associate($request->user());
        $application->exam()->associate($exam);
        $application->save();

        if (! $isVacancy) {
            $request->user()->payments()->updateOrCreate(
                ['exam_id' => $exam->id],
                ['amount' => $exam->fee ?? 500, 'status' => 'pending']
            );

            return redirect()->route('payments.create', $application)->with('status', 'Form saved. Complete payment to submit the application.');
        }

        return redirect()->route('dashboard')->with('status', 'Vacancy application submitted successfully.');
    }

    public function downloadPdf(Request $request, Application $application)
    {
        abort_unless(
            $application->user_id === $request->user()->id,
            403,
            'You are not authorized to view this application.'
        );

        $application->loadMissing(['exam.formFields', 'user.payments']);
        
        $payment = $request->user()->payments->firstWhere('exam_id', $application->exam_id);
        
        $fields = $application->exam->formFields;
        
        $photoField = $fields->first(fn($f) => $f->type === 'file' && (str_contains(Str::lower($f->name), 'photo') || str_contains(Str::lower($f->label), 'photo')));
        $sigField = $fields->first(fn($f) => $f->type === 'file' && (str_contains(Str::lower($f->name), 'signature') || str_contains(Str::lower($f->label), 'signature')));

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
}
