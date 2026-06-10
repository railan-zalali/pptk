<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStrategicActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'kebun_id'                => ['required', 'exists:gardens,id'],
            'program_id'              => ['nullable', 'exists:programs,id'],
            'year'                    => ['required', 'integer', 'min:2000', 'max:2099'],
            'action_type'             => ['required', 'string', 'in:fertilizer_root,fertilizer_leaf,weed_control,cultivator,picking,machine,opt'],
            'status'                  => ['nullable', 'string', 'in:planned,in_progress,completed'],
            'realization_date'        => ['nullable', 'date'],
            'dosis_n_kg_ha'           => ['nullable', 'numeric', 'min:0'],
            'realized_dosis_n_kg_ha'  => ['nullable', 'numeric', 'min:0'],
            'n_protas_percent'        => ['nullable', 'numeric', 'min:0'],
            'application_frequency'   => ['nullable', 'integer', 'min:0'],
            'fertilizer_type'         => ['nullable', 'string', 'max:255'],
            'technical_note'          => ['nullable', 'string'],
            'coverage_target_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'realization_percent'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'application_interval'    => ['nullable', 'string', 'max:255'],
            'rotation_per_year'       => ['nullable', 'integer', 'min:0'],
            'method'                  => ['nullable', 'string', 'max:255'],
            'focus_area'              => ['nullable', 'string', 'max:255'],
            'picking_system'          => ['nullable', 'string', 'max:255'],
            'cushion_consistency'     => ['nullable', 'string', 'max:255'],
            'kandas_risk'             => ['nullable', 'boolean'],
            'total_machine'           => ['nullable', 'integer', 'min:0'],
            'avg_machine_age'         => ['nullable', 'numeric', 'min:0'],
            'renewal_status'          => ['nullable', 'string', 'max:255'],
            'opt_status'              => ['nullable', 'string', 'max:255'],
            'tp_normalization'        => ['nullable', 'boolean'],
            'treatment_note'          => ['nullable', 'string'],
            'note'                    => ['nullable', 'string'],
        ];
    }
}
