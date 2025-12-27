<?php

namespace App\Http\Requests\Cases;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Models\CaseModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', CaseModel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'case_number' => [
                'required',
                'string',
                'max:50',
                'unique:cases,case_number',
                'regex:/^[A-Z]{2,3}-\d{4}-\d{3,5}$/i', // Format: CR-2025-001
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'offense_type' => ['required', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(CaseStatus::class)],
            'priority' => ['nullable', Rule::enum(CasePriority::class)],
            'prosecutor_id' => ['nullable', 'exists:prosecutors,id'],
            'date_filed' => ['required', 'date', 'before_or_equal:today'],
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
            'case_number.unique' => 'This case number is already registered in the system.',
            'date_filed.before_or_equal' => 'The filing date cannot be in the future.',
            'prosecutor_id.exists' => 'The selected prosecutor does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'case_number' => 'case number',
            'offense_type' => 'offense type',
            'prosecutor_id' => 'assigned prosecutor',
            'date_filed' => 'date filed',
            'court_branch' => 'court branch',
            'judge_name' => 'presiding judge',
            'is_confidential' => 'confidentiality status',
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
            'status' => $this->status ?? CaseStatus::Pending->value,
            'priority' => $this->priority ?? CasePriority::Normal->value,
        ]);
    }
}
