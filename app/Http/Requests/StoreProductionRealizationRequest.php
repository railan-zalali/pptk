<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductionRealizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'kebun_id' => ['required', 'exists:gardens,id'],
            'month'    => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('production_realizations')->where(
                    fn ($q) => $q->where('kebun_id', $this->kebun_id)->where('year', $this->year)
                ),
            ],
            'year'                   => ['required', 'integer', 'min:2000', 'max:2099'],
            'active_picking_area_ha' => ['nullable', 'numeric', 'min:0'],
            'wet_production_kg'      => ['nullable', 'numeric', 'min:0'],
            'capacity_per_ha'        => ['nullable', 'numeric', 'min:0'],
            'avg_capacity'           => ['nullable', 'numeric', 'min:0'],
            'estimated_production'   => ['nullable', 'numeric', 'min:0'],
            'quality_score'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assumption_note'        => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'month.unique' => 'Data realisasi untuk kebun dan periode bulan/tahun ini sudah ada.',
        ];
    }
}
