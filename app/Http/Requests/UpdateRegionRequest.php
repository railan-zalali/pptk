<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'regional_name' => ['required', 'string', 'max:255'],
            'province'      => ['required', 'string', 'max:255'],
            'coordinates'   => ['nullable', 'string', 'max:255'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photos'        => ['nullable', 'array', 'max:10'],
            'photos.*'      => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
