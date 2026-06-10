<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGardenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'kebun_name'       => ['required', 'string', 'max:255'],
            'regional_id'      => ['required', 'exists:regions,id'],
            'luas_total_ha'    => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'kebun_type'       => ['required', 'in:Model,Pengembangan'],
            'agro_climate_note' => ['nullable', 'string'],
            'location'         => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'established_at'   => ['nullable', 'date'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
