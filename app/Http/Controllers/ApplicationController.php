<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Exam;
use App\Http\Controllers\Concerns\BuildsDynamicForms;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    use BuildsDynamicForms;

    public function create(Request $request, Exam $exam): View|RedirectResponse
    {
        if (! $exam->isOpen()) {
            return redirect()->route('dashboard')->with('status', 'This application is closed.');
        }

        $application = $request->user()->applications()->firstOrCreate(
            ['exam_id' => $exam->id],
            ['status' => 'not_filled']
        );

        return view('apply-online', [
            'application' => $application,
            'exam' => $exam,
            'fields' => $exam->formFields,
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
                            $paths[] = $file->store('uploads', 'public');
                        }
                        $formData[$field->name] = array_merge((array)($existingData[$field->name] ?? []), $paths);
                    } else {
                        $formData[$field->name] = $existingData[$field->name] ?? [];
                    }
                } else {
                    if ($request->hasFile($field->name)) {
                        $formData[$field->name] = $request->file($field->name)->store('uploads', 'public');
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

        $application->fill([
            'form_data' => $formData,
            'status' => 'not_filled',
        ]);
        $application->user()->associate($request->user());
        $application->exam()->associate($exam);
        $application->save();

        $request->user()->payments()->updateOrCreate(
            ['exam_id' => $exam->id],
            ['amount' => $exam->fee ?? 500, 'status' => 'pending']
        );

        return redirect()->route('payments.create', $application)->with('status', 'Form saved. Complete payment to submit the application.');
    }
}
