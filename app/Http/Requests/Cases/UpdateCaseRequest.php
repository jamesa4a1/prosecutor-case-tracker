<?php

namespace App\Http\Requests\Cases;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('case'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $caseId = $this->route('case')->id ?? $this->route('case');

        return [
            'case_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cases', 'case_number')->ignore($caseId),
                'regex:/^[A-Z]{2,3}-\d{4}-\d{3,5}$/i',
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'offense_type' => ['required', 'string', 'max:100'],
            'status' => ['required', Rule::enum(CaseStatus::class)],
            'priority' => ['required', Rule::enum(CasePriority::class)],
            'prosecutor_id' => ['nullable', 'exists:prosecutors,id'],
            'date_filed' => ['required', 'date', 'before_or_equal:today'],
            'date_closed' => ['nullable', 'date', 'after_or_equal:date_filed'],
            'court_branch' => ['nullable', 'string', 'max:100'],
            'judge_name' => ['nullable', 'string', 'max:255'],
            'is_confidential' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'case_number.regex' => 'Case number must follow the format: XX-YYYY-NNN (e.g., CR-2025-001)',
            'date_closed.after_or_equal' => 'The closing date must be on or after the filing date.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'case_number' => strtoupper($this->case_number ?? ''),
            'is_confidential' => $this->boolean('is_confidential'),
        ]);
    }
}
