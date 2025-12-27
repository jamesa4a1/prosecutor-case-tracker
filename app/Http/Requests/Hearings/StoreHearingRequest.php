<?php

namespace App\Http\Requests\Hearings;

use App\Enums\HearingStatus;
use App\Enums\HearingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHearingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Hearing::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'case_id' => ['required', 'exists:cases,id'],
            'hearing_type' => ['required', Rule::enum(HearingType::class)],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:scheduled_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'court_room' => ['nullable', 'string', 'max:50'],
            'judge_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'scheduled_date.after_or_equal' => 'The hearing date must be today or a future date.',
            'end_time.after' => 'The end time must be after the start time.',
            'case_id.exists' => 'The selected case does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'case_id' => 'case',
            'hearing_type' => 'hearing type',
            'scheduled_date' => 'hearing date',
            'scheduled_time' => 'hearing time',
            'court_room' => 'court room',
            'judge_name' => 'presiding judge',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => HearingStatus::Scheduled->value,
        ]);
    }
}
