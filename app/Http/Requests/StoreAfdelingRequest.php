<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAfdelingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'kebun_id'      => ['required', 'exists:gardens,id'],
            'name'          => ['required', 'string', 'max:255'],
            'total_area_ha' => ['required', 'numeric', 'min:0'],
            'tm_area_ha'    => ['required', 'numeric', 'min:0', 'lte:total_area_ha'],
            'manager_name'  => ['nullable', 'string', 'max:255'],
        ];
    }
}
