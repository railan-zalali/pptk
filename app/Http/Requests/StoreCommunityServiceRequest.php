<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommunityServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'garden_id'        => ['nullable', 'exists:gardens,id'],
            'activity_name'    => ['required', 'string', 'max:255'],
            'team_name'        => ['required', 'string', 'max:255'],
            'total_budget'     => ['required', 'numeric', 'min:0'],
            'remaining_budget' => ['required', 'numeric', 'min:0', 'lte:total_budget'],
            'year'             => ['required', 'integer', 'min:2000', 'max:2099'],
            'status'           => ['required', 'string', 'in:planned,ongoing,completed'],
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'description'      => ['nullable', 'string'],
            'location'         => ['nullable', 'string', 'max:255'],
        ];
    }
}
