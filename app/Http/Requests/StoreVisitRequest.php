<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title'              => ['required', 'string', 'max:255'],
            'garden_id'          => ['required', 'exists:gardens,id'],
            'visit_date'         => ['required', 'date'],
            'duration'           => ['required', 'integer', 'min:1', 'max:24'],
            'participants_count' => ['required', 'integer', 'min:1'],
            'participants_list'  => ['nullable', 'string'],
            'description'        => ['required', 'string'],
            'objectives'         => ['nullable', 'string'],
            'findings'           => ['nullable', 'string'],
            'recommendations'    => ['nullable', 'string'],
            'rating'             => ['required', 'integer', 'between:1,5'],
            'status'             => ['required', 'in:scheduled,completed,cancelled'],
            'photos'             => ['nullable', 'array', 'max:10'],
            'photos.*'           => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
