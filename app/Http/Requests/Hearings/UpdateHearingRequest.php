<?php

namespace App\Http\Requests\Hearings;

use App\Enums\HearingStatus;
use App\Enums\HearingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHearingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('hearing'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'hearing_type' => ['required', Rule::enum(HearingType::class)],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:scheduled_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'court_room' => ['nullable', 'string', 'max:50'],
            'judge_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(HearingStatus::class)],
            'outcome' => ['nullable', 'string', 'max:2000'],
            'next_hearing_date' => ['nullable', 'date', 'after:scheduled_date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'end_time.after' => 'The end time must be after the start time.',
            'next_hearing_date.after' => 'The next hearing date must be after this hearing.',
        ];
    }
}
