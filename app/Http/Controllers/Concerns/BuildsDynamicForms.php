<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Exam;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

trait BuildsDynamicForms
{
    protected function examValidationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'detail_label' => ['nullable', 'string', 'max:100'],
            'detail_value' => ['nullable', 'string', 'max:100'],
            'fee' => ['required', 'numeric', 'min:0'],
            'last_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    protected function normalizeFieldPayload(Request $request, Exam $exam, ?FormField $field = null): array
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('form_fields', 'name')
                    ->where(fn ($query) => $query->where('exam_id', $exam->id))
                    ->ignore($field?->id),
            ],
            'type' => ['required', Rule::in(FormField::FIELD_TYPES)],
            'options_text' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'is_repeatable' => ['nullable', 'boolean'],
            'max_repeat' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $name = $validated['name'] ?: Str::snake($validated['label']);
        $options = in_array($validated['type'], ['select', 'radio'], true)
            ? collect(explode(',', $validated['options_text'] ?? ''))
                ->map(fn ($option) => trim($option))
                ->filter()
                ->values()
                ->all()
            : null;

        if (in_array($validated['type'], ['select', 'radio'], true) && empty($options)) {
            throw ValidationException::withMessages([
                'options_text' => 'Options are required for select and radio fields.',
            ]);
        }

        return [
            'label' => $validated['label'],
            'name' => $name,
            'type' => $validated['type'],
            'options' => $options,
            'is_required' => (bool) ($validated['is_required'] ?? false),
            'is_repeatable' => (bool) ($validated['is_repeatable'] ?? false),
            'max_repeat' => $validated['max_repeat'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($field?->sort_order ?? 0),
        ];
    }

    protected function dynamicApplicationRules(iterable $fields, array $existingData = []): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $baseRule = $field->is_required ? 'required' : 'nullable';
            $itemRules = [];
            $isMobileField = Str::contains(Str::lower($field->name . ' ' . $field->label), 'mobile');

            if ($isMobileField) {
                $itemRules[] = 'digits:10';
            } elseif ($field->type === 'text' || $field->type === 'textarea') {
                $itemRules[] = 'string';
                $itemRules[] = 'max:1000';
            } elseif ($field->type === 'number') {
                $itemRules[] = 'numeric';
                if ($isMobileField) {
                    $itemRules[] = 'digits:10';
                }
            } elseif ($field->type === 'date') {
                $itemRules[] = 'date';
            } elseif (in_array($field->type, ['select', 'radio'], true)) {
                $itemRules[] = Rule::in($field->options ?? []);
            } elseif ($field->type === 'file') {
                $itemRules[] = 'image';
                $itemRules[] = 'max:350';
            }

            if ($field->is_repeatable) {
                $rules[$field->name] = [$baseRule, 'array'];
                if ($field->max_repeat) {
                    $rules[$field->name][] = 'max:' . $field->max_repeat;
                }

                $fileItemRule = $itemRules;
                array_unshift($fileItemRule, $field->type === 'file' ? 'nullable' : 'required');
                $rules[$field->name . '.*'] = $fileItemRule;
            } else {
                if ($field->type === 'file' && ! empty($existingData[$field->name])) {
                    $baseRule = 'nullable';
                }
                $rules[$field->name] = array_merge([$baseRule], $itemRules);
            }
        }

        return $rules;
    }
}
